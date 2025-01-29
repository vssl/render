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

if (count($menu_links)) : ?>
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

    <script>
    (function () {
        const menuEl = document.currentScript.parentElement

        // Collapsible menu
        if (menuEl.getAttribute('data-collapsible')) {
            const menuElIndex = Array.from(menuEl.parentElement.children).indexOf(menuEl)
            const firstLevel = menuEl.querySelectorAll('.vssl-stripe--menu--listitem[data-depth="1"]')

            if (firstLevel && firstLevel.length) {
                firstLevel.forEach((item, index) => {
                    const nested = item.querySelector('ul[data-depth="2"]')

                    if (nested) {
                        const linkNumbers = `${menuElIndex}-${index}`
                        nested.id = `vssl-stripe-menu-${linkNumbers}`
                        nested.setAttribute('hidden', 'true')

                        let ariaLabel = 'menu'
                        let link = item.querySelector('.vssl-stripe--menu--link[data-depth="1"]')

                        // Set up button (whether or not it replaces link).
                        let button = document.createElement('button')
                        button.classList.add('vssl-stripe--menu--button')
                        button.setAttribute('aria-expanded', 'false')
                        button.setAttribute('aria-controls', `vssl-stripe-menu-${linkNumbers}`)

                        // Convert link to button.
                        if (menuEl.getAttribute('data-with-first-level-links-as-buttons')) {
                            button.classList.add('vssl-stripe--menu--link')
                            button.appendChild(link.querySelector('.vssl-stripe--menu--link--text'))
                            button.setAttribute('data-depth', '1')
                            link.replaceWith(button)
                            link = button
                            ariaLabel += ' for ' + link.innerText
                        } else {
                            button.classList.add('vssl-stripe--menu--expand-button')
                            link.parentNode.insertBefore(button, link.nextSibling);
                        }

                        button.setAttribute('aria-label', `Expand ${ariaLabel}`)

                        // Add arrow to link/button.
                        const icon = document.createElement('span')
                        icon.classList.add('vssl-stripe--menu--expand-icon')
                        icon.setAttribute('aria-hidden', 'true')
                        button.appendChild(icon)

                        // Add click event to open/close nested menu.
                        button.addEventListener('click', (e) => {
                            e.preventDefault()
                            if (nested.getAttribute('hidden')) {
                                nested.removeAttribute('hidden')
                                button.setAttribute('aria-label', `Collapse ${ariaLabel}`)
                                button.setAttribute('aria-expanded', 'true')
                                window.dispatchEvent(new CustomEvent('vssl-stripe-menu-open'))
                            } else {
                                nested.setAttribute('hidden', 'true')
                                button.setAttribute('aria-label', `Expand ${ariaLabel}`)
                                button.setAttribute('aria-expanded', 'false')
                                window.dispatchEvent(new CustomEvent('vssl-stripe-menu-close'))
                            }
                            window.dispatchEvent(new CustomEvent('vssl-stripe-menu-resize'))
                        })
                    }
                })

                document.addEventListener('DOMContentLoaded', function() {
                    window.dispatchEvent(new CustomEvent('vssl-stripe-menu-resize'))
                })
            }
        }
    })()
    </script>
</div>
<?php endif;
