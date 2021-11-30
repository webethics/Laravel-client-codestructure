<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{$title or config('app.name')}}</title>

    {{-- CDN Vendor Styles --}}
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700|Noticia+Text:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Styles --}}
    @if(app()->environment('local'))
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
    @else
        <link href="{{elixir('css/app.css')}}" rel="stylesheet">
    @endif
    <base target="_parent" />

    @if(app()->environment('production'))
        {{-- PUT GOOGLE ANALYTICS HERE--}}
    @endif    
</head>
<body class="{{$bodyClasses}}">
    <div id="app">
        @if(isset($fullPageMap) && $fullPageMap)
            @yield('content')
        @else
            @if(!isset($hideBkg))
                {{--<div id="map-background"></div>--}}
            @endif
            <div id="main-container" class="container-fluid">
                @include('layout.partial.errors')
                @include('layout.partial.flash')

                {{-- Render the content --}}
                <div class="panel panel-opaque">
                    <div class="panel-body">
                        @if(!Request::is('account'))
                            {!!Breadcrumbs::render()!!}
                        @endif
                        @yield('content')
                    </div>
                </div>
        @endif
        </div>
    </div>
    <script>
        window.Laravel = {
            csrfToken: '{{csrf_token()}}'
        }
    </script>
    
    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="{{elixir('js/vendor.js')}}"></script>
    <script src="{{elixir('js/app.js')}}"></script>
</body>
</html>
