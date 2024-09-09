<?php if (!empty($url["html"]) || (!empty($url) && is_string($url))): ?>
<div class="<?= $this->e($type, "wrapperClasses") ?>"<?php echo !empty(
  $variation
)
  ? " data-variation=\"{$variation}\""
  : ""; ?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--link--card vssl-stripe--card">
            <?php if (!empty($image)): ?>
            <div class="vssl-stripe--link--thumbnail">
                <a href="<?= !empty($url["html"])
                  ? $this->inline($url["html"])
                  : (!empty($url) && is_string($url)
                    ? $url
                    : "") ?>" tabindex="-1">
                    <img src="<?= $this->image(
                      $image,
                      !empty($image_style) ? $image_style : null
                    ) ?>"
                      alt="<?= $image_alt ?? "" ?>"
                      loading="lazy"
                    />
                </a>
            </div>
            <?php endif; ?>

            <div class="vssl-stripe--link--text">
                <div class="vssl-stripe--link--info">
                    <?php if (!empty($title["html"])): ?>
                    <h3 class="vssl-stripe--link--title">
                        <a href="<?= !empty($url["html"])
                          ? $this->inline($url["html"])
                          : (!empty($url) && is_string($url)
                            ? $url
                            : "") ?>"><?= $this->inline($title["html"]) ?></a>
                    </h3>
                    <?php endif; ?>

                    <?php if (!empty($description["html"])): ?>
                    <p class="vssl-stripe--link--description">
                        <?= $this->inline($description["html"]) ?>
                    </p>
                    <?php endif; ?>
                </div>

                <div class="vssl-stripe--link--url">
                    <?= parse_url(
                      !empty($url) && is_string($url)
                        ? $url
                        : strip_tags($url["html"]),
                      PHP_URL_HOST
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif;
