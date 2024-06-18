<?php
$isEmpty = empty($values) || array_reduce($values, function ($arePreviousEmpty, $value) {
  return $arePreviousEmpty && empty($value);
}, true);
?>

<?php if (!$isEmpty) : ?>
  <div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
      <?php foreach ($values as $key => $value) : ?>
        <div class="vssl-stripe--attributes-fieldwrap">
          <?php if (!empty($value['html'])) : ?>
            <div class="vssl-stripe--attributes--field vssl-stripe--attributes--field--<?= $key ?>">
              <?php
              $attributeDefinition = array_reduce(
                $attribute_list,
                fn ($found, $def) => $key === $def['key'] ? $def : $found,
                null
              );
              ?>
              <div class="vssl-stripe--attributes--label">
                <?= $attributeDefinition['label'] ?? ucfirst($key) ?>
              </div>
              <div class="vssl-stripe--attributes--value">
                <?= $this->inline($value['html'], '<a><b><strong><i><em><p>') ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>