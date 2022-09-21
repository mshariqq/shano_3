<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // lets create a new client in clients directory using exe

        return view('home');
    }

    public function exec(Request $request){
        $command = $request->code;
        print_r($command);

        dd(shell_exec("ls 2>&1"));
    }
}
