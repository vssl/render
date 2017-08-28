<?php if (!empty($value['html'])) : ?>
  <div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--label--value" data-label="<?= strip_tags($value['html']) ?>"><?= $this->inline($value['html']) ?></div>
    </div>
  </div>
<?php endif; ?>
