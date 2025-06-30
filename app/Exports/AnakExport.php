<?php

namespace App\Exports;

use App\Models\Anak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnakExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Anak::with('pengguna')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Anak',
            'Nama Ibu',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Usia',
            'Jenis Kelamin',
            'Terdaftar Pada'
        ];
    }

    public function map($anak): array
    {
        return [
            "'".$anak->pengguna->nik ?? '-',
            $anak->nama_anak ?? '-',
            $anak->pengguna->nama ?? '-',
            $anak->tempat_lahir ?? '-',
            $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d/m/Y') : '-',
            $anak->usia ?? '-',
            $anak->jenis_kelamin ?? '-',
            $anak->created_at->format('d/m/Y H:i')
        ];
    }
} 