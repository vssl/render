<div class="vssl-page" data-id="<?= $this->e($id) ?>">
    <?php if ($stripes) : ?>
    <div class="vssl-stripes">
        <?php foreach ($stripes as $stripe) : ?>
        <?= $this->insert($themePrefix . 'stripes/' . $stripe['type'], $stripe) ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
