<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Thread;

class FileSeeder extends Seeder
{
    public function run()
    {
        $mockFiles = array_values(array_diff(
            scandir(database_path('seeders/mock_files')),
            ['.', '..']
        ));

        if (empty($mockFiles)) {
            dump("❌ Tidak ada file dalam mock_files");
            return;
        }

        $threads = Thread::all();
        if ($threads->isEmpty()) {
            dump("❌ Thread belum ada. Jalankan ThreadSeeder dulu.");
            return;
        }

        // Buat 100 file random
        for ($i = 0; $i < 100; $i++) {

            // Ambil file mock random
            $f = $mockFiles[array_rand($mockFiles)];

            $source = database_path("seeders/mock_files/{$f}");
            if (!file_exists($source)) continue;

            // Pilih thread random
            $thread = $threads->random();

            // Generate nama baru di storage
            $destPath = 'attachments/' . uniqid() . "_" . $f;
            $destFull = storage_path("app/public/{$destPath}");

            // Buat folder jika belum ada
            if (!file_exists(dirname($destFull))) {
                mkdir(dirname($destFull), 0755, true);
            }

            // Copy file ke storage/public
            copy($source, $destFull);

            // Simpan ke database
            File::create([
                'thread_id' => $thread->id,
                'fileName' => $f,
                'path' => $destPath,
                'mime_type' => mime_content_type($destFull),
                'file_size' => filesize($destFull),
                'extension' => pathinfo($f, PATHINFO_EXTENSION),
            ]);
        }
    }
}
