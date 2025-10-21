<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Obat::query()->where('is_aktif', true);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'LIKE', "%{$search}%")
                  ->orWhere('kode_obat', 'LIKE', "%{$search}%");
            });
        }

        $obats = $query->orderBy('nama_obat')
                      ->paginate(10);

        return response()->json([
            'data' => $obats->items(),
            'total' => $obats->total(),
            'per_page' => $obats->perPage(),
            'current_page' => $obats->currentPage(),
            'last_page' => $obats->lastPage(),
        ]);
    }
}