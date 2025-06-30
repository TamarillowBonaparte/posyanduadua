<?php

namespace App\Services;

class StuntingCalculator
{
    // Status constants
    const STATUS_STUNTING = 'Stunting';
    const STATUS_RESIKO_STUNTING = 'Resiko Stunting';
    const STATUS_TIDAK_STUNTING = 'Tidak Stunting';
    
    // Tinggi badan standar sesuai usia (min stunting, min resiko stunting, min tidak stunting)
    protected $standardHeightByAge = [
        '24' => [0, 81.1, 84.1], // ≤ 81.0: Stunting, 81.1-84.0: Resiko, ≥ 84.1: Tidak Stunting
        '25' => [0, 81.8, 84.9],
        '26' => [0, 82.6, 85.6],
        '27' => [0, 83.2, 86.4],
        '28' => [0, 83.9, 87.1],
        '29' => [0, 84.6, 87.8],
        '30' => [0, 85.2, 88.5],
        '31' => [0, 85.8, 89.2],
        '32' => [0, 86.5, 89.9],
        '33' => [0, 87.0, 90.5],
        '34' => [0, 87.6, 91.1],
        '35' => [0, 88.2, 91.8],
        '36' => [0, 88.8, 92.4],
        '37' => [0, 89.3, 93.0],
        '38' => [0, 89.9, 93.6],
        '39' => [0, 90.4, 94.2],
        '40' => [0, 91.0, 94.7],
        '41' => [0, 91.5, 95.3],
        '42' => [0, 92.0, 95.9],
        '43' => [0, 92.5, 96.4],
        '44' => [0, 93.1, 97.0],
        '45' => [0, 93.6, 97.5],
        '46' => [0, 94.1, 98.1],
        '47' => [0, 94.5, 98.6],
        '48' => [0, 95.0, 99.1],
        '49' => [0, 95.5, 99.7],
        '50' => [0, 96.0, 100.2],
        '51' => [0, 96.5, 100.7],
        '52' => [0, 97.0, 101.2],
        '53' => [0, 97.5, 101.7],
        '54' => [0, 97.9, 102.3],
        '55' => [0, 98.4, 102.8],
        '56' => [0, 98.9, 103.3],
        '57' => [0, 99.4, 103.8],
        '58' => [0, 99.8, 104.3],
        '59' => [0, 100.3, 104.8],
        '60' => [0, 100.8, 105.3]
    ];
    
    // Berat badan standar sesuai usia (min stunting, min resiko stunting, min tidak stunting)
    protected $standardWeightByAge = [
        '24' => [0, 9.1, 10.2], // ≤ 9.0: Stunting, 9.1-10.1: Resiko, ≥ 10.2: Tidak Stunting
        '25' => [0, 9.3, 10.3],
        '26' => [0, 9.5, 10.5],
        '27' => [0, 9.6, 10.7],
        '28' => [0, 9.8, 10.9],
        '29' => [0, 9.9, 11.1],
        '30' => [0, 10.1, 11.2],
        '31' => [0, 10.2, 11.4],
        '32' => [0, 10.4, 11.6],
        '33' => [0, 10.5, 11.7],
        '34' => [0, 10.6, 11.9],
        '35' => [0, 10.8, 12.0],
        '36' => [0, 10.9, 12.2],
        '37' => [0, 11.0, 12.4],
        '38' => [0, 11.2, 12.5],
        '39' => [0, 11.3, 12.7],
        '40' => [0, 11.4, 12.8],
        '41' => [0, 11.6, 13.0],
        '42' => [0, 11.7, 13.1],
        '43' => [0, 11.8, 13.3],
        '44' => [0, 11.9, 13.4],
        '45' => [0, 12.1, 13.6],
        '46' => [0, 12.2, 13.7],
        '47' => [0, 12.3, 13.9],
        '48' => [0, 12.4, 14.0],
        '49' => [0, 12.5, 14.2],
        '50' => [0, 12.7, 14.3],
        '51' => [0, 12.8, 14.5],
        '52' => [0, 12.9, 14.6],
        '53' => [0, 13.0, 14.8],
        '54' => [0, 13.1, 14.9],
        '55' => [0, 13.3, 15.1],
        '56' => [0, 13.4, 15.2],
        '57' => [0, 13.5, 15.3],
        '58' => [0, 13.6, 15.5],
        '59' => [0, 13.7, 15.6],
        '60' => [0, 13.8, 15.8]
    ];
    
