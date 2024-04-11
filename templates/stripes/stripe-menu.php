<?php $menu = !empty($menu_id) ? $this->getMenuLinks($menu_id) : [] ?>

<?php if (!empty($menu)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <?php if (count($menu['links'])) : ?>
    <ul>
      <?php foreach($menu['links'] as $key => $link) : ?>
      <li>
        <a href="<?= $link['link'] ?>">
          <?= $link['title'] ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
