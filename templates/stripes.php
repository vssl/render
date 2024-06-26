<?php if ($stripes) : ?>
    <?php foreach ($stripes as $index => $stripe) : ?>
        <?php $stripe['stripe_index'] = $index; ?>
        <?php $stripe['template_name'] = $themePrefix . 'stripes/' . $stripe['type'] ?>
        <?= $this->insert($stripe['template_name'], $stripe) ?>
    <?php endforeach; ?>
<?php endif; ?>
