<?php

namespace App\Exports;

use App\Models\DuDi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DuDiExport implements FromView
{
    public function view(): View
    {
        return view('ekspor.dudi', [
            'dudi' => DuDi::all()
        ]);
    }
}
