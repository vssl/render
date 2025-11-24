<?php if (!empty($reference_page)) : ?>
<div class="vssl-stripe-column">
    <div class="vssl-stripe--reference--card vssl-stripe--card">
        <?php if (!empty($reference_page['image'])) : ?>
        <div class="vssl-stripe--reference--image">
            <img class="vssl-stripe--reference--thumbnail"
                src="<?= $this->image($referencePage['image'], $image_style ?? null) ?>"
                alt="<?= $referencePage['image_alt'] ?? '' ?>"
                loading="lazy" />
            <a class="vssl-stripe--reference--image-overlay" href="<?= $referencePage['slug'] ?>" tabindex="-1" aria-hidden="true"></a>
        </div>
        <?php endif; ?>

        <div class="vssl-stripe--reference--text">
            <div class="vssl-stripe--reference--page-info">
                <? if (!empty($referencePage['title'])) : ?>
                <h3 class="vssl-stripe--reference--title">
                    <a href="<?= $reference_page['slug'] ?>"><?= $this->inline($reference_page['title']) ?></a>
                </h3>
                <? endif; ?>
                <? if (!empty($reference_page['summary'])) : ?>
                <p class="vssl-stripe--reference--description"><?= $this->inline($reference_page['summary']) ?></p>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif;
