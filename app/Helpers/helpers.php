<?php
use Illuminate\Support\Facades\Auth;
function is_admin()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole(1))
        {
            return true;
        }
    }
    return false;
}
function is_login()
{
    if(Auth::check())
    {
        return true;
    }
    return false;
}