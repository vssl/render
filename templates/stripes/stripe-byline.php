<?php if (!empty($author['html']) || !empty($date['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--byline-fieldwrap">
            <?php if (!empty($author['html'])) : ?>
            <div class="vssl-stripe--byline-field vssl-stripe--byline--author">
                <p class="vssl-stripe--byline-label">Written by:</p>
                <p class="vssl-stripe--byline-value"><?= $this->inline($author['html']) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($date['html'])) : ?>
            <div class="vssl-stripe--byline-field vssl-stripe--byline--date">
                <p class="vssl-stripe--byline-label">Published:</p>
                <p class="vssl-stripe--byline-value"><?= $this->inline($date['html']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
