<?php
function vsslStripeMenuLinkList($links, $depth = 1)
{
    $list = '<ul data-depth="' . $depth . '">';
    foreach ($links as $link) {
        if (!empty($link['link']) && (!empty($link['title']) || !empty($link['page_title']))) {
            $list .= '<li class="vssl-stripe--menu--listitem" data-depth="' . $depth . '">'
                . '<a href="' . $link['link'] . '" class="vssl-stripe--menu--link" data-depth="' . $depth . '">'
                . ($link['title'] ?? $link['page_title'])
                . '</a>'
                . (!empty($link['links_nested']) ? vsslStripeMenuLinkList($link['links_nested'], $depth + 1) : '')
                . '</li>';
        }
    }
    $list .= '</ul>';
    return $list;
}

if (count($menu_links)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <div class="vssl-stripe-column">
    <nav>
      <?php if (!empty($menu_label) && !empty($menu_show_label) && $menu_show_label) : ?>
      <h2 class="vssl-stripe--menu--title"><?= $menu_label ?></h2>
      <?php endif; ?>
      <?= vsslStripeMenuLinkList($menu_links) ?>
    </nav>
  </div>
</div>
<?php endif;
