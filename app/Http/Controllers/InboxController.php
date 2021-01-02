<?php

namespace App\Http\Controllers;

use App\Inbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use File;

class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function show(Inbox $inbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Inbox $inbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inbox $inbox)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inbox $inbox)
    {
        //
    }
    public function inbox(Request $request)
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
        $model->op = Auth::user()->id;
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
                return redirect('/home');
            }
        }
        $save = $model->save();
        if($save)
        {
            $request->session()->flash('success', 'Data Updated');
            return redirect('/home');
        }
        $request->session()->flash('error', 'Error');
        return redirect('/home');
    }
}
