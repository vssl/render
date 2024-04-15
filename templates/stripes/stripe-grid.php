<?php if (count($items)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--grid--wrap" data-item-count="<?= count($items) ?>">
            <div class="vssl-stripe--grid--items">
                <?php foreach ($items as $idx => $item) : ?>
                <div class="vssl-stripe--grid-item">
                    <?php if (!empty($item['image'])) : ?>
                    <div class="vssl-stripe--grid-item--image">
                        <img
                            src="<?= $this->image($item['image'], $image_style ?? null) ?>"
                            alt="<?= !empty($item['alt']['html'])
                                ? htmlspecialchars(strip_tags($item['alt']['html']), ENT_QUOTES, 'UTF-8')
                                : '' ?>"
                        />
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item['subhed']['html']) || !empty($item['desc']['html']) || count($items) > 1) : ?>
                    <div class="vssl-stripe--grid-item--text">
                        <?php if (!empty($item['subhed']['html'])) : ?>
                        <h4 class="vssl-stripe--grid-item--subhed">
                            <?php if (!empty($item['url'])) : ?>
                            <a tabindex="-1" href="<?= $item['url'] ?>"><?= $this->inline($item['subhed']['html']) ?></a>
                            <?php else : ?>
                            <?= $this->inline($item['subhed']['html']) ?>
                            <?php endif; ?>
                        </h4>
                        <?php endif; ?>

                        <?php if (!empty($item['desc']['html'])) : ?>
                        <div class="vssl-stripe--grid-item--desc">
                            <?= $this->inline($item['desc']['html']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item['url'])) : ?>
                    <div class="vssl-stripe--grid-item--button">
                        <a
                            class="vssl-button"
                            href="<?= $item['url'] ?>"
                        ><?= $item['btntxt'] ?? 'Learn More' ?></a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
