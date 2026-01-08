<?php

namespace App\Services;

use App\Models\StockSAP;
use App\Models\StockTCINCItmLcBt;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportService
{
    private const CHUNK_SIZE = 1000;
    private string $progressKey;

    public function importSAP($file, string $sessionId): array
    {
        $this->progressKey = "import_progress_{$sessionId}";
        $imported = 0;
        $totalRows = 0;
        $startTime = microtime(true);
        $tempPath = null;

        DB::beginTransaction();
        try {
            // Simpan file temporary dengan ekstensi asli
            $tempPath = $this->saveTempFile($file);

            // Count total rows first
            $totalRows = SimpleExcelReader::create($tempPath)
                ->headersToSnakeCase()
                ->getRows()
                ->count();

            $this->updateProgress(0, $totalRows, 0, 'Memulai import SAP...');

            SimpleExcelReader::create($tempPath)
                ->headersToSnakeCase()
                ->getRows()
                ->chunk(self::CHUNK_SIZE)
                ->each(function ($chunk) use (&$imported, $totalRows, $startTime) {
                    $data = $chunk->map(fn($row) => $this->mapSAPData($row))->toArray();
                    StockSAP::insert($data);
                    $imported += count($data);
                    
                    $percentage = round(($imported / $totalRows) * 100, 2);
                    $duration = microtime(true) - $startTime;
                    $this->updateProgress($imported, $totalRows, $percentage, "Importing SAP data... {$imported}/{$totalRows}");
                });

            DB::commit();
            $duration = round(microtime(true) - $startTime, 2);

            $this->updateProgress($imported, $totalRows, 100, 'Import completed!');

            return [
                'success' => true,
                'imported' => $imported,
                'duration' => $duration,
                'message' => "Data SAP berhasil diimport! {$imported} records dalam {$duration} detik"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->updateProgress($imported, $totalRows, 0, 'Error: ' . $e->getMessage(), true);
            return [
                'success' => false,
                'message' => 'Error saat import SAP: ' . $e->getMessage()
            ];
        } finally {
            // Hapus file temporary
            $this->deleteTempFile($tempPath);
        }
    }

    public function importTrakCare($file, string $sessionId): array
    {
        $this->progressKey = "import_progress_{$sessionId}";
        $imported = 0;
        $totalRows = 0;
        $startTime = microtime(true);
        $tempPath = null;

        DB::beginTransaction();
        try {
            // Simpan file temporary dengan ekstensi asli
            $tempPath = $this->saveTempFile($file);

            // Count total rows first
            $totalRows = SimpleExcelReader::create($tempPath)
                ->headersToSnakeCase()
                ->getRows()
                ->count();

            $this->updateProgress(0, $totalRows, 0, 'Memulai import TrakCare...');

            SimpleExcelReader::create($tempPath)
                ->headersToSnakeCase()
                ->getRows()
                ->chunk(self::CHUNK_SIZE)
                ->each(function ($chunk) use (&$imported, $totalRows, $startTime) {
                    $data = $chunk->map(fn($row) => $this->mapTrakCareData($row))->toArray();
                    StockTCINCItmLcBt::insert($data);
                    $imported += count($data);
                    
                    $percentage = round(($imported / $totalRows) * 100, 2);
                    $this->updateProgress($imported, $totalRows, $percentage, "Importing TrakCare data... {$imported}/{$totalRows}");
                });

            DB::commit();
            $duration = round(microtime(true) - $startTime, 2);

            $this->updateProgress($imported, $totalRows, 100, 'Import completed!');

            return [
                'success' => true,
                'imported' => $imported,
                'duration' => $duration,
                'message' => "Data TrakCare berhasil diimport! {$imported} records dalam {$duration} detik"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->updateProgress($imported, $totalRows, 0, 'Error: ' . $e->getMessage(), true);
            return [
                'success' => false,
                'message' => 'Error saat import TrakCare: ' . $e->getMessage()
            ];
        } finally {
            // Hapus file temporary
            $this->deleteTempFile($tempPath);
        }
    }

    private function mapSAPData(array $row): array
    {
        $now = Carbon::now();

        return [
            'Combine_Code' => ($row['material'] ?? '') . ($row['batch'] ?? '') . ($row['storage_location'] ?? ''),
            'Period_DateTime' => $now,
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
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    private function mapTrakCareData(array $row): array
    {
        $now = Carbon::now();

        return [
            'Combine_Code' => ($row['inclb_inci_code'] ?? '') . ($row['inclb_incib_no'] ?? '') . ($row['inclb_ctloc_code'] ?? ''),
            'Period_DateTime' => $now,
            'INCLB_INCI_Code' => $row['inclb_inci_code'] ?? null,
            'INCLB_INCI_Desc' => $row['inclb_inci_desc'] ?? null,
            'INCLB_INCIB_No' => $row['inclb_incib_no'] ?? null,
            'INCLB_INCIB_ExpDate' => $this->parseDate($row['inclb_incib_expdate'] ?? null),
            'INCLB_CTLOC_Code' => $row['inclb_ctloc_code'] ?? null,
            'INCLB_CTLOC_Desc' => $row['inclb_ctloc_desc'] ?? null,
            'INCLB_PhyQty' => $this->parseDecimal($row['inclb_phyqty'] ?? null),
            'CTUOM_Code' => $row['ctuom_code'] ?? null,
            'CTUOM_Desc' => $row['ctuom_desc'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    private function parseDecimal(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9.-]/', '', (string) $value);

        return ($cleaned === '' || $cleaned === '-') ? null : (float) $cleaned;
    }

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            }

            $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd-m-Y', 'm-d-Y'];
            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
                } catch (\Exception $e) {
                    continue;
                }
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function saveTempFile($file): string
    {
        $tempDir = storage_path('app/temp');
        
        // Buat direktori jika belum ada
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Generate nama file dengan timestamp untuk menghindari konflik
        $filename = time() . '_' . $file->getClientOriginalName();
        $tempPath = $tempDir . '/' . $filename;

        // Pindahkan file ke temporary directory
        $file->move($tempDir, $filename);

        return $tempPath;
    }

    private function deleteTempFile(?string $path): void
    {
        if (!$path || !file_exists($path)) {
            return;
        }

        // Windows file locking workaround: try multiple times with delay
        $maxAttempts = 10;
        $attempt = 0;
        
        while ($attempt < $maxAttempts) {
            try {
                // Force garbage collection to release file handles
                gc_collect_cycles();
                
                // Small delay to allow file handles to close
                usleep(50000); // 50ms
                
                // Suppress errors and try to delete
                if (@unlink($path)) {
                    return; // Success!
                }
                
                $attempt++;
                
                // Exponential backoff
                usleep(50000 * $attempt);
                
            } catch (\Exception $e) {
                $attempt++;
            }
        }
        
        // If all attempts failed, register file for later cleanup
        $this->registerForCleanup($path);
    }

    private function registerForCleanup(string $path): void
    {
        $tempDir = storage_path('app/temp');
        $cleanupFile = $tempDir . '/.cleanup';
        
        // Create cleanup registry file
        if (!file_exists($cleanupFile)) {
            @file_put_contents($cleanupFile, '');
        }
        
        // Add file to cleanup list
        $files = @file($cleanupFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $files[] = basename($path);
        @file_put_contents($cleanupFile, implode(PHP_EOL, array_unique($files)));
    }

    private function updateProgress(int $imported, int $total, float $percentage, string $message, bool $isError = false): void
    {
        $progress = [
            'imported' => $imported,
            'total' => $total,
            'percentage' => $percentage,
            'message' => $message,
            'is_error' => $isError,
            'timestamp' => time()
        ];

        session()->put($this->progressKey, $progress);
        session()->save(); // Force save immediately
    }

    public function getProgress(string $sessionId): array
    {
        $key = "import_progress_{$sessionId}";
        return session()->get($key, [
            'imported' => 0,
            'total' => 0,
            'percentage' => 0,
            'message' => 'Initializing...',
            'is_error' => false
        ]);
    }

    public function clearProgress(string $sessionId): void
    {
        $key = "import_progress_{$sessionId}";
        session()->forget($key);
    }

    public function cleanupOldTempFiles(int $maxAgeHours = 24): int
    {
        $tempDir = storage_path('app/temp');
        $cleanupFile = $tempDir . '/.cleanup';
        $deleted = 0;
        
        if (!file_exists($tempDir)) {
            return 0;
        }

        // Clean files from cleanup registry
        if (file_exists($cleanupFile)) {
            $files = file($cleanupFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($files as $file) {
                $filePath = $tempDir . '/' . $file;
                if (file_exists($filePath)) {
                    // Force garbage collection before delete
                    gc_collect_cycles();
                    usleep(100000); // 100ms
                    
                    if (@unlink($filePath)) {
                        $deleted++;
                    }
                }
            }
            // Clear cleanup registry
            @unlink($cleanupFile);
        }

        // Clean all old files in temp directory
        $files = glob($tempDir . '/*');
        $maxAge = time() - ($maxAgeHours * 3600);
        
        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $maxAge) {
                gc_collect_cycles();
                usleep(50000); // 50ms
                
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }

        Log::info("Temp cleanup completed", ['deleted' => $deleted]);
        
        return $deleted;
    }
}

