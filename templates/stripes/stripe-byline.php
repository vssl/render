<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($author['html'])) : ?>
        <div class="vssl-stripe--byline--author-container">
            <p class="vssl-stripe--byline--admin-label">Written by:</p>
            <p class="vssl-stripe--byline--author"><?= $this->inline($author['html']) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($date['html'])) : ?>
        <div class="vssl-stripe--byline--date-container">
            <p class="vssl-stripe--byline--admin-label">Published:</p>
            <p class="vssl-stripe--byline--date"><?= $this->inline($date['html']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
