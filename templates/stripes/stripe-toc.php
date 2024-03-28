<?php $toc = $this->tableOfContents() ?>

<?php if (!empty($toc)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--card">
      <div class="vssl-stripe--toc--title">Table of Contents</div>
      <?php foreach ($toc as $i => $item) : ?>

        <?php if ($i === 0 || $toc[$i - 1]['level'] < $item['level']) : ?>
        <ul>
        <?php endif ?>

          <li><?= $item['text'] ?></li>

        <?php if ($i === count($toc) - 1 || $toc[$i + 1]['level'] < $item['level']) : ?>
        </ul>
        <?php endif ?>

        <?php endforeach ?>
    </div>
  </div>
</div>
<?php endif ?>
