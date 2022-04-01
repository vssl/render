<?php
$isEmpty = array_reduce($values, function ($arePreviousEmpty, $value) {
  return $arePreviousEmpty && is_null($value);
}, true);
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
                <?= $this->inline($value['html'], '<a><b><strong><i><em><address>') ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>