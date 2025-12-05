<?php if (!empty($reference_pages)) :
    // Replace everything after the last "/" with "stripe-reference"
    $referenceTemplate = preg_replace(
        "/\/.*$/",
        '/stripe-reference',
        $template_name
    );

    $pagination_param = 'p' . ($stripe_index ?? 0);

    $total_items = $reference_pages_total ?? 0;
    $items_per_page = (!empty($max_items) ? $max_items : null) ?? 100;
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = $_REQUEST[$pagination_param] ?? 1;

    $stripeIdAttr = !empty($stripe_index) ? 's' . $this->e($stripe_index) : '';
    ?>
    <div
        id="<?= $stripeIdAttr ?>"
        class="vssl-stripe vssl-stripe--reference-list"
        data-stripe-index="<?= $stripe_index ?? 0 ?>"
        data-item-count="<?= $total_items ?>"
        data-paginate="<?= !empty($paginate) && $paginate ?>"
        data-max-items="<?= (!empty($max_items) ? $max_items : null) ?? 100 ?>"
    >
        <div class="vssl-stripe--reference-list--items">
            <?php foreach ($reference_pages as $reference_page) : ?>
            <div class="vssl-stripe--reference-list--item vssl-stripe--reference"
                data-type="<?= !empty($reference_page['type']) ? $reference_page['type'] : '' ?>">
            <?php
                $referenceTypeTemplate = $referenceTemplate . '--' . ($reference_page['type'] ?? 'default');
                $reference_vars = array_merge(
                    get_defined_vars(),
                    [
                        'type' => 'stripe-reference',
                        'template_name' => $referenceTemplate,
                        'reference_page' => $reference_page
                    ]
                );
                if ($this->engine->exists("$referenceTypeTemplate")) {
                    $this->insert($referenceTypeTemplate, $reference_vars);
                } else {
                    $this->insert($referenceTemplate . '--default', $reference_vars);
                }
            ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($paginate) && $paginate && $total_pages > 1) : ?>
        <div class="vssl-stripe--reference-list--pagination">
            <nav class="vssl-stripe-column" aria-label="Pagination">
                <ul>
                    <li class="previous">
                        <?php if ($current_page <= 1) : ?>
                        <span>Previous</span>
                        <?php else : ?>
                        <a href="?<?=
                        htmlspecialchars(
                            http_build_query(
                                array_merge($_GET, [$pagination_param => max(1, $current_page - 1)])
                            )
                        ) . '#' . $stripeIdAttr
                        ?>">Previous</a>
                        <?php endif; ?>
                    </li>

                    <?php for ($i = 0; $i < $total_pages; $i++) : ?>
                    <li<?= ($i + 1) == $current_page ? ' class="current" aria-current="page"' : '' ?>>
                        <a href="?<?=
                        htmlspecialchars(
                            http_build_query(
                                array_merge($_GET, [$pagination_param => $i + 1])
                            )
                        ) . '#' . $stripeIdAttr
                        ?>"><?= $i + 1 ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="next">
                        <?php if ($current_page >= $total_pages) : ?>
                        <span>Next</span>
                        <?php else : ?>
                        <a href="?<?=
                        htmlspecialchars(
                            http_build_query(
                                array_merge($_GET, [$pagination_param => min($total_pages, $current_page + 1)])
                            )
                        ) . '#' . $stripeIdAttr
                        ?>">Next</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
<?php endif;
