<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style media="screen">
    html, body {
        @import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
        font-family: 'Nanum Gothic', serif;
    }

    *, *:before, *:after
    {
        box-sizing:  border-box !important;
    }

    .rows {
        -moz-column-width: 19em;
        -webkit-column-width: 19em;
        -moz-column-gap: .5em;
        -webkit-column-gap: .5em;
    }

    .panel {
        display: inline-block;
        margin:  .5em;
        padding:  0;
        width:98%;
    }
    .body {
        width:100%;
        word-break: break-all;
    }
    </style>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/memo') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu" style="text-align:center;">
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal">메모 작성</a></li>

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            로그 아웃
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @if (Auth::guest())
            @yield('content1')
        @else
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog" style="width:100%;height:100%;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">메모 입력</h4>
              </div>
              <div class="modal-body">
                  <form class="form" action="/memo/create" method="post" id="memo_write_form">
                      <div class="row">
                          <div class="col-xs-4">
                              <select class="form-control" id="sel1" name="category">
                                  @foreach ($category as $item)
                                  <option>{{$item}}</option>
                                  @endforeach
                              </select>
                            <!-- <input class="form-control" type="text" value="항목" name="category"
                            style="border:none;border-radius: 0px;" onfocus="this.value='';"> -->
                          </div>
                          <div class="col-xs-8">
                              <input type="text" class="form-control" name="title" value="제목"
                               style="border:none;border-radius: 0px;" onfocus="this.value='';">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-xs-12">
                              <textarea class="form-control" name="body" rows="6" style="border:none!important;border-radius: 0px;"
                              onfocus="this.value='';">내용입력..</textarea>
                              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                              <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                              <input type="hidden" name="return_url" value="{{ Request::url() }}">

                          </div>
                      </div>
                  </form>
                     <div class="modal-footer">
                         <div class="row">
                             <div class="btn-group">
                                 <button type="button" class="btn btn-default" name="button" onclick="javascript:document.getElementById('memo_write_form').submit();">입력하기</button>
                                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>
          </div>
        </div>
        <div class="container">
            <div class="col-md-3" style="padding:1%;">
                <ul class="nav flex-column">
                @foreach ($categorys as $item)
                <li class="nav-item">
                  <a class="nav-link" href="/memo/{{urlencode($item->category)}}">{{$item->category}}</a>
                </li>
                @endforeach
                </ul>
            </div>
            <div class="col-md-9">@yield('content2')</div>
        </div>
        @endif
    </div>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
</body>
</html>
