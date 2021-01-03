<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\KpknlOffice;

class KpknlOfficeController extends Controller
{
    public function __construct()
    {
        $this->uri = 'kpknl';
        $this->title = 'KPKNL';
    }
    public function index()
    {
        if(is_admin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = KpknlOffice::latest('id')->paginate(20);
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')))
            {
                $model = KpknlOffice::whereNotNull('name');
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('djkn_name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('djkn_id', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('phone', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('fax', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('address', 'like', '%'.strip_tags($request->get('query')).'%');
                    });
                }
                if($request->get('orderby'))
                {
                    if($request->get('order') == 'asc')
                    {
                        $model->orderBy($request->get('orderby'), 'ASC');
                    }else{
                        $model->orderBy($request->get('orderby'), 'DESC');
                    }
                }else{
                    $model->orderBy('id', 'DESC');
                }

                $data['title'] = "Pencarian ".$this->title." - ".env('APP_NAME', 'Awesome Website');
                $data['pagetitle'] = "Pencarian ".$this->title;
                $data['uri'] = $this->uri;
                $data['lists'] = $model->paginate(20);
                return view('backend.'.$this->uri.'.list', $data);
            }else{
                return redirect()->route('admin.'.$this->uri.'.index');
            }
        }else{
            abort(404);
        }
    }
    public function create()
    {
        if(is_admin())
        {
            $data['title'] = "Tambah ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Tambah ".$this->title;
            $data['uri'] = $this->uri;
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, KpknlOffice $kpknl)
    {
        if(is_admin())
        {
            $validasi =[
                    'name' => ['required'],
                    'djkn_name' => ['required'],
                    'djkn_id' => ['required'],
                    'phone' => ['required'],
                    'fax' => ['required'],
                ];
            $msg = [
                'name.required' => 'Nama KPKNL tidak boleh kosong',
                'djkn_name.required' => 'Nama DJKN tidak boleh kosong',
                'djkn_id.required' => 'ID DJKN tidak boleh kosong',
                'phone.required' => 'Nomor Telepon tidak boleh kosong',
                'fax.required' => 'Fax tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);

            $kpknl->name = trim($request->name);
            $kpknl->djkn_name = trim($request->djkn_name);
            $kpknl->djkn_id = trim($request->djkn_id);
            $kpknl->phone = trim($request->phone);
            $kpknl->fax = trim($request->fax);
            $kpknl->address = trim($request->address);
    
            if($kpknl->save())
            {
                $request->session()->flash('success', $this->title.' baru ditambahkan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat menambah '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function edit(KpknlOffice $kpknl)
    {
        if(is_admin())
        {
            $data['row'] = $kpknl;
            $data['title'] = "Ubah ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Ubah ".$this->title;
            $data['uri'] = $this->uri;
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, KpknlOffice $kpknl)
    {
        if(is_admin())
        {
            $validasi =[
                    'name' => ['required'],
                    'djkn_name' => ['required'],
                    'djkn_id' => ['required'],
                    'phone' => ['required'],
                    'fax' => ['required'],
                ];
            $msg = [
                'name.required' => 'Nama KPKNL tidak boleh kosong',
                'djkn_name.required' => 'Nama DJKN tidak boleh kosong',
                'djkn_id.required' => 'ID DJKN tidak boleh kosong',
                'phone.required' => 'Nomor Telepon tidak boleh kosong',
                'fax.required' => 'Fax tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);

            $kpknl->name = trim($request->name);
            $kpknl->djkn_name = trim($request->djkn_name);
            $kpknl->djkn_id = trim($request->djkn_id);
            $kpknl->phone = trim($request->phone);
            $kpknl->fax = trim($request->fax);
            $kpknl->address = trim($request->address);
    
            if($kpknl->save())
            {
                $request->session()->flash('success', 'Sukses update '.$this->title);
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat update '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, KpknlOffice $kpknl)
    {
        if(is_admin())
        {
            $kpknl->delete();
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function deletemass(Request $request)
    {
        
        if(is_admin())
        {
            $id = explode(",", $request->ids);            
            $kpknl = KpknlOffice::find($id);
            foreach($kpknl as $key=> $kpk)
            {
                $kpk->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
