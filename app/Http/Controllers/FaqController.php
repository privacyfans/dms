<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
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
       
        $faq    = Faq::when($request->keyword, function ($query) use ($request) {
            $query
            ->where('question', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);
    
        $faq->appends($request->only('keyword'));
        //dd($faq);
        return view('faq.index', [
            'faq' => $faq,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

       
    }

    // public function index(Request $request)
    // {

    //     $faq=Faq::when($request->has("question"),function($q)use($request){
    //         return $q->where("question","like","%".$request->get("question")."%");
    //     })->paginate(5);
    //     if($request->ajax()){
    //         return view('faq.faq-pagination',['faq'=>$faq]); 
    //     } 
    //     return view('faq.index',['faq'=>$faq]);
    // }

    // Public function faq_search(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $data = Faq::search($request->get('full_text_search_query'))->get();
    //         return response()->json($data);
    //     }
    // } 

    public function list(Request $request)
    {

        $pagination  = 20;
        $faq    = Faq::when($request->keyword, function ($query) use ($request) {
            $query
            ->where('question', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate($pagination);
    
        $faq->appends($request->only('keyword'));
        //dd($faq);
        return view('faq.list', [
            'faq' => $faq,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

        /*$faq = Faq::orderBy('faq_code','asc')->paginate(10);
    
        return view('faq.index',compact('faq'))
            ->with('i', (request()->input('page', 1) - 1) * 10);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('faq.create');
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
            'question' => 'required|unique:faq,question',
            'answer' => 'required',
        ]);
        
        //dd($request->answer );
        Faq::create($request->all());
     
        return redirect()->route('faq.index')
                        ->with('success','Faq created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return view('faq.show',compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        return view('faq.edit',compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
    
        $faq->update($request->all());
    
        return redirect()->route('faq.index')
                        ->with('success','Faq updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
    
        return redirect()->route('faq.index')
                        ->with('success','Faq deleted successfully');
    }
}
