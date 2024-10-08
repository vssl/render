<?php if ($address) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--googlemap--embed">
            <div id="map-<?= $weight ?>" class="vssl-stripe--googlemap--map">
                <!-- JS-loaded map goes here -->
                <noscript>
                    <div>You must enable JavaScript to load this map.</div>
                </noscript>
            </div>
            <div class="custom-controls">
                <div class="address"><?= $address ?></div>
                <div class="navigate">
                    <a class="navigate-link" href="<?= $navigationUrl ?>" target="_blank">
                        <div class="icon navigate-icon"></div>
                        <div class="navigate-text">Directions</div>
                    </a>
                </div>
                <div class="google-maps-link">
                    <a href="<?= $largerUrl ?>" target="_blank">View larger map</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const stripeEl = document.currentScript.previousElementSibling

if (typeof google !== 'undefined') {
    try {
        initStripe()
    } catch(e) {
        console.error('Failed to initialize google map stripe', e)
    }
} else {
    console.error('Google Maps API is not available in this script.')
}

function initStripe() {
    const marker = <?= !empty($options['marker']) ? "'" . $options['marker'] . "'" : 'false' ?>

    const coordinates = <?= !empty($options['coordinates']) ? "'" . $options['coordinates'] . "'" : 'false' ?>

    const address = <?= !empty($address) ? "'$address'" : 'false' ?>

    const maptype = <?= !empty($maptype) ? "'$maptype'" : "'roadmap'" ?>

    const zoom = parseInt("<?= $zoom ?>", 10)
    const stylesJson = "<?= !empty($options['styles']) ? $options['styles'] : '[]' ?>"
    const styles = maptype === 'roadmap' ? JSON.parse(stylesJson) : []
    const config = { marker, coordinates, address, maptype, zoom, styles }

    if (coordinates) {
        buildMap(config)
    } else if (address) {
        geocode(config)
    } else {
        console.warn('No coordinates or address set for Google Maps API. Doing nothing.')
    }
}

function buildMap(config) {
    const mapWrapEl = stripeEl.querySelector('.vssl-stripe--googlemap--map')
    const customMarker = 'https://s3.amazonaws.com/cdn.vssl.io/marker.png'

    const map = new google.maps.Map(mapWrapEl, {
        center: config.coordinates,
        zoom: config.zoom,
        styles: config.styles,
        mapTypeId: config.maptype,
        mapTypeControl: false
    })
    new google.maps.Marker({
        map,
        position: config.coordinates,
        icon: config.marker || customMarker
    })
}

function geocode(config) {
    const geocoder = new google.maps.Geocoder
    geocoder.geocode({ address: config.address }, (results, status) => {
        if (status === 'OK' && results.length) {
            config.coordinates = results[0].geometry.location
            config.formatted_address = results[0].formatted_address
            buildMap(config)
        }
    })
}

const embedEl = stripeEl.querySelector('.vssl-stripe--googlemap--embed')
embedEl.addEventListener('click', function() {
    this.classList.add('is-engaged')
})
</script>
<?php endif;
