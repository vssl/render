<?php if (!empty($embed)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--embed--content"<?php
            // An explicit height overrides the theme's default aspect-ratio sizing.
            echo !empty($height) ? ' style="height: ' . (int) $height . 'px; padding-bottom: 0;"' : '';
        ?>>
            <?= $embed ?>
        </div>
    </div>
</div>
<?php endif;
