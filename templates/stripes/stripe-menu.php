<?php
// The "collapsed" variation renders the menu as a disclosure toggled by the menu
// label. A collapsed menu with a hidden label skips the disclosure and always
// shows its links.
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

if (!empty($menu_links)) :
    $isCollapsed = ($variation ?? null) === 'collapsed'
        && !empty($menu_label)
        && !empty($menu_show_label);
    ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
    echo !isset($collapsible) || $collapsible ? " data-collapsible=\"true\"" : '';
    echo !isset($convert_first_level_link_to_button) || $convert_first_level_link_to_button
        ? " data-with-first-level-links-as-buttons=\"true\""
        : '';
?>>
    <div class="vssl-stripe-column">
        <?php if ($isCollapsed) : ?>
        <details class="vssl-stripe--menu--disclosure">
            <summary class="vssl-stripe--menu--summary">
                <span class="vssl-stripe--menu--summary-text"><?= $this->e($menu_label) ?></span>
                <span class="vssl-stripe--menu--expand-icon" aria-hidden="true"></span>
            </summary>
            <nav aria-label="<?= $this->e($menu_label) ?>">
                <?= vsslStripeMenuLinkList($menu_links) ?>
            </nav>
        </details>
        <?php else : ?>
        <nav>
            <?php if (!empty($menu_label) && !empty($menu_show_label) && $menu_show_label) :
                $tag = in_array($heading_tag ?? '', ['h1', 'h2', 'h3'], true) ? $heading_tag : 'h2'; ?>
            <<?= $tag ?> class="vssl-stripe--menu--hed"><?= $menu_label ?></<?= $tag ?>>
            <?php endif; ?>
            <?= vsslStripeMenuLinkList($menu_links) ?>
        </nav>
        <?php endif; ?>
    </div>
</div>
<?php endif;
