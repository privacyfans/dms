<?php

namespace App\Http\Controllers;

use App\Models\KebijakanSop;
use Illuminate\Http\Request;

class KebijakanSopController extends Controller
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
        $kebijakanSop = KebijakanSop::when($request->keyword, function ($query) use ($request) {
            $query->where('title', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);

        $kebijakanSop->appends($request->only('keyword'));

        return view('kebijakan-sop.index', [
            'kebijakanSop' => $kebijakanSop,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kebijakan-sop.create');
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
            'title' => 'required|unique:kebijakan_sop,title',
            'deskripsi' => 'required',
            'url' => 'required|url',
            'status' => 'required|in:active,inactive',
        ]);

        $kebijakanSop = new KebijakanSop;
        $kebijakanSop->title = $request->title;
        $kebijakanSop->deskripsi = $request->deskripsi;
        $kebijakanSop->url = $request->url;
        $kebijakanSop->status = $request->status;
        $kebijakanSop->save();

        return redirect()->route('kebijakan-sop.index')
            ->with('success', 'Kebijakan SOP created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KebijakanSop  $kebijakanSop
     * @return \Illuminate\Http\Response
     */
    public function show(KebijakanSop $kebijakanSop)
    {
        return view('kebijakan-sop.show', compact('kebijakanSop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KebijakanSop  $kebijakanSop
     * @return \Illuminate\Http\Response
     */
    public function edit(KebijakanSop $kebijakanSop)
    {
        return view('kebijakan-sop.edit', compact('kebijakanSop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KebijakanSop  $kebijakanSop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KebijakanSop $kebijakanSop)
    {
        $request->validate([
            'title' => 'required',
            'deskripsi' => 'required',
            'url' => 'required|url',
            'status' => 'required|in:active,inactive',
        ]);

        $kebijakanSop->title = $request->title;
        $kebijakanSop->deskripsi = $request->deskripsi;
        $kebijakanSop->url = $request->url;
        $kebijakanSop->status = $request->status;
        $kebijakanSop->save();

        return redirect()->route('kebijakan-sop.index')
            ->with('success', 'Kebijakan SOP updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KebijakanSop  $kebijakanSop
     * @return \Illuminate\Http\Response
     */
    public function destroy(KebijakanSop $kebijakanSop)
    {
        $kebijakanSop->delete();

        return redirect()->route('kebijakan-sop.index')
            ->with('success', 'Kebijakan SOP deleted successfully');
    }
}
