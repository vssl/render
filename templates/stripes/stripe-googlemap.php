<?php
$url = '';
if (!empty($location)) {
  $url = 'https://www.google.com/maps?mapclient=embed&daddr='
    . rawurlencode($location)
    . (!empty($maptype) ? '&maptype=' . $maptype : '');
}

$styles = (!empty($options['styles']) ? $options['styles'] : []);
$address = (!empty($location) ? addslashes($location) : '');
if (empty($zoom)) {
  $zoom = (empty($location) ? 8 : 15);
}
?>

<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--googlemap--embed"
      data-weight="<?= $weight ?>"
      data-styles="<?= htmlspecialchars(json_encode($styles), ENT_QUOTES, 'UTF-8') ?>"
      data-maptype="<?= $maptype ?>"
      data-address="<?= $location ?>"
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
          <a class="navigate-link" href="<?= $url ?>" target="_blank">
            <div class="icon navigate-icon"></div>
            <div class="navigate-text">Directions</div>
          </a>
        </div>
        <div class="google-maps-link">
          <a href="https://maps.google.com/maps/place/<?= rawurlencode($address) ?>" target="_blank">View larger map</a>
        </div>
      </div>
    </div>
  </div>
</div>
