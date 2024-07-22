<?php if (!empty($author['html']) || !empty($date['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--byline-fieldwrap">
            <?php if (!empty($author['html'])) : ?>
            <div class="vssl-stripe--byline-field vssl-stripe--byline--author">
                <div class="vssl-stripe--byline-label">Written by:</div>
                <div class="vssl-stripe--byline-value"><?= $this->inline($author['html']) ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($date['html'])) : ?>
            <div class="vssl-stripe--byline-field vssl-stripe--byline--date">
                <div class="vssl-stripe--byline-label">Published:</div>
                <div class="vssl-stripe--byline-value"><?= $this->inline($date['html']) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif;
