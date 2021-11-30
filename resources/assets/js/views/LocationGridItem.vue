<template>
    <div :id="gridItemId" class="location-grid-item">
        <div class="location-grid-item-inner">
            <div class="location-grid-image" v-html="location.location_grid_html_image"></div>
            <h4 v-text="location.name"></h4>
            <div class="location-grid-actions">

                <ul class="list-inline">
                    <li>
                        <favourite-location
                            :favourited="favourited"
                            :endpoint="endpoint"
                            :show-text="false"
                        >
                        </favourite-location>
                    </li>
                    <li v-html="location.maps_embed_popup_link"></li>
                    <li class="details-li pull-right">
                        <a href="#" class="location-details-toggle" v-on:click.prevent="toggleAndScrollTo(responder, $event)">
                            <i class="fa fa-fw fa-ellipsis-h"></i>
                        </a>

                        <transition name="slide-fade">
                            <span v-if="detailsWindow" class="location-details-arrow"></span>
                        </transition>
                    </li>
                </ul>
            </div>
        </div>
        <transition name="slide-fade">
            <div v-if="detailsWindow" class="location-details">
                <a href="#" @click.prevent="hide" class="pull-right text-danger details-close"><i class="fa fa-close"></i></a>
                <div class="row">
                    <div class="col-sm-6">
                        <h2>{{location.name}}</h2>
                        <p class="location-address">
                            {{location.full_address}},
                            {{location.phone}}
                        </p>
                        <div class="location-description" v-html="location.description"></div>
                        <p>We recommend setting aside {{location.average_visit_time_hours}} for your visit.</p>
                        <div class="thumbnail" v-html="location.medium_html_image"></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Opening Hours:</h4>
                                <p v-if="location.opening_hours" v-html="location.opening_hours_line_breaks"></p>
                                <p v-else>Not Available</p>
                                <h4>Admission Fee:</h4>
                                <p v-if="location.admission_fees" v-html="location.admission_fees_line_breaks"></p>
                                <p v-else>Not Available</p>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled location-action-list">
                                    <li v-if="location.email">
                                        <a v-bind:href="'mailto:' + location.email + '?subject=Ireland Planner Enquiry'"><i class="fa fa-fw fa-send"></i>Email Attraction</a>
                                    </li>
                                    <li v-html="location.maps_embed_popup_link_text"></li>
                                    <li v-if="location.website">
                                        <a target="_blank" v-bind:href="location.website"><i class="fa fa-fw fa-home"></i>Official Website</a>
                                    </li>
                                    <li>
                                        <template v-if="itineraries !== false">
                                            <add-to-itinerary
                                                :itineraries="itineraries"
                                                :itinerary-create-url="itineraryCreateUrl"
                                                :location-hashid="location.hashid"
                                            ></add-to-itinerary>
                                        </template>
                                        <a v-else :href="loginUrl"><i class="fa fa-fw fa-pencil"></i>Add To Itinerary</a>
                                    </li>
                                    <li>
                                        <favourite-location
                                            :favourited="favourited"
                                            :endpoint="endpoint"
                                            :show-text="true"
                                        >
                                        </favourite-location>
                                    </li>
                                </ul>
                                <h3>GPS Co-ordinates</h3>
                                <p v-html="location.lat_long_decimal_and_degrees"></p>
                                <p><a target="_blank" v-bind:href="location.newURL">View this listing in a new tab</a></p>
                                <h3>Find Similar Attractions</h3>
                                <p class="location-category-summary" v-html="location.categories_summary"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                detailsWindow: false,
                arrowStyle: {
                    left: '50px'
                },
            }
        },

        computed : {
            gridItemId () {
                return 'location-details-' + this.location.hashid;
            }
        },
        props : [
            'location',
            'endpoint',
            'favourited',
            'itineraries',
            'itineraryCreateUrl',
            'chunckIndex',
            'loginUrl'
        ],

        mounted() {
        },

        methods: {
            toggle() {

                if (this.detailsWindow) {
                    // If this is open, then we just need to close it
                    this.detailsWindow = false;
                } else {
                    // else we need to close all the other windows and open self
                    let self = this;
                    this.$parent.$children.forEach(
                        function (item) {
                            item.detailsWindow = item === self ? true : false;
                        }
                    );
                }
            },

            hide() {
                this.detailsWindow = false;
            },
            scrollTo(e) {
                this.arrowStyle.left = (e.x - 105) + 'px';
                var self = this;

                if (!this.detailsWindow) {
                    setTimeout(function () {
                        var heightToScroll = $("#" + self.gridItemId).offset().top;
                        var heightPopup = $("#" + self.gridItemId + " .location-details").outerHeight() + $("#" + self.gridItemId).outerHeight();

                        var calc = $("#" + self.gridItemId + " .location-details").offset().top;

                        $('html,body').animate( { scrollTop: heightToScroll }, 'slow');

                        window.top.postMessage({
                            action: 'scrollto',
                            value: calc,
                            chunck: self.chunckIndex
                        }, "*");

                    }, 10);
                }
            },
            toggleAndScrollTo(responder, e) {
                this.scrollTo(e);
                this.toggle();
            },
            castamChek() {
                let self = this;
                this.$http
                    .post('/api/v1/location/check')
                    .then((response) => {
                        self.showModal = false;                        
                        self.tripHashid = false;
                        self.showToast('Location added to trip.');
                    }, (response) => {
                        self.showModal = false;
                        self.showToast(response.data.message, 'danger')                        
                        self.tripHashid = false;
                        self.tripOptions = [];
                    });
            },
        }
    }
</script>