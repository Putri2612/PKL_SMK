<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function downloadFile($filename)
    {
        // Contoh: Ambil file dari direktori "public/files"
        $filePath = public_path('assets/files/' . $filename);

        if (file_exists($filePath)) {
            // Jika file ditemukan, lakukan proses download
            return response()->download($filePath, $filename);
        } else {
            // Jika file tidak ditemukan, tampilkan response 404
            return abort(404);
        }
    }
}
