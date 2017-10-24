<?php if (!empty($embed)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--videoembed--video"><?= $embed ?></div>
    </div>
</div>
<?php endif; ?>
