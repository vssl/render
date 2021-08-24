<?php if (!empty($summary['html']) && !empty($content['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <details<?= !empty($open) && $open ? ' open' : '' ?>>
            <summary class="vssl-stripe--details--summary">
                <h1 class="vssl-stripe--details--hed"><?= $summary['html'] ?></h1>

                <?php if (!empty($subtitle['html'])) : ?>
                <div class="vssl-stripe--details--subtitle"><?= $subtitle['html'] ?></div>
                <?php endif; ?>
            </summary>
            <div class="vssl-stripe--details--content"><?= $content['html'] ?></div>
        </details>
    </div>
</div>
<?php endif; ?>