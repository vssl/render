<div class="vssl-page" data-id="<?= $this->e($id) ?>" data-type="<?= $this->e($type) ?>">
    <?php if ($stripes) : ?>
    <div class="vssl-stripes">
        <?php foreach ($stripes as $index => $stripe) : ?>
            <?php $stripe['stripe_index'] = $index; ?>
            <?= $this->insert($themePrefix . 'stripes/' . $stripe['type'], $stripe) ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
