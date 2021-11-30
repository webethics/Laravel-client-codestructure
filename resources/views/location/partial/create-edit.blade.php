<location-partial-create-edit
    old-latitude="{{old('latitude', isset($location) ? $location->latitude : '')}}"
    old-longitude="{{old('longitude', isset($location) ? $location->longitude : '')}}"
    marker-url="{{elixir('images/default-map-marker.png')}}"
    inline-template>

    <div>
        <div class="form-group form-group-required {{errorClass('submitter_name', $errors)}}">
            {!!Form::label('submitter_name', 'Your Name', ['class' => 'control-label'])!!}
            {!!Form::text('submitter_name', null, ['class' => 'form-control', 'id' => 'submitter_name', 'required', 'v-model' => 'attraction.submitter_name'])!!}
            {!!renderFieldErrors('submitter_name', $errors)!!}
        </div>
        <div class="form-group form-group-required {{errorClass('submitter_email', $errors)}}">
            {!!Form::label('submitter_email', 'Your Email', ['class' => 'control-label'])!!}
            {!!Form::email('submitter_email', null, ['class' => 'form-control', 'id' => 'submitter_email', 'required', 'v-model' => 'attraction.submitter_email'])!!}
            {!!renderFieldErrors('submitter_email', $errors)!!}
        </div>
        <div class="form-group {{errorClass('submitter_phone', $errors)}}">
            {!!Form::label('submitter_phone', 'Your Phone', ['class' => 'control-label'])!!}
            {!!Form::text('submitter_phone', null, ['class' => 'form-control', 'id' => 'submitter_phone', 'v-model' => 'attraction.submitter_phone'])!!}
            {!!renderFieldErrors('submitter_phone', $errors)!!}
        </div>

        <div class="form-group {{errorClass('categories', $errors)}}">
            {!!Form::label('categories', 'Place Category', ['class' => 'control-label'])!!}
            <p class="help-block">What category would you classify this place as?</p>
            <div class="category-list-container">
                @if(!$parentCategories->isEmpty())
                    <ul class="list-unstyled">
                        @foreach($parentCategories as $parent)
                            <li>
                                <div class="checkbox">
                                    <label>
                                        {!!Form::checkbox(
                                            'categories[]',
                                            $parent->hashid,
                                            $currentCategories->has($parent->hashid),
                                            [
                                                'v-model' => 'attraction.categories'
                                            ]
                                        )!!}
                                        {{$parent->name}}
                                    </label>
                                </div>
                                @if($parent->has('children'))
                                    <ul class="list-unstyled" style="margin-left: 30px;">
                                        @foreach($parent->children as $child)
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        {!!Form::checkbox(
                                                            'categories[]',
                                                            $child->hashid,
                                                            $currentCategories->has($child->hashid),
                                                            [
                                                                'v-model' => 'attraction.categories'
                                                            ]
                                                        )!!}
                                                        {{$child->name}}
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            {!!renderFieldErrors('categories', $errors)!!}
        </div>
        <h3>Attraction Details</h3>
        <div class="form-group form-group-required {{errorClass('name', $errors)}}">
            {!!Form::label('name', 'Name of attraction', ['class' => 'control-label'])!!}
            <p class="help-block">Give the attraction its full and correct name</p>
            {!!Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'required', 'v-model' => 'attraction.name'])!!}
            {!!renderFieldErrors('name', $errors)!!}
        </div>

        <div class="form-group {{errorClass('description', $errors)}}">
            {!!Form::label('description', 'Description', ['class' => 'control-label'])!!}
            {!!Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'v-model' => 'attraction.description'])!!}
            {!!renderFieldErrors('description', $errors)!!}
        </div>

        <div class="form-group {{errorClass('address_line_1', $errors)}}">
            {!!Form::label('address_line_1', 'Address Line 1', ['class' => 'control-label'])!!}
            {!!Form::text('address_line_1', null, ['class' => 'form-control', 'id' => 'address_line_1', 'v-model' => 'attraction.address_line_1'])!!}
            {!!renderFieldErrors('address_line_1', $errors)!!}
        </div>

        <div class="form-group {{errorClass('address_line_2', $errors)}}">
            {!!Form::label('address_line_2', 'Address Line 2', ['class' => 'control-label'])!!}
            {!!Form::text('address_line_2', null, ['class' => 'form-control', 'id' => 'address_line_2', 'v-model' => 'attraction.address_line_2'])!!}
            {!!renderFieldErrors('address_line_2', $errors)!!}
        </div>

        <div class="form-group {{errorClass('address_line_3', $errors)}}">
            {!!Form::label('address_line_3', 'Address Line 3', ['class' => 'control-label'])!!}
            {!!Form::text('address_line_3', null, ['class' => 'form-control', 'id' => 'address_line_3', 'v-model' => 'attraction.address_line_3'])!!}
            {!!renderFieldErrors('address_line_3', $errors)!!}
        </div>

        <div class="form-group {{errorClass('address_line_4', $errors)}}">
            {!!Form::label('address_line_4', 'Address Line 4', ['class' => 'control-label'])!!}
            {!!Form::text('address_line_4', null, ['class' => 'form-control', 'id' => 'address_line_4', 'v-model' => 'attraction.address_line_4'])!!}
            {!!renderFieldErrors('address_line_4', $errors)!!}
        </div>

        <div class="form-group {{errorClass('postcode', $errors)}}">
            {!!Form::label('postcode', 'Postcode', ['class' => 'control-label'])!!}
            {!!Form::text('postcode', null, ['class' => 'form-control', 'id' => 'postcode', 'v-model' => 'attraction.postcode'])!!}
            {!!renderFieldErrors('postcode', $errors)!!}
        </div>

        <div class="form-group {{errorClass('city', $errors)}}">
            {!!Form::label('city', 'City', ['class' => 'control-label'])!!}
            {!!Form::text('city', null, ['class' => 'form-control', 'id' => 'city', 'v-model' => 'attraction.city'])!!}
            {!!renderFieldErrors('city', $errors)!!}
        </div>

        <div class="form-group {{errorClass('state_province', $errors)}}">
            {!!Form::label('state_province', 'State/Province', ['class' => 'control-label'])!!}
            {!!Form::text('state_province', null, ['class' => 'form-control', 'id' => 'state_province', 'v-model' => 'attraction.state_province'])!!}
            {!!renderFieldErrors('state_province', $errors)!!}
        </div>

        <div class="form-group {{errorClass('county', $errors)}}">
            {!!Form::label('county', 'County', ['class' => 'control-label'])!!}
            {!!Form::select('county', prefixSelect(config('settings.counties')), null, ['class' => 'form-control', 'id' => 'county', 'v-model' => 'attraction.county'])!!}
            {!!renderFieldErrors('county', $errors)!!}
        </div>

        <div class="form-group {{errorClass('country', $errors)}}">
            {!!Form::label('country', 'Country', ['class' => 'control-label'])!!}
            {!!Form::select('country', prefixSelect(config('settings.countries')), null, ['class' => 'form-control', 'id' => 'country', 'v-model' => 'attraction.country'])!!}
            {!!renderFieldErrors('country', $errors)!!}
        </div>

        <h3>Location Information</h3>
        <p>If you know – or can find out – the latitude and longitude of the attraction you’re adding, please enter it here. You may find <a target="_blank" href="http://www.latlong.net/">this tool</a> useful for getting latitude and longitude.</p>

        <div id="geo-location-wrapper">
            <address-lookup
                :parent-latitude="latitude"
                :parent-longitude="longitude"
                lookup-label="Location Address Search"
                lookup-help-text="If you do not know the precise latitude / longitude, finding your attraction on the map below would be a great help."
                lookup-placeholder="Start typing address.."
                v-on:send-result="receiveGeoLookupResult"
                v-on:reset-map="clearMap"
            >
            </address-lookup>
            <div id="geo-location-map"></div>
        </div>


        <h3>Contact Information</h3>
        <p>Please let us know how best to get in touch with the attraction.</p>
        <div class="form-group {{errorClass('phone', $errors)}}">
            {!!Form::label('phone', 'Phone', ['class' => 'control-label'])!!}
            {!!Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'v-model' => 'attraction.phone'])!!}
            {!!renderFieldErrors('phone', $errors)!!}
        </div>

        <div class="form-group {{errorClass('email', $errors)}}">
            {!!Form::label('email', 'Email', ['class' => 'control-label'])!!}
            {!!Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'v-model' => 'attraction.email'])!!}
            {!!renderFieldErrors('email', $errors)!!}
        </div>

        <div class="form-group {{errorClass('website', $errors)}}">
            {!!Form::label('website', 'Website', ['class' => 'control-label'])!!}
            {!!Form::url('website', null, ['class' => 'form-control', 'id' => 'website', 'v-model' => 'attraction.website'])!!}
            {!!renderFieldErrors('website', $errors)!!}
        </div>

        <div class="form-group form-group-required {{errorClass('minimum_visit_time', $errors)}}">
            {!!Form::label('minimum_visit_time', 'Minimum Visit Time (minutes)', ['class' => 'control-label'])!!}
            <p class="help-block">Enter the minimum number of minutes that a visitor would need to spend at this attraction.</p>
            {!!Form::number('minimum_visit_time', null, ['class' => 'form-control', 'id' => 'minimum_visit_time',  'v-model' => 'attraction.minimum_visit_time'])!!}
            {!!renderFieldErrors('minimum_visit_time', $errors)!!}
        </div>

        <div class="form-group {{errorClass('average_visit_time', $errors)}}">
            <p class="help-block">Enter a number of minutes that a visitor could reasonably expect to spend at this attraction.</p>
            {!!Form::label('average_visit_time', 'Average Visit Time (minutes)', ['class' => 'control-label'])!!}
            {!!Form::number('average_visit_time', null, ['class' => 'form-control', 'id' => 'average_visit_time', 'required', 'v-model' => 'attraction.average_visit_time'])!!}
            {!!renderFieldErrors('average_visit_time', $errors)!!}
        </div>

        <div class="form-group {{errorClass('opening_hours', $errors)}}">
            {!!Form::label('opening_hours', 'Opening Hours', ['class' => 'control-label'])!!}
            {!!Form::textarea('opening_hours', null, ['class' => 'form-control', 'id' => 'opening_hours', 'v-model' => 'attraction.opening_hours'])!!}
            {!!renderFieldErrors('opening_hours', $errors)!!}
        </div>

        <div class="form-group {{errorClass('admission_fees', $errors)}}">
            {!!Form::label('admission_fees', 'Admission Fees', ['class' => 'control-label'])!!}
            {!!Form::textarea('admission_fees', null, ['class' => 'form-control', 'id' => 'admission_fees', 'v-model' => 'attraction.admission_fees'])!!}
            {!!renderFieldErrors('admission_fees', $errors)!!}
        </div>

        @if(isset($location) && $location->image)
            <div class="form-group">
                <p class="form-control-static">
                    {!!$location->htmlImage()!!}
                </p>
            </div>
        @endif
        <div class="form-group {{errorClass('image_upload', $errors)}}">
            {!!Form::label('image_upload', 'Location Picture', ['class' => 'control-label'])!!}
            <p class="help-block">Upload one image here that best represents this attraction. The image might be used in search results, or in the trip planner, where only one image is required.</p>
            <input ref="fileInput" type="file" name="image_upload" id="image_upload" accept="image/*">
            {!!renderFieldErrors('image_upload', $errors)!!}
        </div>
        @if(Request::is('admin/location*'))
            <div class="form-group {{errorClass('data_quality', $errors)}}">
                {!!Form::label('data_quality', 'Data Quality', ['class' => 'control-label'])!!}
                {!!Form::select('data_quality', prefixSelect(config('settings.data_quality')), null, ['class' => 'form-control', 'id' => 'data_quality'])!!}
                {!!renderFieldErrors('data_quality', $errors)!!}
            </div>

            <div class="form-group {{errorClass('published', $errors)}}">
                {!!Form::label('published', 'Published', ['class' => 'control-label'])!!}
                {!!Form::select('published', ['Unpublished', 'Published'], null, ['class' => 'form-control', 'id' => 'published'])!!}
                {!!renderFieldErrors('published', $errors)!!}
            </div>
        @endif

        @if(Request::is('admin/location*'))
            <button type="submit" class="btn btn-success pull-right">Save</button>
        @else
            <button type="submit" @submit.prevent="submitAttraction" class="btn btn-success">Submit Attraction for Review</button>
        @endif
    </div>
</location-partial-create-edit>
