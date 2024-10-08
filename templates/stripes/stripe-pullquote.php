<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($image) ? ' data-has-image="true"' : '';
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <?php if (!empty($image)) : ?>
    <div class="vssl-stripe--pullquote--background"
        style="background-image: url('<?= $this->image($image, $image_style ?? null) ?>');"
    ></div>
    <?php endif; ?>

    <div class="vssl-stripe-column">
        <?php if (!empty($quote['html'])) : ?>
        <div class="vssl-stripe--pullquote--quote"><?= $quote['html'] ?></div>
        <?php endif; ?>

        <div class="vssl-stripe--pullquote--meta">
            <?php if (!empty($attribution['html'])) : ?>
            <div class="vssl-stripe--pullquote--attribution"><?= $this->inline($attribution['html']) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($info['html'])) : ?>
            <div class="vssl-stripe--pullquote--info"><?= $this->inline($info['html']) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
