"use strict";
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        center: {lat: 43.6115554, lng: 1.4263502},
        zoom: 13,
        fullscreenControl: false,
        streetViewControl: true
    });


        new AutocompleteDirectionsHandler(map);
    }

    /**
    * @constructor
    */
    function AutocompleteDirectionsHandler(map) {
        this.map = map;
        this.originPlaceId = null;
        this.destinationPlaceId = null;
        this.travelMode = 'DRIVING';
        this.directionsService = new google.maps.DirectionsService;
        this.directionsRenderer = new google.maps.DirectionsRenderer;
        this.directionsRenderer.setMap(map);

        var originInput = document.getElementById('origin-input');
        var destinationInput = document.getElementById('destination-input');

        var originAutocomplete = new google.maps.places.Autocomplete(originInput);
// Specify just the place data fields that you need.
        originAutocomplete.setFields(['place_id']);

        var destinationAutocomplete =
        new google.maps.places.Autocomplete(destinationInput);
// Specify just the place data fields that you need.
        destinationAutocomplete.setFields(['place_id']);

        this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
        this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
    }

    let arrivee;
    let depart;
    let distance;


    AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function (
    autocomplete, mode) {
        var me = this;
        autocomplete.bindTo('bounds', this.map);

        autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();

        if (!place.place_id) {
        window.alert('Veuillez selectionner une adresse dans la liste déroulante');
        return;
    }
        if (mode === 'ORIG') {
        me.originPlaceId = place.place_id;
    } else {
        me.destinationPlaceId = place.place_id;
    }
        me.route();

    });
    };

    AutocompleteDirectionsHandler.prototype.route = function () {
        if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
    }
        var me = this;

        this.directionsService.route(
    {
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        travelMode: this.travelMode
    },
        function (response, status) {
        if (status === 'OK') {
        reservation.setAdresseArrivee(response.routes[0].legs[0].end_address);
        reservation.setAddresseDepart(response.routes[0].legs[0].start_address);
        reservation.setDistance(response.routes[0].legs[0].distance.value);
        me.directionsRenderer.setDirections(response);
        $('#map').removeClass('hidden');
    } else {
        window.alert('Directions request failed due to ' + status);
    }
    });
    };