<?php

namespace App\Imports;

use App\Models\StockTCINCItmLcBt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StockTCINCItmLcBtImport implements ToModel, WithHeadingRow
{
  public function model(array $row)
  {
    return new StockTCINCItmLcBt([
      'Combine_Code' => $row['inclb_inci_code'].$row['inclb_incib_no'].$row['inclb_ctloc_code'] ?? null,
      'Period_DateTime' => Carbon::now()->format('Y-m-d H:i:s'),
      'INCLB_INCI_Code' => $row['inclb_inci_code'] ?? null,
      'INCLB_INCI_Desc' => $row['inclb_inci_desc'] ?? null,
      'INCLB_INCIB_No' => $row['inclb_incib_no'] ?? null,
      'INCLB_INCIB_ExpDate' => Carbon::parse($row['inclb_incib_expdate'])->format('Y-m-d'),
      'INCLB_CTLOC_Code' => $row['inclb_ctloc_code'] ?? null,
      'INCLB_CTLOC_Desc' => $row['inclb_ctloc_desc'] ?? null,
      'INCLB_PhyQty' => $this->parseDecimal($row['inclb_phyqty'] ?? null),
      'CTUOM_Code' => $row['ctuom_code'] ?? null,
      'CTUOM_Desc' => $row['ctuom_desc'] ?? null,
    ]);
  }

  private function parseDate($value)
  {
    if (empty($value) || $value === null) {
      return null;
    }

    try {
      if (is_numeric($value)) {
        return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
      } else {
        // Try different date formats
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd-m-Y', 'm-d-Y'];
        foreach ($formats as $format) {
          try {
            return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
          } catch (\Exception $e) {
            continue;
          }
        }
        // If all formats fail, try Carbon parse
        return Carbon::parse($value)->format('Y-m-d');
      }
    } catch (\Exception $e) {
      return null;
    }
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
