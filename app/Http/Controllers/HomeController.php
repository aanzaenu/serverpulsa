<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inbox;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->uri = 'home';
        $this->title = 'Inbox';
    }
    public function index()
    {
        $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
        $data['pagetitle'] = $this->title;
        $data['uri'] = $this->uri;
        $data['lists'] = Inbox::orderBy('id', 'DESC')->paginate(20);
        foreach($data['lists'] as $key=> $val)
        {
            $data['lists'][$key]->operator = '-';
            if(User::find($val->op))
            {
                $data['lists'][$key]->operator = User::find($val->op)->name;
            }
        }
        return view('home', $data);
    }
}
