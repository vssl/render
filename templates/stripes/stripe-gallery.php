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
                    <div class="vssl-stripe--gallery--button vssl-stripe--gallery--next">
                        <span class="vssl-icon">&rarr;</span>
                    </div>
                    <div class="vssl-stripe--gallery--button vssl-stripe--gallery--prev">
                        <span class="vssl-icon">&larr;</span>
                    </div>
                </div>
                <div class="vssl-stripe--gallery--counter">
                    <span class="vssl-stripe--gallery--current">1</span>
                    <span class="vssl-stripe--gallery--of">/</span>
                    <span class="vssl-stripe--gallery--total"><?= count($slides) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif;

if (count($slides) > 1) : ?>
<script>
const galleryEl = document.currentScript.previousElementSibling
const nextBtn = galleryEl.querySelector('.vssl-stripe--gallery--next')
const prevBtn = galleryEl.querySelector('.vssl-stripe--gallery--prev')
const currentCounter = galleryEl.querySelector('.vssl-stripe--gallery--current')
const slideEls = galleryEl.querySelectorAll('.vssl-stripe--gallery--slide')
const controls = galleryEl.querySelector('.vssl-stripe--gallery--controls')

let slideIndex = 0
const slideCount = slideEls.length
function setSlideIndex(index) {
    slideIndex = index
    if (slideIndex >= slideCount) slideIndex = 0
    else if (slideIndex < 0) slideIndex = slideCount - 1

    slideEls[slideIndex].dataset.active = true
    currentCounter.innerHTML = slideIndex + 1

    nextSlideIndex = slideIndex + 1 === slideCount ? 0 : slideIndex + 1
    prevSlideIndex = slideIndex === 0 ? slideCount - 1 : slideIndex - 1
    slideEls[nextSlideIndex].querySelector('img').removeAttribute('loading')
    slideEls[prevSlideIndex].querySelector('img').removeAttribute('loading')
    slideEls[nextSlideIndex].dataset.active = false
    slideEls[prevSlideIndex].dataset.active = false

    childImage = slideEls[slideIndex].querySelector('img')
    ratio = (childImage?.clientHeight || 0) / (childImage?.clientWidth || 1)
    controls.style = ratio
      ? `height: 0; padding-bottom: ${ratio * 100}%;`
      : ''
}

setSlideIndex(0)
nextBtn.addEventListener('click', () => setSlideIndex(slideIndex + 1))
prevBtn.addEventListener('click', () => setSlideIndex(slideIndex - 1))
</script>
<?php endif;
