<div class="vssl-stripe-column">
  <table>
    <?php if (!empty($caption)): ?>
      <caption><?= htmlspecialchars($caption) ?></caption>
    <?php endif; ?>

    <?php if ($headersInFirstRow && !$headersInFirstColumn): ?>
      <thead>
        <?php foreach ($dataset[0] as $index => $item): ?>
          <th><?= $item ?></th>
        <?php endforeach; ?>
      </thead>
    <?php endif; ?>

    <tbody>
      <?php if ($headersInFirstRow && $headersInFirstColumn): ?>
        <tr>
          <?php foreach ($dataset[0] as $index => $item): ?>
            <th><?= $item ?></th>
          <?php endforeach; ?>
        </tr>
      <?php endif; ?>

      <?php
        $rows = $headersInFirstRow ? array_slice($dataset, 1) : $dataset;
        foreach ($rows as $rowIndex => $row):
      ?>
        <tr>
          <?php foreach ($row as $index => $item): ?>
            <?php $tag = ($index === 0 && $headersInFirstColumn) ? 'th' : 'td'; ?>
            <<?= $tag ?>>
              <?= $item ?>
            </<?= $tag ?>>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
