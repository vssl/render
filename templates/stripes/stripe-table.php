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
      if (!empty($filterMethods) && $hasHeadersInFirstRow && !empty($tableData[0])) {
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
                <option value="">All</option>
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
  <script>
    (function() {
      const wrapper = document.currentScript.previousElementSibling.parentElement;
      const table = wrapper.querySelector('table');
      const tbody = table.querySelector('tbody');
      const sortMethods = JSON.parse(wrapper.dataset.sortMethods || '[]');
      const filterMethods = JSON.parse(wrapper.dataset.filterMethods || '[]');

      let currentSort = { column: null, direction: null }; // null, 'asc', or 'desc'
      let activeFilters = {};

      // Extract sort type from compound sort method (e.g., 'alpha-asc' -> 'alpha')
      function getSortType(sortMethod) {
        return (sortMethod && sortMethod !== 'disabled')
          ? sortMethod.split('-')[0]
          : 'alpha';
      }

      // Extract direction from compound sort method (e.g., 'alpha-asc' -> 'asc')
      function getSortDirection(sortMethod) {
        if (!sortMethod || sortMethod === 'disabled') return 'asc';
        const parts = sortMethod.split('-');
        return parts[1] || 'asc';
      }

      // Compare two values using the specified sort method
      function compareValues(valueA, valueB, sortMethod) {
        const sortType = getSortType(sortMethod);

        if (sortType === 'alpha') {
          return valueA.toLowerCase().localeCompare(valueB.toLowerCase());
        } else if (sortType === 'numeric') {
          const numA = parseFloat(valueA) || 0;
          const numB = parseFloat(valueB) || 0;
          return numA - numB;
        } else if (sortType === 'date') {
          const dateA = new Date(valueA).getTime() || 0;
          const dateB = new Date(valueB).getTime() || 0;
          return dateA - dateB;
        }
        return 0;
      }

      // Initialize filter dropdowns with unique values
      function initializeFilters() {
        const filterSelects = wrapper.querySelectorAll('.vssl-stripe--table--filter-select');
        filterSelects.forEach(select => {
          const columnIndex = parseInt(select.dataset.column);
          const uniqueValues = new Set();

          // Collect unique values from all rows in this column (exclude header rows)
          const allRows = tbody.querySelectorAll('tr');
          const rows = Array.from(allRows).filter(row => row.querySelector('td'));
          rows.forEach(row => {
            const cell = row.querySelector(`[data-column-index="${columnIndex}"]`);
            if (cell) {
              const value = cell.dataset.value || cell.textContent.trim();
              if (value) {
                uniqueValues.add(value);
              }
            }
          });

          // Populate the select with options
          // Use the column's sort method if defined, otherwise default to alpha-asc
          const sortMethod = sortMethods[columnIndex] && sortMethods[columnIndex] !== 'disabled'
            ? sortMethods[columnIndex]
            : 'alpha-asc';

          const direction = getSortDirection(sortMethod);

          const sortedValues = Array.from(uniqueValues).sort((a, b) => {
            const comparison = compareValues(a, b, sortMethod);
            return direction === 'desc' ? -comparison : comparison;
          });

          sortedValues.forEach(value => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            select.appendChild(option);
          });

          // Add change event listener
          select.addEventListener('change', (e) => {
            handleFilter(columnIndex, e.target.value);
          });
        });
      }

      // Handle sorting
      function handleSort(columnIndex, sortMethod) {
        const defaultDirection = getSortDirection(sortMethod);

        // Toggle sort direction: null -> default -> opposite -> null
        if (currentSort.column === columnIndex) {
          if (currentSort.direction === defaultDirection) {
            currentSort.direction = defaultDirection === 'asc' ? 'desc' : 'asc';
          } else {
            currentSort.direction = null;
            currentSort.column = null;
          }
        } else {
          currentSort.column = columnIndex;
          currentSort.direction = defaultDirection;
        }

        // Update sort button icons
        updateSortIcons();

        // Apply sorting
        if (currentSort.direction) {
          sortTable(columnIndex, sortMethod, currentSort.direction);
        } else {
          // Reset to original order
          resetTableOrder();
        }
      }

      // Update sort icon display
      function updateSortIcons() {
        const sortableHeaders = wrapper.querySelectorAll('th.sortable');
        sortableHeaders.forEach(header => {
          const column = parseInt(header.dataset.columnIndex);
          const icon = header.querySelector('.vssl-stripe--table--sort-icon');

          if (!icon) {
            return;
          }
          if (column === currentSort.column) {
            if (currentSort.direction === 'asc') {
              icon.innerHTML = '&uarr;';
            } else if (currentSort.direction === 'desc') {
              icon.innerHTML = '&darr;';
            }
          } else {
            icon.innerHTML = '&updownarrow;';
          }
        });
      }

      // Sort the table
      function sortTable(columnIndex, sortMethod, direction) {
        // Get all rows, but filter out header rows (rows that only contain th elements)
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        const rows = allRows.filter(row => row.querySelector('td'));

        rows.sort((rowA, rowB) => {
          const cellA = rowA.querySelector(`[data-column-index="${columnIndex}"]`);
          const cellB = rowB.querySelector(`[data-column-index="${columnIndex}"]`);

          if (!cellA || !cellB) return 0;

          const valueA = cellA.dataset.value || cellA.textContent.trim();
          const valueB = cellB.dataset.value || cellB.textContent.trim();

          const comparison = compareValues(valueA, valueB, sortMethod);

          return direction === 'desc' ? -comparison : comparison;
        });

        // Re-append rows in sorted order
        rows.forEach(row => tbody.appendChild(row));
      }

      // Reset table to original order
      function resetTableOrder() {
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        const rows = allRows.filter(row => row.querySelector('td'));
        rows.sort((a, b) => {
          const indexA = parseInt(a.dataset.rowIndex);
          const indexB = parseInt(b.dataset.rowIndex);
          return indexA - indexB;
        });
        rows.forEach(row => tbody.appendChild(row));
      }

      // Handle filtering
      function handleFilter(columnIndex, filterValue) {
        if (filterValue === '') {
          delete activeFilters[columnIndex];
        } else {
          activeFilters[columnIndex] = filterValue;
        }

        applyFilters();
      }

      // Apply all active filters
      function applyFilters() {
        const allRows = tbody.querySelectorAll('tr');
        const rows = Array.from(allRows).filter(row => row.querySelector('td'));

        rows.forEach(row => {
          let shouldShow = true;

          // Check each active filter
          for (const [columnIndex, filterValue] of Object.entries(activeFilters)) {
            const cell = row.querySelector(`[data-column-index="${columnIndex}"]`);
            if (cell) {
              const cellValue = cell.dataset.value || cell.textContent.trim();
              if (cellValue !== filterValue) {
                shouldShow = false;
                break;
              }
            }
          }

          row.style.display = shouldShow ? '' : 'none';
        });
      }

      // Attach event listeners to sort buttons
      const sortButtons = wrapper.querySelectorAll('.vssl-stripe--table--sort-button');
      sortButtons.forEach(button => {
        const header = button.closest('th');

        button.addEventListener('click', (e) => {
          const columnIndex = parseInt(header.dataset.columnIndex);
          const sortMethod = header.dataset.sortMethod;
          handleSort(columnIndex, sortMethod);
        });
      });

      // Initialize filters
      if (filterMethods.some(method => method === 'enabled')) {
        initializeFilters();
      }
    })();
  </script>
</div>
<?php endif;
