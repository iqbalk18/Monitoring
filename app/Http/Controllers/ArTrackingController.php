<?php

namespace App\Http\Controllers;

use App\Models\TcmonArTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArTrackingController extends Controller
{
    public function index()
    {
        // Query from tcmon_ar_billing LEFT JOIN tcmon_ar_tracking
        // tcmon_ar_billing = source of billing/invoice data
        // tcmon_ar_tracking = local tracking metadata (status, courier, ref_no, etc.)
        $rows = DB::table('tcmon_ar_billing as b')
            ->leftJoin('tcmon_ar_tracking as t', 'b.invoiceno', '=', 't.invoice_no')
            ->select([
                // From billing (grouped per invoice)
                DB::raw('MIN(b.id) as id'),
                'b.bat_number',
                'b.bat_datecreated',
                'b.inst_desc',
                'b.firstname',
                'b.lastname',
                'b.nationality',
                'b.admdate',
                'b.paadm_type',
                'b.urn',
                'b.episodeno',
                'b.invoiceno',
                'b.arpbl_dateprinted',
                'b.arpbl_datecancelled',
                DB::raw('SUM(b.beforediscount) as beforediscount'),
                DB::raw('SUM(b.afterdiscount) as afterdiscount'),
                DB::raw('SUM(b.outstanding) as outstanding'),
                // From tracking
                't.id as tracking_id',
                't.status as tracking_status',
                't.ref_no',
                't.courier_via',
                't.tracking_no',
                't.sent_date',
                't.received_date',
                't.paid_on',
                't.cancelled_date as tracking_cancelled_date',
                't.due_days',
                't.remarks',
            ])
            ->groupBy(
                'b.bat_number', 'b.bat_datecreated', 'b.inst_desc',
                'b.firstname', 'b.lastname', 'b.nationality',
                'b.admdate', 'b.paadm_type', 'b.urn', 'b.episodeno',
                'b.invoiceno', 'b.arpbl_dateprinted', 'b.arpbl_datecancelled',
                't.id', 't.status', 't.ref_no', 't.courier_via',
                't.tracking_no', 't.sent_date', 't.received_date',
                't.paid_on', 't.cancelled_date', 't.due_days', 't.remarks'
            )
            ->orderBy('b.bat_datecreated', 'desc')
            ->get();

        // Transform to the JSON structure expected by the frontend
        $invoices = $rows->map(fn ($row) => $this->mapInvoiceRow($row))->values()->toArray();

        return view('track.index', compact('invoices'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:BATCHING,SENT,RECEIVED,REVISE,PAID'],
            'ref_no' => ['nullable', 'string', 'max:100'],
            'courier_via' => ['nullable', 'string', 'max:50'],
            'tracking_no' => ['nullable', 'string', 'max:100'],
            'sent_date' => ['nullable', 'date'],
            'received_date' => ['nullable', 'date'],
            'paid_on' => ['nullable', 'date'],
            'cancelled_date' => ['nullable', 'date'],
            'due_days' => ['nullable', 'integer'],
            'remarks' => ['nullable', 'string'],
        ]);

        $tracking = TcmonArTracking::updateOrCreate(
            ['invoice_no' => $validated['invoice_no']],
            [
                'status' => $validated['status'],
                'ref_no' => $validated['ref_no'] ?? null,
                'courier_via' => $validated['courier_via'] ?? null,
                'tracking_no' => $validated['tracking_no'] ?? null,
                'sent_date' => $validated['sent_date'] ?? null,
                'received_date' => $validated['received_date'] ?? null,
                'paid_on' => $validated['paid_on'] ?? null,
                'cancelled_date' => $validated['cancelled_date'] ?? null,
                'due_days' => $validated['due_days'] ?? 0,
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        $row = DB::table('tcmon_ar_billing as b')
            ->leftJoin('tcmon_ar_tracking as t', 'b.invoiceno', '=', 't.invoice_no')
            ->select([
                DB::raw('MIN(b.id) as id'),
                'b.bat_number',
                'b.bat_datecreated',
                'b.inst_desc',
                'b.firstname',
                'b.lastname',
                'b.nationality',
                'b.admdate',
                'b.paadm_type',
                'b.urn',
                'b.episodeno',
                'b.invoiceno',
                'b.arpbl_dateprinted',
                'b.arpbl_datecancelled',
                DB::raw('SUM(b.beforediscount) as beforediscount'),
                DB::raw('SUM(b.afterdiscount) as afterdiscount'),
                DB::raw('SUM(b.outstanding) as outstanding'),
                't.id as tracking_id',
                't.status as tracking_status',
                't.ref_no',
                't.courier_via',
                't.tracking_no',
                't.sent_date',
                't.received_date',
                't.paid_on',
                't.cancelled_date as tracking_cancelled_date',
                't.due_days',
                't.remarks',
            ])
            ->where('b.invoiceno', $tracking->invoice_no)
            ->groupBy(
                'b.bat_number',
                'b.bat_datecreated',
                'b.inst_desc',
                'b.firstname',
                'b.lastname',
                'b.nationality',
                'b.admdate',
                'b.paadm_type',
                'b.urn',
                'b.episodeno',
                'b.invoiceno',
                'b.arpbl_dateprinted',
                'b.arpbl_datecancelled',
                't.id',
                't.status',
                't.ref_no',
                't.courier_via',
                't.tracking_no',
                't.sent_date',
                't.received_date',
                't.paid_on',
                't.cancelled_date',
                't.due_days',
                't.remarks'
            )
            ->first();

        if (!$row) {
            return response()->json([
                'message' => 'Invoice tidak ditemukan setelah update.',
            ], 404);
        }

        return response()->json([
            'message' => 'Tracking berhasil disimpan.',
            'item' => $this->mapInvoiceRow($row),
        ]);
    }

    private function mapInvoiceRow(object $row): array
    {
        $billingCancelledDate = $row->arpbl_datecancelled ?? null;
        $trackingCancelledDate = $row->tracking_cancelled_date ?? null;
        $cancelledDate = $billingCancelledDate ?? $trackingCancelledDate;
        $isCancelled = !empty($cancelledDate);
        $cancelSource = null;
        if (!empty($billingCancelledDate) && !empty($trackingCancelledDate)) {
            $cancelSource = 'BOTH';
        } elseif (!empty($billingCancelledDate)) {
            $cancelSource = 'BILLING';
        } elseif (!empty($trackingCancelledDate)) {
            $cancelSource = 'TRACKING';
        }
        $status = $row->tracking_status ?? 'BATCHING';
        $outstanding = floatval($row->outstanding ?? 0);

        $paidAmount = 0;
        $balance = $outstanding;
        if ($status === 'PAID') {
            $paidAmount = $outstanding;
            $balance = 0;
        }

        $dueDays = isset($row->due_days) && (int) $row->due_days !== 0 ? (int) $row->due_days : null;

        return [
            'id' => $row->id,
            'batch_number' => $row->bat_number,
            'batch_date' => $row->bat_datecreated,
            'payer_name' => $row->inst_desc,
            'patient_name' => trim(($row->firstname ?? '') . ' ' . ($row->lastname ?? '')),
            'nationality' => $row->nationality,
            'adm_date' => $row->admdate,
            'adm_type' => $row->paadm_type,
            'mrn' => $row->urn,
            'episode_no' => $row->episodeno,
            'invoice_no' => $row->invoiceno,
            'invoice_date' => $row->arpbl_dateprinted,
            'invoice_printed' => $row->arpbl_dateprinted,
            'cancelled_date' => $cancelledDate,
            'before_discount' => floatval($row->beforediscount ?? 0),
            'after_discount' => floatval($row->afterdiscount ?? 0),
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'amount' => $outstanding,
            'total_insurance' => $outstanding,
            'due_days' => $dueDays,
            'status' => $status,
            'ref_no' => $row->ref_no,
            'courier_via' => $row->courier_via,
            'tracking_no' => $row->tracking_no,
            'sent_date' => $row->sent_date,
            'received_date' => $row->received_date,
            'paid_on' => $row->paid_on,
            'remarks' => $row->remarks,
            'is_cancelled' => $isCancelled,
            'cancel_source' => $cancelSource,
        ];
    }
}
