//
// VizzioStream — Global frontend behavior
//

/**
 * Skeleton loading on pagination navigation.
 *
 * The pagination links are plain <a> tags (server-side rendered), so a full
 * page reload happens. On click we instantly swap the real grid for the
 * skeleton overlay so the user sees immediate feedback while the next page
 * is being fetched/rendered.
 */
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('anime-grid');
    const skeleton = document.getElementById('pagination-skeleton');

    if (!grid || !skeleton) {
        return;
    }

    // Keep a reference so we can re-attach after the click (harmless).
    const showSkeleton = () => {
        grid.classList.add('hidden');
        skeleton.classList.remove('hidden');
        // Let the browser paint the skeleton before the navigation starts.
        requestAnimationFrame(() => {
            // Nudge the layout so the shimmer is visible for at least one frame.
            skeleton.getBoundingClientRect();
        });
    };

    const isPaginationLink = (el) => {
        if (!el || el.tagName !== 'A') return false;
        const href = el.getAttribute('href') || '';
        return href.includes('page=') || el.closest('nav[aria-label="Paginasi halaman"]') !== null;
    };

    // Event delegation: capture any pagination click (prev/next/number).
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && isPaginationLink(link)) {
            showSkeleton();
        }
    }, true);

    // If the browser restores this page from the back-forward cache (e.g. user
    // clicked a pagination link, then pressed Back), DOMContentLoaded does not
    // re-run, so the skeleton shown above would stay visible forever. Restore
    // the real grid whenever the page is shown again.
    window.addEventListener('pageshow', () => {
        grid.classList.remove('hidden');
        skeleton.classList.add('hidden');
    });
});

/**
 * Live search suggestions (autocomplete).
 *
 * While the user types in any navbar/mobile search input we fetch up to 7
 * matching titles from /api/search/suggestions (debounced 300ms) and show
 * them in a dropdown inside the form. Navigation only happens on Enter or
 * when a suggestion is clicked / the submit button is pressed.
 */
document.addEventListener('DOMContentLoaded', () => {
    const searchInputs = document.querySelectorAll('form[role="search"] input[name="q"]');

    // Function to submit the search form
    const submitSearch = (input) => {
        const form = input.closest('form');
        if (!form) return;
        const value = input.value.trim();
        const url = new URL(form.action, window.location.origin);
        if (value) {
            url.searchParams.set('q', value);
        } else {
            url.searchParams.delete('q');
        }
        window.location.assign(url.toString());
        // Hide suggestions when submitting the search
        hideSuggestions(input);
    };

    // Resolve the suggestion container relative to the input's own form.
    const getSuggestionContainer = (input) => {
        const form = input.closest('form');
        return form ? form.querySelector('.search-suggestions') : null;
    };

    const hideSuggestions = (input) => {
        const container = getSuggestionContainer(input);
        if (container) {
            container.innerHTML = '';
            container.classList.add('hidden');
        }
    };

    // --- Search Suggestions Logic ---
    const fetchSuggestions = async (input) => {
        const container = getSuggestionContainer(input);
        if (!container) return;

        const query = input.value.trim();
        if (query.length < 2) {
            container.innerHTML = '';
            container.classList.add('hidden');
            return;
        }

        try {
            const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            renderSuggestions(container, data);
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            container.innerHTML = '';
            container.classList.add('hidden');
        }
    };

    const renderSuggestions = (container, suggestions) => {
        if (!Array.isArray(suggestions) || suggestions.length === 0) {
            container.innerHTML = '';
            container.classList.add('hidden');
            return;
        }

        let html = '';
        suggestions.forEach(suggestion => {
            const title = suggestion.title ?? '';
            const link = suggestion.link ?? '#';
            html += `<a href="${link}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-sky-700 hover:text-white transition-colors duration-200 truncate">${title}</a>`;
        });
        container.innerHTML = html;
        container.classList.remove('hidden');
    };

    searchInputs.forEach((input) => {
        let suggestionsTimer = null;
        const debounceSuggestions = () => {
            if (suggestionsTimer) {
                clearTimeout(suggestionsTimer);
            }
            suggestionsTimer = setTimeout(() => {
                suggestionsTimer = null;
                fetchSuggestions(input);
            }, 300); // Debounce 300ms for suggestions
        };

        // Live suggestions while typing (no auto-navigation)
        input.addEventListener('input', () => {
            debounceSuggestions();
        });

        // Enter submits immediately.
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitSearch(input);
            }
        });

        input.addEventListener('focus', () => {
            // Re-fetch suggestions if there's a query and container is hidden
            const container = getSuggestionContainer(input);
            if (input.value.trim().length >= 2 && container && container.classList.contains('hidden')) {
                fetchSuggestions(input);
            }
        });

        // Hide suggestions when input loses focus
        input.addEventListener('blur', () => {
            // Use a small delay to allow click event on suggestion links to fire
            setTimeout(() => hideSuggestions(input), 150);
        });
    });
});
