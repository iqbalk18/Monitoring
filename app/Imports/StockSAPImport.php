<?php

namespace App\Imports;

use App\Models\StockSAP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StockSAPImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new StockSAP([
            'Combine_Code' => $row['material'].$row['batch'].$row['storage_location'] ?? null,
            'Period_DateTime' => Carbon::now()->format('Y-m-d H:i:s'),
            'Material_Desc' => $row['material_description'] ?? null,
            'Material_Code' => $row['material'] ?? null,
            'Plant' => $row['plant'] ?? null,
            'Storage_Loc' => $row['storage_location'] ?? null,
            'Dfstor_loc_level' => $row['dfstor_loc_level'] ?? null,
            'Batch_No' => $row['batch'] ?? null,
            'BU_Code' => $row['base_unit_of_measure'] ?? null,
            'Qty' => $this->parseDecimal($row['unrestricted'] ?? null),
            'Stock_Segment' => $row['stock_segment'] ?? null,
            'Currency' => $row['currency'] ?? null,
            'Value_Unrestricted' => $this->parseDecimal($row['value_unrestricted'] ?? null),
            'Transit_Transfer' => $this->parseDecimal($row['transit_and_transfer'] ?? null),
            'Valin_Trans_Tfr' => $this->parseDecimal($row['val_in_transtfr'] ?? null),
            'Quality_Inspection' => $this->parseDecimal($row['quality_inspection'] ?? null),
            'Value_in_QualInsp' => $this->parseDecimal($row['value_in_qualinsp'] ?? null),
            'Restricted_UseStock' => $this->parseDecimal($row['restricted_use_stock'] ?? null),
            'Value_Restricted' => $this->parseDecimal($row['value_restricted'] ?? null),
            'Blocked' => $this->parseDecimal($row['blocked'] ?? null),
            'Value_BlockedStock' => $this->parseDecimal($row['value_blockedstock'] ?? null),
            'Returns' => $this->parseDecimal($row['returns'] ?? null),
            'Value_RetsBlocked' => $this->parseDecimal($row['value_rets_blocked'] ?? null),
        ]);
    }

    private function parseDecimal($value)
    {
        if (empty($value) || $value === null) {
            return null;
        }

        // Remove any non-numeric characters except decimal point and minus sign
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);
        
        if ($value === '' || $value === '-') {
            return null;
        }

        return (float) $value;
    }
}