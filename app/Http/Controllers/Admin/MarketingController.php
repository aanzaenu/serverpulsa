<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Marketing;
use App\Branch;
use App\PhoneBranch;
use App\Location;
use Illuminate\Support\Str;
use Image;
use File;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->uri = 'marketings';
        $this->title = 'Sales';
    }
    public function index()
    {
        if(is_admin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = Marketing::with(['branches'])->latest('id')->paginate(20);
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
                $model = Marketing::join('branch_marketing', 'marketings.id', '=','branch_marketing.marketing_id')
                        ->join('branches', 'branches.id', '=','branch_marketing.branch_id')
                        ->select('marketings.*')->with(['branches']);
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('marketings.name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('marketings.phone', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('marketings.whatsapp', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('marketings.email', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('marketings.address', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('branches.name', 'like', '%'.strip_tags($request->get('query')).'%');
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
                    $model->orderBy('marketings.id', 'DESC');
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
            $data['branches'] = Branch::latest('id')->get();
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, Marketing $marketing)
    {
        if(is_admin())
        {
            $validasi =[
                    'name' => ['required','unique:marketings'],
                    'branch' => ['required'],
                    'phone' => ['required'],
                    'whatsapp' => ['required'],
                    'email' => ['required', 'email'],
                    'lat' => ['required'],
                    'long' => ['required'],
                    'image' => ['required'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'name.unique' => 'Nama sudah terdaftar',
                'branch.required' => 'Kantor Cabang tidak boleh kosong',
                'phone.required' => 'Nomor Telepon tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format Email salah',
                'lat.required' => 'Latitude tidak boleh kosong',
                'long.required' => 'Longitude tidak boleh kosong',
                'image.required' => 'Gambar tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);

            $marketing->name = trim($request->name);
            $marketing->slug = Str::slug(trim($request->name), '-');
            $marketing->phone = trim($request->phone);
            $marketing->whatsapp = trim($request->whatsapp);
            $marketing->email = trim($request->email);
            $marketing->lat = trim($request->lat);
            $marketing->long = trim($request->long);
            $marketing->address = trim($request->address);
    
            if($marketing->save())
            {
                $marketing->branches()->attach($request->branch);
                if($request->image)
                {
                    $images = $request->image;
                    $image = Image::make($images);
                    $path = 'assets/images/sales/'.$marketing->id.'/';
                    $dir = public_path($path);
                    if(!File::isDirectory($dir))
                    {
                        File::makeDirectory($dir);
                    }
                    $file_name = Str::slug($images->getClientOriginalName(), '-').'-'.time();
                    $name = $file_name.'-full.'.$images->extension();
                    $thumb = $file_name.'-thumb.'.$images->extension();
                    $image->save($dir.$name);
                    $image->crop(300, 300);
                    $image->save($dir.$thumb);

                    $marketing->image = $path.$name;
                    $marketing->thumb = $path.$thumb;
                    $marketing->save();
                }

                $request->session()->flash('success', $this->title.' baru ditambahkan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat menambah '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function edit(Marketing $marketing)
    {
        if(is_admin())
        {
            $data['row'] = $marketing;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            $data['branches'] = Branch::latest('id')->get();
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Marketing $marketing)
    {
        if(is_admin())
        {
            $validasi =[
                    'name' => ['required','unique:marketings,name,'.$marketing->id.',id'],
                    'branch' => ['required'],
                    'phone' => ['required'],
                    'whatsapp' => ['required'],
                    'email' => ['required', 'email'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'name.unique' => 'Nama sudah terdaftar',
                'branch.required' => 'Kantor Cabang tidak boleh kosong',
                'phone.required' => 'Nomor Telepon tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format Email salah',
                'lat.required' => 'Latitude tidak boleh kosong',
                'long.required' => 'Longitude tidak boleh kosong',
                'image.required' => 'Gambar tidak boleh kosong',
            ];
            if($marketing->image == null)
            {
                $validasi['image'] = ['required'];
            }
            $request->validate($validasi, $msg);

            $marketing->name = trim($request->name);
            $marketing->slug = Str::slug(trim($request->name), '-');
            $marketing->phone = trim($request->phone);
            $marketing->whatsapp = trim($request->whatsapp);
            $marketing->email = trim($request->email);
            $marketing->lat = trim($request->lat);
            $marketing->long = trim($request->long);
            $marketing->address = trim($request->address);
    
            if($marketing->save())
            {
                $marketing->branches()->sync($request->branch);
                if($request->image)
                {
                    if(File::exists(public_path($marketing->image)))
                    {
                        File::delete(public_path($marketing->image));
                    }
                    if(File::exists(public_path($marketing->thumb)))
                    {
                        File::delete(public_path($marketing->thumb));
                    }
                    $images = $request->image;
                    $image = Image::make($images);
                    $path = 'assets/images/sales/'.$marketing->id.'/';
                    $dir = public_path($path);
                    if(!File::isDirectory($dir))
                    {
                        File::makeDirectory($dir);
                    }
                    $file_name = Str::slug($images->getClientOriginalName(), '-').'-'.time();
                    $name = $file_name.'-full.'.$images->extension();
                    $thumb = $file_name.'-thumb.'.$images->extension();
                    $image->save($dir.$name);
                    $image->crop(300, 300);
                    $image->save($dir.$thumb);

                    $marketing->image = $path.$name;
                    $marketing->thumb = $path.$thumb;
                    $marketing->save();
                }

                $request->session()->flash('success', 'Sukses update '.$this->title);
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat update '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, Marketing $marketing)
    {
        if(is_admin())
        {
            $path = 'assets/images/sales/'.$marketing->id;
            if(File::exists(public_path($marketing->image)) || File::exists(public_path($marketing->thumb)))
            {
                File::deleteDirectory(public_path($path));
            }
            $number = $marketing->branches()->get();
            if(count($number) > 0)
            {
                $marketing->branches()->detach();
            }

            $marketing->delete();
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function deletemass(Request $request)
    {
        
        if(is_admin() || is_subadmin())
        {
            $id = explode(",", $request->ids);            
            $marketings = Marketing::find($id);
            foreach($marketings as $key=> $marketing)
            {
                $path = 'assets/images/sales/'.$marketing->id;
                if(File::exists(public_path($marketing->image)) || File::exists(public_path($marketing->thumb)))
                {
                    File::deleteDirectory(public_path($path));
                }
                $number = $marketing->branches()->get();
                if(count($marketing) > 0)
                {
                    $marketing->branches()->detach();
                }

                $marketing->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
