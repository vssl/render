
<?php if (!empty($referencePage)) : ?>
<div class="vssl-stripe-column">
    <div class="vssl-stripe--reference--card vssl-stripe--card">
        <?php if (!empty($referencePage['image'])) : ?>
        <div class="vssl-stripe--reference--image">
            <a href="<?= $referencePage["slug"] ?>" tabindex="-1">
                <img class="vssl-stripe--reference--thumbnail"
                    src="<?= $this->image($referencePage['image'], !empty($image_style) ? $image_style : null) ?>"
                    alt="<?= $referencePage['image_alt'] ?? '' ?>"
                    loading="lazy" />
            </a>
        </div>
        <?php endif; ?>

        <div class="vssl-stripe--reference--text">
            <div class="vssl-stripe--reference--page-info">
                <? if (!empty($$referencePage['title'])) : ?>
                <h3 class="vssl-stripe--reference--title">
                    <a href="<?= $referencePage['slug'] ?>"><?= $this->inline($referencePage['title']) ?></a>
                </h3>
                <? endif; ?>
                <? if (!empty($referencePage['summary'])) : ?>
                <p class="vssl-stripe--reference--description"><?= $this->inline($referencePage['summary']) ?></p>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif;
