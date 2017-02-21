jQuery(document).ready(function() {
    google.maps.event.addDomListener(window, "load", initialize);
});

var MAP;
var LOCATION;
var service;
var marker;
var BOUNDS = new google.maps.LatLngBounds();
var GEOCODER = new google.maps.Geocoder();
var INFOWINDOW = new google.maps.InfoWindow();

var updateMap = function() {

    var mapOptions = {
        center: LOCATION,
        zoom: 15,
        minZoom: 15,
        maxZoom: 15,
        styles: styles['black'],
        streetViewControl: false,
        zoomControl: false,
        pancontrol: false,
        mapTypeControl: false,
        scaleControl: false,
        overviewMapControl: false,
        draggable: false,
        disableDoubleClickZoom: true
    };

    MAP = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    service = new google.maps.places.PlacesService(MAP);

};

var addressForMarkers = function(id) {
    var marker = markers.filter(function(s) {
        return s.id == id
    })[0];
    if (!marker) {
        return "";
    }
    return marker.address_1 + ", " + marker.city + ", " + marker.country;
};
var initialize = function() {
    retrieveLatLng(addressForMarkers(markers[0].id), function(err, gc) {
        if (err) {
            console.log(err);
        } else {
            LOCATION = gc.geometry.location;
            updateMap();
            addMarkers();
        }
    });
};
var addMarkers = function() {

    var done = 0;
    markers.forEach(function(marker) {
        retrieveLatLng(addressForMarkers(marker.id), function(err, gc) {
            if (err) {
                console.log(err);
            } else {
                service.getDetails({
                    placeId: gc.place_id
                }, function(place, status) {
                  if (status === google.maps.places.PlacesServiceStatus.OK) {
                    marker = new google.maps.Marker({
                      position: gc.geometry.location,
                      map: MAP,
                      title: marker.name,
                      icon: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAMAAAAM7l6QAAAA51BMVEUAAAARP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1kRP1lWONL1AAAATHRSTlMAAQIDBAUHCAkKCwwPEBESFRgcHh8hIicpMTM2Oj0+P0FDS0xNTlhiZGdocXN8f4CCg4WIl6Kjpqirrbq+wMHDys7R2ubp6+3v9/v9r3MSiAAAAO5JREFUGBl1wQk7AlEYBtD3lkE0JFmTse+KIntRCTPv//89vpnnNvfOdg4s7nHv5enOqyCP26fWnUdG02dsWkdKi7agjoS1gAk/C7ANGLpYhlrtMtSDZYMi2ETkkKEKjBuKU2htigMYHySDMjSXog1jSnKEGUXRh/FNcoQZRfEIY0AyKEOrUnRgXFGcQLul2INRo/AbiHgUvgPLK0VwvgS10mHoGrYGUxaR8M6EeyRtM6GKlC9aHpC2S0sNGWPGnpG1z9g6stSE2hvyHFHbQp7SLyOfyHfGyA7yzf1RjFHkkqKFIs6EHCoUcrxmCbZ/alda5t7XpDYAAAAASUVORK5CYII="
                    });
                    google.maps.event.addListener(marker, "click", function() {
                      INFOWINDOW.setContent(
                        "<div style=\"width:150px;\">"+
                        "   <strong>" +
                        "       Studio cyclone - " + marker.name +
                        "   </strong>" +
                        "   <br>" +
                        "   Adresse: " + place.formatted_address +
                        "   <br>" +
                        "</div>"
                      );
                      INFOWINDOW.open(MAP, this);
                    });
                    BOUNDS.extend(marker.position);
                  }
                });

                done += 1;
                if (done >= markers.length) {
                  updateMap();
                }
            }
        });
    });
};
var retrieveLatLng = function(address, cb_) {
    GEOCODER.geocode({ "address": address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                return cb_(null, results[0]);
            } else {
                return cb_(new Error("Not results"));
            }
        } else {
            return cb_(new Error(status));
        }
    });
};
$(".marker").click(function() {
    var id = parseInt($(this).attr("data-id"), 10);
    retrieveLatLng(addressForMarkers(id), function(err, gc) {
        if (err) {
            console.log(err);
        } else {
            LOCATION = gc.geometry.location;
            updateMap();
            addMarkers();
        }
    });
});
