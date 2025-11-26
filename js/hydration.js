/**
 * Vessel Stripe Hydration
 *
 * This file handles hydration of interactive stripes. It initializes
 * stripe functionality on page load and can re-hydrate when new content
 * is loaded via AJAX.
 *
 * Usage:
 * - Automatically runs on DOMContentLoaded
 * - Dispatch "hydrate-vessel-stripes" event on window to re-hydrate: window.dispatchEvent(new CustomEvent("hydrate-vessel-stripes"))
 * - Or call hydrateVesselStripes(container) directly with a specific container element
 */

(function() {
  "use strict";

  // Track hydrated elements to avoid double-initialization
  const hydratedElements = new WeakSet();

  /**
   * Main hydration function - hydrates all stripes in a container
   */
  function hydrateVesselStripes(container = document) {
    hydrateTableStripes(container);
    hydrateMenuStripes(container);
    hydrateInfographicStripes(container);
    hydrateHeaderVideoStripes(container);
    hydrateGooglemapStripes(container);
    hydrateGalleryStripes(container);
  }

  /**
   * Table Stripe - Sorting and Filtering
   */
  function hydrateTableStripes(container) {
    const tables = container.querySelectorAll(".vssl-stripe--table");

    tables.forEach(wrapper => {
      if (hydratedElements.has(wrapper)) return;
      hydratedElements.add(wrapper);

      const table = wrapper.querySelector("table");
      const tbody = table.querySelector("tbody");
      const sortMethods = JSON.parse(wrapper.dataset.sortMethods || "[]");
      const filterMethods = JSON.parse(wrapper.dataset.filterMethods || "[]");

      let currentSort = { column: null, direction: null };
      let activeFilters = {};

      function getSortType(sortMethod) {
        return (sortMethod && sortMethod !== "disabled")
          ? sortMethod.split("-")[0]
          : "alpha";
      }

      function getSortDirection(sortMethod) {
        if (!sortMethod || sortMethod === "disabled") return "asc";
        const parts = sortMethod.split("-");
        return parts[1] || "asc";
      }

      function compareValues(valueA, valueB, sortMethod) {
        const sortType = getSortType(sortMethod);

        if (sortType === "alpha") {
          return valueA.toLowerCase().localeCompare(valueB.toLowerCase());
        } else if (sortType === "numeric") {
          const numA = parseFloat(valueA) || 0;
          const numB = parseFloat(valueB) || 0;
          return numA - numB;
        } else if (sortType === "date") {
          const dateA = new Date(valueA).getTime() || 0;
          const dateB = new Date(valueB).getTime() || 0;
          return dateA - dateB;
        }
        return 0;
      }

      function initializeFilters() {
        const filterSelects = wrapper.querySelectorAll(".vssl-stripe--table--filter-select");
        filterSelects.forEach(select => {
          const columnIndex = parseInt(select.dataset.column);
          const uniqueValues = new Set();

          const allRows = tbody.querySelectorAll("tr");
          const rows = Array.from(allRows).filter(row => row.querySelector("td"));
          rows.forEach(row => {
            const cell = row.querySelector(`[data-column-index="${columnIndex}"]`);
            if (cell) {
              const value = cell.dataset.value || cell.textContent.trim();
              if (value) {
                uniqueValues.add(value);
              }
            }
          });

          const sortMethod = sortMethods[columnIndex] && sortMethods[columnIndex] !== "disabled"
            ? sortMethods[columnIndex]
            : "alpha-asc";

          const direction = getSortDirection(sortMethod);

          const sortedValues = Array.from(uniqueValues).sort((a, b) => {
            const comparison = compareValues(a, b, sortMethod);
            return direction === "desc" ? -comparison : comparison;
          });

          sortedValues.forEach(value => {
            const option = document.createElement("option");
            option.value = value;
            option.textContent = value;
            select.appendChild(option);
          });

          select.addEventListener("change", (e) => {
            handleFilter(columnIndex, e.target.value);
          });
        });
      }

      function handleSort(columnIndex, sortMethod) {
        const defaultDirection = getSortDirection(sortMethod);

        if (currentSort.column === columnIndex) {
          if (currentSort.direction === defaultDirection) {
            currentSort.direction = defaultDirection === "asc" ? "desc" : "asc";
          } else {
            currentSort.direction = null;
            currentSort.column = null;
          }
        } else {
          currentSort.column = columnIndex;
          currentSort.direction = defaultDirection;
        }

        updateSortIcons();

        if (currentSort.direction) {
          sortTable(columnIndex, sortMethod, currentSort.direction);
        } else {
          resetTableOrder();
        }
      }

      function updateSortIcons() {
        const sortableHeaders = wrapper.querySelectorAll("th.sortable");
        sortableHeaders.forEach(header => {
          const column = parseInt(header.dataset.columnIndex);
          const icon = header.querySelector(".vssl-stripe--table--sort-icon");

          if (!icon) return;

          if (column === currentSort.column) {
            if (currentSort.direction === "asc") {
              icon.innerHTML = "&uarr;";
            } else if (currentSort.direction === "desc") {
              icon.innerHTML = "&darr;";
            }
          } else {
            icon.innerHTML = "&updownarrow;";
          }
        });
      }

      function sortTable(columnIndex, sortMethod, direction) {
        const allRows = Array.from(tbody.querySelectorAll("tr"));
        const rows = allRows.filter(row => row.querySelector("td"));

        rows.sort((rowA, rowB) => {
          const cellA = rowA.querySelector(`[data-column-index="${columnIndex}"]`);
          const cellB = rowB.querySelector(`[data-column-index="${columnIndex}"]`);

          if (!cellA || !cellB) return 0;

          const valueA = cellA.dataset.value || cellA.textContent.trim();
          const valueB = cellB.dataset.value || cellB.textContent.trim();

          const comparison = compareValues(valueA, valueB, sortMethod);

          return direction === "desc" ? -comparison : comparison;
        });

        rows.forEach(row => tbody.appendChild(row));
      }

      function resetTableOrder() {
        const allRows = Array.from(tbody.querySelectorAll("tr"));
        const rows = allRows.filter(row => row.querySelector("td"));
        rows.sort((a, b) => {
          const indexA = parseInt(a.dataset.rowIndex);
          const indexB = parseInt(b.dataset.rowIndex);
          return indexA - indexB;
        });
        rows.forEach(row => tbody.appendChild(row));
      }

      function handleFilter(columnIndex, filterValue) {
        if (filterValue === "") {
          delete activeFilters[columnIndex];
        } else {
          activeFilters[columnIndex] = filterValue;
        }

        applyFilters();
      }

      function applyFilters() {
        const allRows = tbody.querySelectorAll("tr");
        const rows = Array.from(allRows).filter(row => row.querySelector("td"));

        rows.forEach(row => {
          let shouldShow = true;

          for (const [columnIndex, filterValue] of Object.entries(activeFilters)) {
            const cell = row.querySelector(`[data-column-index="${columnIndex}"]`);
            if (cell) {
              const cellValue = cell.dataset.value || cell.textContent.trim();
              if (cellValue !== filterValue) {
                shouldShow = false;
                break;
              }
            }
          }

          row.style.display = shouldShow ? "" : "none";
        });
      }

      const sortButtons = wrapper.querySelectorAll(".vssl-stripe--table--sort-button");
      sortButtons.forEach(button => {
        const header = button.closest("th");
        button.addEventListener("click", () => {
          const columnIndex = parseInt(header.dataset.columnIndex);
          const sortMethod = header.dataset.sortMethod;
          handleSort(columnIndex, sortMethod);
        });
      });

      if (filterMethods.some(method => method === "enabled")) {
        initializeFilters();
      }
    });
  }

  /**
   * Menu Stripe - Collapsible Menu
   */
  function hydrateMenuStripes(container) {
    const menus = container.querySelectorAll(".vssl-stripe--menu");

    menus.forEach(menuEl => {
      if (hydratedElements.has(menuEl)) return;
      hydratedElements.add(menuEl);

      if (!menuEl.getAttribute("data-collapsible")) return;

      const menuElIndex = Array.from(menuEl.parentElement.children).indexOf(menuEl);
      const firstLevel = menuEl.querySelectorAll(".vssl-stripe--menu--listitem[data-depth='1']");

      if (firstLevel && firstLevel.length) {
        firstLevel.forEach((item, index) => {
          const nested = item.querySelector("ul[data-depth='2']");

          if (nested) {
            const linkNumbers = `${menuElIndex}-${index}`;
            nested.id = `vssl-stripe-menu-${linkNumbers}`;
            nested.setAttribute("hidden", "true");

            let ariaLabel = "menu";
            let link = item.querySelector(".vssl-stripe--menu--link[data-depth='1']");

            let button = document.createElement("button");
            button.classList.add("vssl-stripe--menu--button");
            button.setAttribute("aria-expanded", "false");
            button.setAttribute("aria-controls", `vssl-stripe-menu-${linkNumbers}`);

            if (menuEl.getAttribute("data-with-first-level-links-as-buttons")) {
              button.classList.add("vssl-stripe--menu--link");
              button.appendChild(link.querySelector(".vssl-stripe--menu--link--text"));
              button.setAttribute("data-depth", "1");
              link.replaceWith(button);
              link = button;
              ariaLabel += " for " + link.innerText;
            } else {
              button.classList.add("vssl-stripe--menu--expand-button");
              link.parentNode.insertBefore(button, link.nextSibling);
            }

            button.setAttribute("aria-label", `Expand ${ariaLabel}`);

            const icon = document.createElement("span");
            icon.classList.add("vssl-stripe--menu--expand-icon");
            icon.setAttribute("aria-hidden", "true");
            button.appendChild(icon);

            button.addEventListener("click", (e) => {
              e.preventDefault();
              if (nested.getAttribute("hidden")) {
                nested.removeAttribute("hidden");
                button.setAttribute("aria-label", `Collapse ${ariaLabel}`);
                button.setAttribute("aria-expanded", "true");
                window.dispatchEvent(new CustomEvent("vssl-stripe-menu-open"));
              } else {
                nested.setAttribute("hidden", "true");
                button.setAttribute("aria-label", `Expand ${ariaLabel}`);
                button.setAttribute("aria-expanded", "false");
                window.dispatchEvent(new CustomEvent("vssl-stripe-menu-close"));
              }
              window.dispatchEvent(new CustomEvent("vssl-stripe-menu-resize"));
            });
          }
        });

        window.dispatchEvent(new CustomEvent("vssl-stripe-menu-resize"));
      }
    });
  }

  /**
   * Infographic Stripe - Image Lightbox
   */
  function hydrateInfographicStripes(container) {
    const infographics = container.querySelectorAll(".vssl-stripe--infographic[data-is-enlargeable='true']");

    infographics.forEach(infographicEl => {
      if (hydratedElements.has(infographicEl)) return;
      hydratedElements.add(infographicEl);

      const enlargener = infographicEl.querySelector(".vssl-stripe--infographic--enlarge");
      const collapser = infographicEl.querySelector(".vssl-stripe--infographic--collapse");

      function onInfographicKeyup(e) {
        if (e.key === "Escape") {
          onCollapse();
        }
      }

      function onEnlarge() {
        infographicEl.setAttribute("data-is-enlarged", true);
        document.documentElement.classList.add("vssl-scroll-lock");
        document.body.addEventListener("keyup", onInfographicKeyup);
      }

      function onCollapse() {
        infographicEl.removeAttribute("data-is-enlarged");
        document.documentElement.classList.remove("vssl-scroll-lock");
        document.body.removeEventListener("keyup", onInfographicKeyup);
      }

      if (enlargener) enlargener.addEventListener("click", onEnlarge);
      if (collapser) collapser.addEventListener("click", onCollapse);
    });
  }

  /**
   * Header Video Stripe - Video Embed Toggle
   */
  function hydrateHeaderVideoStripes(container) {
    const headerVideos = container.querySelectorAll(".vssl-stripe--header[data-has-video='true']");

    headerVideos.forEach(stripeEl => {
      if (hydratedElements.has(stripeEl)) return;
      hydratedElements.add(stripeEl);

      const videoUI = stripeEl.querySelector(".vssl-stripe--header-video--ui");
      const bgVideo = videoUI?.querySelector("video");
      const playButton = stripeEl.querySelector("a.vssl-stripe--header-video--playbtn-wrap");
      const embedWrap = stripeEl.querySelector(".vssl-stripe--header-video--embed");

      if (!playButton || !embedWrap) return;

      let embed = null;
      let isEmbedLoaded = false;

      const embedDataJSON = embedWrap.getAttribute("data-video-embed");
      const embedData = JSON.parse(embedDataJSON || "null");

      if (embedData?.code) {
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = embedData.code;
        embed = tempDiv.firstChild;
      }

      if (embed) {
        const embedSrc = embed.getAttribute("src");
        const hasAutoplay = /^(.*)autoplay=1(.*)$/.test(embedSrc);
        const hasParameters = /^(.*)(\?)(.+)$/.test(embedSrc);
        if (!hasAutoplay) {
          const prefix = hasParameters ? "&" : "?";
          embed.setAttribute("src", embedSrc + prefix + "autoplay=1");
        }
      }

      function toggle() {
        if (isEmbedLoaded) {
          close();
        } else {
          open();
        }
        isEmbedLoaded = !isEmbedLoaded;
      }

      function close() {
        stripeEl.removeAttribute("data-is-video-showing");
        embed?.parentElement?.removeChild(embed);
        bgVideo?.pause();
      }

      function open() {
        stripeEl.setAttribute("data-is-video-showing", true);
        embedWrap?.appendChild(embed);
        bgVideo?.play();
      }

      playButton.addEventListener("click", function(e) {
        e.preventDefault();
        this.blur();
        toggle();
      });
    });
  }

  /**
   * Google Map Stripe - Map Initialization
   */
  function hydrateGooglemapStripes(container) {
    const googlemaps = container.querySelectorAll(".vssl-stripe--googlemap");

    googlemaps.forEach(stripeEl => {
      if (hydratedElements.has(stripeEl)) return;
      hydratedElements.add(stripeEl);

      if (typeof google === "undefined") {
        console.error("Google Maps API is not available.");
        return;
      }

      try {
        initGoogleMap(stripeEl);
      } catch(e) {
        console.error("Failed to initialize google map stripe", e);
      }
    });

    function initGoogleMap(stripeEl) {
      const marker = stripeEl.dataset.marker || null;
      const coordinatesStr = stripeEl.dataset.coordinates || null;
      const address = stripeEl.dataset.address || null;
      const maptype = stripeEl.dataset.maptype || "roadmap";
      const zoom = parseInt(stripeEl.dataset.zoom || "15", 10);
      const stylesJson = stripeEl.dataset.styles || "[]";

      let coordinates = null;
      if (coordinatesStr) {
        try {
          coordinates = JSON.parse(coordinatesStr);
        } catch(e) {
          console.error("Failed to parse coordinates", e);
        }
      }

      const styles = maptype === "roadmap" ? JSON.parse(stylesJson) : [];
      const config = { marker, coordinates, address, maptype, zoom, styles };

      if (coordinates) {
        buildMap(stripeEl, config);
      } else if (address) {
        geocode(stripeEl, config);
      } else {
        console.warn("No coordinates or address set for Google Maps API. Doing nothing.");
      }

      // Add click engagement handler
      const embedEl = stripeEl.querySelector(".vssl-stripe--googlemap--embed");
      if (embedEl) {
        embedEl.addEventListener("click", function() {
          this.classList.add("is-engaged");
        });
      }
    }

    function buildMap(stripeEl, config) {
      const mapWrapEl = stripeEl.querySelector(".vssl-stripe--googlemap--map");
      const customMarker = "https://s3.amazonaws.com/cdn.vssl.io/marker.png";

      const map = new google.maps.Map(mapWrapEl, {
        center: config.coordinates,
        zoom: config.zoom,
        styles: config.styles,
        mapTypeId: config.maptype,
        mapTypeControl: false
      });
      new google.maps.Marker({
        map,
        position: config.coordinates,
        icon: config.marker || customMarker
      });
    }

    function geocode(stripeEl, config) {
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: config.address }, (results, status) => {
        if (status === "OK" && results.length) {
          config.coordinates = results[0].geometry.location;
          config.formatted_address = results[0].formatted_address;
          buildMap(stripeEl, config);
        }
      });
    }
  }

  /**
   * Gallery Stripe - Carousel Navigation
   */
  function hydrateGalleryStripes(container) {
    const galleries = container.querySelectorAll(".vssl-stripe--gallery[data-slide-count]");

    galleries.forEach(galleryEl => {
      if (hydratedElements.has(galleryEl)) return;
      hydratedElements.add(galleryEl);

      const slideCount = parseInt(galleryEl.dataset.slideCount);
      if (slideCount <= 1) return;

      const galleryWrap = galleryEl.querySelector(".vssl-stripe--gallery--wrap");
      const nextBtn = galleryWrap.querySelector(".vssl-stripe--gallery--next");
      const prevBtn = galleryWrap.querySelector(".vssl-stripe--gallery--prev");
      const currentCounter = galleryWrap.querySelector(".vssl-stripe--gallery--current");
      const slideEls = galleryWrap.querySelectorAll(".vssl-stripe--gallery--slide");
      const controls = galleryWrap.querySelector(".vssl-stripe--gallery--controls");

      let slideIndex = 0;

      function setSlideIndex(index) {
        slideIndex = index >= slideCount ? 0 : index < 0 ? slideCount - 1 : index;

        slideEls[slideIndex].dataset.active = true;
        currentCounter.innerHTML = slideIndex + 1;

        const nextSlideIndex = slideIndex + 1 === slideCount ? 0 : slideIndex + 1;
        const prevSlideIndex = slideIndex === 0 ? slideCount - 1 : slideIndex - 1;
        slideEls[nextSlideIndex].querySelector("img").removeAttribute("loading");
        slideEls[prevSlideIndex].querySelector("img").removeAttribute("loading");
        slideEls[nextSlideIndex].dataset.active = false;
        slideEls[prevSlideIndex].dataset.active = false;

        const childImage = slideEls[slideIndex].querySelector(".vssl-stripe--gallery--image");
        const ratio = (childImage?.clientHeight || 0) / (childImage?.clientWidth || 1);
        controls.style = ratio
          ? `height: 0; padding-bottom: ${ratio * 100}%;`
          : "";
      }

      setSlideIndex(0);
      nextBtn.addEventListener("click", () => setSlideIndex(slideIndex + 1));
      prevBtn.addEventListener("click", () => setSlideIndex(slideIndex - 1));
    });
  }

  // Initialize on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => hydrateVesselStripes());
  } else {
    hydrateVesselStripes();
  }

  // Listen for hydrate event to re-hydrate dynamically loaded content
  window.addEventListener("hydrate-vessel-stripes", (e) => {
    const container = e.detail?.container || document;
    hydrateVesselStripes(container);
  });

  // Expose hydration function globally for manual hydration
  window.hydrateVesselStripes = hydrateVesselStripes;

})();
