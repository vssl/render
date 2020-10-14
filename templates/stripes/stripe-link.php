<?php if (!empty($url['html'])) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <a href="<?= $this->inline($url['html']) ?>" class="vssl-stripe--link">
            <?php if (!empty($image)) : ?>
            <div class="vssl-stripe--link--thumbnail">
                <img
                    src="<?= $this->image($image) ?>"
                    alt="<?=
                    !empty($alt['html'])
                        ? htmlspecialchars(strip_tags($alt['html']), ENT_QUOTES, 'UTF-8')
                        : ''
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
                    <?= parse_url(strip_tags($url['html']), PHP_URL_HOST) ?>
                </p>
            </div>
        </a>
    </div>
</div>
<?php endif; ?>
