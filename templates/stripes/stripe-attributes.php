<?php if (!empty($values)) : ?>
  <div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
      <div class="vssl-stripe--attributes--fieldwrap">
        <?php foreach ($values as $key => $value) : ?>
          <?php if (!empty($value['html']) ? $value['html'] : '') : ?>
          <div class="vssl-stripe--attributes--field vssl-stripe--attributes--field--<?= $key ?>">
            <div class="vssl-stripe--attributes--label"><?= $key ?>:</div>
            <div class="vssl-stripe--attributes--value">
              <?= $value['html'] ?>
            </div>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>