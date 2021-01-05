<?php
use Illuminate\Support\Facades\Auth;
function is_admin()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('Admin'))
        {
            return true;
        }
    }
    return false;
}
function is_subadmin()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('Subadmin'))
        {
            return true;
        }
    }
    return false;
}
function is_cs()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('CS'))
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
function asset_url($string)
{
    return env('APP_URL').$string;
}