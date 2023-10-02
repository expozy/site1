
function initMap() {

  var final_address = '';

  const map = new google.maps.Map(document.getElementById("map"), {
    center: {
      lat: 42.69566314577448,
      lng: 23.310898688636605
    },
    zoom: 13,
    streetViewControl: false,
  });

  const input = document.getElementById("address");
  // Specify just the place data fields that you need.
  const autocomplete = new google.maps.places.Autocomplete(input, {
    fields: ["place_id", "geometry", "name", "formatted_address"],
  });

  autocomplete.bindTo("bounds", map);
  // map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

  const infowindow = new google.maps.InfoWindow();
  const infowindow_location = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");

  // PLACES
  const place__country = document.getElementById("place__country");
  const place__area = document.getElementById("place__area");
  const place__postal_code = document.getElementById("place__postal_code");
  const place__populated_place = document.getElementById("place__populated_place");
  const place__street_number = document.getElementById("place__street_number");
  const place__street = document.getElementById("place__street");

  infowindow.setContent(infowindowContent);

  const geocoder = new google.maps.Geocoder();


  var marker = new google.maps.Marker({
    position: map.getCenter(),
    map: map,
    draggable: true,
  });


  marker.setVisible(true);

  // START add an event MARKER "onDrag"
  google.maps.event.addListener(marker, 'dragend', function() {
    copyMarkerpositionToInput();
  });

  function copyMarkerpositionToInput() {
    if(marker.getPosition().lat() != undefined && marker.getPosition().lng() != undefined ){

      var lat = marker.getPosition().lat();
      var lng = marker.getPosition().lng();

      findMarkerAddress(lat, lng);
    }else {
      console.log('Маркер няма стойност');
      infowindow_location.setContent("Местоположението не е намерено");
      infowindow_location.open(map);
    }

  }
  // START add an event MARKER "onDrag"


  function centerMarker(){
    if(marker){
      marker.setMap(null);
    }

    marker = new google.maps.Marker({
      position: map.getCenter(),
      map: map,
      draggable: true,
    });

    google.maps.event.addListener(marker, 'dragend', function() {
      copyMarkerpositionToInput();
    });

    // marker.setVisible(true);
  }

  // START MAP DRAGED REPLACE MARKER
  google.maps.event.addListener(map, 'dragend', function() {
    centerMarker();
  });
  // END MAP DRAGED REPLACE MARKER



  // START FIND ADDRES BY MARKER
  function findMarkerAddress(lat, lng){

    var latlng = new google.maps.LatLng(lat, lng);

    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {

        updateInputAddress(getLongAddressObject(results));


      }

    });

  }
  // END FIND ADDRESS BY MARKER

  const location = document.getElementById("location");

  function getLongAddressObject(object) {
    console.log(object);

    let address = {};
    const address_components = object[0].address_components;
    address_components.forEach(element => {

      if(element.types[0] == "country"){
        address['country_code'] = element.short_name
        address[element.types[0]] = element.long_name;
      }else {
            address[element.types[0]] = element.short_name;
      }
    });
    return address;
  }

// ПРИ НАСТИКАНЕ НА БУТОН id="LOCATION"
  location.addEventListener("click", () => {
    infowindow.close();

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          var latlng = new google.maps.LatLng(pos.lat, pos.lng);
          var geocoder = geocoder = new google.maps.Geocoder();
          geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              final_address = '';

               updateInputAddress(getLongAddressObject(results));

            }
          });

          // console.log(pos);

          // infowindow_location.setPosition(pos);
          // infowindow_location.setContent("Местоположението е намерено");
          infowindow_location.open(map);

          map.setCenter(pos);
          centerMarker();
        },
        () => {
          handleLocationError(true, infowindow_location, map.getCenter());
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infowindow_location, map.getCenter());
    }
  });

  function handleLocationError(browserHasGeolocation, infowindow_location, pos) {
    infowindow_location.setPosition(pos);
    infowindow_location.setContent(
      browserHasGeolocation ?
      "Error: The Geolocation service failed." :
      "Error: Your browser doesn't support geolocation."
    );
    infowindow_location.open(map);
  }

  autocomplete.addListener("place_changed", () => {
    infowindow_location.close();

    const place = autocomplete.getPlace();

    if (!place.place_id) {
      return;
    }

    geocoder
    .geocode({
      placeId: place.place_id
    })
    .then(({
      results
    }) => {
      map.setZoom(11);
      map.setCenter(results[0].geometry.location);
      // Set the position of the marker using the place ID and location.
      // @ts-ignore TODO This should be in @typings/googlemaps.


updateInputAddress(getLongAddressObject(results));

      centerMarker();
    })
    .catch((e) => window.alert("Geocoder failed due to: " + e));
  });


}


function updateInputAddress(addressObject){
  console.log(addressObject);
  let city = document.getElementById('city');
  let post_code = document.getElementById('post_code');
  let address = document.getElementById('address');
  let finalGeoAddress = document.getElementById('finalGeoAddress');
  let country = document.getElementById('country');
  let streetName = document.getElementById('streetName');
  let streetNumber = document.getElementById('streetNumber');
    let country_code = document.getElementById('country_code');


  if(post_code != undefined && addressObject.postal_code != undefined){
    post_code.value = addressObject.postal_code;
  }

  if(country != undefined && addressObject.country != undefined){
    country.value = addressObject.country;
  }

  if(city != undefined && addressObject.locality != undefined){
    city.value = addressObject.locality;
  }

  if(streetName != undefined && addressObject.route != undefined){
    streetName.value = addressObject.route;
  }

  if(streetNumber != undefined && addressObject.street_number != undefined){
    streetNumber.value = addressObject.street_number;
  }


  if(country_code != undefined && addressObject.country_code != undefined){
    country_code.value = addressObject.country_code;
  }


  final_address = '';

  //Places
  if(addressObject.route != undefined){ final_address += addressObject.route + ' ';}
  if(addressObject.street_number != undefined){ final_address += addressObject.street_number + ' ';}
  if(addressObject.postal_code != undefined){ final_address += addressObject.postal_code + ' '; }
  if(addressObject.administrative_area_level_1 != undefined) { final_address += addressObject.administrative_area_level_1 + ' '; }
  if(addressObject.locality != undefined) { final_address += addressObject.locality + ' '; }
  if(addressObject.country != undefined) { final_address += addressObject.country + ' '; }

  if(final_address != '' && address != undefined){
    address.value = final_address;
    finalGeoAddress.value = final_address;
  }

    document.getElementById('address').value = final_address;

}

window.initMap = initMap;
