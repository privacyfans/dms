<?php

namespace App\Http\Controllers;

use App\Models\MasterKlasifikasiDebitur;
use Illuminate\Http\Request;

class MasterKlasifikasiDebiturController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = 10;
        $klasifikasi = MasterKlasifikasiDebitur::when($request->keyword, function ($query) use ($request) {
            $query->where('klasifikasi_debitur', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);

        $klasifikasi->appends($request->only('keyword'));

        return view('master-klasifikasi-debitur.index', [
            'klasifikasi' => $klasifikasi,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-klasifikasi-debitur.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'klasifikasi_debitur' => 'required',
            'status' => 'required',
        ]);

        MasterKlasifikasiDebitur::create($request->all());

        return redirect()->route('master-klasifikasi-debitur.index')
            ->with('success', 'Master Klasifikasi Debitur berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterKlasifikasiDebitur  $masterKlasifikasiDebitur
     * @return \Illuminate\Http\Response
     */
    public function show(MasterKlasifikasiDebitur $masterKlasifikasiDebitur)
    {
        return view('master-klasifikasi-debitur.show', compact('masterKlasifikasiDebitur'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterKlasifikasiDebitur  $masterKlasifikasiDebitur
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKlasifikasiDebitur $masterKlasifikasiDebitur)
    {
        return view('master-klasifikasi-debitur.edit', compact('masterKlasifikasiDebitur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterKlasifikasiDebitur  $masterKlasifikasiDebitur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterKlasifikasiDebitur $masterKlasifikasiDebitur)
    {
        $request->validate([
            'klasifikasi_debitur' => 'required',
            'status' => 'required',
        ]);

        $masterKlasifikasiDebitur->update($request->all());

        return redirect()->route('master-klasifikasi-debitur.index')
            ->with('success', 'Master Klasifikasi Debitur berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterKlasifikasiDebitur  $masterKlasifikasiDebitur
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKlasifikasiDebitur $masterKlasifikasiDebitur)
    {
        $masterKlasifikasiDebitur->delete();

        return redirect()->route('master-klasifikasi-debitur.index')
            ->with('success', 'Master Klasifikasi Debitur berhasil dihapus.');
    }
}
