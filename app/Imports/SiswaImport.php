<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Siswa([
            'nisn'     => $row['nisn'],
            'nama_siswa'    => $row['nama_siswa'],
            'kelas_id'    => $row['kelas_id'],
            'jurusan_id'    => $row['jurusan_id'],
            'password' => bcrypt($row['nisn']),
            'role' => 4,
            'is_active' => 1
        ]);
    }
}
