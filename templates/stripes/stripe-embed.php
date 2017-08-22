<?php if (!empty($embed)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--embed--iframe"><?= $embed ?></div>
    </div>
</div>
<?php endif; ?>