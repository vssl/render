<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <hr />

        <?php if (!empty($heading['html'])) : ?>
        <<?= !empty($heading_tag) ? $heading_tag : 'h2' ?>
            class="vssl-stripe--break--heading"
            data-heading="<?= strip_tags($heading['html']) ?>"
        ><?=
            $this->inline($heading['html'])
        ?></<?= !empty($heading_tag) ? $heading_tag : 'h2' ?>>
        <?php endif; ?>
    </div>
</div>
