<?php
$isEmpty = empty($values) || array_reduce($values, function ($arePreviousEmpty, $value) {
  return $arePreviousEmpty && empty($value);
}, true);

if (!$isEmpty) : ?>
  <div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
      <?php foreach ($attribute_list as $field) :
        $key = $field['key'];
        $value = $values[$key] ?? null;
        $valueText = !empty($value['html']) ? $this->inline($value['html']) : null;
        $valueText = !empty($valueText) ? str_replace('&nbsp;', '', $valueText) : null;
        $valueText = !empty($valueText) ? trim($valueText) : null;

        if (!empty($value) && !empty($valueText)) :
        ?>
        <div class="vssl-stripe--attributes-fieldwrap">
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
              <?php
              if (!empty($field['template'])) {
                echo str_replace(
                    '{value}',
                    $this->inline($value['html'], $field['allowed_tags'] ?? '<a><b><strong><i><em><p>'),
                    $field['template']
                );
              } else {
                echo $this->inline($value['html'], $field['allowed_tags'] ?? '<a><b><strong><i><em><p>');
              }
              ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif;
