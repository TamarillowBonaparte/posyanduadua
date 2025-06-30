<?php

namespace App\Exports;

use App\Models\Stunting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StuntingExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Stunting::with(['anak', 'perkembangan'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Anak',
            'Tanggal Pemeriksaan',
            'Usia',
            'Tinggi Badan',
            'Berat Badan',
            'Status',
            'Keterangan',
            'Terdaftar Pada'
        ];
    }

    public function map($stunting): array
    {
        return [
            $stunting->anak->nama_anak ?? '-',
            $stunting->tanggal ? $stunting->tanggal->format('d/m/Y') : '-',
            $stunting->usia ?? '-',
            ($stunting->perkembangan->tinggi_badan ?? $stunting->tinggi_badan) . ' cm',
            ($stunting->perkembangan->berat_badan ?? $stunting->berat_badan) . ' kg',
            $stunting->status ?? '-',
            $stunting->catatan ?? '-',
            $stunting->created_at->format('d/m/Y H:i')
        ];
    }
} 