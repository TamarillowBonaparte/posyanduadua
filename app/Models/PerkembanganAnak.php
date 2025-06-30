<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Services\PerkembanganAnakCalculator;

class PerkembanganAnak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perkembangan_anak';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'anak_id',
        'tanggal',
        'berat_badan',
        'tinggi_badan',
        'status_berat_badan',
        'status_tinggi_badan',
        'updated_from_id',
        'is_updated',
        'updated_by_id'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'is_updated' => 'boolean'
    ];

    // Konstanta untuk status
    const STATUS_SANGAT_KURANG = 'Sangat Kurang';
    const STATUS_KURANG = 'Kurang';
    const STATUS_RESIKO_KURANG = 'Resiko Kurang';
    const STATUS_NORMAL = 'Normal';
    const STATUS_RESIKO_LEBIH = 'Resiko Lebih';
    const STATUS_LEBIH = 'Lebih';
    const STATUS_SANGAT_LEBIH = 'Sangat Lebih';

    /**
     * Boot method untuk menghitung status sebelum menyimpan
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Validasi berat badan
            if ($model->berat_badan <= 0) {
                throw new \Exception('Berat badan harus lebih dari 0');
            }
            
            // Validasi tinggi badan
            if ($model->tinggi_badan <= 0) {
                throw new \Exception('Tinggi badan harus lebih dari 0');
            }

            // Hitung status saat membuat record baru
            $model->hitungStatus();
        });

        static::updating(function ($model) {
            // Hitung ulang status saat mengupdate record
            $model->hitungStatus();
        });

        static::saving(function ($model) {
            $model->hitungStatus();
        });
    }

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
    
    public function stunting()
    {
        return $this->hasMany(Stunting::class, 'perkembangan_id');
    }

    // Helper method untuk mendapatkan status pertumbuhan
    public function getStatusPertumbuhan()
    {
        return [
            'status_bb' => $this->status_berat_badan,
            'status_tb' => $this->status_tinggi_badan,
        ];
    }

    /**
     * Menghitung status tinggi badan dan berat badan berdasarkan usia
     */
    public function hitungStatus()
    {
        $calculator = new PerkembanganAnakCalculator();
        
        // Hitung usia dalam bulan
        $tanggalLahir = Carbon::parse($this->anak->tanggal_lahir);
        $tanggalPemeriksaan = Carbon::parse($this->tanggal);
        $usiaBulan = $tanggalLahir->diffInMonths($tanggalPemeriksaan);

        // Hitung status tinggi badan
        if ($this->tinggi_badan) {
            $this->status_tinggi_badan = $calculator->hitungStatusTinggiBadan($usiaBulan, $this->tinggi_badan);
        }

        // Hitung status berat badan
        if ($this->berat_badan) {
            $this->status_berat_badan = $calculator->hitungStatusBeratBadan($usiaBulan, $this->berat_badan);
        }

        return $this;
    }

    // Relationships
    public function oldRecord()
    {
        return $this->belongsTo(PerkembanganAnak::class, 'updated_from_id');
    }

    public function newRecord()
    {
        return $this->hasOne(PerkembanganAnak::class, 'updated_from_id');
    }

    // Validation rules
    public static function rules()
    {
        return [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|numeric|min:0',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_updated', false);
    }

    public function scopeUpdated($query)
    {
        return $query->where('is_updated', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc');
    }

    public function scopeOldest($query)
    {
        return $query->orderBy('tanggal', 'asc');
    }
}
