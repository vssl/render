<?php if (!empty($text['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--lede--text"><?= $text['html'] ?></div>
    </div>
</div>
<?php endif;
