<?php

namespace App\Http\Controllers;

use App\Models\KebijakanSop;
use App\Models\InternalMemo;
use Illuminate\Http\Request;

class PeraturanKebijakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display combined list of Kebijakan SOP and Internal Memo
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $yearSop = $request->get('year_sop');
        $yearMemo = $request->get('year_memo');

        // Get active Kebijakan SOP
        $kebijakanSopQuery = KebijakanSop::where('status', 'active');

        if ($yearSop) {
            $kebijakanSopQuery->whereYear('created_at', $yearSop);
        }

        $kebijakanSop = $kebijakanSopQuery->orderBy('created_at', 'desc')->get();

        // Get active Internal Memo with files
        $internalMemoQuery = InternalMemo::with('files')->where('status', 'active');

        if ($yearMemo) {
            $internalMemoQuery->whereYear('created_at', $yearMemo);
        }

        $internalMemo = $internalMemoQuery->orderBy('created_at', 'desc')->get();

        // Get available years for SOP
        $availableYearsSop = KebijakanSop::where('status', 'active')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get available years for Internal Memo
        $availableYearsMemo = InternalMemo::where('status', 'active')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('peraturan-kebijakan.index', [
            'kebijakanSop' => $kebijakanSop,
            'internalMemo' => $internalMemo,
            'availableYearsSop' => $availableYearsSop,
            'availableYearsMemo' => $availableYearsMemo,
            'selectedYearSop' => $yearSop,
            'selectedYearMemo' => $yearMemo,
        ]);
    }
}
