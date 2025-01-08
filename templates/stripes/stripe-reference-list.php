<?php if (!empty($referencePages)) : ?>
    <?php
        // Replace everything after the last "/" with "stripe-reference"
        $reference_template = preg_replace(
            "/\/.*$/",
            '/stripe-reference',
            $template_name
        );
    ?>
    <div
        class="vssl-stripe vssl-stripe--reference-list"
        data-stripe-index="<?= $stripe_index ?? 0 ?>"
        data-item-count="<?= count($referencePages) ?? 0 ?>"
        data-paginate="<?= !empty($paginate) && $paginate ?>"
        data-max-items="<?= (!empty($max_items) ? $max_items : null) ?? 100 ?>"
    >
        <?php
            foreach ($referencePages as $referencePage) {
                $reference_vars = array_merge(
                    get_defined_vars(),
                    [
                        'type' => 'stripe-reference',
                        'template_name' => $reference_template,
                        'referencePage' => $referencePage
                    ]
                );
                $this->insert($reference_template, $reference_vars);
            }
        ?>

        <?php if (!empty($paginate) && $paginate) : ?>
        <div class="vssl-stripe--reference-list--pagination">
            <nav aria-label="pagination">
                <ul>
                    <li class="previous">
                        <a href="#">Previous</a>
                    </li>

                    <!-- other items added here via javascript -->

                    <li class="next">
                        <a href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <script>
        (function () {
            const listEl = document.currentScript.parentElement;
            const referenceListItems =  listEl.querySelectorAll('.vssl-stripe--reference')
            const paginationEl = listEl.querySelector('.vssl-stripe--reference-list--pagination')
            const prev = paginationEl.querySelector('.previous a')
            const next = paginationEl.querySelector('.next a')
            const { itemCount, maxItems, stripeIndex } = listEl.dataset
            const pageCount = Math.ceil(itemCount / maxItems)

            const pageLinks = [];
            for (let i = 0; i < pageCount; i++) {
                const item = document.createElement('li')
                const link = document.createElement('a')
                link.href = '#'
                link.innerHTML = i + 1
                item.append(link)
                if (i === 0) item.classList.add('current')
                pageLinks.push(item)
            }
            const pageNumbers = Array(pageLinks.length)
                .fill(0)
                .map((_, i) => i + 1)

            paginationEl.querySelector('.previous').after(...pageLinks)

            prev.addEventListener('click', onClickPrevious)
            next.addEventListener('click', onClickNext)

            for (const linkEl of pageLinks) {
                linkEl.addEventListener('click', onClickNumber)
            }

            function goToPage(page) {
                const url = new URL(window.location)
                const index =
                url.searchParams.set('p' + stripeIndex, page)
                if (url.href !== window.location.href) {
                    window.location = url
                }
            }

            function onClickPrevious(e) {
                e.preventDefault()
                e.stopPropagation()
                const currentEl = paginationEl.querySelector('.current')
                if (+currentEl.textContent > 1) {
                  goToPage(+currentEl.previousSibling.textContent)
                }
            }

            function onClickNext(e) {
                e.preventDefault()
                e.stopPropagation()
                const currentEl = paginationEl.querySelector('.current')
                if (+currentEl.textContent < pageNumbers.length) {
                  goToPage(+currentEl.nextSibling.textContent)
                }
            }

            function onClickNumber(e) {
                e.preventDefault()
                e.stopPropagation()
                const item = e.target.tagName.toLowerCase() === 'li'
                    ? e.target
                    : e.target?.parentElement
                if (item) {
                  goToPage(item.textContent)
                }
            }
        })()
        </script>
        <?php endif; ?>
    </div>
<?php endif;
