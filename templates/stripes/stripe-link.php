<?php if (!empty($url['html']) || (!empty($url) && is_string($url))) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <a
            href="<?=
                !empty($url['html'])
                    ? $this->inline($url['html'])
                    : (!empty($url) && is_string($url) ? $url : '')
            ?>"
            class="vssl-stripe--link--card"
        >
            <?php if (!empty($image)) : ?>
            <div class="vssl-stripe--link--thumbnail">
                <img
                    src="<?= $this->image($image) ?>"
                    alt="<?=
                        !empty($alt['html'])
                            ? htmlspecialchars(strip_tags($alt['html']), ENT_QUOTES, 'UTF-8')
                            : (!empty($alt) && is_string($alt) ? $alt : '')
                    ?>"
                >
            </div>
            <?php endif; ?>

            <div class="vssl-stripe--link--text">
                <div class="vssl-stripe--link--info">
                    <?php if (!empty($title['html'])) : ?>
                    <h3 class="vssl-stripe--link--title">
                        <?= $this->inline($title['html']) ?>
                    </h3>
                    <?php endif; ?>

                    <?php if (!empty($description['html'])) : ?>
                    <p class="vssl-stripe--link--description">
                        <?= $this->inline($description['html']) ?>
                    </p>
                    <?php endif; ?>
                </div>

                <p class="vssl-stripe--link--url">
                    <?= parse_url(
                        !empty($url) && is_string($url) ? $url : strip_tags($url['html']),
                        PHP_URL_HOST
                    ) ?>
                </p>
            </div>
        </a>
    </div>
</div>
<?php endif; ?>
