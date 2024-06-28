<?php if (!empty($dataset)) : ?>
<div
  class="<?= $this->e($type, 'wrapperClasses') ?>"
  data-caption-position="<?= $captionPosition === 'above' ? 'above' : 'below' ?>"
  data-has-alternating-rows="<?= $hasAlternatingRows ? 'true' : 'false' ?>"
  data-has-headers-in-first-row="<?= $hasHeadersInFirstRow ? 'true' : 'false' ?>"
  data-has-headers-in-first-column="<?= $hasHeadersInFirstColumn ? 'true' : 'false' ?>"
>
  <div class="vssl-stripe-column">
    <table>
      <?php if (!empty($caption)) : ?>
        <caption class="vssl-stripe--table--caption">
          <?= !empty($caption['html']) ? $this->inline($caption['html']) : $caption ?>
        </caption>
      <?php endif; ?>

      <?php if ($hasHeadersInFirstRow && !$hasHeadersInFirstColumn) : ?>
        <thead>
          <tr>
            <?php foreach ($dataset[0] as $item) : ?>
              <th><?= $item ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
      <?php endif; ?>

      <tbody>
        <?php if ($hasHeadersInFirstRow && $hasHeadersInFirstColumn) : ?>
          <tr>
            <?php foreach ($dataset[0] as $item) : ?>
              <th><?= $item ?></th>
            <?php endforeach; ?>
          </tr>
        <?php endif; ?>

        <?php
          $rows = $hasHeadersInFirstRow ? array_slice($dataset, 1) : $dataset;
          foreach ($rows as $row) :
        ?>
          <tr>
            <?php foreach ($row as $index => $item) : ?>
              <?php $tag = ($index === 0 && $hasHeadersInFirstColumn) ? 'th' : 'td'; ?>
              <<?= $tag ?>>
                <?= $item ?>
              </<?= $tag ?>>
            <?php endforeach; ?>
          </tr>
        <?php
          endforeach;
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif;