    /**
     * Menentukan status stunting berdasarkan tinggi badan
     * 
     * @param int $age Usia dalam bulan (24-60)
     * @param float $height Tinggi badan dalam cm
     * @return string Status stunting
     */
    public function determineStatusByHeight($age, $height)
    {
        // Konversi ke string untuk lookup dalam array
        $age = (string) $age;
        
        // Jika usia tidak dalam range yang ditentukan
        if (!isset($this->standardHeightByAge[$age])) {
            return self::STATUS_TIDAK_STUNTING; // Default
        }
        
        $standards = $this->standardHeightByAge[$age];
        
        // Bandingkan tinggi badan dengan standar
        if ($height <= $standards[0]) {
            return self::STATUS_STUNTING;
        } elseif ($height >= $standards[0] && $height < $standards[1]) {
            return self::STATUS_STUNTING;
        } elseif ($height >= $standards[1] && $height < $standards[2]) {
            return self::STATUS_RESIKO_STUNTING;
        } else {
            return self::STATUS_TIDAK_STUNTING;
        }
    }
    
    /**
     * Menentukan status stunting berdasarkan berat badan
     * 
     * @param int $age Usia dalam bulan (24-60)
     * @param float $weight Berat badan dalam kg
     * @return string Status stunting
     */
    public function determineStatusByWeight($age, $weight)
    {
        // Konversi ke string untuk lookup dalam array
        $age = (string) $age;
        
        // Jika usia tidak dalam range yang ditentukan
        if (!isset($this->standardWeightByAge[$age])) {
            return self::STATUS_TIDAK_STUNTING; // Default
        }
        
        $standards = $this->standardWeightByAge[$age];
        
        // Bandingkan berat badan dengan standar
        if ($weight <= $standards[0]) {
            return self::STATUS_STUNTING;
        } elseif ($weight >= $standards[0] && $weight < $standards[1]) {
            return self::STATUS_STUNTING;
        } elseif ($weight >= $standards[1] && $weight < $standards[2]) {
            return self::STATUS_RESIKO_STUNTING;
        } else {
            return self::STATUS_TIDAK_STUNTING;
        }
    }
    
    /**
     * Menentukan status stunting berdasarkan tinggi dan berat badan
     * Hanya melihat tinggi badan untuk keputusan akhir sesuai pedoman WHO
     * 
     * @param int $age Usia dalam bulan (24-60)
     * @param float $height Tinggi badan dalam cm
     * @param float $weight Berat badan dalam kg (opsional)
     * @return string Status stunting
     */
    public function determineStatus($age, $height, $weight = null)
    {
        // Stunting lebih diutamakan berdasarkan tinggi badan
        return $this->determineStatusByHeight($age, $height);
    }
    
    /**
     * Extract usia dalam bulan dari string usia
     * Contoh input: "24 bulan" atau "2 tahun"
     * 
     * @param string $usiaString
     * @return int
     */
    public function extractAgeInMonths($usiaString)
    {
        $usiaString = strtolower($usiaString);
        
        // Cek apakah mengandung "bulan"
        if (strpos($usiaString, 'bulan') !== false) {
            // Ambil angka dari string
            preg_match('/(\d+)/', $usiaString, $matches);
            return (int) $matches[1];
        }
        
        // Cek apakah mengandung "tahun"
        if (strpos($usiaString, 'tahun') !== false) {
            // Ambil angka dari string
            preg_match('/(\d+)/', $usiaString, $matches);
            // Konversi tahun ke bulan
            return (int) $matches[1] * 12;
        }
        
        // Jika hanya angka, asumsikan dalam bulan
        if (is_numeric($usiaString)) {
            return (int) $usiaString;
        }
        
        // Default
        return 0;
    }
} 