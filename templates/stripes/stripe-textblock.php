<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($content['html'])) : ?>
        <div class="vssl-stripe--textblock--content"><?= $content['html'] ?></div>
        <?php endif; ?>
    </div>
</div>
