<?php
$url = '';
if (!empty($location)):
  $url = 'https://www.google.com/maps?mapclient=embed&daddr='
    . rawurlencode($location)
    . (!empty($maptype) ? '&maptype=' . $maptype : '')
  ?>
<?php endif; ?>

<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div id="map-<?= $weight ?>" class="vssl-stripe--googlemap--embed"></div>
  </div>
</div>


<script>
  // Gather your helpful variables
  var weight = <?= $weight ?>;
  var styles = <?= (!empty($options) && !empty($options['styles'])) ? json_encode($options['styles']) : '[]' ?>;
  var maptype = '<?= !empty($maptype) ? $maptype : "" ?>';
  var address = '<?= !empty($location) ? addslashes($location) : "" ?>';
  var zoom = <?= !empty($zoom) ? $zoom : 15 ?>;
  zoom = ('<?= empty($zoom) && empty($location) ?>') ? 8 : zoom;

  // Create the map
  var map = new google.maps.Map(document.getElementById('map-' + weight), {
    center: {lat: 38.0293, lng: -78.4767},
    zoom: zoom,
    styles: styles,
    mapTypeControl: false
  });

  if (maptype == 'satellite') {
    map.setMapTypeId('hybrid');
    map.setOptions({styles: []});
  }

  // Call functions to add marker & location card to the map
  var geocoder = new google.maps.Geocoder();
  geocodeAddress(geocoder, map, address, weight, function(coordinates, weight) {
    addCard(geocoder, map, coordinates, weight);
  })

  // Translate address to coordinates, and put a marker there
  function geocodeAddress(geocoder, resultsMap, address, weight, callback) {
    if (address != '') {
      geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
          coordinates = results[0].geometry.location
          resultsMap.setCenter(coordinates);

          var marker = new google.maps.Marker({
            map: resultsMap,
            position: coordinates
          });

          callback(coordinates, weight);
        }
      });
    }
  }

  // Add a location card to the map
  function addCard(geocoder, resultsMap, coordinates, weight) {
    if (typeof coordinates != 'undefined') {
      // Translate coordinates to address
      var address = '';
      geocoder.geocode({'location': coordinates}, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            address = results[0]['formatted_address'];
          }
        }
      });

      // Add card to DOM
      google.maps.event.addListener(resultsMap, 'idle', function(e) {
        if ( $( "#map-" + weight + " .place-card" ).length === 0 ) {
          var control = '<div class="custom-controls">\
            <div class="inner">\
              <div class="place-card place-card-large">\
                <div class="place-desc-large">\
                  <div class="address">' 
                    + address +
                  '</div>\
                </div>\
                <div class="navigate">\
                  <a class="navigate-link" href="' + '<?= $url ?>' + '" target="_blank">\
                    <div class="icon navigate-icon"></div>\
                    <div class="navigate-text">Directions</div>\
                  </a>\
                </div>\
                <div class="maps-links-box-exp">\
                  <div class="google-maps-link">\
                    <a href="https://maps.google.com/maps/place/' + encodeURIComponent(address) + '" target="_blank">View larger map</a>\
                  </div>\
                </div>\
              </div>\
            </div>\
          </div>';

          $("#map-" + weight + " .gm-style").append(control);
        }
      });
    }
  }
</script>
