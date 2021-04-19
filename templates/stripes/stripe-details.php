<?php if (!empty($summary['html']) && !empty($content['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <details<?= !empty($open) && $open ? ' open' : '' ?>>
            <summary class="vssl-stripe--details--summary"><?= $summary['html'] ?></summary>
            <div class="vssl-stripe--details--content"><?= $content['html'] ?></div>
        </details>
    </div>
</div>
<?php endif; ?>