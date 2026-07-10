<?php if (!empty($file)) : ?>
<?php
$fileExtension = !empty($file['file'])
    ? strtoupper(pathinfo($file['file'], PATHINFO_EXTENSION))
    : null;

$downloadText = !empty($link_text) ? $link_text : 'Download File';

$downloadLabel = !empty($link_text)
    ? $link_text
    : (!empty($filename['html'])
        ? 'Download ' . strip_tags($filename['html'])
        : 'Download File');

$downloadAriaLabel = htmlspecialchars($downloadLabel, ENT_QUOTES);

$fileUrl = !empty($file['file']) ? $this->file($file) : null;
$fileUrlParts = $fileUrl ? parse_url($fileUrl) : null;

$fileProtocol = !empty($fileUrlParts['scheme']) ? $fileUrlParts['scheme'] . '://' : null;
$fileDomain = $fileUrlParts['host'] ?? null;
$filePath = $fileUrlParts['path'] ?? null;
?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
    <div class="vssl-stripe-column">
        <div class="vssl-stripe--file--card vssl-stripe--card">
            <div class="vssl-stripe--file--info">
                <div class="vssl-stripe--file--icon"></div>

                <div class="vssl-stripe--file--text">
                    <?php if (!empty($fileExtension)) : ?>
                    <div class="vssl-stripe--file--extension">
                        <?= $fileExtension ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($filename['html'])) : ?>
                    <div class="vssl-stripe--file--filename">
                        <p><?= $this->inline($filename['html']) ?></p>
                    </div>
                    <?php else : ?>
                    <div class="vssl-stripe--file--filename">
                        <p>Attachment</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($description['html'])) : ?>
                    <div class="vssl-stripe--file--description">
                        <p><?= $this->inline($description['html']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($fileUrl)) : ?>
                    <div class="vssl-stripe--file--url">
                        <a
                            href="<?= $this->e($fileUrl) ?>"
                            <?php if (!empty($openInNewTab)) : ?>
                            target="_blank"
                            rel="noopener noreferrer"
                            <?php endif; ?>
                        >
                            <?php if (!empty($fileProtocol)) : ?>
                            <span class="vssl-stripe--file--path--protocol"><?= $this->e($fileProtocol) ?></span><?php
                            endif;
                            if (!empty($fileDomain)) :
                            ?><span class="vssl-stripe--file--path--domain"><?= $this->e($fileDomain) ?></span><?php
                            endif;
                            if (!empty($filePath)) :
                            ?><span class="vssl-stripe--file--path--path"><?= $this->e($filePath) ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="vssl-stripe--file--download">
                <a
                    class="vssl-button"
                    href="<?= $this->file($file) ?>"
                    aria-label="<?= $downloadAriaLabel ?>"
                    <?php if (!empty($openInNewTab)) : ?>
                    target="_blank"
                    rel="noopener noreferrer"
                    <?php endif; ?>
                ><?= $this->e($downloadText) ?></a>
            </div>
        </div>
    </div>
</div>
<?php endif;
