<?php
if (!function_exists('vsslStripeMenuLinkList')) {
    function vsslStripeMenuLinkList($links, $depth = 1)
    {
        $list = '<ul data-depth="' . $depth . '">';
        foreach ($links as $link) {
            if (!empty($link['link']) && (!empty($link['title']) || !empty($link['page_title']))) {
                $list .= '<li class="vssl-stripe--menu--listitem" data-depth="' . $depth . '">'
                    . '<a href="' . $link['link'] . '" class="vssl-stripe--menu--link" data-depth="' . $depth . '">'
                    . '<span class="vssl-stripe--menu--link--text">' . ($link['title'] ?? $link['page_title']) . '</span>'
                    . '</a>'
                    . (!empty($link['links_nested']) ? vsslStripeMenuLinkList($link['links_nested'], $depth + 1) : '')
                    . '</li>';
            }
        }
        $list .= '</ul>';
        return $list;
    }
}

if (!empty($menu_links)) : ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
    echo !isset($collapsible) || $collapsible ? " data-collapsible=\"true\"" : '';
    echo !isset($convert_first_level_link_to_button) || $convert_first_level_link_to_button
        ? " data-with-first-level-links-as-buttons=\"true\""
        : '';
?>>
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
