@if(Session::has('success'))
    <div class="alert alert-success">
        {!!Session::get('success')!!}
    </div>
@endif

@if(Session::has('success-once'))
    <div class="alert alert-success">
        {!!Session::pull('success-once')!!}
    </div>
@endif

@if(Session::has('status'))
    <div class="alert alert-info">
        {!!Session::get('status')!!}
    </div>
@endif

@if(Session::has('info-once'))
    <div class="alert alert-info">
        {!!Session::pull('info-once')!!}
    </div>
@endif

@if(Session::has('danger'))
    <div class="alert alert-danger">
        {!!Session::get('danger')!!}
    </div>
@endif

@if(Session::has('danger-once'))
    <div class="alert alert-danger">
        {!!Session::pull('danger-once')!!}
    </div>
@endif
