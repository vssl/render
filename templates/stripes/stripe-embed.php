<?php if (!empty($embed)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--embed--content">
            <?= $embed ?>
        </div>
    </div>
</div>
<?php endif;
