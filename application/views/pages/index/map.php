<?php
    $my_full_name = "Me";
    if (isset($this->session->userdata["user_id"]))
        $my_full_name = html_escape($this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"]);
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

    function markerCenterOnClick()
    {
        google.maps.event.addListener(marker, "click", function() {
            infowindow.setContent(this.html);
            infowindow.open(map, this);
            map.setCenter(this.getPosition());
//            map.setZoom(10);
        });
    }

    // To add the Content box on the marker, which will displyed when clicked on the marker
    function addInfoWindow(mapObject, marker, message) {
        var info = message;

        var infoWindow = new google.maps.InfoWindow({
            content: message
        });

        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.open(mapObject, marker);
        });
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setAllMap(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setAllMap(map);
    }

    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(<?php echo $my_latitude; ?>, <?php echo $my_longitude; ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: 12
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        var customerLatLon = new google.maps.LatLng(<?php echo $my_latitude; ?>, <?php echo $my_longitude; ?>);
        var myMarker = new google.maps.Marker({
            position: customerLatLon,
            map: map,
            title: "<?php echo $my_full_name; ?>",
        });
        myMarker.setIcon("<?php echo IMAGES_PATH; ?>/map-icons/my-marker.png");
        addInfoWindow(map, myMarker, "<?php echo $my_full_name; ?>");


        //***********WORKERS****************//

        var markers = [<?php echo $nearby_records_array; ?>];

        var lat_lng = new Array();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);

            var nearbyMarker = "";
            var nearbyMarker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title,
            });

            nearbyMarker.setIcon(data.marker_img_path);
            addInfoWindow(map, nearbyMarker, data.title);
            lat_lng.push(myLatlng);
        }
    }

    //marker.setPosition(location);
    google.maps.event.addDomListener(window, 'load', initialize);


    // to adjust the screen layout so that the map looks full-screen
    $(document).ready(function() {
        $(".outer-wrapper").removeClass('container');
        $(".top-height-90").css('height', '50px');
        $("#footer-upper").css('margin-top', '0');
    });
</script>

<div id="map-canvas"/></div>