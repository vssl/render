<?php if (!empty($image)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column<?= $layout == 'image-left' ? ' image-left' : '' ?>">
        <?php if (!empty($text['html'])) : ?>
        <div class="vssl-stripe--text-image--text"><?= $text['html'] ?></div>
        <?php endif; ?>
        <div class="vssl-stripe--text-image--image">
            <img src="<?= $this->image($image, $image_style ?? null) ?>"
                alt="<?= $image_alt ?? '' ?>"
                loading="lazy" />
        </div>
    </div>
</div>
<?php endif;
