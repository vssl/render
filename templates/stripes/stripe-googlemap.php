<?php if ($address) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
    echo !empty($options['marker']) ? " data-marker=\"" . htmlspecialchars($options['marker'], ENT_QUOTES) . "\"" : '';
    echo !empty($options['coordinates']) ? " data-coordinates=\"" . htmlspecialchars(json_encode($options['coordinates']), ENT_QUOTES) . "\"" : '';
    echo !empty($address) ? " data-address=\"" . htmlspecialchars($address, ENT_QUOTES) . "\"" : '';
    echo !empty($maptype) ? " data-maptype=\"{$maptype}\"" : ' data-maptype="roadmap"';
    echo !empty($zoom) ? " data-zoom=\"{$zoom}\"" : '';
    echo !empty($options['styles']) ? " data-styles=\"" . htmlspecialchars($options['styles'], ENT_QUOTES) . "\"" : ' data-styles="[]"';
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
<?php endif;
