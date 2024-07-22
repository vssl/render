<?php if (!empty($summary['html']) && !empty($content['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <details<?= !empty($open) && $open ? ' open' : '' ?>>
            <summary class="vssl-stripe--details--summary">
                <div class="vssl-stripe--details--hed"><?= $summary['html'] ?></div>
                <?php if (!empty($subtitle['html'])) : ?>
                <div class="vssl-stripe--details--subtitle"><?= $subtitle['html'] ?></div>
                <?php endif; ?>
            </summary>
            <div class="vssl-stripe--details--content"><?= $content['html'] ?></div>
        </details>
    </div>
</div>
<?php endif;
