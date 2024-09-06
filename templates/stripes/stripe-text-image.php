<?php if (!empty($image)): ?>
<div class="<?= $this->e($type, "wrapperClasses") ?>"<?php echo !empty(
  $variation
)
  ? " data-variation=\"{$variation}\""
  : ""; ?>>
    <div class="vssl-stripe-column<?= $layout == "image-left"
      ? " image-left"
      : "" ?>">
        <div class="vssl-stripe--text-image--text"><?= $text["html"] ?></div>
        <div class="vssl-stripe--text-image--image">
            <img
              src="<?= $this->image(
                $image,
                !empty($image_style) ? $image_style : null
              ) ?>"
              alt="<?= $image_alt ?? $image ?>"
              loading="lazy"
            />
        </div>
    </div>
</div>
<?php endif;
