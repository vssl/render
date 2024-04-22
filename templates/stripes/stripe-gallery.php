<?php
$slides = array_values(array_filter($slides, function ($slide) {
    return !empty($slide['image']);
}));
?>
<?php if (count($slides)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--gallery--wrap" data-slide-count="<?= count($slides) ?>">
            <div class="vssl-stripe--gallery--slides">
                <?php foreach ($slides as $idx => $slide) : ?>
                <div class="vssl-stripe--gallery--slide">
                    <div class="vssl-stripe--gallery--image">
                        <img
                            src="<?= $this->image($slide['image'], !empty($image_style) ? $image_style : null) ?>"
                            alt="<?= !empty($slide['alt']['html'])
                                ? htmlspecialchars(strip_tags($slide['alt']['html']), ENT_QUOTES, 'UTF-8')
                                : ''
                            ?>"
                            loading="lazy"
                        />
                    </div>
                    <?php if (!empty($slide['caption']['html']) || !empty($slide['credit']['html']) || count($slides) > 1) : ?>
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
            <div
                class="vssl-stripe--gallery--controls"
                style="height: 0px; padding-bottom: 100%;"
            >
                <div class="vssl-stripe--gallery--buttons">
                    <div class="vssl-stripe--gallery--button vssl-stripe--gallery--next"></div>
                    <div class="vssl-stripe--gallery--button vssl-stripe--gallery--prev"></div>
                </div>
                <div class="vssl-stripe--gallery--counter">
                    <span class="vssl-stripe--gallery--current">1</span>
                    <span class="vssl-stripe--gallery--of">/</span>
                    <span class="vssl-stripe--gallery--total"><?= count($slides) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const galleryEl = document.currentScript.previousElementSibling
const nextBtn = galleryEl.querySelector('.vssl-stripe--gallery--next')
const prevBtn = galleryEl.querySelector('.vssl-stripe--gallery--prev')
const currentCounter = galleryEl.querySelector('.vssl-stripe--gallery--current')
const slideEls = galleryEl.querySelectorAll('.vssl-stripe--gallery--slide')

let slideIndex = 0
const slideCount = slideEls.length
function moveGallery(next = true) {
    const prevIndex = slideIndex;
    slideIndex = next ? slideIndex + 1 : slideIndex - 1
    if (slideIndex >= slideCount) slideIndex = 0
    else if (slideIndex < 0) slideIndex = slideCount - 1
    slideEls[slideIndex].dataset.active = true
    slideEls[prevIndex].dataset.active = false
    currentCounter.innerHTML = slideIndex + 1
}

nextBtn.addEventListener('click', () => moveGallery(true))
prevBtn.addEventListener('click', () => moveGallery(false))
</script>

<?php endif; ?>
