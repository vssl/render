<div class="vssl-page" data-id="<?= $this->e($id) ?>" data-type="<?= $this->e($type) ?>">
    <?php if ($stripes) : ?>
    <div class="vssl-stripes">
        <?php foreach ($stripes as $index => $stripe) : ?>
            <?php $stripe['stripe_index'] = $index; ?>
            <?php $stripe['template_name'] = $themePrefix . 'stripes/' . $stripe['type'] ?>
            <?= $this->insert($stripe['template_name'], $stripe) ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
