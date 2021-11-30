@extends('layout.master')

@section('content')
    <!--location-index v-cloak inline-template-->
    <location-index  inline-template>
        <div class="location-index-container">
            <p class="clearfix">
                <a href="{{ route('location.create') }}" class="pull-right btn btn-success">Add an attraction</a>
            </p>
            <hr>
            {!!Form::open(['class' => 'index-search', 'method' => 'GET', 'url' => url('location')])!!}
                {!!Form::hidden('s', 1)!!}
                <location-filter
                    :counties="{{collect(prefixSelect(config('settings.counties'), 'All Counties'))->mapFromAssoc()}}"
                    old-free-text="{{Request::get('free_text')}}"
                    selected-county="{{Request::get('county')}}"
                    :categories="{{$categories}}"
                    selected-parent-category="{{Request::get('parent_category')}}"
                    selected-child-category="{{Request::get('child_category')}}"
                >
                </location-filter>
            {!!Form::close()!!}
            @if(!$locations->isEmpty())
                <h3>
                    Viewing {{$firstRecordCount}} to
                    {{$lastRecordCount}} of
                    {{$locations->total()}}
                    records.
                </h3>
            @else
                <h3>No results</h3>
            @endif
            {!!renderPagination($locations)!!}
            @if(!$locations->isEmpty())
                <div class="location-grid">
                    @foreach($locations->chunk(4) as $chunk)
                        <div class="row location-grid-row">
                            <?php $remaining = $loop->remaining; ?>
                            @foreach($chunk as $location)
                                <div class="col-xs-6 col-sm-3">
                                    <location-grid-item
                                        :location="{{$location}}"
                                        :favourited="{{$favouriteIds->contains($location->id) ? 'true' : 'false'}}"
                                        endpoint="{{$location->favouriteEndpoint}}"
                                        :itineraries="{{$itineraries !== false ? $itineraries : 'false'}}"
                                        itinerary-create-url="{{iframeUrl(route('itinerary.create'))}}"
                                        chunck-index="{{$remaining}}"
                                        login-url="{{iframeUrl(url('login'))}}"
                                    >
                                    </location-grid-item>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

            @elseif($locations->isEmpty() && Request::has('s'))
                <p class="lead text-center">No results for your query, please broaden your search criteria.</p>
            @else
                <p class="lead text-center">No locations available.</p>
            @endif
        </div>
    </location-index>
@endsection
