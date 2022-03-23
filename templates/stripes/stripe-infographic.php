<?php if (!empty($image)) : ?>
<div
    class="<?= $this->e($type, 'wrapperClasses') ?>"
    <?= !isset($is_enlargeable) || $is_enlargeable ? 'data-is-enlargeable="true"' : '' ?>
>
    <div class="vssl-stripe-column">
        <?php if (!empty($title['html'])) : ?>
        <div class="vssl-stripe--infographic--title"><?= $this->inline($title['html']) ?></div>
        <?php endif; ?>

        <div class="vssl-stripe--infographic--image">
            <img src="<?= $this->image($image, !empty($image_style) ? $image_style : null) ?>" alt="<?= $image ?>" />
        </div>
        <?php if (!empty($caption['html']) || !empty($credit['html'])) : ?>
        <div class="vssl-stripe--infographic--meta">
            <?php if (!empty($caption['html'])) : ?>
            <div class="vssl-stripe--infographic--caption"><?= $this->inline($caption['html']) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($credit['html'])) : ?>
            <div class="vssl-stripe--infographic--credit"><?= $this->inline($credit['html']) ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!isset($is_enlargeable) || $is_enlargeable): ?>
        <div class="vssl-stripe--infographic--actions">
            <button type="button" class="vssl-stripe--infographic--enlarge">Enlarge</button>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
