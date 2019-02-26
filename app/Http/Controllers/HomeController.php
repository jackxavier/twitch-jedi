<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        try{
//            event(new NewMessage("hello world"));
//        } catch (\Exception $exception){
//            dd($exception);
//        }

        return view('home');
    }
}
