<?php if (!empty($embed)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--videoembed--video"><?= $embed ?></div>
        
        <?php if (!empty($transcript['html'])) : ?>
        <div class="vssl-stripe--videoembed--transcript">
            <details class="vssl-stripe--videoembed--transcript--details">
                <summary class="vssl-stripe--videoembed--transcript--summary">
                    Read transcript
                </summary>
                <div class="vssl-stripe--videoembed--transcript--contents">
                    <?= $transcript['html'] ?>
                </div>
            </details>
        </div>
        <?php endif ?>

        <?php if (!empty($caption['html']) || !empty($credit['html'])) : ?>
        <div class="vssl-stripe--videoembed--meta">
            <?php if (!empty($caption['html'])) : ?>
            <div class="vssl-stripe--videoembed--caption"><?= $this->inline($caption['html']) ?></div>
            <?php endif; ?>
            <?php if (!empty($credit['html'])) : ?>
            <div class="vssl-stripe--videoembed--credit"><?= $this->inline($credit['html']) ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif;
