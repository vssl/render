<?php
$slides = array_values(array_filter($slides, fn($slide) => !empty($slide['image'])));

if (count($slides)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--gallery--wrap" data-slide-count="<?= count($slides) ?>">
            <div class="vssl-stripe--gallery--slides">
                <?php foreach ($slides as $slide) : ?>
                <div class="vssl-stripe--gallery--slide">
                    <div class="vssl-stripe--gallery--image">
                        <img src="<?= $this->image($slide['image'], $image_style ?? null) ?>"
                            alt="<?= $slide['image_alt'] ?? '' ?>"
                            loading="lazy" />
                    </div>
                    <?php if (!empty($slide['caption']['html']) ||
                        !empty($slide['credit']['html']) ||
                        count($slides) > 1
                    ) : ?>
                    <div class="vssl-stripe--gallery--meta">
                        <?php if (!empty($slide['caption']['html'])) : ?>
                        <div class="vssl-stripe--gallery--caption"><?= $this->inline($slide['caption']['html']) ?></div>
                        <?php endif; ?>

                        <?php if (!empty($slide['credit']['html'])) : ?>
                        <div class="vssl-stripe--gallery--credit"><?= $this->inline($slide['credit']['html']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($slides) > 1) : ?>
            <div class="vssl-stripe--gallery--controls" style="height: 0px; padding-bottom: 100%;">
                <div class="vssl-stripe--gallery--buttons">
                    <button type="button" class="vssl-stripe--gallery--button vssl-stripe--gallery--next" aria-label="Next image">
                        <span class="vssl-icon" aria-hidden="true">&rarr;</span>
                    </button>
                    <button type="button" class="vssl-stripe--gallery--button vssl-stripe--gallery--prev" aria-label="Previous image">
                        <span class="vssl-icon" aria-hidden="true">&larr;</span>
                    </button>
                </div>
                <div class="vssl-stripe--gallery--counter">
                    <span class="vssl-stripe--gallery--current">1</span
                    ><span class="vssl-stripe--gallery--of">/</span
                    ><span class="vssl-stripe--gallery--total"><?= count($slides) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (count($slides) > 1) : ?>
    <?php endif; ?>
</div>
<?php endif;
