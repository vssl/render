<?php $tag = in_array($heading_tag ?? '', ['h1', 'h2', 'h3'], true) ? $heading_tag : 'h2'; ?>

<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <hr />

        <?php if (!empty($heading['html'])) : ?>
        <<?= $tag ?>
            <?php if (!empty($heading_id)) : ?>
            id="<?= $heading_id ?>"
            <?php endif; ?>
            class="vssl-stripe--break--heading"
            data-heading="<?= strip_tags($heading['html']) ?>"
        >
            <?= $this->inline($heading['html'])?>
        </<?= $tag ?>>
        <?php endif; ?>
    </div>
</div>
