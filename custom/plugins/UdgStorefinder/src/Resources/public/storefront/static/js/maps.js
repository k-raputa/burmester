var map;
var geocoder;
var addresses = [];
var baseIcon1;
var baseIcon2;
var md;

function initMap() {
    baseIcon1 = new google.maps.MarkerImage('/bundles/udgstorefinder/storefront/static/img/module/udgstorefinder/burmi4.png', new google.maps.Size(50, 50), new google.maps.Point(0,0), new google.maps.Point(20,50));
    baseIcon2 = new google.maps.MarkerImage('/bundles/udgstorefinder/storefront/static/img/module/udgstorefinder/burmi3.png', new google.maps.Size(50, 50), new google.maps.Point(0,0), new google.maps.Point(20,50));

    google.maps.visualRefresh = true;

    var myLatlng = new google.maps.LatLng(50.90072325, 10.4699707031);
    var myOptions2 = {
        position: myLatlng,
        addressControl: true,
        linksControl: true,
        imageDateControl: true,
        navigationControlOptions: {
            style: google.maps.NavigationControlStyle.SMALL
        },
        enableCloseButton: true,
        visible: true
    };

    var streetMap = new google.maps.StreetViewPanorama(document.getElementById("twGmap"), myOptions2);

    var myOptions = {
        zoom: 6,
        center: myLatlng,
        navigationControl: true,
        mapTypeControl: true,
        minZoom: 2,
        overviewMapControl: true,
        overviewMapControlOptions: {
            opened: true
        },
        streetViewControl: true,
        scaleControl: true,
        streetView: streetMap,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var stylesArray = [
        {"featureType":"administrative", "elementType":"all"},
        {"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"}, {"color":"#fcfcfc"}]},
        {"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"}, {"color":"#fcfcfc"}]},
        {"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},
        {"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},
        {"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},
        {"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]}];

    map = new google.maps.Map(document.getElementById("twGmap"), myOptions);
    map.setOptions({styles: stylesArray});

    geocoder = new google.maps.Geocoder();
}

function rawurlencode (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
    replace(/\)/g, '%29').replace(/\*/g, '%2A');
}
