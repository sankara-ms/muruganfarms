/* ============================================================
   Murugan Farms — motion layer
   SAL scroll reveals, Slick product strip, header, parallax.
   ============================================================ */
(function () {
  "use strict";

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------- SAL ---------- */
  function showAll() {
    document.querySelectorAll("[data-sal]").forEach((el) => el.classList.add("sal-animate"));
  }

  if (typeof window.sal !== "function" || reduceMotion) {
    showAll();
  } else {
    window.sal({
      threshold: 0.15,
      once: true,
      animateClassName: "sal-animate",
      disabledClassName: "sal-disabled",
    });
    // safety net: never leave content hidden if the observer misfires
    window.setTimeout(showAll, 6000);
  }

  /* ---------- Header: sticky + hide on scroll down ---------- */
  const header = document.querySelector(".site-header");
  const progressBar = document.getElementById("scrollProgressBar");

  if (header) {
    let lastY = window.scrollY;
    let ticking = false;

    const onScroll = () => {
      const y = window.scrollY;
      const menuOpen = document.body.classList.contains("nav-open");

      header.classList.toggle("is-stuck", y > 12);
      if (!menuOpen && Math.abs(y - lastY) > 6) {
        header.classList.toggle("is-hidden", y > lastY && y > 260);
        lastY = y;
      }

      if (progressBar) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progressBar.style.width = max > 0 ? Math.min(100, (y / max) * 100) + "%" : "0%";
      }

      ticking = false;
    };

    window.addEventListener(
      "scroll",
      () => {
        if (!ticking) {
          ticking = true;
          window.requestAnimationFrame(onScroll);
        }
      },
      { passive: true }
    );
    onScroll();
  }

  /* ---------- Parallax (transform-only, via CSS variable) ---------- */
  const parallaxItems = Array.from(document.querySelectorAll("[data-parallax]"));

  if (parallaxItems.length && !reduceMotion) {
    let frame = 0;

    const render = () => {
      const vh = window.innerHeight;
      parallaxItems.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.bottom < -120 || rect.top > vh + 120) return;
        const depth = parseFloat(el.dataset.parallax) || 0;
        const centreOffset = rect.top + rect.height / 2 - vh / 2;
        const shift = (-centreOffset / vh) * depth * 100;
        el.style.setProperty("--parallax-y", shift.toFixed(2) + "px");
      });
      frame = 0;
    };

    window.addEventListener(
      "scroll",
      () => {
        if (!frame) frame = window.requestAnimationFrame(render);
      },
      { passive: true }
    );
    render();
  }

  /* ---------- Slick: product strip on small screens ---------- */
  function initProductSlider() {
    const $ = window.jQuery;
    const grid = document.getElementById("productGrid");
    if (!$ || !$.fn || !$.fn.slick || !grid) return;

    const query = window.matchMedia("(max-width: 700px)");
    const $grid = $(grid);

    const apply = () => {
      if (query.matches) {
        if (!$grid.hasClass("slick-initialized")) {
          $grid.slick({
            slidesToShow: 1.12,
            slidesToScroll: 1,
            infinite: false,
            arrows: false,
            dots: true,
            swipeToSlide: true,
            touchThreshold: 8,
            adaptiveHeight: true,
          });
          // Slides parked off-screen inside the Slick track never intersect the
          // viewport, so SAL would leave them invisible. Reveal them explicitly.
          grid.querySelectorAll("[data-sal]").forEach((el) => el.classList.add("sal-animate"));
          // Same for lazy images: hidden slides never load, so a swipe would
          // land on an empty card. Pull them in eagerly on the small-screen strip.
          grid.querySelectorAll('img[loading="lazy"]').forEach((img) => {
            img.loading = "eager";
          });
        }
      } else if ($grid.hasClass("slick-initialized")) {
        $grid.slick("unslick");
      }
    };

    apply();
    if (query.addEventListener) query.addEventListener("change", apply);
  }

  /* ---------- Magnetic buttons (pointer devices only) ---------- */
  function initMagnetic() {
    if (reduceMotion) return;
    if (!window.matchMedia("(hover: hover) and (pointer: fine)").matches) return;

    document.querySelectorAll(".btn-magnetic").forEach((btn) => {
      btn.addEventListener("mousemove", (event) => {
        const rect = btn.getBoundingClientRect();
        const dx = (event.clientX - rect.left - rect.width / 2) / rect.width;
        const dy = (event.clientY - rect.top - rect.height / 2) / rect.height;
        btn.style.transform = "translate3d(" + (dx * 5).toFixed(2) + "px," + (dy * 4).toFixed(2) + "px,0)";
      });
      btn.addEventListener("mouseleave", () => {
        btn.style.transform = "";
      });
    });
  }

  /* ---------- Smooth in-page anchor scrolling ---------- */
  function initSmoothAnchors() {
    if (reduceMotion) return;
    document.addEventListener("click", (event) => {
      const link = event.target.closest('a[href^="#"]');
      if (!link) return;
      const id = link.getAttribute("href");
      if (id === "#" || id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      event.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
      history.replaceState(null, "", id);
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    initProductSlider();
    initMagnetic();
    initSmoothAnchors();
  });
})();
