<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->uri = 'dashboard';
        $this->title = 'Dashboard';
    }
    public function index()
    {
        if(is_admin() || is_cs())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            return view('backend.'.$this->uri, $data);
        }else{
            abort(404);
        }
    }
}
