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
    const marquee = !reduceMotion;

    const apply = () => {
      if (query.matches) {
        if (!$grid.hasClass("slick-initialized")) {
          $grid.slick({
            slidesToShow: 1.12,
            slidesToScroll: 1,
            infinite: marquee,
            arrows: false,
            dots: true,
            swipeToSlide: true,
            touchThreshold: 8,
            // Slick restarts the autoplay timer after each transition, so a long
            // speed with a near-zero delay leaves no visible pause: the strip glides
            // right to left without stopping.
            adaptiveHeight: !marquee,
            autoplay: marquee,
            autoplaySpeed: 120,
            speed: marquee ? 2500 : 500,
            cssEase: "linear",
            pauseOnHover: true,
            pauseOnFocus: true,
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

  /* ---------- Static builds: remember a manual language pick ---------- */
  function initLocaleMemory() {
    if (!document.documentElement.hasAttribute("data-static-locale")) return;
    document.addEventListener("click", (event) => {
      const option = event.target.closest(".lang-option");
      if (!option) return;
      const code = option.getAttribute("hreflang");
      if (!code) return;
      try {
        window.localStorage.setItem("mf-locale", code);
        // Tie the pick to the language the phone reports today, so switching the
        // device language later re-opens detection instead of being blocked by it.
        window.localStorage.setItem(
          "mf-locale-device",
          String((navigator.languages && navigator.languages[0]) || navigator.language || "en").toLowerCase()
        );
      } catch (err) {
        /* private mode: the pick still works, it just is not remembered */
      }
    });
  }

  /* ---------- Hero title words rising into view ---------- */
  function initHeroWords() {
    const title = document.querySelector(".hero-copy h1");
    if (!title || reduceMotion) return;

    const words = (title.textContent || "").trim().split(/\s+/).filter(Boolean);
    if (words.length < 2) return;

    // A word mask is atomic, so a script with very long words (Tamil) can end up
    // with more lines than plain text. Measure, and fall back to a single rise.
    const style = window.getComputedStyle(title);
    const lineHeight = parseFloat(style.lineHeight) || parseFloat(style.fontSize) * 1.2;
    const plainHeight = title.getBoundingClientRect().height;
    title.textContent = "";
    words.forEach((word, index) => {
      const mask = document.createElement("span");
      mask.className = "hero-word";
      mask.style.setProperty("--word-i", String(index));
      const inner = document.createElement("span");
      inner.className = "hero-word-inner";
      inner.textContent = word;
      mask.appendChild(inner);
      title.appendChild(mask);
      title.appendChild(document.createTextNode(" "));
    });

    if (title.getBoundingClientRect().height > plainHeight + lineHeight * 0.6) {
      title.textContent = words.join(" ");
      title.classList.add("is-title-rise");
    } else {
      title.classList.add("is-words-in");
    }
  }

  /* ---------- Back to top, ringed with page progress ---------- */
  function initBackToTop() {
    const button = document.getElementById("toTop");
    if (!button) return;

    const bar = document.getElementById("toTopBar");
    const length = bar ? 2 * Math.PI * Number(bar.getAttribute("r")) : 0;
    if (bar && length) {
      bar.style.strokeDasharray = String(length);
      bar.style.strokeDashoffset = String(length);
    }

    let ticking = false;
    const render = () => {
      const y = window.scrollY;
      const max = document.documentElement.scrollHeight - window.innerHeight;
      button.classList.toggle("is-visible", y > 600);
      // the footer credit owns the bottom-right corner once you reach the end
      button.classList.toggle("is-raised", max - y < 96);
      if (bar && length && max > 0) {
        bar.style.strokeDashoffset = String(length * (1 - Math.min(1, y / max)));
      }
      ticking = false;
    };

    window.addEventListener(
      "scroll",
      () => {
        if (!ticking) {
          ticking = true;
          window.requestAnimationFrame(render);
        }
      },
      { passive: true }
    );

    button.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" });
    });
    render();
  }

  document.addEventListener("DOMContentLoaded", () => {
    initProductSlider();
    initMagnetic();
    initSmoothAnchors();
    initLocaleMemory();
    initHeroWords();
    initBackToTop();
  });
})();
