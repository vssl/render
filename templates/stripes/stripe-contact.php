<div<?= (!empty($headshot) ? ' data-has-headshot="true"' : '') ?> class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--contact--card vssl-stripe--card">
            <?php if (!empty($headshot)): ?>
            <div class="vssl-stripe--contact--headshot">
                <img
                    src="<?= $this->image($headshot, !empty($style) ? $style : 'headshot') ?>"
                    alt="Contact Headshot"
                />
            </div>
            <?php endif; ?>

            <div class="vssl-stripe--contact--info">
                <?php if (!empty($name['html'])): ?>
                <h3 class="vssl-stripe--contact--name"><?= $this->inline($name['html']) ?></h3>
                <?php endif; ?>

                <?php if (!empty($jobtitle['html'])): ?>
                <div class="vssl-stripe--contact--jobtitle"><?= $this->inline($jobtitle['html']) ?></div>
                <?php endif; ?>

                <?php if (!empty($email['html'])): ?>
                <div class="vssl-stripe--contact--email">
                    <a href="mailto:<?= strip_tags($email['html']) ?>"><?= strip_tags($email['html']) ?></a>
                </div>
                <?php endif; ?>

                <?php if (!empty($phone['html'])): ?>
                <div class="vssl-stripe--contact--phone"><?= $this->inline($phone['html']) ?></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($btn) && !empty($btntxt) && !empty($btnurl)): ?>
                <div class="vssl-stripe--contact--button">
                    <a href="<?= $btnurl ?>" class="vssl-button"><?= $btntxt ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
