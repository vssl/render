
<div class="vssl-stripe-column">
  <div class="vssl-stripe--reference--link">
    <a href="<?= $referencePage['slug'] ?>" target="_blank">
      <?php if (!empty($referencePage['image'])) : ?>
        <img
          class="vssl-stripe--reference--thumbnail"
          src="<?= $this->image($referencePage['image'], !empty($image_style) ? $image_style : null) ?>"
          alt="<?= $referencePage['image'] ?>"
          loading="lazy"
        />
      <?php endif; ?>

      <div class="vssl-stripe--reference--text">
        <div class="vssl-stripe--reference--page-info">
          <h3 class="vssl-stripe--reference--title">
            <?= $referencePage['title'] ?>
          </h3>
          <p class="vssl-stripe--reference--description">
            <?= $referencePage['summary'] ?>
          </p>
        </div>
        <p class="vssl-stripe--reference--url">
          <?= $_SERVER['SERVER_NAME'] . $referencePage['slug'] ?>
        </p>
      </div>
    </a>
  </div>
</div>
