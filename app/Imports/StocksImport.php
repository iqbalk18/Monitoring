<?php

namespace App\Imports;

use App\Models\StockImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StocksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $expiredDate = null;

            if (!empty($row['expireddate'])) { 
                $value = $row['expireddate'];

                if (is_numeric($value)) {
                    $expiredDate = ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
                } else {
                    $expiredDate = Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
                }
            }

        return new StockImport([
            'materialDocument'      => $row['materialdocument'] ?? null,
            'movementType'          => $row['movementtype'] ?? null,
            'specialStockIndicator' => $row['specialstockindicator'] ?? null,
            'indicator'             => $row['indicator'] ?? null,
            'material'              => $row['material'] ?? null,
            'sloc'                  => $row['sloc'] ?? null,
            'batch'                 => $row['batch'] ?? null,
            'expiredDate'           => $expiredDate,
            'expiredDateFreeText'   => $row['expireddatefreetext'] ?? null,
            'qty'                   => $row['qty'] ?? null,
            'uom'                   => $row['uom'] ?? null,
            'qtySku'                => $row['qtysku'] ?? null,
            'uomSku'                => $row['uomsku'] ?? null,
            'currency'              => $row['currency'] ?? null,
            'poBasePricePerUnit'    => $row['pobasepriceperunit'] ?? null,
            'poDiscountPerUnit'     => $row['podiscountperunit'] ?? null,
            'amountInLocalCurrency' => $row['amountinlocalcurrency'] ?? null,
            'map'                   => $row['map'] ?? null,
            'taxCode'               => $row['taxcode'] ?? null,
            'taxRate'               => $row['taxrate'] ?? null,
        ]);
    }
}
