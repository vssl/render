<?php if (count($items)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--grid--wrap" data-item-count="<?= count($items) ?>">
            <div class="vssl-stripe--grid--items">
                <?php foreach ($items as $item) : ?>
                <div <?= empty($item['image']) ? 'data-no-image="true"' : '' ?>
                    class="vssl-stripe--grid-item">
                    <?php if (!empty($item['image'])) : ?>
                    <div class="vssl-stripe--grid-item--image">
                        <img src="<?= $this->image($item['image'], $image_style ?? null) ?>"
                            alt="<?= $item['image_alt'] ?? '' ?>" />
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item['subhed']['html']) ||
                        !empty($item['desc']['html']) ||
                        count($items) > 1
                    ) : ?>
                    <div class="vssl-stripe--grid-item--text">
                        <?php if (!empty($item["subhed"]["html"])) : ?>
                        <h4 class="vssl-stripe--grid-item--subhed"><?= $this->inline($item["subhed"]["html"]) ?></h4>
                        <?php endif; ?>

                        <?php if (!empty($item['desc']['html'])) : ?>
                        <div class="vssl-stripe--grid-item--desc"><?= $this->inline($item['desc']['html']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item['url'])) : ?>
                    <div class="vssl-stripe--grid-item--button">
                        <a class="vssl-button" href="<?= $item['url'] ?>"><?= $item["btntxt"] ?? "Learn More" ?></a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif;
