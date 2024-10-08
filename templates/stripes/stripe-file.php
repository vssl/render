<?php if (!empty($file)) :
    $fileExtension = !empty($file['file']) ? strtoupper(pathinfo($file['file'], PATHINFO_EXTENSION)) : null;
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
                </div>
            </div>

            <div class="vssl-stripe--file--download">
                <a class="vssl-button" href="<?= $this->file($file) ?>">Download File</a>
            </div>
        </div>
    </div>
</div>
<?php endif;
