<?php

namespace App\Http\Controllers;

use App\Inbox;
use App\Setting;
use App\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use File;
use App\Exports\InboxExport;
use Maatwebsite\Excel\Facades\Excel;

class InboxController extends Controller
{
    public function inbox(Request $request)
    {
        //return $request->data;
        if(!empty($request))
        {
            $pengirim = $request->get('pengirim');
            $latest = Inbox::orderby('code', 'DESC')->first();
            $lastid = 0;
            if($latest)
            {
                $lastid = $latest->code;
            }
            $cek = Inbox::where('code', $request->get('code'))->first();
            if(!$cek){
                if(intval($request->get('code')) > $lastid)
                {
                    //if(!empty($pengirim))
                    //{
                        //if (in_array($request->get('sender'), $pengirim))
                        //{
                            $array = array(
                                'code' => $request->get('code'),
                                'sender' => $request->get('sender'),
                                'transaction_id' => $request->get('transactionid'),
                                'status' => 0,
                                'message' => $request->get('message'),
                                'tanggal' => $request->get('tgl'),
                                'terminal' => $request->get('terminal'),
                                'total' => $request->get('total'),
                                'op' => 0,
                                'identifier' => $request->get('identifier'),
                            );
                            Inbox::create($array);
                        //}
                    //}
                    return response()->json([
                        'status' => 'sukses'
                    ], 200);
                }
            }

            return response()->json([
                'status' => 'gagal'
            ], 200);
        }
    }
    public function inboxbak(Request $request)
    {
        //return $request->data;
        if(!empty($request->data))
        {
            $datas = $request->data;
            $pengirim = $request->pengirim;
            $latest = Inbox::orderby('code', 'DESC')->first();
            $lastid = 0;
            if($latest)
            {
                $lastid = $latest->code;
            }
            foreach($datas as $data)
            {
                $cek = Inbox::where('code', $data['code'])->first();
                if(!$cek){
                    if(intval($data['code']) > $lastid)
                    {
                        if(!empty($pengirim))
                        {
                            if (in_array($data['sender'], $pengirim))
                            {
                                $array = array(
                                    'code' => $data['code'],
                                    'sender' => $data['sender'],
                                    'transaction_id' => $data['transactionid'],
                                    'status' => 0,
                                    'message' => $data['message'],
                                    'tanggal' => $data['tgl'],
                                    'op' => 0
                                );
                                Inbox::create($array);
                            }
                        }
                    }
                }
            }
            return response()->json([
                'status' => 'success'
            ], 200);
        }
    }
    public function apdet(Request $request)
    {
        $model = Inbox::find($request->id);
        $model->status = $request->get('status');
        if($request->get('status') == 1)
        {
            $model->op = Auth::user()->id;
        }
        if($request->file('file'))
        {
            $validasi = Validator::make($request->all(), [
                'file' => 'mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:2046',
            ],[
                'file.mimes' => 'File tidak didukung',
                'file.max' => 'Ukuran maksimal 2Mb',
            ]);
            if($validasi->validate())
            {
                $images = $request->file('file');
                $image = Image::make($images);            
                $path = 'assets/images/inbox/'.$model->id.'/';
                $dir = public_path($path);
                if(!File::isDirectory($dir))
                {
                    File::makeDirectory($dir);
                }
                $file_name = Str::slug($images->getClientOriginalName(), '-').'-'.time();
                $name = $file_name.'-full.'.$images->extension();
                $thumb = $file_name.'-thumb.'.$images->extension();
                $image->save($dir.$name);
                $image->crop(400, 400);
                $image->save($dir.$thumb);
                $model->image = $path.$name;
                $model->thumb = $path.$thumb;
            }else{
                $request->session()->flash('error', 'Error Image Format');
                return redirect()->route('admin.inboxes.index');
            }
        }
        $save = $model->save();
        if($save)
        {
            $request->session()->flash('success', 'Data Updated');
            return redirect()->route('admin.inboxes.index');
        }
        $request->session()->flash('error', 'Error');
        return redirect()->route('admin.inboxes.index');
    }
    public function lastinbox($identifier = 'default')
    {
        date_default_timezone_set('Asia/Jakarta');
        $sett = Setting::where('key', 'lastupdate')->first();
        $sett->value = date('Y-m-d H:i:s', time());
        $sett->save();

        $model = Inbox::where('identifier', $identifier)->orderBy('code', 'DESC')->first();
        if($model)
        {
            return intval($model->code);
        }
        return 0;
    }
    public function unduh(Request $request)
    {
        return Excel::download(new InboxExport($request->op, $request->from, $request->to, $request->terminal), 'report.xlsx');
    }
    public function saldo(Request $request)
    {
        if(!empty($request))
        {
            $terminal = $request->get('terminal');
            $modelterminal = Terminal::all();
            foreach($modelterminal as $key=>$val)
            {
                if($val->terminal_id == $terminal)
                {
                    $save_terminal = Terminal::where('terminal_id', $terminal)->first();
                    $save_terminal->saldo = $request->get('saldo');
                    $save_terminal->save();
                }
            }
            $model = Setting::where('key', 'saldo')->first();
            $model->value = intval($request->get('saldo'));
            $model->save();
            return response()->json([
                'status' => 'sukses'
            ], 200);
            
        }
        return response()->json([
            'status' => 'gagal'
        ], 200);
    }
}
