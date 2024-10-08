<?php if (!empty($content['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <?php if (!empty($content['html'])) : ?>
        <div class="vssl-stripe--textblock--content"><?= $content['html'] ?></div>
        <?php endif; ?>
    </div>
</div>
<?php endif;
