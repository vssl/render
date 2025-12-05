<?php if (!empty($tableData)) :
  $rowCount = $rowCount ?? count($tableData);
  $firstRow = $tableData[0] ?? [];
  $columnCount = $columnCount ?? count($firstRow);

  $tableData = array_slice($tableData, 0, $rowCount);
  $tableData = array_map(function($row) use ($columnCount) {
    return array_slice($row, 0, $columnCount);
  }, $tableData);

  // Sort and filter configuration
  $sortMethods = $sortMethods ?? [];
  $filterMethods = $filterMethods ?? [];

  $dataAttributes = [
    'rowCount' => $rowCount,
    'columnCount' => $columnCount,
    'captionPosition' => !empty($captionPosition) && $captionPosition === 'above' ? $captionPosition : 'below',
    'hasAlternatingRows' => !empty($hasAlternatingRows) && $hasAlternatingRows ? 'true' : 'false',
    'hasHeadersInFirstRow' => !empty($hasHeadersInFirstRow) && $hasHeadersInFirstRow ? 'true' : 'false',
    'hasHeadersInFirstColumn' => !empty($hasHeadersInFirstColumn) && $hasHeadersInFirstColumn ? 'true' : 'false',
    'sortMethods' => !empty($sortMethods) ? json_encode($sortMethods) : '[]',
    'filterMethods' => !empty($filterMethods) ? json_encode($filterMethods) : '[]',
  ];
?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo " data-row-count=\"{$dataAttributes['rowCount']}\"";
    echo " data-column-count=\"{$dataAttributes['columnCount']}\"";
    echo " data-caption-position=\"{$dataAttributes['captionPosition']}\"";
    echo " data-has-alternating-rows=\"{$dataAttributes['hasAlternatingRows']}\"";
    echo " data-has-headers-in-first-row=\"{$dataAttributes['hasHeadersInFirstRow']}\"";
    echo " data-has-headers-in-first-column=\"{$dataAttributes['hasHeadersInFirstColumn']}\"";
    echo " data-sort-methods=\"" . htmlspecialchars($dataAttributes['sortMethods'], ENT_QUOTES) . "\"";
    echo " data-filter-methods=\"" . htmlspecialchars($dataAttributes['filterMethods'], ENT_QUOTES) . "\"";
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
  <div class="vssl-stripe-column">
    <?php
      // Render filter inputs for columns with filtering enabled
      $hasFilters = false;
      if (!empty($filterMethods)) {
        foreach ($filterMethods as $colIndex => $filterMethod) {
          if ($filterMethod === 'enabled') {
            $hasFilters = true;
            break;
          }
        }
      }
    ?>
    <?php if ($hasFilters) : ?>
      <div class="vssl-stripe--table--filters">
        <?php foreach ($tableData[0] as $colIndex => $headerCell) : ?>
          <?php
            $filterEnabled = !empty($filterMethods[$colIndex]) && $filterMethods[$colIndex] === 'enabled';
          ?>
          <?php if ($filterEnabled) : ?>
            <div class="vssl-stripe--table--filter" data-column-index="<?= $colIndex ?>">
              <label for="filter-col-<?= $colIndex ?>">
                Filter by <?= strip_tags($headerCell['text']) ?>
              </label>
              <select class="vssl-stripe--table--filter-select" id="filter-col-<?= $colIndex ?>" data-column="<?= $colIndex ?>">
                <option value="">-- Select a filter --</option>
              </select>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <table>
      <?php if (!empty($caption)) : ?>
        <caption class="vssl-stripe--table--caption">
          <?= !empty($caption['html']) ? $this->inline($caption['html']) : $caption ?>
        </caption>
      <?php endif; ?>

      <?php if ($hasHeadersInFirstRow && !$hasHeadersInFirstColumn) : ?>
        <thead>
          <tr>
            <?php foreach ($tableData[0] as $colIndex => $item) : ?>
              <?php
                $sortMethod = !empty($sortMethods[$colIndex]) && $sortMethods[$colIndex] !== 'disabled' && $sortMethods[$colIndex] !== '' ? $sortMethods[$colIndex] : false;
              ?>
              <th colspan="<?= $item['colspan'] ?? 1 ?>" <?= $sortMethod ? 'class="sortable"' : '' ?> data-column-index="<?= $colIndex ?>" <?= $sortMethod ? 'data-sort-method="' . $sortMethod . '"' : '' ?>><?= $item['text'] ?><?php if ($sortMethod) : ?>
                 <button type="button" class="vssl-stripe--table--sort-button" aria-label="Sort by <?= htmlspecialchars(strip_tags($item['text']), ENT_QUOTES) ?>">
                   <span class="vssl-icon vssl-stripe--table--sort-icon" aria-hidden="true">&updownarrow;</span>
                 </button>
                 <?php endif; ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
      <?php endif; ?>

      <tbody>
        <?php if ($hasHeadersInFirstRow && $hasHeadersInFirstColumn) : ?>
          <tr>
            <?php foreach ($tableData[0] as $colIndex => $item) : ?>
              <?php
                $sortMethod = !empty($sortMethods[$colIndex]) && $sortMethods[$colIndex] !== 'disabled' && $sortMethods[$colIndex] !== '' ? $sortMethods[$colIndex] : false;
              ?>
              <th colspan="<?= $item['colspan'] ?? 1 ?>" <?= $sortMethod ? 'class="sortable"' : '' ?> data-column-index="<?= $colIndex ?>" <?= $sortMethod ? 'data-sort-method="' . $sortMethod . '"' : '' ?>><?= $item['text'] ?><?php if ($sortMethod) : ?> <button type="button" class="vssl-stripe--table--sort-button" aria-label="Sort by <?= htmlspecialchars(strip_tags($item['text']), ENT_QUOTES) ?>"><span class="vssl-icon vssl-stripe--table--sort-icon" aria-hidden="true">&updownarrow;</span></button><?php endif; ?></th>
            <?php endforeach; ?>
          </tr>
        <?php endif; ?>

        <?php
          $rows = $hasHeadersInFirstRow ? array_slice($tableData, 1) : $tableData;
          foreach ($rows as $rowIndex => $row) :
        ?>
          <tr data-row-index="<?= $rowIndex ?>">
            <?php foreach ($row as $colIndex => $item) : ?>
              <?php $tag = ($colIndex === 0 && $hasHeadersInFirstColumn) || ($item['isAdditionalHeader']) ? 'th' : 'td'; ?>
              <<?= $tag ?> colspan="<?= $item['colspan'] ?? 1 ?>" data-column-index="<?= $colIndex ?>" data-value="<?= htmlspecialchars(strip_tags($item['text']), ENT_QUOTES) ?>">
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
