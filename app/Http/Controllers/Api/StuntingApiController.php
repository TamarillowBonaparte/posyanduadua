<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stunting;
use App\Models\Anak;
use App\Models\PerkembanganAnak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StuntingApiController extends Controller
{
    protected $stuntingCalculator;

    public function __construct()
    {
        // Fix error "Class App\Services\StuntingCalculator not found"
        try {
            $this->stuntingCalculator = app()->make('App\Services\StuntingCalculator');
        } catch (\Throwable $e) {
            // Buat manual jika injeksi gagal
            require_once app_path('Services/StuntingCalculator.php');
            $this->stuntingCalculator = new \App\Services\StuntingCalculator();
        }
    }
    
    /**
     * Get all stunting records for a specific child
     */
    public function getByAnakId($anak_id)
    {
        $anak = Anak::find($anak_id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }
        
        $stunting = Stunting::with('perkembangan')
            ->where('anak_id', $anak_id)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'stunting' => $stunting,
        ]);
    }
    
    /**
     * Get a specific stunting record
     */
    public function show($id)
    {
        $stunting = Stunting::with('perkembangan')->find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'stunting' => $stunting,
        ]);
    }
    
    /**
     * Calculate stunting status based on age, height, and weight
     */
    public function calculateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usia' => 'required|string',
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Extract usia dalam bulan
        $ageInMonths = $this->stuntingCalculator->extractAgeInMonths($request->usia);
        
        // Jika usia kurang dari 24 bulan atau lebih dari 60 bulan
        if ($ageInMonths < 24 || $ageInMonths > 60) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usia harus antara 24-60 bulan untuk perhitungan stunting',
            ], 422);
        }
        
        // Hitung status stunting
        $statusByHeight = $this->stuntingCalculator->determineStatusByHeight(
            $ageInMonths, 
            $request->tinggi_badan
        );
        
        $statusByWeight = $this->stuntingCalculator->determineStatusByWeight(
            $ageInMonths, 
            $request->berat_badan
        );
        
        $finalStatus = $this->stuntingCalculator->determineStatus(
            $ageInMonths, 
            $request->tinggi_badan,
            $request->berat_badan
        );
        
        return response()->json([
            'status' => 'success',
            'usia_bulan' => $ageInMonths,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'status_by_height' => $statusByHeight,
            'status_by_weight' => $statusByWeight,
            'final_status' => $finalStatus,
        ]);
    }
    
    /**
     * Create a new stunting record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:Stunting,Resiko Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Get perkembangan for height and weight
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Extract usia dalam bulan
        $ageInMonths = $this->stuntingCalculator->extractAgeInMonths($request->usia);
        
        // Calculate stunting status
        $calculatedStatus = $this->stuntingCalculator->determineStatus(
            $ageInMonths, 
            $perkembangan->tinggi_badan,
            $perkembangan->berat_badan
        );
        
        // Use provided status or calculated status
        $data = $request->all();
        $data['status'] = $request->status ?? $calculatedStatus;
        $data['tinggi_badan'] = $perkembangan->tinggi_badan;
        $data['berat_badan'] = $perkembangan->berat_badan;
        
        $stunting = Stunting::create($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil ditambahkan',
            'stunting' => $stunting,
            'calculated_status' => $calculatedStatus
        ], 201);
    }
    
    /**
     * Update a stunting record
     */
    public function update(Request $request, $id)
    {
        $stunting = Stunting::find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'usia' => 'required|string',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:Stunting,Resiko Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Get perkembangan for height and weight
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Extract usia dalam bulan
        $ageInMonths = $this->stuntingCalculator->extractAgeInMonths($request->usia);
        
        // Calculate stunting status
        $calculatedStatus = $this->stuntingCalculator->determineStatus(
            $ageInMonths, 
            $perkembangan->tinggi_badan,
            $perkembangan->berat_badan
        );
        
        // Get data to update
        $data = $request->all();
        $data['status'] = $request->status ?? $calculatedStatus;
        $data['tinggi_badan'] = $perkembangan->tinggi_badan;
        $data['berat_badan'] = $perkembangan->berat_badan;
        
        $stunting->update($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil diperbarui',
            'stunting' => $stunting,
            'calculated_status' => $calculatedStatus
        ]);
    }
    
    /**
     * Delete a stunting record
     */
    public function destroy($id)
    {
        $stunting = Stunting::find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        $stunting->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil dihapus',
        ]);
    }
} 