<?php if ($address): ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--googlemap--embed"
      <?php if (isset($options['styles'])): ?>
      data-styles="<?= $this->inlineJson($options['styles']) ?>"
      <?php endif; ?>
      <?php if (isset($coordinates)): ?>
      data-coordinates="<?= $this->inlineJson($coordinates) ?>"
      <?php else: ?>
      data-location="<?= $address ?>"
      <?php endif; ?>
      data-maptype="<?= isset($maptype) ? $maptype : 'roadmap' ?>"
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
