<div class="popup-inner">
    <div class="row">
        <div class="col-xs-6">
            <h3>{{$location->name}}</h3>
        </div>
        <div class="col-xs-6 add-to-itinerary-column text-right">
            <button type="button" class="btn btn-success btn-sm btn-add-to-itinerary" data-location-hashid="{{$location->hashid}}" data-action="add"><i class="fa fa-fw fa-camera"></i> Add to intinerary</button>
        </div>
    </div>
    <hr class="hr-info">

    <div class="row small-popup-content">
        <div class="col-xs-9 col-xs-push-3">
            <p>{!!$location->short_description_line_breaks!!}</p>

            <!-- <p><a href="{{iframeUrl(route('location.show', $location))}}" target="_blank">More information</a></p> -->
            <p><a class="popup-more-information-btn" href="#">More information</a></p>
        </div>
        <div class="col-xs-3 col-xs-pull-9">
            <div class="thumbnail">
                {!!$location->htmlImage('location-grid', ['class' => 'img-responsive'])!!}
            </div>
        </div>
    </div>

    <div class="row location-show full-popup-content">
        <div class="col-sm-8">
            <h2>{{$location->name}}</h2>
            <p class="location-address">
                {{$location->full_address}},
                {{$location->phone}}
            </p>
            <div class="location-description">{!!$location->description_html!!}</div>
            <p>We recommend setting aside {{$location->average_visit_time_hours}} for your visit.</p>
            <div class="thumbnail">
                {!!$location->medium_html_image!!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-12">
                    <h4>Opening Hours:</h4>
                    <p>{!!$location->opening_hours_line_breaks or 'Not Available'!!}</p>
                    <h4>Admission Fee:</h4>
                    <p>{!!$location->admission_fees_line_breaks or 'Not Available'!!}</p>
                    <h3>GPS Co-ordinates</h3>
                    <p>{!!$location->lat_long_decimal_and_degrees!!}</p>
                </div>
            </div>
        </div>
    </div>

    @include('location.partial.actions', ['location' => $location, 'hidden' => ['add', 'popup', 'favourite']])
</div>
