<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <?php if (count($menu_links)) : ?>
    <ul>
      <?php foreach($menu_links as $link) : ?>
      <li>
        <a href="<?= !empty($link['url']) ? $link['url'] : '#' ?>">
          <?= $link['title'] ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
