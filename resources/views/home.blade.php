@extends('layouts.app')

<!-- 현재 페이지는 모든 메모 보이기(페이징) -->
<!-- 카테고리별 반복문(페이)  -->
<!-- 카테고리 컨트롤러 필요 -->

@section('content2')
  <div class="rows">
      @foreach ($user_memo_all_data as $item)
      <div class="panel panel-default">
   		<div class="panel-heading">[{{$item->category}}] {{$item->title}}</div>
   		<div class="panel-body">{{$item->body}}</div>
      </div>
      @endforeach
</div>
@endsection
