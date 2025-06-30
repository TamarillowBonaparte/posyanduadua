<?php

namespace App\Exports;

use App\Models\Imunisasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImunisasiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Imunisasi::with(['anak', 'jenisImunisasi'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Anak',
            'Tanggal Imunisasi',
            'Jenis Imunisasi',
            'Status',
            'Terdaftar Pada'
        ];
    }

    public function map($imunisasi): array
    {
        return [
            $imunisasi->anak->nama_anak ?? '-',
            $imunisasi->tanggal ? $imunisasi->tanggal->format('d/m/Y') : '-',
            $imunisasi->jenisImunisasi->nama ?? '-',
            $imunisasi->status ?? '-',
            $imunisasi->created_at->format('d/m/Y H:i')
        ];
    }
} 