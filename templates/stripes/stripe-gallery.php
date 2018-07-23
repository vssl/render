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
                        <img src="<?= $this->image($slide['image']) ?>" alt="<?= !empty($slide['alt']['html'])
                            ? htmlspecialchars(strip_tags($slide['alt']['html']), ENT_QUOTES, 'UTF-8')
                            : '' ?>">
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
        </div>
    </div>
</div>
<?php endif; ?>
