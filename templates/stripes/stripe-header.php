<header<?= (!empty($image) ? ' data-has-image="true"' : '') ?> class="<?= $this->e($type, 'wrapperClasses') ?>"
    style="<?= (!empty($image)) ? 'background-image: url(' . $this->image($image) . ');' : ''?> background-size: cover; background-position: center;">
    <div class="vssl-stripe-column">
        <?php if (!empty($hed['html'])) : ?>
        <h1 class="vssl-stripe--header--hed"><?= $this->inline($hed['html']) ?></h1>
        <?php endif; ?>

        <?php if (!empty($dek['html'])) : ?>
        <div class="vssl-stripe--header--dek"><?= $this->inline($dek['html']) ?></div>
        <?php endif; ?>
    </div>
</header>
