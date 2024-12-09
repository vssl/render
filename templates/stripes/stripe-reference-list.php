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
            const { itemCount, maxItems } = listEl.dataset
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

            updateUI()

            function updateUI() {
                const currentEl = paginationEl.querySelector('.current')
                const currentPageNumber = +currentEl.textContent

                let visiblePageNumbers
                if (pageLinks.length > 5) {
                    if (currentPageNumber <= 3) {
                        visiblePageNumbers = pageNumbers.slice(0, 5)
                    } else if (currentPageNumber >= pageLinks.length - 2) {
                        visiblePageNumbers = pageNumbers.slice(-5)
                    } else {
                        visiblePageNumbers = pageNumbers.slice(
                            currentPageNumber - 3,
                            currentPageNumber + 2
                        )
                    }
                }

                for (const link of pageLinks) {
                    const show = visiblePageNumbers.includes(+link.textContent)
                    link.style = 'display: ' + (show ? 'inline-block' : 'none')
                }

                for (const page of pageNumbers) {
                    for (let i = 0; i < +maxItems; i++) {
                        const index = i + (page - 1) * +maxItems;
                        const item = referenceListItems[index]
                        if (!item) continue
                        const show = currentPageNumber === page
                        item.style = show ? 'display: initial' : 'display: none'
                    }
                }
            }

            function onClickPrevious(e) {
                e.preventDefault()
                e.stopPropagation()
                const currentEl = paginationEl.querySelector('.current')
                if (+currentEl.textContent > 1) {
                    currentEl.classList.remove('current')
                    currentEl.previousSibling.classList.add('current')
                }
                updateUI()
            }

            function onClickNext(e) {
                e.preventDefault()
                e.stopPropagation()
                const currentEl = paginationEl.querySelector('.current')
                if (+currentEl.textContent < pageNumbers.length) {
                    currentEl.classList.remove('current')
                    currentEl.nextSibling.classList.add('current')
                }
                updateUI()
            }

            function onClickNumber(e) {
                e.preventDefault()
                e.stopPropagation()

                const item = e.target.tagName.toLowerCase() === 'li'
                    ? e.target
                    : e.target?.parentElement
                if (!item) return;

                const currentEl = paginationEl.querySelector('.current')
                currentEl?.classList.remove('current')
                item.classList.add('current')
                updateUI()
            }
        })()
        </script>
        <?php endif; ?>
    </div>
<?php endif;
