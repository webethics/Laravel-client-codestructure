<?php
    $hidden = isset($hidden) ? collect($hidden) : collect();
?>
<ul class="list-unstyled location-action-list">
    @if(!$hidden->contains('add'))
        @if(auth()->check())
            <li>
                <add-to-itinerary
                    :itineraries="{{$itineraries}}"
                    itinerary-create-url="{{iframeUrl(route('itinerary.create'))}}"
                    location-hashid="{{$location->hashid}}"
                ></add-to-itinerary>
            </li>
        @else
            <li>
                <a href="{{iframeUrl(url('login'))}}"><i class="fa fa-fw fa-camera"></i> Add To Itinerary</a>
            </li>
        @endif
    @endif

    @if(!$hidden->contains('popup'))
        <li>{!!$location->maps_embed_popup_link_text!!}</li>
    @endif

    @if(!$hidden->contains('phone') && $location->phone)
        <li>
            <i class="fa fa-fw fa-phone"></i> {{$location->phone}}
        </li>
    @endif

    @if(!$hidden->contains('email') && $location->email)
        <li>
            <a href="mailto:{{$location->email}}?subject=Ireland Planner Enquiry"><i class="fa fa-fw fa-send"></i> Email Attraction</a>
        </li>
    @endif


    @if(!$hidden->contains('website') && $location->website)
        <li>
            <a target="_blank" href="{{$location->website}}"><i class="fa fa-fw fa-home"></i> Official Website</a>
        </li>
    @endif


    @if(!$hidden->contains('favourite'))
        <li>
            <favourite-location
                :favourited="{{$favouriteIds->contains($location->id) ? 'true' : 'false'}}"
                endpoint="{{$location->favouriteEndpoint}}"
                :show-text="true"
            >
            </favourite-location>
        </li>
    @endif
</ul>
