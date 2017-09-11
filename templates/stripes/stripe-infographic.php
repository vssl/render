<?php if (!empty($image)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($title['html'])) : ?>
        <div class="vssl-stripe--infographic--title"><?= $this->inline($title['html']) ?></div>
        <?php endif; ?>

        <div class="vssl-stripe--infographic--image">
            <img src="<?= $this->image($image) ?>" alt="<?= $image ?>">
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
    </div>
</div>
<?php endif; ?>
