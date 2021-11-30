<?php $errors = Session::has('errors') ? Session::get('errors') : null; ?>
@if($errors && $errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <p>
            @foreach($errors->all() as $error)
                {{$error}} <br>
            @endforeach
        </p>
    </div>
@endif
