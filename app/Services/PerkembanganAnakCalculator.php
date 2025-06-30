<?php

namespace App\Services;

class PerkembanganAnakCalculator
{
    // Konstanta untuk status
    const STATUS_SANGAT_KURANG = 'Sangat Kurang';
    const STATUS_KURANG = 'Kurang';
    const STATUS_RESIKO_KURANG = 'Resiko Kurang';
    const STATUS_NORMAL = 'Normal';
    const STATUS_RESIKO_LEBIH = 'Resiko Lebih';
    const STATUS_LEBIH = 'Lebih';
    const STATUS_SANGAT_LEBIH = 'Sangat Lebih';

    // Array untuk menyimpan rentang tinggi badan berdasarkan usia
    private $tinggiRanges = [
        0 => [
            'sangat_kurang' => ['min' => 0, 'max' => 44.2],
            'kurang' => ['min' => 44.3, 'max' => 46.1],
            'resiko_kurang' => ['min' => 46.2, 'max' => 47.9],
            'normal' => ['min' => 48.0, 'max' => 51.8],
            'resiko_lebih' => ['min' => 51.9, 'max' => 53.6],
            'lebih' => ['min' => 53.7, 'max' => 55.5],
            'sangat_lebih' => ['min' => 55.6, 'max' => 999]
        ],
        1 => [
            'sangat_kurang' => ['min' => 0, 'max' => 48.9],
            'kurang' => ['min' => 49.0, 'max' => 50.8],
            'resiko_kurang' => ['min' => 50.9, 'max' => 52.7],
            'normal' => ['min' => 52.8, 'max' => 56.7],
            'resiko_lebih' => ['min' => 56.8, 'max' => 58.5],
            'lebih' => ['min' => 58.6, 'max' => 60.5],
            'sangat_lebih' => ['min' => 60.6, 'max' => 999]
        ],
        2 => [
            'sangat_kurang' => ['min' => 0, 'max' => 52.4],
            'kurang' => ['min' => 52.5, 'max' => 54.4],
            'resiko_kurang' => ['min' => 54.5, 'max' => 56.3],
            'normal' => ['min' => 56.4, 'max' => 60.4],
            'resiko_lebih' => ['min' => 60.5, 'max' => 62.3],
            'lebih' => ['min' => 62.4, 'max' => 64.3],
            'sangat_lebih' => ['min' => 64.4, 'max' => 999]
        ],
        3 => [
            'sangat_kurang' => ['min' => 0, 'max' => 55.3],
            'kurang' => ['min' => 55.4, 'max' => 57.2],
            'resiko_kurang' => ['min' => 57.3, 'max' => 59.3],
            'normal' => ['min' => 59.4, 'max' => 63.5],
            'resiko_lebih' => ['min' => 63.6, 'max' => 65.4],
            'lebih' => ['min' => 65.5, 'max' => 67.5],
            'sangat_lebih' => ['min' => 67.6, 'max' => 999]
        ],
        4 => [
            'sangat_kurang' => ['min' => 0, 'max' => 57.6],
            'kurang' => ['min' => 57.7, 'max' => 59.6],
            'resiko_kurang' => ['min' => 59.7, 'max' => 61.7],
            'normal' => ['min' => 61.8, 'max' => 66.0],
            'resiko_lebih' => ['min' => 66.1, 'max' => 67.9],
            'lebih' => ['min' => 68.0, 'max' => 70.0],
            'sangat_lebih' => ['min' => 70.1, 'max' => 999]
        ],
        5 => [
            'sangat_kurang' => ['min' => 0, 'max' => 59.6],
            'kurang' => ['min' => 59.7, 'max' => 61.6],
            'resiko_kurang' => ['min' => 61.7, 'max' => 63.7],
            'normal' => ['min' => 63.8, 'max' => 68.0],
            'resiko_lebih' => ['min' => 68.1, 'max' => 70.0],
            'lebih' => ['min' => 70.1, 'max' => 72.1],
            'sangat_lebih' => ['min' => 72.2, 'max' => 999]
        ],
        6 => [
            'sangat_kurang' => ['min' => 0, 'max' => 61.2],
            'kurang' => ['min' => 61.3, 'max' => 63.2],
            'resiko_kurang' => ['min' => 63.3, 'max' => 65.4],
            'normal' => ['min' => 65.5, 'max' => 69.8],
            'resiko_lebih' => ['min' => 69.9, 'max' => 71.8],
            'lebih' => ['min' => 71.9, 'max' => 73.9],
            'sangat_lebih' => ['min' => 74.0, 'max' => 999]
        ],
        7 => [
            'sangat_kurang' => ['min' => 0, 'max' => 62.7],
            'kurang' => ['min' => 62.8, 'max' => 64.7],
            'resiko_kurang' => ['min' => 64.8, 'max' => 66.9],
            'normal' => ['min' => 67.0, 'max' => 71.3],
            'resiko_lebih' => ['min' => 71.4, 'max' => 73.4],
            'lebih' => ['min' => 73.5, 'max' => 75.6],
            'sangat_lebih' => ['min' => 75.7, 'max' => 999]
        ],
        8 => [
            'sangat_kurang' => ['min' => 0, 'max' => 64.0],
            'kurang' => ['min' => 64.1, 'max' => 66.1],
            'resiko_kurang' => ['min' => 66.2, 'max' => 68.3],
            'normal' => ['min' => 68.4, 'max' => 72.8],
            'resiko_lebih' => ['min' => 72.9, 'max' => 74.9],
            'lebih' => ['min' => 75.0, 'max' => 77.1],
            'sangat_lebih' => ['min' => 77.2, 'max' => 999]
        ],
        9 => [
            'sangat_kurang' => ['min' => 0, 'max' => 65.2],
            'kurang' => ['min' => 65.3, 'max' => 67.4],
            'resiko_kurang' => ['min' => 67.5, 'max' => 69.6],
            'normal' => ['min' => 69.7, 'max' => 74.2],
            'resiko_lebih' => ['min' => 74.3, 'max' => 76.4],
            'lebih' => ['min' => 76.5, 'max' => 78.6],
            'sangat_lebih' => ['min' => 78.7, 'max' => 999]
        ],
        10 => [
            'sangat_kurang' => ['min' => 0, 'max' => 66.4],
            'kurang' => ['min' => 66.5, 'max' => 68.6],
            'resiko_kurang' => ['min' => 68.7, 'max' => 70.9],
            'normal' => ['min' => 71.0, 'max' => 75.6],
            'resiko_lebih' => ['min' => 75.7, 'max' => 77.8],
            'lebih' => ['min' => 77.9, 'max' => 80.0],
            'sangat_lebih' => ['min' => 80.1, 'max' => 999]
        ],
        11 => [
            'sangat_kurang' => ['min' => 0, 'max' => 67.6],
            'kurang' => ['min' => 67.7, 'max' => 69.8],
            'resiko_kurang' => ['min' => 69.9, 'max' => 72.1],
            'normal' => ['min' => 72.2, 'max' => 76.9],
            'resiko_lebih' => ['min' => 77.0, 'max' => 79.1],
            'lebih' => ['min' => 79.2, 'max' => 81.4],
            'sangat_lebih' => ['min' => 81.5, 'max' => 999]
        ],
        12 => [
            'sangat_kurang' => ['min' => 0, 'max' => 68.6],
            'kurang' => ['min' => 68.7, 'max' => 70.9],
            'resiko_kurang' => ['min' => 71.0, 'max' => 73.3],
            'normal' => ['min' => 73.4, 'max' => 78.1],
            'resiko_lebih' => ['min' => 78.2, 'max' => 80.4],
            'lebih' => ['min' => 80.5, 'max' => 82.8],
            'sangat_lebih' => ['min' => 82.9, 'max' => 999]
        ],
        13 => [
            'sangat_kurang' => ['min' => 0, 'max' => 69.6],
            'kurang' => ['min' => 69.7, 'max' => 72.0],
            'resiko_kurang' => ['min' => 72.1, 'max' => 74.4],
            'normal' => ['min' => 74.5, 'max' => 79.3],
            'resiko_lebih' => ['min' => 79.4, 'max' => 81.7],
            'lebih' => ['min' => 81.8, 'max' => 84.1],
            'sangat_lebih' => ['min' => 84.2, 'max' => 999]
        ],
        14 => [
            'sangat_kurang' => ['min' => 0, 'max' => 70.6],
            'kurang' => ['min' => 70.7, 'max' => 73.0],
            'resiko_kurang' => ['min' => 73.1, 'max' => 75.5],
            'normal' => ['min' => 75.6, 'max' => 80.5],
            'resiko_lebih' => ['min' => 80.6, 'max' => 82.9],
            'lebih' => ['min' => 83.0, 'max' => 85.4],
            'sangat_lebih' => ['min' => 85.5, 'max' => 999]
        ],
        15 => [
            'sangat_kurang' => ['min' => 0, 'max' => 71.5],
            'kurang' => ['min' => 71.6, 'max' => 74.0],
            'resiko_kurang' => ['min' => 74.1, 'max' => 76.5],
            'normal' => ['min' => 76.6, 'max' => 81.7],
            'resiko_lebih' => ['min' => 81.8, 'max' => 84.1],
            'lebih' => ['min' => 84.2, 'max' => 86.6],
            'sangat_lebih' => ['min' => 86.7, 'max' => 999]
        ],
        16 => [
            'sangat_kurang' => ['min' => 0, 'max' => 72.5],
            'kurang' => ['min' => 72.6, 'max' => 74.9],
            'resiko_kurang' => ['min' => 75.0, 'max' => 77.5],
            'normal' => ['min' => 77.6, 'max' => 82.8],
            'resiko_lebih' => ['min' => 82.9, 'max' => 85.3],
            'lebih' => ['min' => 85.4, 'max' => 87.9],
            'sangat_lebih' => ['min' => 88.0, 'max' => 999]
        ],
        17 => [
            'sangat_kurang' => ['min' => 0, 'max' => 73.3],
            'kurang' => ['min' => 73.4, 'max' => 75.9],
            'resiko_kurang' => ['min' => 76.0, 'max' => 78.5],
            'normal' => ['min' => 78.6, 'max' => 83.9],
            'resiko_lebih' => ['min' => 84.0, 'max' => 86.4],
            'lebih' => ['min' => 86.5, 'max' => 89.1],
            'sangat_lebih' => ['min' => 89.2, 'max' => 999]
        ],
        18 => [
            'sangat_kurang' => ['min' => 0, 'max' => 74.2],
            'kurang' => ['min' => 74.3, 'max' => 76.8],
            'resiko_kurang' => ['min' => 76.9, 'max' => 79.5],
            'normal' => ['min' => 79.6, 'max' => 85.0],
            'resiko_lebih' => ['min' => 85.1, 'max' => 87.6],
            'lebih' => ['min' => 87.7, 'max' => 90.3],
            'sangat_lebih' => ['min' => 90.4, 'max' => 999]
        ],
        19 => [
            'sangat_kurang' => ['min' => 0, 'max' => 75.0],
            'kurang' => ['min' => 75.1, 'max' => 77.6],
            'resiko_kurang' => ['min' => 77.7, 'max' => 80.4],
            'normal' => ['min' => 80.5, 'max' => 86.0],
            'resiko_lebih' => ['min' => 86.1, 'max' => 88.7],
            'lebih' => ['min' => 88.8, 'max' => 91.4],
            'sangat_lebih' => ['min' => 91.5, 'max' => 999]
        ],
        20 => [
            'sangat_kurang' => ['min' => 0, 'max' => 75.8],
            'kurang' => ['min' => 75.9, 'max' => 78.5],
            'resiko_kurang' => ['min' => 78.6, 'max' => 81.3],
            'normal' => ['min' => 81.4, 'max' => 87.0],
            'resiko_lebih' => ['min' => 87.1, 'max' => 89.7],
            'lebih' => ['min' => 89.8, 'max' => 92.5],
            'sangat_lebih' => ['min' => 92.6, 'max' => 999]
        ],
        21 => [
            'sangat_kurang' => ['min' => 0, 'max' => 76.5],
            'kurang' => ['min' => 76.6, 'max' => 79.3],
            'resiko_kurang' => ['min' => 79.4, 'max' => 82.2],
            'normal' => ['min' => 82.3, 'max' => 88.0],
            'resiko_lebih' => ['min' => 88.1, 'max' => 90.8],
            'lebih' => ['min' => 90.9, 'max' => 93.7],
            'sangat_lebih' => ['min' => 93.8, 'max' => 999]
        ],
        22 => [
            'sangat_kurang' => ['min' => 0, 'max' => 77.2],
            'kurang' => ['min' => 77.3, 'max' => 80.1],
            'resiko_kurang' => ['min' => 80.2, 'max' => 83.0],
            'normal' => ['min' => 83.1, 'max' => 89.0],
            'resiko_lebih' => ['min' => 89.1, 'max' => 91.8],
            'lebih' => ['min' => 91.9, 'max' => 94.8],
            'sangat_lebih' => ['min' => 94.9, 'max' => 999]
        ],
        23 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.0],
            'kurang' => ['min' => 78.1, 'max' => 81.5],
            'resiko_kurang' => ['min' => 81.6, 'max' => 83.8],
            'normal' => ['min' => 83.9, 'max' => 89.9],
            'resiko_lebih' => ['min' => 90.0, 'max' => 92.8],
            'lebih' => ['min' => 92.9, 'max' => 95.8],
            'sangat_lebih' => ['min' => 95.9, 'max' => 999]
        ],
        24 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.7],
            'kurang' => ['min' => 78.8, 'max' => 81.6],
            'resiko_kurang' => ['min' => 81.7, 'max' => 84.7],
            'normal' => ['min' => 84.8, 'max' => 90.9],
            'resiko_lebih' => ['min' => 91.0, 'max' => 93.8],
            'lebih' => ['min' => 93.9, 'max' => 96.9],
            'sangat_lebih' => ['min' => 97.0, 'max' => 999]
        ],
        25 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.8],
            'kurang' => ['min' => 79.0, 'max' => 81.8],
            'resiko_kurang' => ['min' => 81.9, 'max' => 84.9],
            'normal' => ['min' => 85.0, 'max' => 91.1],
            'resiko_lebih' => ['min' => 91.2, 'max' => 94.0],
            'lebih' => ['min' => 94.1, 'max' => 97.1],
            'sangat_lebih' => ['min' => 97.2, 'max' => 999]
        ],
        26 => [
            'sangat_kurang' => ['min' => 0, 'max' => 79.3],
            'kurang' => ['min' => 79.4, 'max' => 82.4],
            'resiko_kurang' => ['min' => 82.5, 'max' => 85.5],
            'normal' => ['min' => 85.6, 'max' => 92.0],
            'resiko_lebih' => ['min' => 92.1, 'max' => 95.1],
            'lebih' => ['min' => 95.2, 'max' => 98.2],
            'sangat_lebih' => ['min' => 98.3, 'max' => 999]
        ],
        27 => [
            'sangat_kurang' => ['min' => 0, 'max' => 79.9],
            'kurang' => ['min' => 80.0, 'max' => 83.0],
            'resiko_kurang' => ['min' => 83.1, 'max' => 86.3],
            'normal' => ['min' => 86.4, 'max' => 92.9],
            'resiko_lebih' => ['min' => 93.0, 'max' => 96.0],
            'lebih' => ['min' => 96.1, 'max' => 99.2],
            'sangat_lebih' => ['min' => 99.3, 'max' => 999]
        ],
        28 => [
            'sangat_kurang' => ['min' => 0, 'max' => 80.5],
            'kurang' => ['min' => 80.6, 'max' => 83.7],
            'resiko_kurang' => ['min' => 83.8, 'max' => 87.0],
            'normal' => ['min' => 87.1, 'max' => 93.7],
            'resiko_lebih' => ['min' => 93.8, 'max' => 96.9],
            'lebih' => ['min' => 97.0, 'max' => 100.0],
            'sangat_lebih' => ['min' => 100.1, 'max' => 999]
        ],
        29 => [
            'sangat_kurang' => ['min' => 0, 'max' => 81.1],
            'kurang' => ['min' => 81.2, 'max' => 84.4],
            'resiko_kurang' => ['min' => 84.5, 'max' => 87.7],
            'normal' => ['min' => 87.8, 'max' => 94.5],
            'resiko_lebih' => ['min' => 94.6, 'max' => 97.8],
            'lebih' => ['min' => 97.9, 'max' => 101.1],
            'sangat_lebih' => ['min' => 101.2, 'max' => 999]
        ],
        30 => [
            'sangat_kurang' => ['min' => 0, 'max' => 81.7],
            'kurang' => ['min' => 81.8, 'max' => 85.0],
            'resiko_kurang' => ['min' => 85.1, 'max' => 88.4],
            'normal' => ['min' => 88.5, 'max' => 95.3],
            'resiko_lebih' => ['min' => 95.4, 'max' => 98.6],
            'lebih' => ['min' => 98.7, 'max' => 102.0],
            'sangat_lebih' => ['min' => 102.1, 'max' => 999]
        ],
        31 => [
            'sangat_kurang' => ['min' => 0, 'max' => 82.2],
            'kurang' => ['min' => 82.3, 'max' => 85.6],
            'resiko_kurang' => ['min' => 85.7, 'max' => 89.0],
            'normal' => ['min' => 89.1, 'max' => 96.1],
            'resiko_lebih' => ['min' => 96.2, 'max' => 99.4],
            'lebih' => ['min' => 99.5, 'max' => 102.8],
            'sangat_lebih' => ['min' => 102.9, 'max' => 999]
        ],
        32 => [
            'sangat_kurang' => ['min' => 0, 'max' => 82.8],
            'kurang' => ['min' => 82.9, 'max' => 86.3],
            'resiko_kurang' => ['min' => 86.4, 'max' => 89.8],
            'normal' => ['min' => 89.9, 'max' => 96.9],
            'resiko_lebih' => ['min' => 97.0, 'max' => 100.3],
            'lebih' => ['min' => 100.4, 'max' => 103.7],
            'sangat_lebih' => ['min' => 103.8, 'max' => 999]
        ],
        33 => [
            'sangat_kurang' => ['min' => 0, 'max' => 83.4],
            'kurang' => ['min' => 83.5, 'max' => 86.8],
            'resiko_kurang' => ['min' => 86.9, 'max' => 90.4],
            'normal' => ['min' => 90.5, 'max' => 97.6],
            'resiko_lebih' => ['min' => 97.7, 'max' => 101.1],
            'lebih' => ['min' => 101.2, 'max' => 104.7],
            'sangat_lebih' => ['min' => 104.8, 'max' => 999]
        ],
        34 => [
            'sangat_kurang' => ['min' => 0, 'max' => 83.9],
            'kurang' => ['min' => 84.0, 'max' => 87.4],
            'resiko_kurang' => ['min' => 87.5, 'max' => 91.0],
            'normal' => ['min' => 91.1, 'max' => 98.4],
            'resiko_lebih' => ['min' => 98.5, 'max' => 101.9],
            'lebih' => ['min' => 102.0, 'max' => 105.5],
            'sangat_lebih' => ['min' => 105.6, 'max' => 999]
        ],
        35 => [
            'sangat_kurang' => ['min' => 0, 'max' => 84.4],
            'kurang' => ['min' => 84.5, 'max' => 88.0],
            'resiko_kurang' => ['min' => 88.1, 'max' => 91.7],
            'normal' => ['min' => 91.8, 'max' => 99.1],
            'resiko_lebih' => ['min' => 99.2, 'max' => 102.6],
            'lebih' => ['min' => 102.7, 'max' => 106.3],
            'sangat_lebih' => ['min' => 106.4, 'max' => 999]
        ],
        36 => [
            'sangat_kurang' => ['min' => 0, 'max' => 85.0],
            'kurang' => ['min' => 85.1, 'max' => 88.6],
            'resiko_kurang' => ['min' => 88.7, 'max' => 92.3],
            'normal' => ['min' => 92.4, 'max' => 99.8],
            'resiko_lebih' => ['min' => 99.9, 'max' => 103.4],
            'lebih' => ['min' => 103.5, 'max' => 107.1],
            'sangat_lebih' => ['min' => 107.2, 'max' => 999]
        ],
        37 => [
            'sangat_kurang' => ['min' => 0, 'max' => 85.5],
            'kurang' => ['min' => 85.6, 'max' => 89.1],
            'resiko_kurang' => ['min' => 89.2, 'max' => 92.9],
            'normal' => ['min' => 93.0, 'max' => 100.5],
            'resiko_lebih' => ['min' => 100.6, 'max' => 104.1],
            'lebih' => ['min' => 104.2, 'max' => 107.9],
            'sangat_lebih' => ['min' => 108.0, 'max' => 999]
        ],
        38 => [
            'sangat_kurang' => ['min' => 0, 'max' => 86.0],
            'kurang' => ['min' => 86.1, 'max' => 89.7],
            'resiko_kurang' => ['min' => 89.8, 'max' => 93.5],
            'normal' => ['min' => 93.6, 'max' => 101.2],
            'resiko_lebih' => ['min' => 101.3, 'max' => 104.9],
            'lebih' => ['min' => 105.0, 'max' => 108.7],
            'sangat_lebih' => ['min' => 108.8, 'max' => 999]
        ],
        39 => [
            'sangat_kurang' => ['min' => 0, 'max' => 86.5],
            'kurang' => ['min' => 86.6, 'max' => 90.2],
            'resiko_kurang' => ['min' => 90.3, 'max' => 94.1],
            'normal' => ['min' => 94.2, 'max' => 101.8],
            'resiko_lebih' => ['min' => 101.9, 'max' => 105.6],
            'lebih' => ['min' => 105.7, 'max' => 109.4],
            'sangat_lebih' => ['min' => 109.5, 'max' => 999]
        ],
        40 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.3],
            'kurang' => ['min' => 11.4, 'max' => 12.7],
            'resiko_kurang' => ['min' => 12.8, 'max' => 14.1],
            'normal' => ['min' => 14.2, 'max' => 18.8],
            'resiko_lebih' => ['min' => 18.9, 'max' => 20.9],
            'lebih' => ['min' => 21.0, 'max' => 23.6],
            'sangat_lebih' => ['min' => 23.7, 'max' => 999]
        ],
        41 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.4],
            'kurang' => ['min' => 11.5, 'max' => 12.8],
            'resiko_kurang' => ['min' => 12.9, 'max' => 14.2],
            'normal' => ['min' => 14.3, 'max' => 19.0],
            'resiko_lebih' => ['min' => 19.1, 'max' => 21.1],
            'lebih' => ['min' => 21.2, 'max' => 23.8],
            'sangat_lebih' => ['min' => 23.9, 'max' => 999]
        ],
        42 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.5],
            'kurang' => ['min' => 11.6, 'max' => 12.9],
            'resiko_kurang' => ['min' => 13.0, 'max' => 14.3],
            'normal' => ['min' => 14.4, 'max' => 19.2],
            'resiko_lebih' => ['min' => 19.3, 'max' => 21.3],
            'lebih' => ['min' => 21.4, 'max' => 24.0],
            'sangat_lebih' => ['min' => 24.1, 'max' => 999]
        ],
        43 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.6],
            'kurang' => ['min' => 11.7, 'max' => 13.0],
            'resiko_kurang' => ['min' => 13.1, 'max' => 14.4],
            'normal' => ['min' => 14.5, 'max' => 19.4],
            'resiko_lebih' => ['min' => 19.5, 'max' => 21.5],
            'lebih' => ['min' => 21.6, 'max' => 24.2],
            'sangat_lebih' => ['min' => 24.3, 'max' => 999]
        ],
        44 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.7],
            'kurang' => ['min' => 11.8, 'max' => 13.1],
            'resiko_kurang' => ['min' => 13.2, 'max' => 14.5],
            'normal' => ['min' => 14.6, 'max' => 19.6],
            'resiko_lebih' => ['min' => 19.7, 'max' => 21.7],
            'lebih' => ['min' => 21.8, 'max' => 24.4],
            'sangat_lebih' => ['min' => 24.5, 'max' => 999]
        ],
        45 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.8],
            'kurang' => ['min' => 11.9, 'max' => 13.2],
            'resiko_kurang' => ['min' => 13.3, 'max' => 14.6],
            'normal' => ['min' => 14.7, 'max' => 19.8],
            'resiko_lebih' => ['min' => 19.9, 'max' => 21.9],
            'lebih' => ['min' => 22.0, 'max' => 24.6],
            'sangat_lebih' => ['min' => 24.7, 'max' => 999]
        ],
        46 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.9],
            'kurang' => ['min' => 12.0, 'max' => 13.3],
            'resiko_kurang' => ['min' => 13.4, 'max' => 14.7],
            'normal' => ['min' => 14.8, 'max' => 20.0],
            'resiko_lebih' => ['min' => 20.1, 'max' => 22.1],
            'lebih' => ['min' => 22.2, 'max' => 24.8],
            'sangat_lebih' => ['min' => 24.9, 'max' => 999]
        ],
        47 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.0],
            'kurang' => ['min' => 12.1, 'max' => 13.4],
            'resiko_kurang' => ['min' => 13.5, 'max' => 14.8],
            'normal' => ['min' => 14.9, 'max' => 20.2],
            'resiko_lebih' => ['min' => 20.3, 'max' => 22.3],
            'lebih' => ['min' => 22.4, 'max' => 25.0],
            'sangat_lebih' => ['min' => 25.1, 'max' => 999]
        ],
        48 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.1],
            'kurang' => ['min' => 12.2, 'max' => 13.5],
            'resiko_kurang' => ['min' => 13.6, 'max' => 14.9],
            'normal' => ['min' => 15.0, 'max' => 20.4],
            'resiko_lebih' => ['min' => 20.5, 'max' => 22.5],
            'lebih' => ['min' => 22.6, 'max' => 25.2],
            'sangat_lebih' => ['min' => 25.3, 'max' => 999]
        ],
        49 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.2],
            'kurang' => ['min' => 12.3, 'max' => 13.6],
            'resiko_kurang' => ['min' => 13.7, 'max' => 15.0],
            'normal' => ['min' => 15.1, 'max' => 20.6],
            'resiko_lebih' => ['min' => 20.7, 'max' => 22.7],
            'lebih' => ['min' => 22.8, 'max' => 25.4],
            'sangat_lebih' => ['min' => 25.5, 'max' => 999]
        ],
        50 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.3],
            'kurang' => ['min' => 12.4, 'max' => 13.7],
            'resiko_kurang' => ['min' => 13.8, 'max' => 15.1],
            'normal' => ['min' => 15.2, 'max' => 20.8],
            'resiko_lebih' => ['min' => 20.9, 'max' => 22.9],
            'lebih' => ['min' => 23.0, 'max' => 25.6],
            'sangat_lebih' => ['min' => 25.7, 'max' => 999]
        ],
        51 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.4],
            'kurang' => ['min' => 11.5, 'max' => 12.8],
            'resiko_kurang' => ['min' => 12.9, 'max' => 14.2],
            'normal' => ['min' => 14.3, 'max' => 19.0],
            'resiko_lebih' => ['min' => 19.1, 'max' => 21.1],
            'lebih' => ['min' => 21.2, 'max' => 23.8],
            'sangat_lebih' => ['min' => 23.9, 'max' => 999]
        ],
        52 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.5],
            'kurang' => ['min' => 11.6, 'max' => 12.9],
            'resiko_kurang' => ['min' => 13.0, 'max' => 14.3],
            'normal' => ['min' => 14.4, 'max' => 19.2],
            'resiko_lebih' => ['min' => 19.3, 'max' => 21.3],
            'lebih' => ['min' => 21.4, 'max' => 24.0],
            'sangat_lebih' => ['min' => 24.1, 'max' => 999]
        ],
        53 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.6],
            'kurang' => ['min' => 11.7, 'max' => 13.0],
            'resiko_kurang' => ['min' => 13.1, 'max' => 14.4],
            'normal' => ['min' => 14.5, 'max' => 19.4],
            'resiko_lebih' => ['min' => 19.5, 'max' => 21.5],
            'lebih' => ['min' => 21.6, 'max' => 24.2],
            'sangat_lebih' => ['min' => 24.3, 'max' => 999]
        ],
        54 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.7],
            'kurang' => ['min' => 11.8, 'max' => 13.1],
            'resiko_kurang' => ['min' => 13.2, 'max' => 14.5],
            'normal' => ['min' => 14.6, 'max' => 19.6],
            'resiko_lebih' => ['min' => 19.7, 'max' => 21.7],
            'lebih' => ['min' => 21.8, 'max' => 24.4],
            'sangat_lebih' => ['min' => 24.5, 'max' => 999]
        ],
        55 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.8],
            'kurang' => ['min' => 11.9, 'max' => 13.2],
            'resiko_kurang' => ['min' => 13.3, 'max' => 14.6],
            'normal' => ['min' => 14.7, 'max' => 19.8],
            'resiko_lebih' => ['min' => 19.9, 'max' => 21.9],
            'lebih' => ['min' => 22.0, 'max' => 24.6],
            'sangat_lebih' => ['min' => 24.7, 'max' => 999]
        ],
        56 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.9],
            'kurang' => ['min' => 12.0, 'max' => 13.3],
            'resiko_kurang' => ['min' => 13.4, 'max' => 14.7],
            'normal' => ['min' => 14.8, 'max' => 20.0],
            'resiko_lebih' => ['min' => 20.1, 'max' => 22.1],
            'lebih' => ['min' => 22.2, 'max' => 24.8],
            'sangat_lebih' => ['min' => 24.9, 'max' => 999]
        ],
        57 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.0],
            'kurang' => ['min' => 12.1, 'max' => 13.4],
            'resiko_kurang' => ['min' => 13.5, 'max' => 14.8],
            'normal' => ['min' => 14.9, 'max' => 20.2],
            'resiko_lebih' => ['min' => 20.3, 'max' => 22.3],
            'lebih' => ['min' => 22.4, 'max' => 25.0],
            'sangat_lebih' => ['min' => 25.1, 'max' => 999]
        ],
        58 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.1],
            'kurang' => ['min' => 12.2, 'max' => 13.5],
            'resiko_kurang' => ['min' => 13.6, 'max' => 14.9],
            'normal' => ['min' => 15.0, 'max' => 20.4],
            'resiko_lebih' => ['min' => 20.5, 'max' => 22.5],
            'lebih' => ['min' => 22.6, 'max' => 25.2],
            'sangat_lebih' => ['min' => 25.3, 'max' => 999]
        ],
        59 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.2],
            'kurang' => ['min' => 12.3, 'max' => 13.6],
            'resiko_kurang' => ['min' => 13.7, 'max' => 15.0],
            'normal' => ['min' => 15.1, 'max' => 20.6],
            'resiko_lebih' => ['min' => 20.7, 'max' => 22.7],
            'lebih' => ['min' => 22.8, 'max' => 25.4],
            'sangat_lebih' => ['min' => 25.5, 'max' => 999]
        ],
        60 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.3],
            'kurang' => ['min' => 12.4, 'max' => 13.7],
            'resiko_kurang' => ['min' => 13.8, 'max' => 15.1],
            'normal' => ['min' => 15.2, 'max' => 20.8],
            'resiko_lebih' => ['min' => 20.9, 'max' => 22.9],
            'lebih' => ['min' => 23.0, 'max' => 25.6],
            'sangat_lebih' => ['min' => 25.7, 'max' => 999]
        ]
    ];

    // Array untuk menyimpan rentang berat badan berdasarkan usia
    private $beratRanges = [
        0 => [
            'sangat_kurang' => ['min' => 0, 'max' => 2.0],
            'kurang' => ['min' => 2.1, 'max' => 2.4],
            'resiko_kurang' => ['min' => 2.5, 'max' => 2.7],
            'normal' => ['min' => 2.8, 'max' => 3.7],
            'resiko_lebih' => ['min' => 3.8, 'max' => 4.3],
            'lebih' => ['min' => 4.4, 'max' => 4.7],
            'sangat_lebih' => ['min' => 4.8, 'max' => 999]
        ],
        1 => [
            'sangat_kurang' => ['min' => 0, 'max' => 2.7],
            'kurang' => ['min' => 2.8, 'max' => 3.2],
            'resiko_kurang' => ['min' => 3.3, 'max' => 3.5],
            'normal' => ['min' => 3.6, 'max' => 4.8],
            'resiko_lebih' => ['min' => 4.9, 'max' => 5.4],
            'lebih' => ['min' => 5.5, 'max' => 6.1],
            'sangat_lebih' => ['min' => 6.2, 'max' => 999]
        ],
        2 => [
            'sangat_kurang' => ['min' => 0, 'max' => 3.4],
            'kurang' => ['min' => 3.5, 'max' => 3.9],
            'resiko_kurang' => ['min' => 4.0, 'max' => 4.4],
            'normal' => ['min' => 4.5, 'max' => 5.8],
            'resiko_lebih' => ['min' => 5.9, 'max' => 6.5],
            'lebih' => ['min' => 6.6, 'max' => 7.4],
            'sangat_lebih' => ['min' => 7.5, 'max' => 999]
        ],
        3 => [
            'sangat_kurang' => ['min' => 0, 'max' => 4.0],
            'kurang' => ['min' => 4.1, 'max' => 4.5],
            'resiko_kurang' => ['min' => 4.6, 'max' => 5.1],
            'normal' => ['min' => 5.2, 'max' => 6.6],
            'resiko_lebih' => ['min' => 6.7, 'max' => 7.4],
            'lebih' => ['min' => 7.5, 'max' => 8.4],
            'sangat_lebih' => ['min' => 8.5, 'max' => 999]
        ],
        4 => [
            'sangat_kurang' => ['min' => 0, 'max' => 4.4],
            'kurang' => ['min' => 4.5, 'max' => 5.0],
            'resiko_kurang' => ['min' => 5.1, 'max' => 5.6],
            'normal' => ['min' => 5.7, 'max' => 7.3],
            'resiko_lebih' => ['min' => 7.4, 'max' => 8.1],
            'lebih' => ['min' => 8.2, 'max' => 9.2],
            'sangat_lebih' => ['min' => 9.3, 'max' => 999]
        ],
        5 => [
            'sangat_kurang' => ['min' => 0, 'max' => 4.8],
            'kurang' => ['min' => 4.9, 'max' => 5.4],
            'resiko_kurang' => ['min' => 5.5, 'max' => 6.0],
            'normal' => ['min' => 6.1, 'max' => 7.8],
            'resiko_lebih' => ['min' => 7.9, 'max' => 8.7],
            'lebih' => ['min' => 8.8, 'max' => 9.9],
            'sangat_lebih' => ['min' => 10.0, 'max' => 999]
        ],
        6 => [
            'sangat_kurang' => ['min' => 0, 'max' => 5.1],
            'kurang' => ['min' => 5.2, 'max' => 5.7],
            'resiko_kurang' => ['min' => 5.8, 'max' => 6.4],
            'normal' => ['min' => 6.5, 'max' => 8.2],
            'resiko_lebih' => ['min' => 8.3, 'max' => 9.2],
            'lebih' => ['min' => 9.3, 'max' => 10.5],
            'sangat_lebih' => ['min' => 10.6, 'max' => 999]
        ],
        7 => [
            'sangat_kurang' => ['min' => 0, 'max' => 5.3],
            'kurang' => ['min' => 5.4, 'max' => 6.0],
            'resiko_kurang' => ['min' => 6.1, 'max' => 6.7],
            'normal' => ['min' => 6.8, 'max' => 8.6],
            'resiko_lebih' => ['min' => 8.7, 'max' => 9.7],
            'lebih' => ['min' => 9.8, 'max' => 11.0],
            'sangat_lebih' => ['min' => 11.1, 'max' => 999]
        ],
        8 => [
            'sangat_kurang' => ['min' => 0, 'max' => 5.6],
            'kurang' => ['min' => 5.7, 'max' => 6.3],
            'resiko_kurang' => ['min' => 6.4, 'max' => 6.9],
            'normal' => ['min' => 7.0, 'max' => 9.0],
            'resiko_lebih' => ['min' => 9.1, 'max' => 10.1],
            'lebih' => ['min' => 10.2, 'max' => 11.5],
            'sangat_lebih' => ['min' => 11.6, 'max' => 999]
        ],
        9 => [
            'sangat_kurang' => ['min' => 0, 'max' => 5.8],
            'kurang' => ['min' => 5.9, 'max' => 6.5],
            'resiko_kurang' => ['min' => 6.6, 'max' => 7.2],
            'normal' => ['min' => 7.3, 'max' => 9.3],
            'resiko_lebih' => ['min' => 9.4, 'max' => 10.4],
            'lebih' => ['min' => 10.5, 'max' => 11.9],
            'sangat_lebih' => ['min' => 12.0, 'max' => 999]
        ],
        10 => [
            'sangat_kurang' => ['min' => 0, 'max' => 5.9],
            'kurang' => ['min' => 6.0, 'max' => 6.7],
            'resiko_kurang' => ['min' => 6.8, 'max' => 7.4],
            'normal' => ['min' => 7.5, 'max' => 9.6],
            'resiko_lebih' => ['min' => 9.7, 'max' => 10.8],
            'lebih' => ['min' => 10.9, 'max' => 12.3],
            'sangat_lebih' => ['min' => 12.4, 'max' => 999]
        ],
        11 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.1],
            'kurang' => ['min' => 6.2, 'max' => 6.9],
            'resiko_kurang' => ['min' => 7.0, 'max' => 7.6],
            'normal' => ['min' => 7.7, 'max' => 9.9],
            'resiko_lebih' => ['min' => 10.0, 'max' => 11.1],
            'lebih' => ['min' => 11.2, 'max' => 12.7],
            'sangat_lebih' => ['min' => 12.8, 'max' => 999]
        ],
        12 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.3],
            'kurang' => ['min' => 6.4, 'max' => 7.0],
            'resiko_kurang' => ['min' => 7.1, 'max' => 7.8],
            'normal' => ['min' => 7.9, 'max' => 10.1],
            'resiko_lebih' => ['min' => 10.2, 'max' => 11.4],
            'lebih' => ['min' => 11.5, 'max' => 13.0],
            'sangat_lebih' => ['min' => 13.1, 'max' => 999]
        ],
        13 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.4],
            'kurang' => ['min' => 6.5, 'max' => 7.2],
            'resiko_kurang' => ['min' => 7.3, 'max' => 8.0],
            'normal' => ['min' => 8.1, 'max' => 10.4],
            'resiko_lebih' => ['min' => 10.5, 'max' => 11.7],
            'lebih' => ['min' => 11.8, 'max' => 13.4],
            'sangat_lebih' => ['min' => 13.5, 'max' => 999]
        ],
        14 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.6],
            'kurang' => ['min' => 6.7, 'max' => 7.4],
            'resiko_kurang' => ['min' => 7.5, 'max' => 8.2],
            'normal' => ['min' => 8.3, 'max' => 10.6],
            'resiko_lebih' => ['min' => 10.7, 'max' => 12.0],
            'lebih' => ['min' => 12.1, 'max' => 13.7],
            'sangat_lebih' => ['min' => 13.8, 'max' => 999]
        ],
        15 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.7],
            'kurang' => ['min' => 6.8, 'max' => 7.6],
            'resiko_kurang' => ['min' => 7.7, 'max' => 8.4],
            'normal' => ['min' => 8.5, 'max' => 10.9],
            'resiko_lebih' => ['min' => 11.0, 'max' => 12.3],
            'lebih' => ['min' => 12.4, 'max' => 14.0],
            'sangat_lebih' => ['min' => 14.1, 'max' => 999]
        ],
        16 => [
            'sangat_kurang' => ['min' => 0, 'max' => 6.9],
            'kurang' => ['min' => 7.0, 'max' => 7.7],
            'resiko_kurang' => ['min' => 7.8, 'max' => 8.6],
            'normal' => ['min' => 8.7, 'max' => 11.1],
            'resiko_lebih' => ['min' => 11.2, 'max' => 12.5],
            'lebih' => ['min' => 12.6, 'max' => 14.4],
            'sangat_lebih' => ['min' => 14.5, 'max' => 999]
        ],
        17 => [
            'sangat_kurang' => ['min' => 0, 'max' => 7.0],
            'kurang' => ['min' => 7.1, 'max' => 7.9],
            'resiko_kurang' => ['min' => 8.0, 'max' => 8.8],
            'normal' => ['min' => 8.9, 'max' => 11.4],
            'resiko_lebih' => ['min' => 11.5, 'max' => 12.8],
            'lebih' => ['min' => 12.9, 'max' => 14.7],
            'sangat_lebih' => ['min' => 14.8, 'max' => 999]
        ],
        18 => [
            'sangat_kurang' => ['min' => 0, 'max' => 7.2],
            'kurang' => ['min' => 7.3, 'max' => 8.1],
            'resiko_kurang' => ['min' => 8.2, 'max' => 9.0],
            'normal' => ['min' => 9.1, 'max' => 11.6],
            'resiko_lebih' => ['min' => 11.7, 'max' => 13.1],
            'lebih' => ['min' => 13.2, 'max' => 15.0],
            'sangat_lebih' => ['min' => 15.1, 'max' => 999]
        ],
        19 => [
            'sangat_kurang' => ['min' => 0, 'max' => 7.3],
            'kurang' => ['min' => 7.4, 'max' => 8.2],
            'resiko_kurang' => ['min' => 8.3, 'max' => 9.1],
            'normal' => ['min' => 9.2, 'max' => 11.8],
            'resiko_lebih' => ['min' => 11.9, 'max' => 13.4],
            'lebih' => ['min' => 13.5, 'max' => 15.3],
            'sangat_lebih' => ['min' => 15.4, 'max' => 999]
        ],
        20 => [
            'sangat_kurang' => ['min' => 0, 'max' => 7.5],
            'kurang' => ['min' => 7.6, 'max' => 8.4],
            'resiko_kurang' => ['min' => 8.5, 'max' => 9.3],
            'normal' => ['min' => 9.4, 'max' => 12.1],
            'resiko_lebih' => ['min' => 12.2, 'max' => 13.6],
            'lebih' => ['min' => 13.7, 'max' => 15.6],
            'sangat_lebih' => ['min' => 15.7, 'max' => 999]
        ],
        21 => [
            'sangat_kurang' => ['min' => 0, 'max' => 76.5],
            'kurang' => ['min' => 76.6, 'max' => 79.3],
            'resiko_kurang' => ['min' => 79.4, 'max' => 82.2],
            'normal' => ['min' => 82.3, 'max' => 88.0],
            'resiko_lebih' => ['min' => 88.1, 'max' => 90.8],
            'lebih' => ['min' => 90.9, 'max' => 93.7],
            'sangat_lebih' => ['min' => 93.8, 'max' => 999]
        ],
        22 => [
            'sangat_kurang' => ['min' => 0, 'max' => 77.2],
            'kurang' => ['min' => 77.3, 'max' => 80.1],
            'resiko_kurang' => ['min' => 80.2, 'max' => 83.0],
            'normal' => ['min' => 83.1, 'max' => 89.0],
            'resiko_lebih' => ['min' => 89.1, 'max' => 91.8],
            'lebih' => ['min' => 91.9, 'max' => 94.8],
            'sangat_lebih' => ['min' => 94.9, 'max' => 999]
        ],
        23 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.0],
            'kurang' => ['min' => 78.1, 'max' => 81.5],
            'resiko_kurang' => ['min' => 81.6, 'max' => 83.8],
            'normal' => ['min' => 83.9, 'max' => 89.9],
            'resiko_lebih' => ['min' => 90.0, 'max' => 92.8],
            'lebih' => ['min' => 92.9, 'max' => 95.8],
            'sangat_lebih' => ['min' => 95.9, 'max' => 999]
        ],
        24 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.7],
            'kurang' => ['min' => 78.8, 'max' => 81.6],
            'resiko_kurang' => ['min' => 81.7, 'max' => 84.7],
            'normal' => ['min' => 84.8, 'max' => 90.9],
            'resiko_lebih' => ['min' => 91.0, 'max' => 93.8],
            'lebih' => ['min' => 93.9, 'max' => 96.9],
            'sangat_lebih' => ['min' => 97.0, 'max' => 999]
        ],
        25 => [
            'sangat_kurang' => ['min' => 0, 'max' => 78.8],
            'kurang' => ['min' => 79.0, 'max' => 81.8],
            'resiko_kurang' => ['min' => 81.9, 'max' => 84.9],
            'normal' => ['min' => 85.0, 'max' => 91.1],
            'resiko_lebih' => ['min' => 91.2, 'max' => 94.0],
            'lebih' => ['min' => 94.1, 'max' => 97.1],
            'sangat_lebih' => ['min' => 97.2, 'max' => 999]
        ],
        26 => [
            'sangat_kurang' => ['min' => 0, 'max' => 79.3],
            'kurang' => ['min' => 79.4, 'max' => 82.4],
            'resiko_kurang' => ['min' => 82.5, 'max' => 85.5],
            'normal' => ['min' => 85.6, 'max' => 92.0],
            'resiko_lebih' => ['min' => 92.1, 'max' => 95.1],
            'lebih' => ['min' => 95.2, 'max' => 98.2],
            'sangat_lebih' => ['min' => 98.3, 'max' => 999]
        ],
        27 => [
            'sangat_kurang' => ['min' => 0, 'max' => 79.9],
            'kurang' => ['min' => 80.0, 'max' => 83.0],
            'resiko_kurang' => ['min' => 83.1, 'max' => 86.3],
            'normal' => ['min' => 86.4, 'max' => 92.9],
            'resiko_lebih' => ['min' => 93.0, 'max' => 96.0],
            'lebih' => ['min' => 96.1, 'max' => 99.2],
            'sangat_lebih' => ['min' => 99.3, 'max' => 999]
        ],
        28 => [
            'sangat_kurang' => ['min' => 0, 'max' => 80.5],
            'kurang' => ['min' => 80.6, 'max' => 83.7],
            'resiko_kurang' => ['min' => 83.8, 'max' => 87.0],
            'normal' => ['min' => 87.1, 'max' => 93.7],
            'resiko_lebih' => ['min' => 93.8, 'max' => 96.9],
            'lebih' => ['min' => 97.0, 'max' => 100.0],
            'sangat_lebih' => ['min' => 100.1, 'max' => 999]
        ],
        29 => [
            'sangat_kurang' => ['min' => 0, 'max' => 81.1],
            'kurang' => ['min' => 81.2, 'max' => 84.4],
            'resiko_kurang' => ['min' => 84.5, 'max' => 87.7],
            'normal' => ['min' => 87.8, 'max' => 94.5],
            'resiko_lebih' => ['min' => 94.6, 'max' => 97.8],
            'lebih' => ['min' => 97.9, 'max' => 101.1],
            'sangat_lebih' => ['min' => 101.2, 'max' => 999]
        ],
        30 => [
            'sangat_kurang' => ['min' => 0, 'max' => 81.7],
            'kurang' => ['min' => 81.8, 'max' => 85.0],
            'resiko_kurang' => ['min' => 85.1, 'max' => 88.4],
            'normal' => ['min' => 88.5, 'max' => 95.3],
            'resiko_lebih' => ['min' => 95.4, 'max' => 98.6],
            'lebih' => ['min' => 98.7, 'max' => 102.0],
            'sangat_lebih' => ['min' => 102.1, 'max' => 999]
        ],
        31 => [
            'sangat_kurang' => ['min' => 0, 'max' => 82.2],
            'kurang' => ['min' => 82.3, 'max' => 85.6],
            'resiko_kurang' => ['min' => 85.7, 'max' => 89.0],
            'normal' => ['min' => 89.1, 'max' => 96.1],
            'resiko_lebih' => ['min' => 96.2, 'max' => 99.4],
            'lebih' => ['min' => 99.5, 'max' => 102.8],
            'sangat_lebih' => ['min' => 102.9, 'max' => 999]
        ],
        32 => [
            'sangat_kurang' => ['min' => 0, 'max' => 82.8],
            'kurang' => ['min' => 82.9, 'max' => 86.3],
            'resiko_kurang' => ['min' => 86.4, 'max' => 89.8],
            'normal' => ['min' => 89.9, 'max' => 96.9],
            'resiko_lebih' => ['min' => 97.0, 'max' => 100.3],
            'lebih' => ['min' => 100.4, 'max' => 103.7],
            'sangat_lebih' => ['min' => 103.8, 'max' => 999]
        ],
        33 => [
            'sangat_kurang' => ['min' => 0, 'max' => 83.4],
            'kurang' => ['min' => 83.5, 'max' => 86.9],
            'resiko_kurang' => ['min' => 87.0, 'max' => 90.5],
            'normal' => ['min' => 90.6, 'max' => 97.6],
            'resiko_lebih' => ['min' => 97.7, 'max' => 101.1],
            'lebih' => ['min' => 101.2, 'max' => 104.5],
            'sangat_lebih' => ['min' => 104.6, 'max' => 999]
        ],
        34 => [
            'sangat_kurang' => ['min' => 0, 'max' => 83.9],
            'kurang' => ['min' => 84.0, 'max' => 87.5],
            'resiko_kurang' => ['min' => 87.6, 'max' => 91.1],
            'normal' => ['min' => 91.2, 'max' => 98.4],
            'resiko_lebih' => ['min' => 98.5, 'max' => 101.9],
            'lebih' => ['min' => 102.0, 'max' => 105.4],
            'sangat_lebih' => ['min' => 105.5, 'max' => 999]
        ],
        35 => [
            'sangat_kurang' => ['min' => 0, 'max' => 84.4],
            'kurang' => ['min' => 84.5, 'max' => 88.1],
            'resiko_kurang' => ['min' => 88.2, 'max' => 91.8],
            'normal' => ['min' => 91.9, 'max' => 99.1],
            'resiko_lebih' => ['min' => 99.2, 'max' => 102.7],
            'lebih' => ['min' => 102.8, 'max' => 106.2],
            'sangat_lebih' => ['min' => 106.3, 'max' => 999]
        ],
        36 => [
            'sangat_kurang' => ['min' => 0, 'max' => 85.0],
            'kurang' => ['min' => 85.1, 'max' => 88.7],
            'resiko_kurang' => ['min' => 88.8, 'max' => 92.4],
            'normal' => ['min' => 92.5, 'max' => 99.9],
            'resiko_lebih' => ['min' => 100.0, 'max' => 103.5],
            'lebih' => ['min' => 103.6, 'max' => 107.0],
            'sangat_lebih' => ['min' => 107.1, 'max' => 999]
        ],
        37 => [
            'sangat_kurang' => ['min' => 0, 'max' => 85.5],
            'kurang' => ['min' => 85.6, 'max' => 89.2],
            'resiko_kurang' => ['min' => 89.3, 'max' => 93.0],
            'normal' => ['min' => 93.1, 'max' => 100.6],
            'resiko_lebih' => ['min' => 100.7, 'max' => 104.2],
            'lebih' => ['min' => 104.3, 'max' => 107.8],
            'sangat_lebih' => ['min' => 107.9, 'max' => 999]
        ],
        38 => [
            'sangat_kurang' => ['min' => 0, 'max' => 86.0],
            'kurang' => ['min' => 86.1, 'max' => 89.8],
            'resiko_kurang' => ['min' => 89.9, 'max' => 93.6],
            'normal' => ['min' => 93.7, 'max' => 101.3],
            'resiko_lebih' => ['min' => 101.4, 'max' => 105.0],
            'lebih' => ['min' => 105.1, 'max' => 108.6],
            'sangat_lebih' => ['min' => 108.7, 'max' => 999]
        ],
        39 => [
            'sangat_kurang' => ['min' => 0, 'max' => 86.5],
            'kurang' => ['min' => 86.6, 'max' => 90.3],
            'resiko_kurang' => ['min' => 90.4, 'max' => 94.2],
            'normal' => ['min' => 94.3, 'max' => 102.0],
            'resiko_lebih' => ['min' => 102.1, 'max' => 105.7],
            'lebih' => ['min' => 105.8, 'max' => 109.3],
            'sangat_lebih' => ['min' => 109.4, 'max' => 999]
        ],
        40 => [
            'sangat_kurang' => ['min' => 0, 'max' => 87.0],
            'kurang' => ['min' => 87.1, 'max' => 90.9],
            'resiko_kurang' => ['min' => 91.0, 'max' => 94.7],
            'normal' => ['min' => 94.8, 'max' => 102.7],
            'resiko_lebih' => ['min' => 102.8, 'max' => 106.4],
            'lebih' => ['min' => 106.5, 'max' => 110.1],
            'sangat_lebih' => ['min' => 110.2, 'max' => 999]
        ],
        41 => [
            'sangat_kurang' => ['min' => 0, 'max' => 87.5],
            'kurang' => ['min' => 87.6, 'max' => 91.4],
            'resiko_kurang' => ['min' => 91.5, 'max' => 95.3],
            'normal' => ['min' => 95.4, 'max' => 103.4],
            'resiko_lebih' => ['min' => 103.5, 'max' => 107.1],
            'lebih' => ['min' => 107.2, 'max' => 110.8],
            'sangat_lebih' => ['min' => 110.9, 'max' => 999]
        ],
        42 => [
            'sangat_kurang' => ['min' => 0, 'max' => 88.0],
            'kurang' => ['min' => 88.1, 'max' => 91.9],
            'resiko_kurang' => ['min' => 92.0, 'max' => 95.9],
            'normal' => ['min' => 96.0, 'max' => 104.1],
            'resiko_lebih' => ['min' => 104.2, 'max' => 107.8],
            'lebih' => ['min' => 107.9, 'max' => 111.5],
            'sangat_lebih' => ['min' => 111.6, 'max' => 999]
        ],
        43 => [
            'sangat_kurang' => ['min' => 0, 'max' => 88.4],
            'kurang' => ['min' => 88.5, 'max' => 92.4],
            'resiko_kurang' => ['min' => 92.5, 'max' => 96.4],
            'normal' => ['min' => 96.5, 'max' => 104.8],
            'resiko_lebih' => ['min' => 104.9, 'max' => 108.5],
            'lebih' => ['min' => 108.6, 'max' => 112.3],
            'sangat_lebih' => ['min' => 112.4, 'max' => 999]
        ],
        44 => [
            'sangat_kurang' => ['min' => 0, 'max' => 88.9],
            'kurang' => ['min' => 89.0, 'max' => 92.9],
            'resiko_kurang' => ['min' => 93.0, 'max' => 97.0],
            'normal' => ['min' => 97.1, 'max' => 105.4],
            'resiko_lebih' => ['min' => 105.5, 'max' => 109.2],
            'lebih' => ['min' => 109.3, 'max' => 113.0],
            'sangat_lebih' => ['min' => 113.1, 'max' => 999]
        ],
        45 => [
            'sangat_kurang' => ['min' => 0, 'max' => 89.4],
            'kurang' => ['min' => 89.5, 'max' => 93.4],
            'resiko_kurang' => ['min' => 93.5, 'max' => 97.5],
            'normal' => ['min' => 97.6, 'max' => 106.1],
            'resiko_lebih' => ['min' => 106.2, 'max' => 109.9],
            'lebih' => ['min' => 109.3, 'max' => 113.7],
            'sangat_lebih' => ['min' => 113.8, 'max' => 999]
        ],
        46 => [
            'sangat_kurang' => ['min' => 0, 'max' => 89.8],
            'kurang' => ['min' => 89.9, 'max' => 93.9],
            'resiko_kurang' => ['min' => 94.0, 'max' => 98.0],
            'normal' => ['min' => 98.1, 'max' => 106.7],
            'resiko_lebih' => ['min' => 106.8, 'max' => 110.6],
            'lebih' => ['min' => 110.7, 'max' => 114.4],
            'sangat_lebih' => ['min' => 114.5, 'max' => 999]
        ],
        47 => [
            'sangat_kurang' => ['min' => 0, 'max' => 90.3],
            'kurang' => ['min' => 90.4, 'max' => 94.4],
            'resiko_kurang' => ['min' => 94.5, 'max' => 98.5],
            'normal' => ['min' => 98.6, 'max' => 107.4],
            'resiko_lebih' => ['min' => 107.5, 'max' => 111.3],
            'lebih' => ['min' => 111.4, 'max' => 115.1],
            'sangat_lebih' => ['min' => 115.2, 'max' => 999]
        ],
        48 => [
            'sangat_kurang' => ['min' => 0, 'max' => 90.7],
            'kurang' => ['min' => 90.8, 'max' => 94.9],
            'resiko_kurang' => ['min' => 95.0, 'max' => 99.1],
            'normal' => ['min' => 99.2, 'max' => 108.0],
            'resiko_lebih' => ['min' => 108.1, 'max' => 111.9],
            'lebih' => ['min' => 112.0, 'max' => 115.8],
            'sangat_lebih' => ['min' => 115.9, 'max' => 999]
        ],
        49 => [
            'sangat_kurang' => ['min' => 0, 'max' => 91.2],
            'kurang' => ['min' => 91.3, 'max' => 95.4],
            'resiko_kurang' => ['min' => 95.5, 'max' => 99.6],
            'normal' => ['min' => 99.7, 'max' => 108.6],
            'resiko_lebih' => ['min' => 108.7, 'max' => 112.6],
            'lebih' => ['min' => 112.7, 'max' => 116.5],
            'sangat_lebih' => ['min' => 116.6, 'max' => 999]
        ],
        50 => [
            'sangat_kurang' => ['min' => 0, 'max' => 91.6],
            'kurang' => ['min' => 91.7, 'max' => 95.9],
            'resiko_kurang' => ['min' => 96.0, 'max' => 100.1],
            'normal' => ['min' => 100.2, 'max' => 109.2],
            'resiko_lebih' => ['min' => 109.3, 'max' => 113.2],
            'lebih' => ['min' => 113.3, 'max' => 117.2],
            'sangat_lebih' => ['min' => 117.3, 'max' => 999]
        ],
        51 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.4],
            'kurang' => ['min' => 11.5, 'max' => 12.8],
            'resiko_kurang' => ['min' => 12.9, 'max' => 14.2],
            'normal' => ['min' => 14.3, 'max' => 19.0],
            'resiko_lebih' => ['min' => 19.1, 'max' => 21.1],
            'lebih' => ['min' => 21.2, 'max' => 23.8],
            'sangat_lebih' => ['min' => 23.9, 'max' => 999]
        ],
        52 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.5],
            'kurang' => ['min' => 11.6, 'max' => 12.9],
            'resiko_kurang' => ['min' => 13.0, 'max' => 14.3],
            'normal' => ['min' => 14.4, 'max' => 19.2],
            'resiko_lebih' => ['min' => 19.3, 'max' => 21.3],
            'lebih' => ['min' => 21.4, 'max' => 24.0],
            'sangat_lebih' => ['min' => 24.1, 'max' => 999]
        ],
        53 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.6],
            'kurang' => ['min' => 11.7, 'max' => 13.0],
            'resiko_kurang' => ['min' => 13.1, 'max' => 14.4],
            'normal' => ['min' => 14.5, 'max' => 19.4],
            'resiko_lebih' => ['min' => 19.5, 'max' => 21.5],
            'lebih' => ['min' => 21.6, 'max' => 24.2],
            'sangat_lebih' => ['min' => 24.3, 'max' => 999]
        ],
        54 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.7],
            'kurang' => ['min' => 11.8, 'max' => 13.1],
            'resiko_kurang' => ['min' => 13.2, 'max' => 14.5],
            'normal' => ['min' => 14.6, 'max' => 19.6],
            'resiko_lebih' => ['min' => 19.7, 'max' => 21.7],
            'lebih' => ['min' => 21.8, 'max' => 24.4],
            'sangat_lebih' => ['min' => 24.5, 'max' => 999]
        ],
        55 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.8],
            'kurang' => ['min' => 11.9, 'max' => 13.2],
            'resiko_kurang' => ['min' => 13.3, 'max' => 14.6],
            'normal' => ['min' => 14.7, 'max' => 19.8],
            'resiko_lebih' => ['min' => 19.9, 'max' => 21.9],
            'lebih' => ['min' => 22.0, 'max' => 24.6],
            'sangat_lebih' => ['min' => 24.7, 'max' => 999]
        ],
        56 => [
            'sangat_kurang' => ['min' => 0, 'max' => 11.9],
            'kurang' => ['min' => 12.0, 'max' => 13.3],
            'resiko_kurang' => ['min' => 13.4, 'max' => 14.7],
            'normal' => ['min' => 14.8, 'max' => 20.0],
            'resiko_lebih' => ['min' => 20.1, 'max' => 22.1],
            'lebih' => ['min' => 22.2, 'max' => 24.8],
            'sangat_lebih' => ['min' => 24.9, 'max' => 999]
        ],
        57 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.0],
            'kurang' => ['min' => 12.1, 'max' => 13.4],
            'resiko_kurang' => ['min' => 13.5, 'max' => 14.8],
            'normal' => ['min' => 14.9, 'max' => 20.2],
            'resiko_lebih' => ['min' => 20.3, 'max' => 22.3],
            'lebih' => ['min' => 22.4, 'max' => 25.0],
            'sangat_lebih' => ['min' => 25.1, 'max' => 999]
        ],
        58 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.1],
            'kurang' => ['min' => 12.2, 'max' => 13.5],
            'resiko_kurang' => ['min' => 13.6, 'max' => 14.9],
            'normal' => ['min' => 15.0, 'max' => 20.4],
            'resiko_lebih' => ['min' => 20.5, 'max' => 22.5],
            'lebih' => ['min' => 22.6, 'max' => 25.2],
            'sangat_lebih' => ['min' => 25.3, 'max' => 999]
        ],
        59 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.2],
            'kurang' => ['min' => 12.3, 'max' => 13.6],
            'resiko_kurang' => ['min' => 13.7, 'max' => 15.0],
            'normal' => ['min' => 15.1, 'max' => 20.6],
            'resiko_lebih' => ['min' => 20.7, 'max' => 22.7],
            'lebih' => ['min' => 22.8, 'max' => 25.4],
            'sangat_lebih' => ['min' => 25.5, 'max' => 999]
        ],
        60 => [
            'sangat_kurang' => ['min' => 0, 'max' => 12.3],
            'kurang' => ['min' => 12.4, 'max' => 13.7],
            'resiko_kurang' => ['min' => 13.8, 'max' => 15.1],
            'normal' => ['min' => 15.2, 'max' => 20.8],
            'resiko_lebih' => ['min' => 20.9, 'max' => 22.9],
            'lebih' => ['min' => 23.0, 'max' => 25.6],
            'sangat_lebih' => ['min' => 25.7, 'max' => 999]
        ]
    ];

    /**
     * Menghitung status tinggi badan berdasarkan usia dan tinggi badan
     *
     * @param int $usia Usia dalam bulan
     * @param float $tinggiBadan Tinggi badan dalam cm
     * @return string Status tinggi badan
     */
    public function hitungStatusTinggiBadan(int $usia, float $tinggiBadan): string
    {
        if (!isset($this->tinggiRanges[$usia])) {
            return 'Usia tidak valid';
        }

        $ranges = $this->tinggiRanges[$usia];

        if ($tinggiBadan <= $ranges['sangat_kurang']['max']) {
            return self::STATUS_SANGAT_KURANG;
        } elseif ($tinggiBadan >= $ranges['kurang']['min'] && $tinggiBadan <= $ranges['kurang']['max']) {
            return self::STATUS_KURANG;
        } elseif ($tinggiBadan >= $ranges['resiko_kurang']['min'] && $tinggiBadan <= $ranges['resiko_kurang']['max']) {
            return self::STATUS_RESIKO_KURANG;
        } elseif ($tinggiBadan >= $ranges['normal']['min'] && $tinggiBadan <= $ranges['normal']['max']) {
            return self::STATUS_NORMAL;
        } elseif ($tinggiBadan >= $ranges['resiko_lebih']['min'] && $tinggiBadan <= $ranges['resiko_lebih']['max']) {
            return self::STATUS_RESIKO_LEBIH;
        } elseif ($tinggiBadan >= $ranges['lebih']['min'] && $tinggiBadan <= $ranges['lebih']['max']) {
            return self::STATUS_LEBIH;
        } else {
            return self::STATUS_SANGAT_LEBIH;
        }
    }

    /**
     * Menghitung status berat badan berdasarkan usia dan berat badan
     *
     * @param int $usia Usia dalam bulan
     * @param float $beratBadan Berat badan dalam kg
     * @return string Status berat badan
     */
    public function hitungStatusBeratBadan(int $usia, float $beratBadan): string
    {
        if (!isset($this->beratRanges[$usia])) {
            return 'Usia tidak valid';
        }

        $ranges = $this->beratRanges[$usia];

        if ($beratBadan <= $ranges['sangat_kurang']['max']) {
            return self::STATUS_SANGAT_KURANG;
        } elseif ($beratBadan >= $ranges['kurang']['min'] && $beratBadan <= $ranges['kurang']['max']) {
            return self::STATUS_KURANG;
        } elseif ($beratBadan >= $ranges['resiko_kurang']['min'] && $beratBadan <= $ranges['resiko_kurang']['max']) {
            return self::STATUS_RESIKO_KURANG;
        } elseif ($beratBadan >= $ranges['normal']['min'] && $beratBadan <= $ranges['normal']['max']) {
            return self::STATUS_NORMAL;
        } elseif ($beratBadan >= $ranges['resiko_lebih']['min'] && $beratBadan <= $ranges['resiko_lebih']['max']) {
            return self::STATUS_RESIKO_LEBIH;
        } elseif ($beratBadan >= $ranges['lebih']['min'] && $beratBadan <= $ranges['lebih']['max']) {
            return self::STATUS_LEBIH;
        } else {
            return self::STATUS_SANGAT_LEBIH;
        }
    }
} 