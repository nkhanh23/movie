/**
 * Mobile Header Handlers - Search Overlay & Notification Dropdown
 */
document.addEventListener('DOMContentLoaded', function() {
  
  // === MOBILE SEARCH OVERLAY ===
  const mobileSearchBtn = document.getElementById('mobileSearchBtn');
  const mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
  const closeMobileSearch = document.getElementById('closeMobileSearch');
  const mobileSearchInput = document.getElementById('mobileSearchInput');

  if (mobileSearchBtn && mobileSearchOverlay) {
    mobileSearchBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      mobileSearchOverlay.classList.remove('opacity-0', 'invisible');
      mobileSearchOverlay.classList.add('opacity-100', 'visible');
      document.body.style.overflow = 'hidden';
      setTimeout(() => mobileSearchInput?.focus(), 100);
      if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    if (closeMobileSearch) {
      closeMobileSearch.addEventListener('click', function() {
        mobileSearchOverlay.classList.add('opacity-0', 'invisible');
        mobileSearchOverlay.classList.remove('opacity-100', 'visible');
        document.body.style.overflow = '';
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && mobileSearchOverlay.classList.contains('visible')) {
        mobileSearchOverlay.classList.add('opacity-0', 'invisible');
        mobileSearchOverlay.classList.remove('opacity-100', 'visible');
        document.body.style.overflow = '';
      }
    });
  }

  // === MOBILE NOTIFICATION DROPDOWN ===
  const mobileNotificationBtn = document.getElementById('mobileNotificationBtn');
  const mobileNotificationDropdown = document.getElementById('mobileNotificationDropdown');
  const mobileNotificationOverlay = document.getElementById('mobileNotificationOverlay');
  let isMobileNotificationOpen = false;

  if (mobileNotificationBtn && mobileNotificationDropdown) {
    mobileNotificationBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      isMobileNotificationOpen = !isMobileNotificationOpen;

      if (isMobileNotificationOpen) {
        mobileNotificationDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
        mobileNotificationDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
        if (mobileNotificationOverlay) {
          mobileNotificationOverlay.classList.remove('opacity-0', 'invisible');
          mobileNotificationOverlay.classList.add('opacity-100', 'visible');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
      } else {
        closeMobileNotif();
      }
    });

    function closeMobileNotif() {
      mobileNotificationDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
      mobileNotificationDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
      if (mobileNotificationOverlay) {
        mobileNotificationOverlay.classList.add('opacity-0', 'invisible');
        mobileNotificationOverlay.classList.remove('opacity-100', 'visible');
      }
      isMobileNotificationOpen = false;
    }

    if (mobileNotificationOverlay) {
      mobileNotificationOverlay.addEventListener('click', closeMobileNotif);
    }

    document.addEventListener('click', function(e) {
      if (isMobileNotificationOpen && !mobileNotificationDropdown.contains(e.target) && !mobileNotificationBtn.contains(e.target)) {
        closeMobileNotif();
      }
    });
  }
});
