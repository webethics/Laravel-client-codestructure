@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h2>Suggest an Attraction</h2>

            <p>If you would like to suggest an attraction – a local favourite, a regular family outing, or perhaps your own business – please complete the short form below with as much detail as possible, and we’ll be happy to review it.</p>
        </div>
        <div class="col-md-6">
            <h2>Suggestion Form</h2>
            <p>Please complete as much information as possible - thank you!</p>
            <hr>
            <p>Please let us know your contact information in case we need to get in touch to verify any details. Thank you!</p>
            {!!Form::open(
                [
                    'class' => 'form',
                    'route' => 'location.store',
                    'method' => 'POST',
                    'enctype' => 'multipart/form-data',
                ]
            )!!}
                <h2>Suggestion Form</h2>
                @include('location.partial.create-edit')
            {!!Form::close()!!}

        </div>
    </div>
@endsection
