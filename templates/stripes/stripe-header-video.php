<header class="vssl-stripe--header <?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($image) ? ' data-has-image="true"' : '';
    echo !empty($videoEmbed) ? ' data-has-video="true"' : '';
    echo !empty($inset) || !empty($bgVideoUrl) ? ' data-has-inset="true"' : '';
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <?php if (!empty($image)) : ?>
    <div class="vssl-stripe--header--background" style="background-image: url('<?= $this->image($image) ?>');"></div>
    <?php endif; ?>
    <div class="vssl-stripe-column<?= !empty($layout) && $layout == 'video-left' ? ' video-left' : '' ?>">
        <?php if (!empty($hed['html'])) : ?>
        <div class=vssl-stripe--header-video--text>
            <h1 class="vssl-stripe--header--hed vssl-stripe--header-video--hed"><?= $this->inline($hed['html']) ?></h1>

            <?php if (!empty($btn) && !empty($btntxt) && !empty($btnurl)) : ?>
            <div class="vssl-stripe--header--button vssl-stripe--header-video--button">
                <a href="<?= $btnurl ?>" class="vssl-button"><?= $btntxt ?></a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($inset) || !empty($bgVideoUrl)) : ?>
        <div class="vssl-stripe--header-video--inset">
            <div class="vssl-stripe--header-video--ui">
                <?php if (!empty($bgVideoUrl)) : ?>
                <video loop autoplay muted playsinline<?php
                    echo !empty($inset) ? ' poster="' . $this->image($inset, $image_style ?? null) . '"' : '';
                ?>>
                    <source src="<?= $bgVideoUrl ?>" type="video/mp4">
                    <?php if (!empty($inset)) : ?>
                    <img src="<?= $this->image($inset, $image_style ?? null) ?>" alt="<?= $inset_alt ?? '' ?>" />
                    <?php endif; ?>
                </video>
                <?php else : ?>
                <img src="<?= $this->image($inset, $image_style ?? null) ?>" alt="<?= $inset_alt ?? '' ?> " />
                <?php endif; ?>

                <?php if (!empty($videoEmbed)) : ?>
                <a class="vssl-stripe--header-video--playbtn-wrap"
                    href="<?= $videoUrl ?? '#video--' . $id ?>"
                    target="_blank">
                    <div class="vssl-stripe--header-video--playbtn">
                        <div class="vssl-stripe--header-video--playicon">
                            <span class="sr-only">Play video</span>
                        </div>
                        <div aria-hidden="true" class="vssl-stripe--header-video--close">Close</div>
                    </div>
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($videoEmbed)) : ?>
            <div id="<?= 'video--' . $id ?>"
                class="vssl-stripe--header-video--embed"
                data-video-embed="<?= htmlspecialchars(json_encode(['code' => $videoEmbed]), ENT_QUOTES) ?>"
            ></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</header>
