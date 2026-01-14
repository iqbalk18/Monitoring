<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportService;

class CleanupTempFiles extends Command
{
    protected $signature = 'temp:cleanup {--age=24 : Maximum age in hours for files to keep}';
    
    protected $description = 'Cleanup old temporary import files';

    public function handle(ImportService $importService): int
    {
        $age = (int) $this->option('age');
        
        $this->info("Cleaning up temporary files older than {$age} hours...");
        
        $deleted = $importService->cleanupOldTempFiles($age);
        
        if ($deleted > 0) {
            $this->info("✓ Successfully deleted {$deleted} temporary file(s)");
        } else {
            $this->info("No temporary files to clean up");
        }
        
        return Command::SUCCESS;
    }
}








