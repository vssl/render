<header class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo (!empty($image) ? ' data-has-background-image="true"' : '');
    echo (!empty($featured_image) ? ' data-has-featured-image="true"' : '');
    echo (!empty($variation) ? " data-variation=\"{$variation}\"" : '');
?>>
    <?php if (!empty($image)) : ?>
    <div class="vssl-stripe--header--background"
        style="background-image: url('<?= $this->image($image, $image_style ?? null) ?>');"
    ></div>
    <?php endif; ?>

    <div class="vssl-stripe-column">
        <?php if (!empty($featured_image)) : ?>
        <div class="vssl-stripe--header--featured">
            <img src="<?= $this->image($featured_image, $featured_image_style ?? null) ?>"
                alt="<?= $featured_image_alt ?? '' ?>" />
        </div>
        <?php endif; ?>

        <div class="vssl-stripe--header--info">
            <div class="vssl-stripe--header--text">
                <?php if (!empty($label['html'])) : ?>
                <div class="vssl-stripe--header--label" data-label="<?= strip_tags($label['html']) ?>"
                    ><?= $this->inline($label['html']) ?></div>
                <?php endif; ?>

                <?php if (!empty($hed['html'])) : ?>
                <h1 class="vssl-stripe--header--hed"><?= $this->inline($hed['html']) ?></h1>
                <?php endif; ?>

                <?php if (!empty($dek['html'])) : ?>
                <div class="vssl-stripe--header--dek"><?= $this->inline($dek['html']) ?></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($btn) && !empty($btntxt) && !empty($btnurl)) : ?>
            <div class="vssl-stripe--header--button">
                <a href="<?= $btnurl ?>" class="vssl-button"><?= $btntxt ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>
