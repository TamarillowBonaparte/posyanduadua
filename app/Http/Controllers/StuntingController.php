<?php

namespace App\Http\Controllers;

use App\Models\Stunting;
use App\Models\Anak;
use App\Models\PerkembanganAnak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\StuntingExport;
use Maatwebsite\Excel\Facades\Excel;

class StuntingController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        // Ambil semua anak yang memiliki setidaknya satu data stunting
        $query = Anak::has('stunting')->with(['stunting' => function($query) {
            $query->latest('tanggal'); // Ambil yang terbaru berdasarkan tanggal
        }]);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%")
                  ->orWhereHas('stunting', function($sq) use ($search) {
                      $sq->where('status', 'like', "%{$search}%")
                          ->orWhere('catatan', 'like', "%{$search}%");
                  });
            });
        }
        
        // Dapatkan hasil paginasi dari query Anak
        $anakPaginator = $query->paginate($perPage);
        
        // Proses koleksi Anak untuk mendapatkan data stunting terbaru
        $stuntingCollection = collect();
        foreach ($anakPaginator as $anak) {
            if ($anak->stunting && $anak->stunting->count() > 0) {
                $latest = $anak->stunting->first(); // Ambil data stunting terbaru
                if ($latest) {
                    $latest->setRelation('anak', $anak);
                    $stuntingCollection->push($latest);
                }
            }
        }
        
        // Gunakan paginator yang ada dengan koleksi baru
        $stunting = new \Illuminate\Pagination\LengthAwarePaginator(
            $stuntingCollection,
            $anakPaginator->total(),
            $anakPaginator->perPage(),
            $anakPaginator->currentPage(),
            ['path' => \Illuminate\Support\Facades\Request::url()]
        );
        
        // Set query string dari request asli ke paginator
        $stunting->appends($request->query());
        
        return view('stunting', compact('stunting', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $dataAnak = Anak::all();
        $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
        return view('stunting', compact('action', 'dataAnak', 'dataPerkembangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string|max:10',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:Stunting,Resiko Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil data perkembangan untuk mendapatkan tinggi_badan dan berat_badan
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Extract usia dalam bulan
        $ageInMonths = $this->stuntingCalculator->extractAgeInMonths($request->usia);
        
        // Hitung status stunting berdasarkan usia dan tinggi badan
        $calculatedStatus = $this->stuntingCalculator->determineStatus(
            $ageInMonths, 
            $perkembangan->tinggi_badan,
            $perkembangan->berat_badan
        );
        
        // Gunakan status yang dihitung atau yang dipilih pengguna jika ada
        $status = $request->status ?? $calculatedStatus;
        
        // Buat data stunting baru dengan nilai dari form dan data dari perkembangan
        $stunting = new Stunting();
        $stunting->anak_id = $request->anak_id;
        $stunting->tanggal = $request->tanggal;
        $stunting->usia = $request->usia;
        $stunting->catatan = $request->catatan;
        $stunting->status = $status;
        $stunting->perkembangan_id = $request->perkembangan_id;
        // Ambil tinggi_badan dan berat_badan langsung dari perkembangan terkait
        $stunting->tinggi_badan = $perkembangan->tinggi_badan;
        $stunting->berat_badan = $perkembangan->berat_badan;
        
        $stunting->save();

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stunting = Stunting::with(['anak', 'perkembangan'])->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'stunting' => $stunting,
                'anak' => $stunting->anak,
                'perkembangan' => $stunting->perkembangan
            ]);
        }
        
        $action = 'show';
        return view('stunting', compact('stunting', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stunting = Stunting::with(['anak', 'perkembangan'])->findOrFail($id);
        
        if (request()->ajax()) {
            // Get all children and perkembangan for dropdown
            $dataAnak = Anak::all();
            $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
            
            return response()->json([
                'stunting' => $stunting,
                'anak' => $stunting->anak,
                'perkembangan' => $stunting->perkembangan,
                'dataAnak' => $dataAnak,
                'dataPerkembangan' => $dataPerkembangan
            ]);
        }
        
        $action = 'edit';
        $dataAnak = Anak::all();
        $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
        return view('stunting', compact('stunting', 'action', 'dataAnak', 'dataPerkembangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string|max:10',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:Stunting,Resiko Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $stunting = Stunting::findOrFail($id);
        
        // Ambil data perkembangan untuk mendapatkan tinggi_badan dan berat_badan
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Extract usia dalam bulan
        $ageInMonths = $this->stuntingCalculator->extractAgeInMonths($request->usia);
        
        // Hitung status stunting berdasarkan usia dan tinggi badan
        $calculatedStatus = $this->stuntingCalculator->determineStatus(
            $ageInMonths, 
            $perkembangan->tinggi_badan,
            $perkembangan->berat_badan
        );
        
        // Gunakan status yang dihitung atau yang dipilih pengguna jika ada
        $status = $request->status ?? $calculatedStatus;
        
        // Update data stunting dengan nilai dari form dan data dari perkembangan
        $stunting->anak_id = $request->anak_id;
        $stunting->tanggal = $request->tanggal;
        $stunting->usia = $request->usia;
        $stunting->catatan = $request->catatan;
        $stunting->status = $status;
        $stunting->perkembangan_id = $request->perkembangan_id;
        // Ambil tinggi_badan dan berat_badan langsung dari perkembangan terkait
        $stunting->tinggi_badan = $perkembangan->tinggi_badan;
        $stunting->berat_badan = $perkembangan->berat_badan;
        
        $stunting->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data stunting berhasil diperbarui!'
            ]);
        }

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stunting = Stunting::findOrFail($id);
        $stunting->delete();

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil dihapus!');
    }
    
    /**
     * Menampilkan riwayat stunting untuk satu anak.
     */
    public function riwayat($anak_id)
    {
        try {
            // Cari data anak
            $anak = Anak::findOrFail($anak_id);
            
            // Ambil parameter perPage dari request dengan default 5 data per halaman
            $perPage = request()->input('perPage', 5);
            
            // Ambil semua data stunting untuk anak ini dengan pagination
            $stunting = Stunting::where('anak_id', $anak_id)
                ->orderBy('tanggal', 'desc')
                ->paginate($perPage)
                ->appends(['perPage' => $perPage]);
            
            if ($stunting->isEmpty()) {
                return redirect()->route('stunting.index')
                    ->with('error', 'Tidak ada data riwayat stunting untuk anak ini');
            }
            
            $action = 'riwayat';
            return view('stunting', compact('stunting', 'anak', 'action'));
            
        } catch (\Exception $e) {
            return redirect()->route('stunting.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data riwayat: ' . $e->getMessage());
        }
    }

    public function excel()
    {
        return Excel::download(new StuntingExport, 'data-stunting.xlsx');
    }
}
