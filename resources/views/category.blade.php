@extends('layouts.app')

@section('content2')
  <!-- insert memo -->
  <div class="container" style="width:98%;">
      <div class="memo" style="text-align:center;max-width:800px;">
          <form class="form" action="/memo/create" method="post" id="memo_write_form">
            <!-- input form -->
            <div class="col-xs-4">
                <select class="form-control" id="category_sel" name="category" onchange="checkAddCategory(this);">
                    @if (isset($id))
                        <option>{{$id}}</option>
                    @else
                        @foreach ($category as $item)
                        <option>{{$item}}</option>
                        @endforeach
                        <option value="add">카테고리 추가</option>
                    @endif
                </select>
                <input class="form-control" id="category_txt" type="text" placeholder="카테고리 추가"
                 name="category" style="display:none;">
            </div>
            <div class="col-xs-8">
                <input class="form-control" id="txt_title" type="text" value="메모를 입력해주세요.."
                onfocus="inputSetting(this);" onblur="txtFocusOut(this)" name="title">
            </div>
            <div class="col-xs-12" style="margin-top:5px;">
                <textarea class="form-control" id="txt_area" name="body" type="text" style="display:none;"
                onfocus="inputSetting(this);" onblur="txtAreaFocusOut(this)" rows=4>내용을 입력해주세요..</textarea>
            </div>
            <!-- hidden -->
            <div class="col-xs-12" style="margin-top:5px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="return_url" value="{{ Request::url() }}">
            </div>
            <!-- submmit -->
            <div class="col-xs-12" style="margin-top:5px;">
                <button type="submit" id="btn-sub" class="btn btn-danger pull-right" name="button" style="display:none;">메모 입력</button>
            </div>
          </form>
      </div>
  </div>
  <!-- memo list -->
  <div class="rows">
      @foreach ($user_memo_all_data as $item)
      <div class="panel panel-default">
          <div class="panel-heading" onclick="updateMemo(this);">
              <span class="id" style="display:none;">{{$item->id}}</span>
              <span class="cate">[{{$item->category}}]</span>
              <span class="title"> {{$item->title}}</span></div>
          <div class="panel-body"  onclick="updateMemo(this);"><span class="body">{{$item->body}}</span></div>
          <div class="pull-right" style="margin:3%;">
              <form class="memo_remove_form" action="/memo/remove" method="post"
                onsubmit="return confirm('Are you sure you want to delete this note?');">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="remove_memo_id" value="{{$item->id}}">
                  <input type="hidden" name="return_url" value="{{ Request::url() }}">
                  <button class="glyphicon glyphicon-remove" type="submit" name="button"></button>
              </form>
          </div>
      </div>
      @endforeach
  </div>

  <!-- Update Modal -->
<div class="modal fade" id="memo_update_modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 style="color:blue;"><span class="glyphicon glyphicon-pencil"></span>메모 수정</h4>
      </div>
      <form class="form" action="/memo/update" method="post" id="memo_rewrite_form">
          <div class="modal-body">
            <!-- input form -->
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control" id="sel1" name="edit_category">
                        @if (isset($id))
                            <option>{{$id}}</option>
                        @else
                            @foreach ($category as $item)
                            <option>{{$item}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-xs-8">
                    <input class="form-control" id="edit_title" type="text" value="메모를 입력해주세요.."
                     name="edit_title">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" style="margin-top:5px;">
                    <textarea class="form-control" id="edit_body" name="edit_body" type="text" rows=8>내용을 입력해주세요..</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" style="margin-top:5px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="edit_memo_id" id="edit_memo_id">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="return_url" value="{{ Request::url() }}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-xs-12" style="margin-top:5px;">
                    <button type="submit" class="btn btn-default btn-success btn-block">
                        <span class="glyphicon glyphicon-pencil"></span>수정하기
                    </button>
                </div>
            </div>
       </div>
    </form>

</div>
  <script type="text/javascript">
      //memo insert form validate
      function inputSetting(elem)
      {
          if (elem.value == '메모를 입력해주세요..')
          {
              elem.value= "";
          } else if (elem.innerHTML == '내용을 입력해주세요..') {
              elem.innerHTML= "";
          }
          $('#txt_area').show();
          $('#btn-sub').show();
          elem.focus();
          return false;
      }

      function txtAreaFocusOut(elem)
      {
          if ($('#txt_area').val() == '')
          {
              $('#txt_area').val('내용을 입력해주세요..');
          }

          $('#txt_area').fadeOut();
          $('#btn-sub').fadeOut();
          return false;
      }

      function txtFocusOut(elem)
      {
          if(elem.value == '')
          {
              elem.value = '메모를 입력해주세요..'
          }
          var selected = $( "#category_sel option:selected" ).val();
          if (selected != 'add')
          {
              $( "#category_txt" ).attr('name', '').attr('style', 'display:none;');
              $( "#category_sel" ).attr('style', 'display:block;');
          }
          return false;
      }

      function updateMemo(elem)
      {
          $('#txt_area').fadeOut();
          $('#btn-sub').fadeOut();

          //get parent value of element
          elem = $(elem).parent();
          var this_content = $(elem).children();
          var div_cate_title = this_content[0];

          //set input value
          $('#edit_memo_id').val($(elem).find( ".id" ).text());
          $('#edit_title').val($(elem).find( ".title" ).text());
          $('#edit_body').val($(elem).find( ".body" ).text());
        //   $('#edit_category').prepend('<option>'+$(elem).find( ".cate" ).text()+'</option>');

          // open modal
          $("#memo_update_modal").modal();

          return false;
      }

      function checkAddCategory(elem)
      {
          var selected = $( "#category_sel option:selected" ).val();
          if (selected == 'add')
          {
              $( "#category_sel" ).attr('name', '').attr('style', 'display:none;');
              $( "#category_txt" ).attr('style', 'display:block;');
          }
      }
  </script>
@endsection
