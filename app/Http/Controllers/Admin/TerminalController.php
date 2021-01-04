<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Terminal;

class TerminalController extends Controller
{
    public function __construct()
    {
        $this->uri = 'terminals';
        $this->title = 'Terminal';
    }
    public function index()
    {
        if(is_admin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = Terminal::orderBy('id', 'DESC')->paginate(10);
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
                $model = Terminal::where('id', '>=', 0);
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('name', 'like', '%'.strip_tags($request->get('query')).'%')
                                ->orWhere('terminal_id', 'like', '%'.strip_tags($request->get('query')).'%');
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
    public function store(Request $request, Terminal $terminal)
    {
        if(is_admin())
        {
            $validasi =[
                    'terminal_id' => ['required', 'unique:terminals'],
                    'name' => ['required'],
                ];
            $msg = [
                'terminal_id.required' => 'Terminal ID tidak boleh kosong',
                'name.required' => 'Nama tidak boleh kosong',
            ];
            $terminal->terminal_id = trim($request->terminal_id);
            $terminal->name = trim($request->name);
            $request->validate($validasi, $msg);
            if($terminal->save())
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
    public function edit(Terminal $terminal)
    {
        if(is_admin())
        {
            $data['row'] = $terminal;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Terminal $terminal)
    {
        if(is_admin())
        {
            $validasi =[
                    'name' => ['required'],
                    'terminal_id' => ['required', 'unique:terminals,terminal_id,'.$terminal->id.',id'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'terminal_id.required' => 'Terminal ID tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);

            $terminal->name = trim($request->name);
            $terminal->terminal_id = trim($request->name);
    
            if($terminal->save())
            {
                $request->session()->flash('success', 'Sukses update '.$this->title);
                if(is_cs())
                {
                    return redirect()->route('admin.home');
                }
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat update '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, Terminal $terminal)
    {
        if(is_admin())
        {
            $terminal->delete();
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
            $terminals = Terminal::find($id);
            foreach($terminals as $key=> $terminal)
            {
                $terminal->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
