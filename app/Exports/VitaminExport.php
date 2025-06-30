<?php

namespace App\Exports;

use App\Models\Vitamin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VitaminExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Vitamin::with(['anak', 'jenisVitamin'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Anak',
            'Tanggal Vitamin',
            'Jenis Vitamin',
            'Status',
            'Terdaftar Pada'
        ];
    }

    public function map($vitamin): array
    {
        return [
            $vitamin->anak->nama_anak ?? '-',
            $vitamin->tanggal ? $vitamin->tanggal->format('d/m/Y') : '-',
            $vitamin->jenisVitamin->nama ?? '-',
            $vitamin->status ?? '-',
            $vitamin->created_at->format('d/m/Y H:i')
        ];
    }
} 