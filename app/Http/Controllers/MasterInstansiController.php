<?php

namespace App\Http\Controllers;

use App\Models\MasterInstansi;
use App\Models\MasterKlasifikasiDebitur;
use Illuminate\Http\Request;

class MasterInstansiController extends Controller
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
        $instansi = MasterInstansi::when($request->keyword, function ($query) use ($request) {
            $query
                ->where('instansi', 'like', "%{$request->keyword}%")
                ->orWhere('klasifikasi_debitur', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);

        $instansi->appends($request->only('keyword'));

        return view('master-instansi.index', [
            'instansi' => $instansi,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    public function klasifikasiSearch(Request $request)
    {
        $klasifikasi = [];

        if ($request->has('q')) {
            $search = $request->q;
            $klasifikasi = MasterKlasifikasiDebitur::select("id", "klasifikasi_debitur")
                ->where('klasifikasi_debitur', 'LIKE', "%$search%")
                ->get();
        } else {
            $klasifikasi = MasterKlasifikasiDebitur::select("id", "klasifikasi_debitur")
                ->get();
        }
        return response()->json($klasifikasi);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-instansi.create');
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
            'instansi' => 'required',
            'klasifikasi_debitur' => 'required',
            'status' => 'required',
        ]);

        MasterInstansi::create($request->all());

        return redirect()->route('master-instansi.index')
            ->with('success', 'Master Instansi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterInstansi  $masterInstansi
     * @return \Illuminate\Http\Response
     */
    public function show(MasterInstansi $masterInstansi)
    {
        return view('master-instansi.show', compact('masterInstansi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterInstansi  $masterInstansi
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterInstansi $masterInstansi)
    {
        $klasifikasiSelected = [];
        $klasifikasiList = MasterKlasifikasiDebitur::select("id", "klasifikasi_debitur")
            ->where('klasifikasi_debitur', $masterInstansi->klasifikasi_debitur)
            ->get();

        foreach ($klasifikasiList as $klas) {
            $klasifikasiSelected[] = $klas->klasifikasi_debitur;
        }

        return view('master-instansi.edit', compact('masterInstansi', 'klasifikasiList', 'klasifikasiSelected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterInstansi  $masterInstansi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterInstansi $masterInstansi)
    {
        $request->validate([
            'instansi' => 'required',
            'klasifikasi_debitur' => 'required',
            'status' => 'required',
        ]);

        $masterInstansi->update($request->all());

        return redirect()->route('master-instansi.index')
            ->with('success', 'Master Instansi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterInstansi  $masterInstansi
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterInstansi $masterInstansi)
    {
        $masterInstansi->delete();

        return redirect()->route('master-instansi.index')
            ->with('success', 'Master Instansi berhasil dihapus.');
    }
}
