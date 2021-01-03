<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Asset;
use App\Branch;
use App\Marketing;
use App\KpknlOffice;
use App\Location;
use App\Media;
use App\Category;
use Image;
use File;

class AssetController extends Controller
{    
    public function __construct()
    {
        $this->uri = 'assets';
        $this->title = 'Assets';
    }
    public function index()
    {
        if(is_admin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = Asset::with(['branches'])->where('deleted_at', null)->latest('id')->paginate(20);
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
                $model = Asset::with(['branches'])->join('asset_branch', 'assets.id', '=','asset_branch.asset_id')
                        ->join('branches', 'branches.id', '=','asset_branch.branch_id')
                        ->select('assets.*')->where('deleted_at', null);
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('assets.code', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.rec', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.doc_type', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.doc_no', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.doc_name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.address', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('assets.price', 'like', '%'.strip_tags($request->get('query')).'%')
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
                    $model->orderBy('assets.id', 'DESC');
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
            $data['branches'] = Branch::orderBy('name', 'ASC')->get();
            $data['marketings'] = Marketing::with('branches')->orderBy('name', 'ASC')->get();
            $data['kpknl'] = KpknlOffice::orderBy('name', 'ASC')->get();
            $data['provinsi'] = Location::where('type', 'provinsi')->orderBy('name', 'ASC')->get();
            $data['categories'] = Category::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, Asset $asset)
    {
        if(is_admin())
        {
            $formV = [
                'code_location' => ['required', 'max:4'],
                //'code_type' => ['required'],
                'code_year' => ['required'],
                'asset_type' => ['required'],
                'rec' => ['required'],
                'price' => ['required'],
                'house_category' => ['required'],
                'house_type' => ['required'],
                'house_wide' => ['required'],
                'house_building' => ['required'],
                'doc_type' => ['required'],
                'doc_no' => ['required'],
                'doc_name' => ['required'],
                'sales' => ['required'],
                'branch' => ['required'],
                'sold_method' => ['required'],
                'address' => ['required'],
                'address_no' => ['required'],
                'address_rt' => ['required'],
                'address_rw' => ['required'],
                'address_prov' => ['required'],
                'address_kab' => ['required'],
                'address_kec' => ['required'],
                'address_lat' => ['required'],
                'address_long' => ['required'],
            ];
            $fromE = [
                'code_location.required' => 'Nama tidak boleh kosong',
                //'code_type.required' => 'Tipe Assets tidak boleh kosong',
                'code_year.required' => 'Nomor Assets tidak boleh kosong',
                'asset_type.required' => 'Jenis Produk tidak boleh kosong',
                'rec.required' => 'Nomor Rekening Debitur tidak boleh kosong',
                'price.required' => 'Harga tidak boleh kosong',
                'house_category.required' => 'Kategori Produk tidak boleh kosong',
                'house_type.required' => 'Tipe Rumah tidak boleh kosong',
                'house_wide.required' => 'Luas Tanah tidak boleh kosong',
                'house_building.required' => 'Luas Bangunan tidak boleh kosong',
                'doc_type.required' => 'Tipe Dokumen tidak boleh kosong',
                'doc_no.required' => 'Nomor Dokumen tidak boleh kosong',
                'doc_name.required' => 'Atas Nama Dokumen tidak boleh kosong',
                'sales.required' => 'Sales tidak boleh kosong',
                'branch.required' => 'Kantor Cabang tidak boleh kosong',
                'sold_method.required' => 'Cara Penjualan tidak boleh kosong',
                'address.required' => 'Alamat tidak boleh kosong',
                'address_no.required' => 'Nomor Rumah tidak boleh kosong',
                'address_rt.required' => 'RT tidak boleh kosong',
                'address_rw.required' => 'RW tidak boleh kosong',
                'address_prov.required' => 'Provinsi tidak boleh kosong',
                'address_kab.required' => 'Kabupaten/Kota tidak boleh kosong',
                'address_kec.required' => 'kecamatan tidak boleh kosong',
                'address_lat.required' => 'Latitude tidak boleh kosong',
                'address_long.required' => 'Longitude tidak boleh kosong',
            ];

            if(!empty($request->sold_method))
            {
                if($request->sold_method == 1)
                {
                    $formV['bank_location'] = ['required'];
                    $fromE['bank_location.required'] = 'Bank BTN tidak boleh kosong';
                    $formV['sold_note'] = ['required'];
                    $fromE['sold_note.required'] = 'Keterangan Penjualan tidak boleh kosong';
                }else if($request->sold_method == 2)
                {
                    $formV['auction_location'] = ['required'];
                    $fromE['auction_location.required'] = 'KPKNL tidak boleh kosong';
                    $formV['auction_time'] = ['required'];
                    $fromE['auction_time.required'] = 'Estimasi Tanggal Lelang tidak boleh kosong';
                }
            }
            if($request->image)
            {
                foreach($request->image as $key=>$val)
                {
                    $nom = $key+1;
                    $formV['image.'.$key] = ['required'];
                    $fromE['image.'.$key.'.required'] = 'Gambar '.$nom.' tidak boleh kosong';
                }
            }else{
                $formV['image'] = ['required'];
                $fromE['image.required'] = 'Gambar tidak boleh kosong';
            }
            
            $request->validate($formV, $fromE);

            if(str_replace(array('.', ','), '', $request->price) < 1)
            {
                $validator = Validator::make([], []);
                $validator->getMessageBag()->add('price', 'Harga tidak valid');
                return back()->withErrors($validator)->withInput();
            }

            //proses
            $code_location = $request->code_location;
            $code_type = $request->code_type;
            $code_year = $request->code_year;
            
            $asset->code_location = $code_location;
            $asset->code_type = $code_type;
            if($request->code_type == '0')
            {
                $code_type = '';
            }
            if(intval($code_year) > 0  && intval($code_year) < 10)
            {
                $code_year = '0'.$request->code_year;
            }
            $asset->code_year = $code_year;
            
            $code = $code_location.'-'.date('ym').$code_year;
            $asset->code = $code;
            $asset->slug = Str::slug(trim($code), '-');
            $asset->asset_type = $request->asset_type;
            $asset->rec = $request->rec;
            $price = str_replace(array('.', ','), '', $request->price);
            $asset->price = $price;
            if($request->price_sale)
            {
                $price_sale = str_replace(array('.', ','), '', $request->price_sale);
                $asset->price_sale = $price_sale;
            }
            $asset->house_type = $request->house_type;
            $house_street = str_replace(array('.', ','), '', $request->house_street);
            $asset->house_street = $house_street;
            $house_wide = str_replace(array('.', ','), '', $request->house_wide);
            $asset->house_wide = $house_wide;
            $house_building = str_replace(array('.', ','), '', $request->house_building);
            $asset->house_building = $house_building;
            $asset->doc_type = $request->doc_type;
            $asset->doc_no = $request->doc_no;
            $asset->doc_name = $request->doc_name;
            $asset->sales = $request->sales;

            $sold_method = $request->sold_method;
            $asset->sold_method = $sold_method;
            $asset->bank_location = ($request->bank_location ? $request->bank_location : 0);
            $asset->sold_note = ($request->sold_note ? $request->sold_note : 0);
            $asset->auction_location = $request->auction_location;
            if($sold_method == 2)
            {
                $asset->auction_time = \DateTime::createFromFormat("Y-m-d H:i:00", $request->auction_time)->format("Y-m-d H:i:00");
                $asset->auction_location = $request->auction_location;
            }else{
                $asset->auction_time = date("Y-m-d H:i:s", time());
                $asset->auction_location = 0;
            }
            $asset->publish = $request->publish;
            $asset->status = $request->status;

            $asset->operator = Auth::user()->id;

            $asset->address = $request->address;
            $asset->address_no = $request->address_no;
            $asset->address_rt = $request->address_rt;
            $asset->address_rw = $request->address_rw;
            $asset->address_prov = $request->address_prov;
            $asset->address_kab = $request->address_kab;
            $asset->address_kec = $request->address_kec;
            $asset->address_lat = $request->address_lat;
            $asset->address_long = $request->address_long;
            $asset->ip = \Request::ip();
    
            if($asset->save())
            {
                $asset->categories()->attach($request->house_category);
                $asset->branches()->attach($request->branch);
                foreach($request->file('image') as $key=>$images)
                {
                    $image = Image::make($images);
                    $path = 'assets/images/properties/'.$asset->id.'/';
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
                    
                    $media = new Media();
                    $media->name = $file_name;
                    $media->type = $images->extension();
                    $media->path = $path.$name;
                    $media->thumb = $path.$thumb;
                    $media->save();
                    $asset->medias()->attach($media->id);
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
    public function edit(Asset $asset)
    {
        if(is_admin())
        {
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            $data['row'] = $asset;
            $data['branches'] = Branch::orderBy('name', 'ASC')->get();
            $data['marketings'] = Marketing::with('branches')->orderBy('name', 'ASC')->get();
            $data['kpknl'] = KpknlOffice::orderBy('name', 'ASC')->get();
            $data['provinsi'] = Location::where('type', 'provinsi')->orderBy('name', 'ASC')->get();
            $data['categories'] = Category::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Asset $asset)
    {
        if(is_admin())
        {
            $formV = [
                'code_location' => ['required', 'max:4'],
                //'code_type' => ['required'],
                'code_year' => ['required'],
                'asset_type' => ['required'],
                'rec' => ['required'],
                'price' => ['required'],
                'house_category' => ['required'],
                'house_type' => ['required'],
                'house_wide' => ['required'],
                'house_building' => ['required'],
                'doc_type' => ['required'],
                'doc_no' => ['required'],
                'doc_name' => ['required'],
                'sales' => ['required'],
                'branch' => ['required'],
                'sold_method' => ['required'],
                'address' => ['required'],
                'address_no' => ['required'],
                'address_rt' => ['required'],
                'address_rw' => ['required'],
                'address_prov' => ['required'],
                'address_kab' => ['required'],
                'address_kec' => ['required'],
                'address_lat' => ['required'],
                'address_long' => ['required'],
            ];
            $fromE = [
                'code_location.required' => 'Nama tidak boleh kosong',
                //'code_type.required' => 'Tipe Assets tidak boleh kosong',
                'code_year.required' => 'Nomor Assets tidak boleh kosong',
                'asset_type.required' => 'Jenis Produk tidak boleh kosong',
                'rec.required' => 'Nomor Rekening Debitur tidak boleh kosong',
                'price.required' => 'Harga tidak boleh kosong',
                'house_category.required' => 'Kategori Produk tidak boleh kosong',
                'house_type.required' => 'Tipe Rumah tidak boleh kosong',
                'house_wide.required' => 'Luas Tanah tidak boleh kosong',
                'house_building.required' => 'Luas Bangunan tidak boleh kosong',
                'doc_type.required' => 'Tipe Dokumen tidak boleh kosong',
                'doc_no.required' => 'Nomor Dokumen tidak boleh kosong',
                'doc_name.required' => 'Atas Nama Dokumen tidak boleh kosong',
                'sales.required' => 'Sales tidak boleh kosong',
                'branch.required' => 'Kantor Cabang tidak boleh kosong',
                'sold_method.required' => 'Cara Penjualan tidak boleh kosong',
                'address.required' => 'Alamat tidak boleh kosong',
                'address_no.required' => 'Nomor Rumah tidak boleh kosong',
                'address_rt.required' => 'RT tidak boleh kosong',
                'address_rw.required' => 'RW tidak boleh kosong',
                'address_prov.required' => 'Provinsi tidak boleh kosong',
                'address_kab.required' => 'Kabupaten/Kota tidak boleh kosong',
                'address_kec.required' => 'kecamatan tidak boleh kosong',
                'address_lat.required' => 'Latitude tidak boleh kosong',
                'address_long.required' => 'Longitude tidak boleh kosong',
            ];

            if(!empty($request->sold_method))
            {
                if($request->sold_method == 1)
                {
                    $formV['bank_location'] = ['required'];
                    $fromE['bank_location.required'] = 'Bank BTN tidak boleh kosong';
                    $formV['sold_note'] = ['required'];
                    $fromE['sold_note.required'] = 'Keterangan Penjualan tidak boleh kosong';
                }else if($request->sold_method == 2)
                {
                    $formV['auction_location'] = ['required'];
                    $fromE['auction_location.required'] = 'KPKNL tidak boleh kosong';
                    $formV['auction_time'] = ['required'];
                    $fromE['auction_time.required'] = 'Estimasi Tanggal Lelang tidak boleh kosong';
                }
            }
            if($request->image)
            {
                foreach($request->image as $key=>$val)
                {
                    $nom = $key+1;
                    $formV['image.'.$key] = ['required'];
                    $fromE['image.'.$key.'.required'] = 'Gambar '.$nom.' tidak boleh kosong';
                }
            }else{
                if(count($asset->medias()->get()) == 0)
                {
                    $formV['image'] = ['required'];
                    $fromE['image.required'] = 'Gambar tidak boleh kosong';
                }
            }
            
            $request->validate($formV, $fromE);

            if(str_replace(array('.', ','), '', $request->price) < 1)
            {
                $validator = Validator::make([], []);
                $validator->getMessageBag()->add('price', 'Harga tidak valid');
                return back()->withErrors($validator)->withInput();
            }

            //proses
            $code_location = $request->code_location;
            $code_type = $request->code_type;
            $code_year = $request->code_year;
            
            $asset->code_location = $code_location;
            $asset->code_type = $code_type;
            if($request->code_type == '0')
            {
                $code_type = '';
            }
            if(intval($code_year) > 0  && intval($code_year) < 10)
            {
                $code_year = '0'.$request->code_year;
            }
            $asset->code_year = $code_year;
            
            $code = $code_location.'-'.date('ym').$code_year;
            $asset->code = $code;
            $asset->slug = Str::slug(trim($code), '-');
            $asset->asset_type = $request->asset_type;
            $asset->rec = $request->rec;
            $price = str_replace(array('.', ','), '', $request->price);
            $asset->price = $price;
            if($request->price_sale)
            {
                $price_sale = str_replace(array('.', ','), '', $request->price_sale);
                $asset->price_sale = $price_sale;
            }
            $asset->house_type = $request->house_type;
            $house_street = str_replace(array('.', ','), '', $request->house_street);
            $asset->house_street = $house_street;
            $house_wide = str_replace(array('.', ','), '', $request->house_wide);
            $asset->house_wide = $house_wide;
            $house_building = str_replace(array('.', ','), '', $request->house_building);
            $asset->house_building = $house_building;
            $asset->doc_type = $request->doc_type;
            $asset->doc_no = $request->doc_no;
            $asset->doc_name = $request->doc_name;
            $asset->sales = $request->sales;

            $sold_method = $request->sold_method;
            $asset->sold_method = $sold_method;
            $asset->bank_location = ($request->bank_location ? $request->bank_location : 0);
            $asset->sold_note = ($request->sold_note ? $request->sold_note : 0);
            $asset->auction_location = $request->auction_location;
            if($sold_method == 2)
            {
                $asset->auction_time = \DateTime::createFromFormat("Y-m-d H:i:00", $request->auction_time)->format("Y-m-d H:i:00");
                $asset->auction_location = $request->auction_location;
            }else{
                $asset->auction_time = date("Y-m-d H:i:s", time());
                $asset->auction_location = 0;
            }
            $asset->publish = $request->publish;
            $asset->status = $request->status;

            $asset->operator = Auth::user()->id;

            $asset->address = $request->address;
            $asset->address_no = $request->address_no;
            $asset->address_rt = $request->address_rt;
            $asset->address_rw = $request->address_rw;
            $asset->address_prov = $request->address_prov;
            $asset->address_kab = $request->address_kab;
            $asset->address_kec = $request->address_kec;
            $asset->address_lat = $request->address_lat;
            $asset->address_long = $request->address_long;
            $asset->ip = \Request::ip();
    
            if($asset->save())
            {
                $asset->categories()->sync($request->house_category);
                $asset->branches()->sync($request->branch);
                if($request->image){
                    foreach($request->file('image') as $key=>$images)
                    {
                        $image = Image::make($images);
                        $path = 'assets/images/properties/'.$asset->id.'/';
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
                        
                        $media = new Media();
                        $media->name = $file_name;
                        $media->type = $images->extension();
                        $media->path = $path.$name;
                        $media->thumb = $path.$thumb;
                        $media->save();
                        $asset->medias()->attach($media->id);
                    }
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
    public function delete(Request $request)
    {
        if(is_admin())
        {
            $asset = Asset::find($request->id);
            $asset->update([
                'deleted_at' => date('Y-m-d H:i:s', time())
            ]);
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
            $ids = explode(",", $request->ids);
            foreach($ids as $key=> $id)
            {
                $asset = Asset::find($id);
                $asset->update([
                    'deleted_at' => date('Y-m-d H:i:s', time())
                ]);
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function ajaxlocation(Request $request)
    {
        if(is_admin())
        {
            if($request->ajax())
            {
                $kabupaten = array();
                $kecamatan = array();
                if($request->from == 'prov')
                {
                    $kabupaten = Location::where('type', 'kabupaten')->whereRaw('LENGTH(code) = 5')->whereRaw('LEFT(code, 2) = '.$request->val )->orderBy('name', 'ASC')->get();
                }else if($request->from == 'kab')
                {
                    $kecamatan = Location::where('type', 'kecamatan')->whereRaw('LENGTH(code) = 8')->whereRaw('LEFT(code, 5) = '.$request->val )->orderBy('name', 'ASC')->get();
                }
                return response()->json([
                    'status' => true,
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $kecamatan,
                ], 200);
            }
            return response()->json(['status' => false], 404);
        }else{
            return response()->json(['status' => false], 404);
        }
    }
    public function ajaxdeleteimage(Request $request)
    {
        if(is_admin())
        {
            if($request->ajax())
            {
                $id = $request->id;
                $assetsid = $request->assetsid;
                $model = Media::find($id);
                if($model)
                {
                    if(File::exists(public_path($model->path)))
                    {
                        File::delete(public_path($model->path));
                    }
                    if(File::exists(public_path($model->thumb)))
                    {
                        File::delete(public_path($model->thumb));
                    }
                    $asset = Asset::find($assetsid);
                    $asset->medias()->detach($id);
                    $model->delete();
                    return response()->json([
                        'status' => true
                    ], 200);
                }

                return response()->json([
                    'status' => false
                ], 200);
            }
            return response()->json(['status' => false], 404);
        }else{
            return response()->json(['status' => false], 404);
        }
    }
}
