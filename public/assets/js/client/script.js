
// Initialize Lucide icons if they aren't already
if (window.lucide) {
    window.lucide.createIcons();
}

// Helper to generate HTML for new cards
const createMovieCardHTML = (movie) => {
    return `
    <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
         data-title="${movie.title}"
         data-year="${movie.year}"
         data-genre="${movie.genre}"
         data-image="${movie.image}"
         data-desc="${movie.desc}">
        <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
            <img src="${movie.image}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                <div class="flex items-center gap-2 mb-6">
                    <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                        <i data-lucide="info" class="w-3 h-3"></i> Quick View
                    </button>
                    <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                        <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                    <div class="flex items-center justify-center gap-2">
                         <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">${movie.year}</span>
                         <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">${movie.genre}</span>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="text-white font-medium text-sm md:text-base truncate">${movie.title}</h4>
        <p class="text-xs text-gray-400">${movie.genre} • ${movie.year}</p>
    </div>
    `;
};

// Hero Section Logic
const initHero = () => {
    const bgEl = document.getElementById('heroBackground');
    const titleEl = document.getElementById('heroTitle');
    const subtitleEl = document.getElementById('heroSubtitle');
    const descEl = document.getElementById('heroDesc');
    const thumbsContainer = document.getElementById('heroThumbnails');

    if (!bgEl || !thumbsContainer) return;

    let currentIndex = 0;

    const updateHeroFromThumb = (thumb) => {
        const data = thumb.dataset;

        // 1. Background + Title + Subtitle + Desc
        bgEl.style.backgroundImage = `url('${data.bg}')`;
        if (titleEl) titleEl.textContent = data.title || '';
        if (subtitleEl) subtitleEl.textContent = data.subtitle || '';
        if (descEl) descEl.textContent = data.desc || '';

        // 2. Meta (tìm trực tiếp từng element theo class để chắc chắn đúng)
        const imdbEl = document.querySelector('#heroMeta .js-meta-imdb');
        const ageEl = document.querySelector('#heroMeta .js-meta-age');
        const yearEl = document.querySelector('#heroMeta .js-meta-year');
        const durationEl = document.querySelector('#heroMeta .js-meta-duration');
        const typeEl = document.querySelector('#heroMeta .js-meta-type');

        if (imdbEl) imdbEl.textContent = `IMDb ${data.imdb || 'N/A'}`;
        if (ageEl) ageEl.textContent = data.age || 'T13';
        if (yearEl) yearEl.textContent = data.year || '';
        if (durationEl) durationEl.textContent = data.duration || '';
        if (typeEl) typeEl.textContent = data.type || 'HD';

        // 3. Genres
        const genresEl = document.getElementById('heroGenres');
        if (genresEl) {
            if (data.info && data.info.trim() !== '') {
                const genresList = data.info.split(',');
                const genreHTML = genresList
                    .map((g, i) =>
                        `<span>${g.trim()}</span>${i < genresList.length - 1
                            ? '<span class="text-gray-500 mx-1">•</span>'
                            : ''
                        }`
                    )
                    .join('');
                genresEl.innerHTML = genreHTML;
            } else {
                genresEl.innerHTML = '';
            }
        }

        // 4. Active state cho thumbnail
        thumbsContainer.querySelectorAll('.hero-thumb').forEach((t) => {
            if (t === thumb) {
                t.classList.add('border-white', 'opacity-100', 'scale-105');
                t.classList.remove('border-transparent', 'opacity-60');
            } else {
                t.classList.remove('border-white', 'opacity-100', 'scale-105');
                t.classList.add('border-transparent', 'opacity-60');
            }
        });
    };

    // Lắng nghe click trên thumbnail
    thumbsContainer.querySelectorAll('.hero-thumb').forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            if (currentIndex !== index) {
                currentIndex = index;
                updateHeroFromThumb(thumb);
            }
        });
    });
};

