@extends('layout.master')

@section('content')
    <hr>
    <div class="row location-show">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-12">
                <h2>{{$location->name}}</h2>
            <p class="location-address">
                {{$location->full_address}},
                {{$location->phone}}
            </p>
                    <div class="thumbnail col-md-8">
                        {!!$location->medium_html_image!!}
                    </div>
                    <div class="location-description   col-md-12">{!!$location->description!!}
                    <p>We recommend setting aside {{$location->average_visit_time_hours}} for your visit.</p>

                    </div>
                   
                </div>
               
            </div>
        </div>
        <div class="col-sm-6">
           
            <div class="col-md-6">
                    <h4>Opening Hours:</h4>
                    <p>{!!$location->opening_hours_line_breaks or 'Not Available'!!}</p>
                    <h4>Admission Fee:</h4>
                    <p>{!!$location->admission_fees_line_breaks or 'Not Available'!!}</p>
                </div>
                <div class="col-md-6">
                    @include('location.partial.actions')
                    <h3>GPS Co-ordinates</h3>
                    <p>{!!$location->lat_long_decimal_and_degrees!!}</p>
                    <h3>Find Similar Attractions</h3>
                    <p>{!!$location->categories_summary!!}</p>
                </div>
            
        </div>
    </div>
@endsection
