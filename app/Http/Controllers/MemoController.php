<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->getData2View(null);
    }

    private function insertDefaultDataWhenNodata($category, $user_id)
    {
        if ($category->isEmpty())
        {
            $memo = new \App\Memo;
            $memo->title = '메모앱 jkeep입니다.';
            $memo->body = '메모를 생성해주세요.';
            $memo->category = 'default';
            $memo->user_id = $user_id;
            $result = $memo->save();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $memo = new \App\Memo;

        $memo->title = $request->title;
        $memo->body = $request->body;
        $memo->category = $request->category;
        $memo->user_id = $request->user_id;

        $result = $memo->save();

        return $this->return2Url($result, $request->return_url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->getData2View($id);
    }

    public function getData2View($id)
    {
        if (!\Auth::check())
        {
            return redirect('/login');
        }
        else
        {
            $user = \Auth::user();
            $user_id = $user ['attributes'] ['id'];

            if (!empty($id))
            {
                $user_memo_all_data =  \App\Memo::where('user_id','=', $user_id)
                                        ->where('category','=', $id)->get();
            } else
            {
                $user_memo_all_data =  \App\Memo::where('user_id','=', $user_id)
                                        ->get();
            }

            $category =  \App\Memo::distinct() ->where('user_id','=', $user_id)
                            ->pluck('category');

            $this->insertDefaultDataWhenNodata($category, $user_id);

            $category =  \App\Memo::distinct() ->where('user_id','=', $user_id)
                            ->pluck('category');

            $categorys = \App\Memo::distinct()
                        ->where('user_id','=', $user_id)
                        ->get(['category']);

            $view_category = view('category');
            $view_category->user_memo_all_data = $user_memo_all_data;
            $view_category->category = $category;
            $view_category->id = $id;
            $view_category->categorys = $categorys;

            return $view_category;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $memo = \App\Memo::find($request->edit_memo_id);
        $memo->category = $request->edit_category;
        $memo->title = $request->edit_title;
        $memo->body = $request->edit_body;
        $result = $memo->save();

        return $this->return2Url($result, $request->return_url);
    }

    public function remove(Request $request)
    {
        $memo = \App\Memo::find($request->remove_memo_id);
        $result = $memo->forceDelete();

        return $this->return2Url($result, $request->return_url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function return2Url( $result , $return_url )
    {
        if ($result)
            return redirect($return_url);
        else
            return redirect('/memo/error');
    }
}
