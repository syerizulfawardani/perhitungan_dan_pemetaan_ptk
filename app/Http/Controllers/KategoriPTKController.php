<?php

namespace App\Http\Controllers;

use App\Models\KategoriPTK;
use Illuminate\Http\Request;

class KategoriPTKController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kategori' => 'required|string',
        ]);

        $kategori = new KategoriPTK();

        $kategori->jenis_kategori = $request->jenis_kategori;

        $kategori->save();

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }
}