// Carousel Functionality
const initCarousel = () => {
    // Helper to setup a single carousel
    const setupCarousel = (containerId, leftBtnId, rightBtnId, enableInfinite) => {
        const container = document.getElementById(containerId);
        const leftBtn = document.getElementById(leftBtnId);
        const rightBtn = document.getElementById(rightBtnId);

        if (!container) {
            console.warn(`Carousel container #${containerId} not found`);
            return;
        }

        const getScrollAmount = () => container.clientWidth * 0.7;

        if (leftBtn) {
            leftBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`Left button clicked for #${containerId}`);
                container.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });
        } else {
            console.log(`Left button #${leftBtnId} not found`);
        }

        if (rightBtn) {
            rightBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`Right button clicked for #${containerId}`);
                container.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });
        } else {
            console.log(`Right button #${rightBtnId} not found`);
        }

        if (enableInfinite) {
            // Existing infinite scroll logic for trending
            let isLoading = false;
            const handleInfiniteScroll = () => {
                if (isLoading) return;
                const scrollPosition = container.scrollLeft + container.clientWidth;
                const scrollWidth = container.scrollWidth;

                if (scrollPosition >= scrollWidth - 300) {
                    isLoading = true;
                    setTimeout(() => {
                        additionalMovies.forEach(movie => {
                            const randomMovie = { ...movie, title: `${movie.title}` };
                            container.insertAdjacentHTML('beforeend', createMovieCardHTML(randomMovie));
                        });
                        if (window.lucide) window.lucide.createIcons();
                        isLoading = false;
                    }, 300);
                }
            };
            container.addEventListener('scroll', handleInfiniteScroll);
        }
    };

    // Initialize Trending Carousel (Infinite)
    setupCarousel('trendingCarousel', 'trendingLeft', 'trendingRight', true);

    // Initialize Cinema Carousel (Static)
    setupCarousel('cinemaCarousel', 'cinemaLeft', 'cinemaRight', false);

    // Initialize Country Carousels (Static)
    setupCarousel('koreaCarousel', 'koreaLeft', 'koreaRight', false);
    setupCarousel('chinaCarousel', 'chinaLeft', 'chinaRight', false);
    setupCarousel('usukCarousel', 'usukLeft', 'usukRight', false);
};

// Modal Logic
const initModal = () => {
    const modal = document.getElementById('movieModal');
    const closeBtn = document.getElementById('closeModalBtn');
    const backdrop = document.getElementById('modalBackdrop');
    let lastFocusedElement = null; // Store trigger element

    // Elements to populate
    const mTitle = document.getElementById('modalTitle');
    const mYear = document.getElementById('modalYear');
    const mGenre = document.getElementById('modalGenre');
    const mDesc = document.getElementById('modalDesc');
    const mImage = document.getElementById('modalImage');

    if (!modal) return;

    const openModal = (data) => {
        // Store the element that had focus before opening the modal
        lastFocusedElement = document.activeElement;

        mTitle.textContent = data.title;
        mYear.textContent = data.year;
        mGenre.textContent = data.genre;
        mDesc.textContent = data.desc;
        mImage.src = data.image;

        modal.classList.remove('hidden');
        // Prevent body scroll
        document.body.style.overflow = 'hidden';

        // Refresh icons inside modal
        if (window.lucide) window.lucide.createIcons();

        // Focus the first focusable element (Close button usually)
        // We use a timeout to ensure visibility transition (if any) or DOM update completes
        setTimeout(() => {
            const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusable.length) focusable[0].focus();
        }, 50);
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';

        // Return focus to the element that triggered the modal
        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
    };

    // Focus Trap
    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    });

    // Event Delegation for Quick View buttons (handles dynamic content)
    document.addEventListener('click', (e) => {
        // Use closest to find the button if clicked on icon/text inside
        const btn = e.target.closest('.quick-view-btn');
        if (!btn) return;

        // Ensure the button belongs to a movie card
        const dataEl = btn.closest('.movie-card-wrapper');
        if (dataEl) {
            e.stopPropagation(); // Prevent bubbling/other click effects
            const data = {
                title: dataEl.dataset.title,
                year: dataEl.dataset.year,
                genre: dataEl.dataset.genre,
                desc: dataEl.dataset.desc,
                image: dataEl.dataset.image
            };
            openModal(data);
        }
    });

    if (closeBtn) closeBtn.onclick = closeModal;
    if (backdrop) backdrop.onclick = closeModal;

    // Escape key to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
};

// Main Initialization
const init = () => {
    initHero();
    initCarousel();
    initModal();
};

// Run initialization immediately if DOM is ready, otherwise wait for it
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    // If already loaded (which is likely with type="module"), run immediately
    init();
}