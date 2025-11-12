<?php

namespace App\Http\Controllers;

use App\Models\SettingTime;
use Illuminate\Http\Request;

class SettingTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function edit($id)
    {
        $setting_time = SettingTime::findOrFail($id);
        return view('setting_time.edit', compact('setting_time'));
    }

    public function update(Request $request, $id)
    {
        $setting_time = SettingTime::findOrFail($id);
        $setting_time->update($request->all());
        return redirect()->route('setting_time.edit', $setting_time->id)
                        ->with('success', 'Waktu Berhasil Diupdate');
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettingTime $setting_time)
    {
        $setting_time->delete();
    
        return redirect()->route('setting_time.index')
                        ->with('success','Permintaan Perpanjangan Waktu Layanan deleted successfully');
    }
}
