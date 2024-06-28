<?php if (!empty($toc)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <nav class="vssl-stripe--toc--nav">
      <h4 class="vssl-stripe--toc--title">
        <?= $this->inline(empty($title['html']) ? 'Table of Contents' : $title['html']) ?>
      </h4>

      <?php foreach ($toc as $i => $item) :
        if ($i === 0 || $toc[$i - 1]['level'] < $item['level']) {
            echo '<ul>';
        }
        ?>
          <li>
            <a href="#<?= empty($item['id']) ? '' : $item['id'] ?>">
              <?= $item['headingText'] ?? '' ?>
            </a>
          </li>
        <?php
        if ($i === count($toc) - 1 || $toc[$i + 1]['level'] < $item['level']) {
            echo '</ul>';
        }
      endforeach; ?>
    </nav>
  </div>
</div>
<?php endif;
