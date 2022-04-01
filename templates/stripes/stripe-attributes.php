<?php
$isEmpty = !empty($values) ? array_reduce($values, function ($arePreviousEmpty, $value) {
  return $arePreviousEmpty && empty($value);
}, true) : true;
?>

<?php if (!$isEmpty) : ?>
  <div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
      <div class="vssl-stripe--attributes--fieldwrap">
        <?php foreach ($values as $key => $value) : ?>
          <?php if (!empty($value['html'])) : ?>
            <div class="vssl-stripe--attributes--field vssl-stripe--attributes--field--<?= $key ?>">
              <div class="vssl-stripe--attributes--label"><?= $key ?>:</div>
              <div class="vssl-stripe--attributes--value">
                <?= $this->inline($value['html'], '<a><b><strong><i><em><p>') ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
