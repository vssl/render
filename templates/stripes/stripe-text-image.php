<?php if (!empty($image)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column<?= ($layout == 'image-left') ? ' image-left' : '' ?>">
    <div class="vssl-stripe--text-image--text"><?= $text['html'] ?></div>
    <div class="vssl-stripe--text-image--image">
        <img src="<?= $this->image($image) ?>" alt="<?= $image ?>">
    </div>
  </div>
</div>
<?php endif; ?>
