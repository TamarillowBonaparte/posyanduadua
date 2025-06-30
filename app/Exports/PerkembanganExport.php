<?php

namespace App\Exports;

use App\Models\PerkembanganAnak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerkembanganExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PerkembanganAnak::with(['anak', 'anak.pengguna'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Anak',
            'Nama Ibu',
            'Tempat Lahir',
            'Tanggal',
            'Berat Badan (kg)',
            'Tinggi Badan (cm)',
            'Terdaftar Pada'
        ];
    }

    public function map($perkembangan): array
    {
        return [
            $perkembangan->anak->nama_anak ?? '-',
            $perkembangan->anak->pengguna->nama ?? '-',
            $perkembangan->anak->tempat_lahir ?? '-',
            $perkembangan->tanggal ? $perkembangan->tanggal->format('d/m/Y') : '-',
            $perkembangan->berat_badan ?? '-',
            $perkembangan->tinggi_badan ?? '-',
            $perkembangan->created_at->format('d/m/Y H:i')
        ];
    }
} 