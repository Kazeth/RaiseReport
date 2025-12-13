<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Thread;
use App\Services\SupabaseStorage;

class FileSeeder extends Seeder
{
    public function run()
    {
        $storage = new SupabaseStorage();

        $mockDir = database_path('seeders/mock_files');
        $mockFiles = array_values(array_diff(scandir($mockDir), ['.', '..']));

        if (empty($mockFiles)) {
            $this->command->warn('❌ mock_files kosong');
            return;
        }

        $threads = Thread::all();
        if ($threads->isEmpty()) {
            $this->command->warn('❌ Thread belum ada');
            return;
        }

        // jumlah mock attachment
        for ($i = 0; $i < 50; $i++) {

            $thread = $threads->random();
            $fileName = $mockFiles[array_rand($mockFiles)];
            $localPath = $mockDir . '/' . $fileName;

            if (!file_exists($localPath)) {
                continue;
            }

            // path di Supabase (SAMA DENGAN USER UPLOAD)
            $supabasePath = 'threads/' . uniqid() . '_' . $fileName;

            // upload ke Supabase
            $publicUrl = $storage->uploadFromPath(
                $localPath,
                $supabasePath
            );

            // simpan ke database
            File::create([
                'thread_id' => $thread->id,
                'fileName'  => $fileName,
                'path'      => $publicUrl, // ⬅️ URL SUPABASE
                'mime_type' => mime_content_type($localPath),
                'file_size' => filesize($localPath),
                'extension' => pathinfo($fileName, PATHINFO_EXTENSION),
            ]);
        }

        $this->command->info('FileSeeder Supabase done');
    }
}
