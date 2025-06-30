<?php

namespace App\Exports;

use App\Models\Pengguna;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PenggunaExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    public function collection()
    {
        return Pengguna::with('anak')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Ibu',
            'Email',
            'No. Telp',
            'Alamat',
            'Nama Anak',
            'Usia Anak',
            'Jenis Kelamin Anak',
            'Tanggal Lahir Anak',
            'Tempat Lahir Anak',
            'Terdaftar Pada'
        ];
    }

    public function map($pengguna): array
    {
        $anak = $pengguna->anak->first();
        
        return [
            "'".$pengguna->nik,
            $pengguna->nama,
            $pengguna->email,
            $pengguna->no_telp,
            $pengguna->alamat,
            $anak ? $anak->nama_anak : '-',
            $anak ? $anak->usia : '-',
            $anak ? $anak->jenis_kelamin : '-',
            $anak ? $anak->tanggal_lahir->format('d/m/Y') : '-',
            $anak ? $anak->tempat_lahir : '-',
            $pengguna->created_at->format('d/m/Y H:i')
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'D' => '@'
        ];
    }
} 