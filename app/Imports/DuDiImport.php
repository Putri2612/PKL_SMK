<?php

namespace App\Imports;

use App\Models\DuDi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DuDiImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new DuDi([
            'nama_dudi'    => $row['nama_dudi'],
            'no_telp'    => $row['no_telp'],
            'email'    => $row['email'],
            'alamat'    => $row['alamat'],
            'password' => bcrypt('123'),
            'jurusan_id'    => $row['jurusan_id'],
            'role' => 5,
            'is_active' => 1
        ]);
    }
}
