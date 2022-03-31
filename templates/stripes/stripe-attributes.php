<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--attributes-fieldwrap">
      <?php foreach ($values as $key => $value) : ?>
        <div class="vssl-stripe--attributes-field vssl-stripe--attributes--<?= $key ?>">
          <div class="vssl-stripe--attributes-label"><?= $key ?>:</div>
          <div class="vssl-stripe--attributes-value">
            <?= $value['html'] ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>