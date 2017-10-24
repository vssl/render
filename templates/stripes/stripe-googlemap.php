<?php
if (!empty($location)):
    $url = 'https://www.google.com/maps/embed/v1/place'
        . '?key=AIzaSyC6Ex622FurbibGk5io9rq3StLgg1_04aU'
        . '&q=' . rawurlencode($location)
        . (!empty($maptype) ? '&maptype=' . $maptype : '')
        . '&zoom=' . (isset($zoom) ? $zoom : 15);
    ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--googlemap--embed">
            <iframe
                src="<?= $url ?>"
                width="600"
                height="450"
                frameborder="0"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
<?php endif; ?>
