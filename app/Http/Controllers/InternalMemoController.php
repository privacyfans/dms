<?php

namespace App\Http\Controllers;

use App\Models\InternalMemo;
use App\Models\InternalMemoFile;
use Illuminate\Http\Request;

class InternalMemoController extends Controller
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
        $internalMemo = InternalMemo::with('files')->when($request->keyword, function ($query) use ($request) {
            $query->where('title', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);

        $internalMemo->appends($request->only('keyword'));

        return view('internal-memo.index', [
            'internalMemo' => $internalMemo,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('internal-memo.create');
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
            'title' => 'required|unique:internal_memo,title',
            'deskripsi' => 'required',
            'status' => 'required|in:active,inactive',
            'files.*' => 'nullable|mimes:pdf,png,jpg,jpeg|max:2048',
        ], [
            'title.required' => 'Judul harus diisi.',
            'title.unique' => 'Judul sudah digunakan, silakan gunakan judul lain.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'files.*.mimes' => 'File harus berformat: PDF, PNG, JPG, atau JPEG.',
            'files.*.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Create Internal Memo
        $internalMemo = new InternalMemo;
        $internalMemo->title = $request->title;
        $internalMemo->deskripsi = $request->deskripsi;
        $internalMemo->status = $request->status;
        $internalMemo->save();

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = 'memo_' . time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads'), $fileName);

                $memoFile = new InternalMemoFile;
                $memoFile->internal_memo_id = $internalMemo->id;
                $memoFile->file_name = $file->getClientOriginalName();
                $memoFile->file_path = $fileName;
                $memoFile->save();
            }
        }

        return redirect()->route('internal-memo.index')
            ->with('success', 'Internal Memo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InternalMemo  $internalMemo
     * @return \Illuminate\Http\Response
     */
    public function show(InternalMemo $internalMemo)
    {
        $internalMemo->load('files');
        return view('internal-memo.show', compact('internalMemo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InternalMemo  $internalMemo
     * @return \Illuminate\Http\Response
     */
    public function edit(InternalMemo $internalMemo)
    {
        $internalMemo->load('files');
        return view('internal-memo.edit', compact('internalMemo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InternalMemo  $internalMemo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InternalMemo $internalMemo)
    {
        $request->validate([
            'title' => 'required',
            'deskripsi' => 'required',
            'status' => 'required|in:active,inactive',
            'files.*' => 'nullable|mimes:pdf,png,jpg,jpeg|max:2048',
        ], [
            'title.required' => 'Judul harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'files.*.mimes' => 'File harus berformat: PDF, PNG, JPG, atau JPEG.',
            'files.*.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $internalMemo->title = $request->title;
        $internalMemo->deskripsi = $request->deskripsi;
        $internalMemo->status = $request->status;
        $internalMemo->save();

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = 'memo_' . time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads'), $fileName);

                $memoFile = new InternalMemoFile;
                $memoFile->internal_memo_id = $internalMemo->id;
                $memoFile->file_name = $file->getClientOriginalName();
                $memoFile->file_path = $fileName;
                $memoFile->save();
            }
        }

        return redirect()->route('internal-memo.index')
            ->with('success', 'Internal Memo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InternalMemo  $internalMemo
     * @return \Illuminate\Http\Response
     */
    public function destroy(InternalMemo $internalMemo)
    {
        // Delete associated files from storage
        foreach ($internalMemo->files as $file) {
            $filePath = public_path('uploads/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $internalMemo->delete();

        return redirect()->route('internal-memo.index')
            ->with('success', 'Internal Memo deleted successfully');
    }

    /**
     * Delete individual file
     *
     * @param  int  $fileId
     * @return \Illuminate\Http\Response
     */
    public function deleteFile($fileId)
    {
        $file = InternalMemoFile::findOrFail($fileId);
        $filePath = public_path('uploads/' . $file->file_path);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file->delete();

        return back()->with('success', 'File deleted successfully');
    }
}
