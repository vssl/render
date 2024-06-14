
<div class="vssl-stripe-column">
  <div class="vssl-stripe--related--link">
    <a href="<?= $referencePage['slug'] ?>" target="_blank">
      <?php if (!empty($referencePage['image'])) : ?>
        <img
          class="vssl-stripe--related--thumbnail"
          src="<?= $this->image($referencePage['image'], !empty($image_style) ? $image_style : null) ?>"
          alt="<?= $referencePage['image'] ?>"
          loading="lazy"
        />
      <?php endif; ?>

      <div class="vssl-stripe--related--text">
        <div class="vssl-stripe--related--page-info">
          <h3 class="vssl-stripe--related--title">
            <?= $referencePage['title'] ?>
          </h3>
          <p class="vssl-stripe--related--description">
            <?= $referencePage['summary'] ?>
          </p>
        </div>
        <p class="vssl-stripe--related--url">
          <?= $_SERVER['SERVER_NAME'] . $referencePage['slug'] ?>
        </p>
      </div>
    </a>
  </div>
</div>
