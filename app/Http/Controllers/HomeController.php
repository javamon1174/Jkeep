<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request)
    {
        if (!Auth::check())
        {
            return redirect('/login');
        }
        $user_id = $this->userReturnData();
        return $this->getUserMemoDataAll($user_id);
    }

    public function test()
    {
        // App\Memo::distinct()->get(['category']);
        return view('test');
    }

    private function getUserMemoDataAll($user_id)
    {

        $user_memo_all_data =  \App\Memo::where('user_id','=', $user_id)->get();
        return view('home')->with([
            'user_memo_all_data'=>$user_memo_all_data
    ]);

        // $flights = \App\Memo::where('active', 1)
        //        ->orderBy('name', 'desc')
        //        ->take(10)
        //        ->get();
    }

    private function userReturnData()
    {
        $user = Auth::user();
        return $user['attributes']['id'];
        // $data['name'] = $user['attributes']['name'];
        // return $data;
    }

    private function userCheckTrue(Array $data = [])
    {
        return view('home')->with(['id'=>$data['id'], 'name'=>$data['name']]);
    }
}
