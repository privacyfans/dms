<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use DB;
class NewsController extends Controller
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

        $pagination  = 10;
        $news    = News::when($request->keyword, function ($query) use ($request) {
            $query
            ->where('title', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);
    
        $news->appends($request->only('keyword'));
        //dd($news);
        return view('news.index', [
            'news' => $news,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

        /*$news = News::orderBy('news_code','asc')->paginate(10);
    
        return view('news.index',compact('news'))
            ->with('i', (request()->input('page', 1) - 1) * 10);*/
    }

    public function list(Request $request)
    {

        $pagination  = 20;
        $news    = News::when($request->keyword, function ($query) use ($request) {
            $query
            ->where('title', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);
    
        $news->appends($request->only('keyword'));
        //dd($news);
        return view('news.list', [
            'news' => $news,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

        /*$news = News::orderBy('news_code','asc')->paginate(10);
    
        return view('news.index',compact('news'))
            ->with('i', (request()->input('page', 1) - 1) * 10);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create');
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
            'title' => 'required|unique:news,title',
            'desc'  => 'required',
            'popup' => 'required',
            'file'  => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048', // nullable agar opsional
        ]);

        $fileName = null;

        if ($request->hasFile('file')) {
            $fileName = 'news_'.time().'.'.$request->file->extension();  
            $request->file->move(public_path('uploads'), $fileName);
        }

        $news = new News;
        $news->title = $request->title;
        $news->desc  = $request->desc;
        $news->popup = $request->popup;
        $news->file  = $fileName; // bisa null kalau tidak upload
        $news->save();

        return redirect()->route('news.index')
                        ->with('success','News created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('news.show',compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('news.edit',compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required',
            'desc' => 'required',
            'popup' => 'required',
            //'file' => 'mimes:pdf|max:2048',
        ]);
        $affected = DB::table('news')->update(array('popup' => 0));
        //$model= News::findOrFail($request->id);
        //dd($request->file->extension());
        if(!empty($request->file)){
        $fileName = 'news_'.time().'.'.$request->file->extension();  
        $request->file->move(public_path('uploads'), $fileName);
        }else{
            $fileName=$news->file;
        }
        $news->title =  $request->title;
        $news->desc =  $request->desc;
        $news->popup =  $request->popup;
        $news->file =  $fileName;
        $news->save();
        //$news->update($request->all());
    
        return redirect()->route('news.index')
                        ->with('success','News updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
    
        return redirect()->route('news.index')
                        ->with('success','News deleted successfully');
    }
}
