<div class="ws-page" data-id="<?= $this->e($id) ?>">
    <?php if ($stripes): ?>
    <div class="ws-stripe-list">
        <?php foreach ($stripes as $stripe): ?>
        <?= $this->insert($themePrefix . 'stripes/' . $stripe['type'], $stripe) ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>