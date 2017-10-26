<?php
$styles = (!empty($options['styles']) ? $options['styles'] : []);
$address = (!empty($formatted_address) ? $formatted_address : $location);

if (!empty($address)) {
  $navigationUrl = 'https://www.google.com/maps?mapclient=embed&daddr='
    . rawurlencode($address) . (!empty($maptype) ? '&maptype=' . $maptype : '');

  $largerUrl = 'https://maps.google.com/maps/place/' . rawurlencode($address);
}

if (empty($zoom)) {
  $zoom = (empty($address) ? 8 : 15);
}

if ($address):
?>

<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--googlemap--embed"
      data-styles="<?= htmlspecialchars(json_encode($styles), ENT_QUOTES, 'UTF-8') ?>"
      data-coordinates="<?= htmlspecialchars(json_encode($coordinates), ENT_QUOTES, 'UTF-8') ?>"
      data-maptype="<?= $maptype ?>"
      data-zoom="<?= $zoom ?>"
    >
      <div id="map-<?= $weight ?>" class="vssl-stripe--googlemap--map">
        <!-- JS-loaded map goes here -->
        <noscript>
          <div>You must enable JavaScript to load this map.</div>
        </noscript>
      </div>
      <div class="custom-controls">
        <div class="address">
          <?= $address ?>
        </div>
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
<?php endif; ?>
