<?php if (!empty($tableData)) :
  $dataAttributes = [
    'captionPosition' => !empty($captionPosition) && $captionPosition === 'above' ? $captionPosition : 'below',
    'hasAlternatingRows' => !empty($hasAlternatingRows) && $hasAlternatingRows ? 'true' : 'false',
    'hasHeadersInFirstRow' => !empty($hasHeadersInFirstRow) && $hasHeadersInFirstRow ? 'true' : 'false',
    'hasHeadersInFirstColumn' => !empty($hasHeadersInFirstColumn) && $hasHeadersInFirstColumn ? 'true' : 'false',
  ];
?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo " data-caption-position=\"{$dataAttributes['captionPosition']}\"";
    echo " data-has-alternating-rows=\"{$dataAttributes['hasAlternatingRows']}\"";
    echo " data-has-headers-in-first-row=\"{$dataAttributes['hasHeadersInFirstRow']}\"";
    echo " data-has-headers-in-first-column=\"{$dataAttributes['hasHeadersInFirstColumn']}\"";
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
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
            <?php foreach ($tableData[0] as $item) : ?>
              <th colspan="<?= $item['colspan'] ?? 1 ?>"><?= $item['text'] ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
      <?php endif; ?>

      <tbody>
        <?php if ($hasHeadersInFirstRow && $hasHeadersInFirstColumn) : ?>
          <tr>
            <?php foreach ($tableData[0] as $item) : ?>
              <th colspan="<?= $item['colspan'] ?? 1 ?>"><?= $item['text'] ?></th>
            <?php endforeach; ?>
          </tr>
        <?php endif; ?>

        <?php
          $rows = $hasHeadersInFirstRow ? array_slice($tableData, 1) : $tableData;
          foreach ($rows as $row) :
        ?>
          <tr>
            <?php foreach ($row as $index => $item) : ?>
              <?php $tag = ($index === 0 && $hasHeadersInFirstColumn) || ($item['isAdditionalHeader']) ? 'th' : 'td'; ?>
              <<?= $tag ?> colspan="<?= $item['colspan'] ?? 1 ?>">
                <?= $item['text'] ?>
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
