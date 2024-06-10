<header
    <?= (!empty($image) ? ' data-has-image="true"' : '') ?>
    <?= (!empty($videoEmbed) ? ' data-has-video="true"' : '') ?>
    <?= (!empty($inset) || !empty($bgVideoUrl) ? ' data-has-inset="true"' : '') ?>
    class="vssl-stripe--header <?= $this->e($type, 'wrapperClasses') ?>"
>
    <?php if (!empty($image)) : ?>
    <div class="vssl-stripe--header--background" style="background-image: url('<?= $this->image($image) ?>');"></div>
    <?php endif; ?>
    <div class="vssl-stripe-column<?= (!empty($layout) && $layout == 'video-left') ? ' video-left' : '' ?>">
        <?php if (!empty($hed['html'])) : ?>
        <div class=vssl-stripe--header-video--text>
            <h1 class="vssl-stripe--header--hed vssl-stripe--header-video--hed"><?= $this->inline($hed['html']) ?></h1>

            <?php if (!empty($btn) && !empty($btntxt) && !empty($btnurl)): ?>
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
                <video loop autoplay muted playsinline
                    <?= !empty($inset) ? 'poster="' . $this->image($inset, $image_style ?? null) . '"' : '' ?>
                >
                    <source src="<?= $bgVideoUrl ?>" type="video/mp4">
                    <?php if (!empty($inset)) : ?>
                    <img src="<?= $this->image($inset, $image_style ?? null) ?>" alt="">
                    <?php endif; ?>
                </video>
                <?php else : ?>
                <img src="<?= $this->image($inset, $image_style ?? null) ?>" alt="">
                <?php endif; ?>

                <?php if (!empty($videoEmbed)) : ?>
                <a class="vssl-stripe--header-video--playbtn-wrap" href="<?= $videoUrl ?? '#video--' . $id ?>" target="_blank">
                    <div class="vssl-stripe--header-video--playbtn">
                        <div class="vssl-stripe--header-video--playicon">
                            <span class="sr-only">Play video</span>
                        </div>
                        <div aria-hidden="true" class="vssl-stripe--header-video--close">
                            Close
                        </div>
                    </div>
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($videoEmbed)) : ?>
            <div
                id="<?= 'video--' . $id ?>"
                class="vssl-stripe--header-video--embed"
                data-video-embed="<?= htmlspecialchars(json_encode(['code' => $videoEmbed]), ENT_QUOTES) ?>"
            ></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</header>
<script>
const stripeEl = document.currentScript.previousElementSibling
const videoUI = stripeEl.querySelector('.vssl-stripe--header-video--ui')
const bgVideo = videoUI?.querySelector('video')
const playButton = stripeEl.querySelector('a.vssl-stripe--header-video--playbtn-wrap')
const embedWrap = stripeEl.querySelector('.vssl-stripe--header-video--embed')

let embed = null;
let isEmbedLoaded = false;

function toggle() {
    if (isEmbedLoaded) close()
    else open()
    isEmbedLoaded = !isEmbedLoaded
}

function close() {
    stripeEl.removeAttribute('data-is-video-showing')
    embed?.parentElement?.removeChild(embed)
    bgVideo?.pause()
}

function open() {
    stripeEl.setAttribute('data-is-video-showing', true)
    embedWrap?.appendChild(embed)
    bgVideo?.play()
}

if (playButton) {
    const embedDataJSON = embedWrap.getAttribute('data-video-embed')
    const embedData = JSON.parse(embedDataJSON || 'null')

    if (embedData?.code) {
        const tempDiv = document.createElement('div')
        tempDiv.innerHTML = embedData.code
        embed = tempDiv.firstChild
    }

    const embedSrc = embed.getAttribute('src')
    const hasAutoplay = /^(.*)autoplay=1(.*)$/.test(embedSrc)
    const hasParameters = /^(.*)(\?)(.+)$/.test(embedSrc)
    if (!hasAutoplay) {
        const prefix = hasParameters ? '&' : '?'
        embed.setAttribute('src', embedSrc + prefix + 'autoplay=1')
    }

    playButton.addEventListener('click', function(e) {
        e.preventDefault()
        this.blur()
        toggle()
    })
}
</script>
