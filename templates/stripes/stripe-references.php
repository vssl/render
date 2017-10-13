<?php
$links = array_values(array_filter($links, function ($link) {
        return !empty($link['page_id']);
}));
?>
<?php if (count($links)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($label['html'])) : ?>
        <h2 class="vssl-stripe--references--label"><?= $label['html'] ?></h2>
        <?php endif;?>
        <div class="vssl-stripe--references--links">
            <?php foreach ($links as $idx => $link) : ?>
                <div class="vssl-stripe--references--link">
                    <a href="<?= $link['page']['slug'] ?>" target="_blank">
                        <?php if (!empty($link['page']['image'])) : ?>
                        <img class="vssl-stripe--references--thumbnail" src="<?= $this->image($link['page']['image']) ?>" alt="<?= $link['page']['image'] ?>">
                        <?php endif; ?>

                        <div class="vssl-stripe--references--text">
                            <div class="vssl-stripe--references--page-info">
                                <h3 class="vssl-stripe--references--title"><?= $link['page']['title'] ?></h3>
                                <p class="vssl-stripe--references--description"><?= $link['page']['summary'] ?></p>
                            </div>
                            <p class="vssl-stripe--references--url"><?= $_SERVER['SERVER_NAME'] . $link['page']['slug'] ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
