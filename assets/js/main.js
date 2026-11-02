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
  const inquiryForm = document.getElementById('kataskevi-inquiry-form');
  const statusBox = document.getElementById('form-status-message');
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

  if (inquiryForm && statusBox && submitBtn) {
    inquiryForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const fullnameEl = document.getElementById('fullname');
      const emailEl = document.getElementById('email');
      const phoneEl = document.getElementById('phone');
      const serviceEl = document.getElementById('service');
      const companyEl = document.getElementById('company');
      const messageEl = document.getElementById('message');

      const fullname = fullnameEl ? fullnameEl.value.trim() : '';
      const email = emailEl ? emailEl.value.trim() : '';
      const phone = phoneEl ? phoneEl.value.trim() : '';
      const service = serviceEl ? serviceEl.value : '';
      const company = companyEl ? companyEl.value.trim() : '';
      const message = messageEl ? messageEl.value.trim() : '';

      if (!fullname || !email || !phone) {
        showStatus('Please complete all required fields (Name, Email, Phone).', 'error');
        return;
      }

      // UI Loading state
      submitBtn.disabled = true;
      const originalBtnHtml = submitBtn.innerHTML;
      submitBtn.innerHTML = '<span>Sending Request...</span>';
      statusBox.style.display = 'none';

      const formData = new FormData();
      formData.append('action', 'kataskevi_submit_inquiry');
      formData.append('nonce', typeof kataskeviData !== 'undefined' ? kataskeviData.nonce : '');
      formData.append('fullname', fullname);
      formData.append('email', email);
      formData.append('phone', phone);
      formData.append('service', service);
      formData.append('sector', company);
      formData.append('message', (company ? 'Company: ' + company + '\n\n' : '') + message);

      const ajaxUrl = typeof kataskeviData !== 'undefined' ? kataskeviData.ajaxUrl : '/wp-admin/admin-ajax.php';

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;

          if (data && data.success) {
            if (successState) {
              inquiryForm.style.display = 'none';
              successState.style.display = 'flex';
            } else {
              showStatus(data.data.message || 'Thank you! Your inquiry has been received. Our engineers will contact you shortly.', 'success');
              inquiryForm.reset();
            }
          } else {
            showStatus((data && data.data && data.data.message) ? data.data.message : 'Please check your information and try again.', 'error');
          }
        })
        .catch((err) => {
          console.error('Submission error:', err);
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;
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
      statusBox.textContent = msg;
      statusBox.className = 'form-status-alert ' + type;
      statusBox.style.display = 'block';
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
});
