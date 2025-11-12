<?php

namespace App\Http\Controllers;

use App\Models\SettingAi;
use Illuminate\Http\Request;

class SettingAiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function edit($id)
    {
        $setting_ai = SettingAi::findOrFail($id);
        return view('setting_ai.edit', compact('setting_ai'));
    }

    public function update(Request $request, $id)
    {
        $setting_ai = SettingAi::findOrFail($id);
        $setting_ai->update($request->all());
        return redirect()->route('setting_ai.edit', $setting_ai->id)
                        ->with('success', 'Setting AI Validator Berhasil Diupdate');
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettingTime $setting_ai)
    {
        $setting_ai->delete();
    
        return redirect()->route('setting_ai.index')
                        ->with('success','Setting AI deleted successfully');
    }
}
