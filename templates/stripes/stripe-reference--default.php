<?php if (!empty($reference_page)) : ?>
<?php
// The stripe's own label wins; otherwise fall back to the referenced page's header label.
$referenceLabel = !empty($label['html']) ? $label : ($reference_page['label'] ?? null);
?>
<div class="vssl-stripe-column">
    <div class="vssl-stripe--reference--card vssl-stripe--card">
        <?php if (!empty($reference_page['image'])) : ?>
        <div class="vssl-stripe--reference--image">
            <img class="vssl-stripe--reference--thumbnail"
                src="<?= $this->image($reference_page['image'], $image_style ?? null) ?>"
                alt="<?= $reference_page['image_alt'] ?? '' ?>"
                loading="lazy" />
            <a class="vssl-stripe--reference--image-overlay" href="<?= $reference_page['slug'] ?>" tabindex="-1" aria-hidden="true"></a>
        </div>
        <?php endif; ?>

        <div class="vssl-stripe--reference--text">
            <div class="vssl-stripe--reference--page-info">
                <?php if (!empty($referenceLabel['html'])) : ?>
                <div class="vssl-stripe--reference--label"
                    data-label="<?= $this->e(strip_tags($referenceLabel['html'])) ?>"
                    ><?= $this->inline($referenceLabel['html']) ?></div>
                <?php endif; ?>
                <?php if (!empty($reference_page['title'])) : ?>
                <h3 class="vssl-stripe--reference--title">
                    <a href="<?= $reference_page['slug'] ?>"><?= $this->inline($reference_page['title']) ?></a>
                </h3>
                <?php endif; ?>
                <?php if (!empty($reference_page['summary'])) : ?>
                <p class="vssl-stripe--reference--description"><?= $this->inline($reference_page['summary']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif;
