<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($author['html'])) : ?>
        <p class="vssl-stripe--byline--admin-label">Written by:</p>
        <p class="vssl-stripe--byline--author"><?= $this->inline($author['html']) ?></p>
        <?php endif; ?>

        <?php if (!empty($date['html'])) : ?>
        <p class="vssl-stripe--byline--admin-label">Published:</p>
        <p class="vssl-stripe--byline--date"><?= $this->inline($date['html']) ?></p>
        <?php endif; ?>
    </div>
</div>
