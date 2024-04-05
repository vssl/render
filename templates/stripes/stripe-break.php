<?php $tag = !empty($heading_tag) ? $heading_tag : 'h2' ?>

<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <hr />

        <?php if (!empty($heading['html'])) : ?>
        <<?= $tag ?>
            id="<?= $heading_id ?>"
            class="vssl-stripe--break--heading"
            data-heading="<?= strip_tags($heading['html']) ?>"
        >
            <?= $this->inline($heading['html'])?>
        </<?= $tag ?>>
        <?php endif; ?>
    </div>
</div>
