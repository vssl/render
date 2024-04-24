<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <?php if (count($menu_links)) : ?>
    <ul>
      <?php foreach($menu_links as $link) : ?>
        <?php if (!empty($link['url']) && !empty($link['title'])) : ?>
        <li>
          <a href="<?= $link['url'] ?>">
            <?= $link['title'] ?>
          </a>
        </li>
        <?php endif ?>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
