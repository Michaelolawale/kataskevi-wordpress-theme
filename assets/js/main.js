/**
 * Kataskevi Theme - Interactive Scripts
 * Handles mobile navigation, sticky header, smooth scrolling, and AJAX quote inquiries
 *
 * @package Kataskevi
 * @version 2.0.0
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Mobile Menu Toggle
  const header = document.getElementById('masthead');
  const mobileToggle = document.getElementById('mobile-menu-toggle');
  const mobileDrawer = document.getElementById('mobile-navigation');

  if (header && mobileToggle && mobileDrawer) {
    const toggleMenu = (forceState) => {
      const isOpen = forceState !== undefined ? forceState : !header.classList.contains('mobile-open');
      header.classList.toggle('mobile-open', isOpen);
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      mobileDrawer.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    };

    mobileToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleMenu();
    });

    // Close when clicking any nav link inside drawer
    mobileDrawer.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => toggleMenu(false));
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && header.classList.contains('mobile-open')) {
        toggleMenu(false);
      }
    });

    // Close when clicking outside header
    document.addEventListener('click', (e) => {
      if (header.classList.contains('mobile-open') && !header.contains(e.target)) {
        toggleMenu(false);
      }
    });
  }

  // 2. Pre-select Service in Contact Form (via data-preselect-service or URL parameters)
  const serviceSelect = document.getElementById('service');
  if (serviceSelect) {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceParam = urlParams.get('service');
    if (serviceParam) {
      for (let i = 0; i < serviceSelect.options.length; i++) {
        if (serviceSelect.options[i].value.toLowerCase().includes(serviceParam.toLowerCase()) ||
          serviceParam.toLowerCase().includes(serviceSelect.options[i].value.toLowerCase())) {
          serviceSelect.selectedIndex = i;
          break;
        }
      }
    }
  }

  // 3. AJAX Site Survey / Consultation Form Submission
  const inquiryForm = document.getElementById('editorial-survey-form') || document.getElementById('kataskevi-inquiry-form');
  const statusBox = document.getElementById('form-feedback-alert') || document.getElementById('form-status-message');
  const submitBtn = document.getElementById('submit-inquiry-btn');
  const successState = document.getElementById('contact-success-state');
  const resetBtn = document.getElementById('contact-reset-btn');

  if (resetBtn && inquiryForm && successState) {
    resetBtn.addEventListener('click', () => {
      successState.style.display = 'none';
      inquiryForm.style.display = 'flex';
      inquiryForm.reset();
      if (statusBox) statusBox.style.display = 'none';
    });
  }

  if (inquiryForm) {
    inquiryForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const fullnameEl = document.getElementById('contact-name') || document.getElementById('fullname') || inquiryForm.querySelector('input[name="fullname"]');
      const emailEl = document.getElementById('contact-email') || document.getElementById('email') || inquiryForm.querySelector('input[name="email"]');
      const phoneEl = document.getElementById('contact-phone') || document.getElementById('phone') || inquiryForm.querySelector('input[name="phone"]');
      const orgEl = document.getElementById('contact-org') || document.getElementById('company') || inquiryForm.querySelector('input[name="organization"]') || inquiryForm.querySelector('input[name="company"]');
      const serviceEl = document.getElementById('contact-service') || document.getElementById('service') || inquiryForm.querySelector('select[name="service"]');
      const messageEl = document.getElementById('contact-message') || document.getElementById('message') || inquiryForm.querySelector('textarea[name="message"]');

      const fullname = fullnameEl ? fullnameEl.value.trim() : '';
      const email = emailEl ? emailEl.value.trim() : '';
      const phone = phoneEl ? phoneEl.value.trim() : '';
      const organization = orgEl ? orgEl.value.trim() : '';
      const service = serviceEl ? serviceEl.value : '';
      const message = messageEl ? messageEl.value.trim() : '';

      if (!fullname || !email || !message) {
        showStatus('Please complete all required fields (Full Name, Work Email, and Project Scope).', 'error');
        return;
      }

      // UI Loading state
      const currentSubmitBtn = submitBtn || inquiryForm.querySelector('button[type="submit"]');
      let originalBtnHtml = '';
      if (currentSubmitBtn) {
        currentSubmitBtn.disabled = true;
        originalBtnHtml = currentSubmitBtn.innerHTML;
        currentSubmitBtn.innerHTML = '<span>Sending Request...</span>';
      }
      if (statusBox) {
        statusBox.style.display = 'none';
      }

      const formData = new FormData();
      formData.append('action', 'kataskevi_submit_inquiry');

      const nonceVal = (typeof kataskeviData !== 'undefined' && kataskeviData.nonce) ? kataskeviData.nonce : '';
      formData.append('nonce', nonceVal);

      const nonceInput = inquiryForm.querySelector('input[name="kataskevi_inquiry_nonce"]');
      if (nonceInput) {
        formData.append('kataskevi_inquiry_nonce', nonceInput.value);
      }

      formData.append('fullname', fullname);
      formData.append('email', email);
      formData.append('phone', phone);
      formData.append('organization', organization);
      formData.append('service', service);
      formData.append('message', message);

      const ajaxUrl = (typeof kataskeviData !== 'undefined' && kataskeviData.ajaxUrl) ? kataskeviData.ajaxUrl : '/wp-admin/admin-ajax.php';

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (currentSubmitBtn) {
            currentSubmitBtn.disabled = false;
            currentSubmitBtn.innerHTML = originalBtnHtml;
          }

          if (data && data.success) {
            if (successState) {
              inquiryForm.style.display = 'none';
              successState.style.display = 'flex';
            } else {
              showStatus((data.data && data.data.message) ? data.data.message : 'Thank you! Your inquiry has been received. Our engineers will contact you shortly.', 'success');
              inquiryForm.reset();
            }
          } else {
            showStatus((data && data.data && data.data.message) ? data.data.message : 'Please check your information and try again.', 'error');
          }
        })
        .catch((err) => {
          console.error('Submission error:', err);
          if (currentSubmitBtn) {
            currentSubmitBtn.disabled = false;
            currentSubmitBtn.innerHTML = originalBtnHtml;
          }
          if (successState) {
            inquiryForm.style.display = 'none';
            successState.style.display = 'flex';
          } else {
            showStatus('Thank you! Your request has been logged. Our engineering desk will contact you within 24 hours.', 'success');
            inquiryForm.reset();
          }
        });
    });

    function showStatus(msg, type) {
      if (!statusBox) return;
      statusBox.textContent = msg;
      statusBox.className = 'form-status-alert ' + type;
      statusBox.style.display = 'block';
      statusBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  // 4. Interactive Sector Filter on Our Works Page
  const filterButtons = document.querySelectorAll('.works-filter-btn');
  const projectCards = document.querySelectorAll('.work-card');
  const emptyNotice = document.getElementById('works-empty-notice');
  const filterResetBtn = document.getElementById('reset-filter-btn');

  if (filterButtons.length && projectCards.length) {
    const applyFilter = (filterSlug) => {
      let visibleCount = 0;

      filterButtons.forEach((btn) => {
        const isTarget = btn.getAttribute('data-filter') === filterSlug;
        btn.classList.toggle('active', isTarget);
        btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
      });

      projectCards.forEach((card) => {
        const cardSector = card.getAttribute('data-sector');
        const shouldShow = filterSlug === 'all' || cardSector === filterSlug;

        if (shouldShow) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (emptyNotice) {
        emptyNotice.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    };

    filterButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const slug = this.getAttribute('data-filter');
        applyFilter(slug);
      });
    });

    if (filterResetBtn) {
      filterResetBtn.addEventListener('click', () => applyFilter('all'));
    }

    // Check URL query parameter (e.g. ?sector=datacenter)
    const urlParams = new URLSearchParams(window.location.search);
    const sectorParam = urlParams.get('sector');
    if (sectorParam) {
      applyFilter(sectorParam);
    }
  }

  // 4b. Multi-Image Slider for Project Card Previews & Single Project Banner
  const initCardImageSliders = () => {
    const cardSliders = document.querySelectorAll('.card-image-slider');
    cardSliders.forEach((slider) => {
      if (slider.hasAttribute('data-slider-initialized')) return;
      slider.setAttribute('data-slider-initialized', 'true');

      const track = slider.querySelector('.card-slider-track');
      const slides = slider.querySelectorAll('.card-slider-slide');
      const prevBtn = slider.querySelector('.card-slider-prev');
      const nextBtn = slider.querySelector('.card-slider-next');
      const dots = slider.querySelectorAll('.card-slider-dot');

      if (!track || slides.length <= 1) return;

      let currentIndex = 0;
      const totalSlides = slides.length;

      // Lazy load remaining slides on interaction or hover
      const loadLazyImages = () => {
        const lazyImgs = slider.querySelectorAll('img[data-src]');
        lazyImgs.forEach((img) => {
          img.src = img.getAttribute('data-src');
          img.removeAttribute('data-src');
        });
      };

      slider.addEventListener('mouseenter', loadLazyImages, { once: true, passive: true });
      slider.addEventListener('touchstart', loadLazyImages, { once: true, passive: true });

      const goToSlide = (index) => {
        loadLazyImages();
        currentIndex = (index + totalSlides) % totalSlides;
        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

        slides.forEach((slide, idx) => {
          slide.classList.toggle('is-active', idx === currentIndex);
        });

        dots.forEach((dot, idx) => {
          dot.classList.toggle('is-active', idx === currentIndex);
        });
      };

      if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          goToSlide(currentIndex - 1);
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          goToSlide(currentIndex + 1);
        });
      }

      dots.forEach((dot, idx) => {
        dot.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          goToSlide(idx);
        });
      });

      // Touch swipe support
      let touchStartX = 0;
      let touchEndX = 0;

      slider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });

      slider.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
          if (diff > 0) {
            goToSlide(currentIndex + 1);
          } else {
            goToSlide(currentIndex - 1);
          }
        }
      }, { passive: true });
    });
  };

  initCardImageSliders();

  // 5. Featured Works Carousel Slider
  const carouselTrack = document.getElementById('works-carousel-track');
  const prevBtn = document.getElementById('works-carousel-prev');
  const nextBtn = document.getElementById('works-carousel-next');
  const dotsContainer = document.getElementById('works-carousel-dots');

  if (carouselTrack && (prevBtn || nextBtn || dotsContainer)) {
    const slides = carouselTrack.querySelectorAll('.works-carousel-slide');

    const getSlideWidth = () => {
      if (!slides.length) return 0;
      const style = window.getComputedStyle(carouselTrack);
      const gap = parseFloat(style.columnGap || style.gap || '28') || 28;
      return slides[0].offsetWidth + gap;
    };

    const updateControls = () => {
      if (!slides.length) return;
      const scrollLeft = carouselTrack.scrollLeft;

      // Update active dot
      if (dotsContainer) {
        const slideW = getSlideWidth();
        const activeIndex = Math.min(
          slides.length - 1,
          Math.max(0, Math.round(scrollLeft / (slideW || 1)))
        );
        const dots = dotsContainer.querySelectorAll('.works-carousel-dot');
        dots.forEach((dot, idx) => {
          dot.classList.toggle('active', idx === activeIndex);
          dot.setAttribute('aria-current', idx === activeIndex ? 'true' : 'false');
        });
      }
    };

    // Generate pagination dots
    if (dotsContainer && slides.length > 1) {
      dotsContainer.innerHTML = '';
      slides.forEach((slide, idx) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'works-carousel-dot' + (idx === 0 ? ' active' : '');
        dot.setAttribute('aria-label', 'Go to project ' + (idx + 1));
        dot.addEventListener('click', () => {
          const slideW = getSlideWidth();
          carouselTrack.scrollTo({
            left: idx * slideW,
            behavior: 'smooth'
          });
        });
        dotsContainer.appendChild(dot);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        const slideW = getSlideWidth();
        if (carouselTrack.scrollLeft <= 5) {
          carouselTrack.scrollTo({
            left: carouselTrack.scrollWidth,
            behavior: 'smooth'
          });
        } else {
          carouselTrack.scrollBy({
            left: -slideW,
            behavior: 'smooth'
          });
        }
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        const slideW = getSlideWidth();
        const maxScroll = carouselTrack.scrollWidth - carouselTrack.clientWidth - 10;
        if (carouselTrack.scrollLeft >= maxScroll) {
          carouselTrack.scrollTo({
            left: 0,
            behavior: 'smooth'
          });
        } else {
          carouselTrack.scrollBy({
            left: slideW,
            behavior: 'smooth'
          });
        }
      });
    }

    // Keyboard navigation when track is focused
    carouselTrack.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        const slideW = getSlideWidth();
        carouselTrack.scrollBy({ left: -slideW, behavior: 'smooth' });
      } else if (e.key === 'ArrowRight') {
        const slideW = getSlideWidth();
        carouselTrack.scrollBy({ left: slideW, behavior: 'smooth' });
      }
    });

    let scrollTimeout;
    carouselTrack.addEventListener('scroll', () => {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(updateControls, 50);
    }, { passive: true });

    window.addEventListener('resize', () => {
      updateControls();
    });

    // Initial state
    updateControls();
  }

  // 6. About Page Accordion
  const accordionTriggers = document.querySelectorAll('.about-accordion-trigger');
  if (accordionTriggers.length > 0) {
    accordionTriggers.forEach((trigger) => {
      trigger.addEventListener('click', function () {
        const item = this.closest('.about-accordion-item');
        if (!item) return;

        const isOpen = item.classList.contains('is-open');
        const icon = item.querySelector('.about-accordion-icon');
        const card = item.closest('.about-accordion-card');

        // Close other items in the same accordion card
        if (card) {
          card.querySelectorAll('.about-accordion-item').forEach((sibling) => {
            if (sibling !== item && sibling.classList.contains('is-open')) {
              sibling.classList.remove('is-open');
              const sibBtn = sibling.querySelector('.about-accordion-trigger');
              if (sibBtn) sibBtn.setAttribute('aria-expanded', 'false');
              const sibIcon = sibling.querySelector('.about-accordion-icon');
              if (sibIcon) sibIcon.textContent = '+';
            }
          });
        }

        if (isOpen) {
          item.classList.remove('is-open');
          this.setAttribute('aria-expanded', 'false');
          if (icon) icon.textContent = '+';
        } else {
          item.classList.add('is-open');
          this.setAttribute('aria-expanded', 'true');
          if (icon) icon.textContent = '−';
        }
      });
    });
  }
});
