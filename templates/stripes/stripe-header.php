<header<?= (!empty($image) ? ' data-has-image="true"' : '') ?> class="<?= $this->e($type, 'wrapperClasses') ?>">
    <?php if (!empty($image)): ?>
    <div 
        class="vssl-stripe--header--background"
        style="background-image: url('<?= $this->image($image, !empty($style) ? $style : null) ?>');"
    ></div>
    <?php endif; ?>

    <div class="vssl-stripe-column">
        <?php if (!empty($label['html'])) : ?>
        <div
            class="vssl-stripe--header--label"
            data-label="<?= strip_tags($label['html']) ?>"
        ><?= $this->inline($label['html']) ?></div>
        <?php endif; ?>

        <?php if (!empty($hed['html'])) : ?>
        <h1 class="vssl-stripe--header--hed"><?= $this->inline($hed['html']) ?></h1>
        <?php endif; ?>

        <?php if (!empty($dek['html'])) : ?>
        <div class="vssl-stripe--header--dek"><?= $this->inline($dek['html']) ?></div>
        <?php endif; ?>
    </div>
</header>
