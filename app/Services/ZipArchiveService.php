<?php

namespace App\Services;

use ZipArchive;

class ZipArchiveService
{
    public static function addArchive($filename, $path, $files)
    {
        $zip = new ZipArchive();
        if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
            return 1;
        }
        $signature = 'Дата: ' . date('d.m.y') . "\n";

        $zip->addFromString("signature.txt", $signature);
        $zip->close();
        foreach ($files as $file) {
            $zip->open($filename);
            $zip->addFile($path . '/' . $file, $file);
            $zip->close();
        }

        return $zip->status;
    }
}
