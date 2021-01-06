<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Inbox;
use App\Setting;
use App\Terminal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function __construct()
    {
        $this->uri = 'inboxes';
        $this->title = 'Report';
    }
    public function index()
    {
        if(is_admin() || is_subadmin() || is_cs())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            if(is_admin())
            {
                $data['lists'] = Inbox::orderBy('code', 'DESC')->paginate(20);
                $list_all = Inbox::orderBy('code', 'DESC')->get();
                $data['users'] = User::orderBy('name', 'ASC')->get();
            }else{
                $data['users'] = User::where('owner', Auth::user()->id)->orderBy('name', 'ASC')->get();
                $data['lists'] = Inbox::where('terminal', Auth::user()->terminal)->orderBy('code', 'DESC')->paginate(20);
                $list_all = Inbox::where('terminal', Auth::user()->terminal)->orderBy('code', 'DESC')->get();
            }
            $data['saldo'] = Setting::where('key', 'saldo')->first();
            $data['lastupdate'] = Setting::where('key', 'lastupdate')->first();
            $data['terminals'] = Terminal::orderBy('name', 'ASC')->get();
            foreach($data['lists'] as $key=> $val)
            {
                $data['lists'][$key]->operator = '-';
                if(User::find($val->op))
                {
                    $data['lists'][$key]->operator = User::find($val->op)->name;
                }
                $terminal = Terminal::where('terminal_id', $val->terminal)->first();
                if($terminal)
                {
                    $data['lists'][$key]->terminal = $terminal->name;
                }else{
                    $data['lists'][$key]->terminal = '-';
                }
            }
            $total_saldo = 0;
            foreach($list_all as $key=>$val)
            {
                $total_saldo += $val->total;
            }
            $data['total_saldo'] = $total_saldo;
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin() || is_cs())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')) || !empty($request->get('from')) || !empty($request->get('to')) || !empty($request->get('operator')) || !empty($request->get('terminal')))
            {
                if(is_admin())
                {
                    $model = Inbox::whereNotNull('code');
                }else{
                    $model = Inbox::where('terminal', Auth::user()->terminal);
                }
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('code', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('sender', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('message', 'like', '%'.strip_tags($request->get('query')).'%');
                    });
                }
                if(!empty($request->get('from')) && !empty($request->get('to')))
                {
                    $from = $request->get('from').' 00:00:01';
                    $to = $request->get('to').' 23:23:59';
                    $d_from = strtotime($from);
                    $d_to = strtotime($to);
                    $sfrom = date('Y-m-d H:i:s', $d_from);
                    $sto = date('Y-m-d H:i:s', $d_to);

                    $model->where('tanggal', '>=', $sfrom);
                    $model->where('tanggal', '<=', $sto);
                }
                if(!empty($request->get('operator')))
                {
                    $model->where('op', $request->get('operator'));
                }
                if(!empty($request->get('terminal')))
                {
                    $model->where('terminal', $request->get('terminal'));
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
                    $model->orderBy('code', 'DESC');
                }

                $data['title'] = "Pencarian ".$this->title." - ".env('APP_NAME', 'Awesome Website');
                $data['pagetitle'] = "Pencarian ".$this->title;
                $data['uri'] = $this->uri;
                $data['lists'] = $model->paginate(20);
                foreach($data['lists'] as $key=> $val)
                {
                    $data['lists'][$key]->operator = '-';
                    if(User::find($val->op))
                    {
                        $data['lists'][$key]->operator = User::find($val->op)->name;
                    }
                    $terminal = Terminal::where('terminal_id', $val->terminal)->first();
                    if($terminal)
                    {
                        $data['lists'][$key]->terminal = $terminal->name;
                    }else{
                        $data['lists'][$key]->terminal = '-';
                    }
                }
                if(is_admin())
                {
                    $data['users'] = User::orderBy('name', 'ASC')->get();
                }else{
                    $data['users'] = User::where('owner', Auth::user()->id)->orderBy('name', 'ASC')->get();
                }
                $data['saldo'] = Setting::where('key', 'saldo')->first();
                $data['lastupdate'] = Setting::where('key', 'lastupdate')->first();
                $data['terminals'] = Terminal::orderBy('name', 'ASC')->get();

                $total_saldo = 0;
                foreach($model->get() as $key=>$val)
                {
                    $total_saldo += $val->total;
                }
                $data['total_saldo'] = $total_saldo;
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
        if(is_admin() || is_cs())
        {
            $data['title'] = "Tambah ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Tambah ".$this->title;
            $data['uri'] = $this->uri;
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, Category $category)
    {
        if(is_admin() || is_cs())
        {
            $validasi =[
                    'name' => ['required','unique:categories'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'name.unique' => 'Nama sudah terdaftar',
            ];
            
            $request->validate($validasi, $msg);
            $category->name = trim($request->name);
            $category->slug = Str::slug(trim($request->name), '-');
            $category->status = trim($request->status);
            $category->description = trim($request->description);
    
            if($category->save())
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
    public function edit(Category $category)
    {
        if(is_admin() || is_cs())
        {
            $data['row'] = $category;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Category $category)
    {
        if(is_admin() || is_cs())
        {
            $validasi =[
                    'name' => ['required','unique:categories,name,'.$category->id.',id'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'name.unique' => 'Nama sudah terdaftar',
            ];
            
            $request->validate($validasi, $msg);
            $category->name = trim($request->name);
            $category->slug = Str::slug(trim($request->name), '-');
            $category->status = trim($request->status);
            $category->description = trim($request->description);
    
            if($category->save())
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
    public function destroy(Request $request, Inbox $inbox)
    {
        if(is_admin())
        {
            $inbox->delete();
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
            $ids = explode(",", $request->ids);  
            foreach($ids as $key=> $id)
            {
                $inbox = Inbox::find($id);
                $inbox->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
