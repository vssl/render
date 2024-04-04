<?php $toc = $this->getTableOfContents($scope) ?>

<?php if (!empty($toc)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <div class="vssl-stripe--card">
      <div class="vssl-stripe--toc--title">
        <?= $this->inline(empty($title['html']) ? 'Table of Contents' : $title['html']) ?>
      </div>

      <?php foreach ($toc as $i => $item) : ?>

        <?php if ($i === 0 || $toc[$i - 1]['level'] < $item['level']) : ?>
        <ul>
        <?php endif ?>

          <li>
            <a href="#<?= $item['id'] ?>">
              <?= $item['headingText'] ?>
            </a>
          </li>

        <?php if ($i === count($toc) - 1 || $toc[$i + 1]['level'] < $item['level']) : ?>
        </ul>
        <?php endif ?>

      <?php endforeach ?>
    </div>
  </div>
</div>
<?php endif ?>
