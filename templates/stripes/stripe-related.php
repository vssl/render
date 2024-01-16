<?php
$links = array_values(array_filter($links, function ($link) {
    return !empty($link['page_id']);
}));
?>
<?php if (count($links)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
    <div class="vssl-stripe-column">
        <?php if (!empty($relatedLabel['html'])) : ?>
        <h2 class="vssl-stripe--related--label"><?= $relatedLabel['html'] ?></h2>
        <?php endif;?>
        <div class="vssl-stripe--related--links">
            <?php foreach ($links as $idx => $link) : ?>
                <div class="vssl-stripe--related--link">
                    <a href="<?= $link['page']['slug'] ?>" target="_blank">
                        <?php if (!empty($link['page']['image'])) : ?>
                        <img
                            class="vssl-stripe--related--thumbnail"
                            src="<?= $this->image($link['page']['image'], !empty($image_style) ? $image_style : null) ?>"
                            alt="<?= $link['page']['image'] ?>"
                            loading="lazy"
                        />
                        <?php endif; ?>

                        <div class="vssl-stripe--related--text">
                            <div class="vssl-stripe--related--page-info">
                                <h3 class="vssl-stripe--related--title"><?= $link['page']['title'] ?></h3>
                                <p class="vssl-stripe--related--description"><?= $link['page']['summary'] ?></p>
                            </div>
                            <p class="vssl-stripe--related--url"><?= $_SERVER['SERVER_NAME'] . $link['page']['slug'] ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
