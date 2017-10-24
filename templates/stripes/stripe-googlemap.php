<?php
$url = '';
if (!empty($location)):
  $url = 'https://www.google.com/maps?mapclient=embed&daddr='
    . rawurlencode($location)
    . (!empty($maptype) ? '&maptype=' . $maptype : '')
  ?>
<?php endif; ?>

<style type="text/css">
.custom-controls {
  position: absolute;
  left: 0;
  top: 0;
  z-index: 1;
}
.custom-controls .inner {
  margin: 10px;
  padding: 1px;
  -webkit-box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;
  box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;
  border-radius: 2px;
  background-color: white;
}

/* Google maps card styles */
.gm-style .place-card div,
.gm-style .place-card a {
  color: #5B5B5B;
  font-family: Roboto, Arial;
  font-size: 12px;
}
.gm-style .place-card a {
  color: #3a84df
}
.gm-style .place-card-large {
  padding: 9px 4px 9px 11px
}
.gm-style .place-desc-large {
  width: 160px;
  display: inline-block
}

.gm-style .place-card .address {
  margin-top: 6px
}
.gm-style .navigate {
  display: inline-block;
  vertical-align: top;
  height: 43px;
  padding: 0 7px
}
.gm-style .navigate-icon {
  width: 22px;
  height: 22px;
  overflow: hidden;
  margin: 0 auto
}
.gm-style .icon {
  background-image: url(https://maps.gstatic.com/mapfiles/embed/images/entity11.png);
  background-size: 70px 210px;
}
</style>

<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div id="map-<?= $weight ?>" class="vssl-stripe--googlemap--embed"></div>

    <script>
      start();

      // Start things off
      function start() {
        var zoom = <?= !empty($zoom) ? $zoom : 15 ?>;
        zoom = ((zoom === 16) && ('<?= !empty($location) ?>')) ? 8 : zoom;

        var styles = <?= (!empty($options) && !empty($options['styles'])) ? json_encode($options['styles']) : '[]' ?>;
        var maptype = '<?= !empty($maptype) ? $maptype : "" ?>'

        wait('<?= !empty($location) ? $location : "" ?>', <?= $weight ?>, zoom, styles, maptype)
      }

      // Take a break 
      function wait(location, weight, zoom, styles, maptype) {
        setTimeout( function() {
          initMap(location, weight, zoom, styles, maptype)
        }, 500);
      }

      // Do the thing
      function initMap(location, weight, zoom, styles, maptype) {
        var map = new google.maps.Map(document.getElementById('map-' + weight), {
          center: {lat: 38.0293, lng: -78.4767},
          zoom: zoom,
          styles: styles,
          mapTypeControl: false
        });

        if (maptype == 'satellite') {
          map.setMapTypeId('hybrid');
        }

        var geocoder = new google.maps.Geocoder();
        geocodeAddress(geocoder, map, location);
        addCard(map, location, weight);
      }

      // Then the next thing
      function geocodeAddress(geocoder, resultsMap, address) {
        if (address != '') {
          geocoder.geocode({'address': address}, function(results, status) {
            if (status === 'OK') {
              resultsMap.setCenter(results[0].geometry.location);

              var marker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location
              });
            } 
          });
        }
      }

      // Then add a card if it's got an address
      function addCard(resultsMap, address, weight) {
        if (address != '') {
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

  </div>
</div>