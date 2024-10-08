<?php if (!empty($first['html'])
    || !empty($second['html'])
    || (!empty($btn) && !empty($btntxt) && !empty($btnurl))
) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--cta--card vssl-stripe--card">
            <div class="vssl-stripe--cta--text">
                <?php if (!empty($first['html'])) : ?>
                <p class="vssl-stripe--cta--first"><?= $this->inline($first['html']) ?></p>
                <?php endif; ?>

                <?php if (!empty($second['html'])) : ?>
                <p class="vssl-stripe--cta--second"><?= $this->inline($second['html']) ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($btn) && !empty($btntxt) && !empty($btnurl)) : ?>
            <div class="vssl-stripe--cta--button">
                <a href="<?= $btnurl ?>" class="vssl-button"><?= $btntxt ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif;
