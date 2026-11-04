/* ============================================================
   Shared site behaviour — navigation, lightbox, enquiry form.
   Per-client animation layer lives in sites/{client}/js/site.js
   ============================================================ */

const SITE = window.SITE || {};
const STRINGS = SITE.strings || {};

const nav = document.getElementById("mainNav");
const menuToggle = document.querySelector(".menu-toggle");
const form = document.getElementById("enquiryForm");
const year = document.getElementById("year");
const lightbox = document.getElementById("lightbox");
const lightboxImg = document.getElementById("lightboxImg");
const lightboxCaption = document.getElementById("lightboxCaption");
const lightboxClose = lightbox ? lightbox.querySelector(".lightbox-close") : null;

/* ---------------- Mobile menu ---------------- */
function setMenuState(open) {
  if (!nav || !menuToggle) return;
  nav.classList.toggle("open", open);
  menuToggle.classList.toggle("is-open", open);
  menuToggle.setAttribute("aria-expanded", String(open));
  menuToggle.setAttribute(
    "aria-label",
    open ? STRINGS.menuClose || "Close navigation menu" : STRINGS.menuOpen || "Open navigation menu"
  );
  document.body.classList.toggle("nav-open", open);
}

function bindNavigation() {
  if (!menuToggle || !nav) return;
  menuToggle.addEventListener("click", () => setMenuState(!nav.classList.contains("open")));
  nav.querySelectorAll(".nav-link").forEach((link) => {
    link.addEventListener("click", () => setMenuState(false));
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") setMenuState(false);
  });
  document.addEventListener("click", (event) => {
    if (!nav.classList.contains("open")) return;
    if (nav.contains(event.target) || menuToggle.contains(event.target)) return;
    setMenuState(false);
  });
}

/* ---------------- Active nav on scroll ---------------- */
function bindActiveNav() {
  const links = Array.from(document.querySelectorAll(".nav-link"));
  if (!links.length || !("IntersectionObserver" in window)) return;

  const sections = links
    .map((link) => document.querySelector(link.getAttribute("href")))
    .filter(Boolean);
  if (!sections.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        links.forEach((link) => {
          link.classList.toggle("active", link.getAttribute("href") === "#" + entry.target.id);
        });
      });
    },
    { rootMargin: "-45% 0px -50% 0px" }
  );

  sections.forEach((section) => observer.observe(section));
}

/* ---------------- Lightbox ---------------- */
let lastFocusedElement = null;

function galleryItems() {
  return Array.from(document.querySelectorAll("#galleryGrid .gallery-item"));
}

function openLightbox(index) {
  const item = galleryItems()[index];
  if (!lightbox || !item) return;
  const img = item.querySelector("img");
  const caption = item.querySelector("figcaption");
  if (!img) return;

  lastFocusedElement = document.activeElement;
  lightboxImg.src = img.currentSrc || img.src;
  lightboxImg.alt = img.alt || "";
  lightboxCaption.textContent = caption ? caption.textContent : "";
  lightbox.hidden = false;
  document.body.style.overflow = "hidden";
  if (lightboxClose) lightboxClose.focus();
}

function closeLightbox() {
  if (!lightbox || lightbox.hidden) return;
  lightbox.hidden = true;
  document.body.style.overflow = "";
  if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
    lastFocusedElement.focus();
  }
}

function bindLightbox() {
  if (!lightbox) return;
  if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);
  lightbox.addEventListener("click", (event) => {
    if (event.target === lightbox) closeLightbox();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closeLightbox();
  });
  document.getElementById("galleryGrid")?.addEventListener("click", (event) => {
    const button = event.target.closest("button[data-index]");
    if (button) openLightbox(Number(button.dataset.index));
  });
}

/* ---------------- Product enquire buttons ---------------- */
function bindEnquireButtons() {
  const select = document.getElementById("product");
  document.getElementById("productGrid")?.addEventListener("click", (event) => {
    const button = event.target.closest(".enquire-btn");
    if (!button) return;

    if (select) {
      const wanted = button.dataset.product;
      const option = Array.from(select.options).find((opt) => opt.value === wanted);
      if (option) select.value = option.value;
    }

    document.getElementById("contact")?.scrollIntoView({ behavior: "smooth", block: "start" });
    window.setTimeout(() => document.getElementById("message")?.focus({ preventScroll: true }), 600);
  });
}

/* ---------------- Enquiry form ---------------- */
function setFieldError(fieldId, message) {
  const field = document.getElementById(fieldId);
  const error = document.getElementById(fieldId + "Error");
  if (!field || !error) return;

  if (message) {
    field.setAttribute("aria-invalid", "true");
    error.textContent = message;
  } else {
    field.setAttribute("aria-invalid", "false");
    error.textContent = "";
  }
}

function validateForm() {
  const errors = STRINGS.errors || {};
  const name = document.getElementById("name");
  const phone = document.getElementById("phone");
  const message = document.getElementById("message");
  let valid = true;

  if (!name.value.trim()) {
    setFieldError("name", errors.name_required || "Please enter your name.");
    valid = false;
  } else if (name.value.trim().length < 2) {
    setFieldError("name", errors.name_short || "Name looks too short.");
    valid = false;
  } else {
    setFieldError("name", "");
  }

  const digits = phone.value.replace(/[^0-9]/g, "");
  if (!phone.value.trim()) {
    setFieldError("phone", errors.phone_required || "Please enter your phone number.");
    valid = false;
  } else if (digits.length < 10 || digits.length > 15) {
    setFieldError("phone", errors.phone_invalid || "Enter a valid phone number (10–15 digits).");
    valid = false;
  } else {
    setFieldError("phone", "");
  }

  if (!message.value.trim()) {
    setFieldError("message", errors.message_required || "Please write a short message.");
    valid = false;
  } else if (message.value.trim().length < 10) {
    setFieldError("message", errors.message_short || "Message should be at least 10 characters.");
    valid = false;
  } else {
    setFieldError("message", "");
  }

  return valid;
}

function bindForm() {
  if (!form) return;
  const success = document.getElementById("formSuccess");
  const submit = form.querySelector('button[type="submit"]');

  ["name", "phone", "message"].forEach((id) => {
    document.getElementById(id)?.addEventListener("input", () => setFieldError(id, ""));
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!validateForm()) {
      if (success) success.hidden = true;
      return;
    }

    const select = document.getElementById("product");
    const label = select && select.selectedIndex > 0 ? select.options[select.selectedIndex].textContent.trim() : "";
    const payload = {
      name: document.getElementById("name").value.trim(),
      phone: document.getElementById("phone").value.trim(),
      product: label,
      message: document.getElementById("message").value.trim(),
    };

    if (submit) submit.disabled = true;
    if (success) {
      success.hidden = false;
      success.textContent = STRINGS.sending || "Sending…";
    }

    try {
      const response = await fetch(SITE.enquiryUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) throw new Error("Request failed");

      if (success) success.textContent = STRINGS.success || "Thank you! Your enquiry has been received.";
      form.reset();
      ["name", "phone", "message"].forEach((id) => setFieldError(id, ""));
    } catch (error) {
      if (success) success.textContent = STRINGS.error || "Sorry — your enquiry could not be sent.";
    } finally {
      if (submit) submit.disabled = false;
    }
  });
}

/* ---------------- Init ---------------- */
document.addEventListener("DOMContentLoaded", () => {
  bindNavigation();
  bindActiveNav();
  bindLightbox();
  bindEnquireButtons();
  bindForm();
  if (year) year.textContent = new Date().getFullYear();
});
