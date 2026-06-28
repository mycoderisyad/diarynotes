document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('public-notes-search');
    const filterButton = document.getElementById('public-notes-filter');
    const sortButton = document.getElementById('public-notes-sort');
    const notesGrid = document.getElementById('public-notes-grid');
    const noteItems = Array.from(document.querySelectorAll('[data-note-item]'));
    const notesCount = document.getElementById('public-notes-count');
    const emptySearchState = document.getElementById('public-notes-empty-search');
    const customSelects = Array.from(document.querySelectorAll('[data-public-select]'));

    if (!searchInput || !filterButton || !sortButton || !notesGrid || !noteItems.length) {
        return;
    }

    const closeSelect = (selectWrap) => {
        const button = selectWrap.querySelector('[data-select-button]');
        const menu = selectWrap.querySelector('.notes-select-menu');

        button?.setAttribute('aria-expanded', 'false');
        if (menu) {
            menu.hidden = true;
        }
    };

    const closeOtherSelects = (activeSelect) => {
        customSelects.forEach((selectWrap) => {
            if (selectWrap !== activeSelect) {
                closeSelect(selectWrap);
            }
        });
    };

    const applyFilters = () => {
        const query = searchInput.value.trim().toLowerCase();
        const selectedFilter = filterButton.dataset.value || 'all';
        const sortMode = sortButton.dataset.value || 'latest';

        const filteredItems = noteItems.filter((item) => {
            const source = item.dataset.source || '';
            const haystack = [
                item.dataset.title || '',
                item.dataset.author || '',
                item.dataset.content || '',
            ].join(' ');

            return (!query || haystack.includes(query)) && (selectedFilter === 'all' || source === selectedFilter);
        });

        const sortedItems = [...filteredItems].sort((a, b) => {
            const titleA = a.dataset.title || '';
            const titleB = b.dataset.title || '';
            const timeA = Number(a.dataset.createdAt || 0);
            const timeB = Number(b.dataset.createdAt || 0);

            switch (sortMode) {
                case 'oldest':
                    return timeA - timeB;
                case 'az':
                    return titleA.localeCompare(titleB);
                case 'za':
                    return titleB.localeCompare(titleA);
                case 'latest':
                default:
                    return timeB - timeA;
            }
        });

        noteItems.forEach((item) => {
            item.style.display = 'none';
        });

        sortedItems.forEach((item) => {
            item.style.display = '';
            notesGrid.appendChild(item);
        });

        notesCount.textContent = sortedItems.length;
        if (emptySearchState) {
            emptySearchState.hidden = sortedItems.length > 0;
        }
    };

    searchInput.addEventListener('input', applyFilters);

    customSelects.forEach((selectWrap) => {
        const button = selectWrap.querySelector('[data-select-button]');
        const label = selectWrap.querySelector('[data-select-label]');
        const menu = selectWrap.querySelector('.notes-select-menu');
        const options = Array.from(selectWrap.querySelectorAll('[data-select-option]'));

        button.addEventListener('click', () => {
            const willOpen = menu.hidden;
            closeOtherSelects(selectWrap);
            button.setAttribute('aria-expanded', String(willOpen));
            menu.hidden = !willOpen;
        });

        options.forEach((option) => {
            option.addEventListener('click', () => {
                button.dataset.value = option.dataset.value;
                label.textContent = option.textContent;

                options.forEach((item) => {
                    item.setAttribute('aria-selected', String(item === option));
                });

                closeSelect(selectWrap);
                applyFilters();
            });
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-public-select]')) {
            customSelects.forEach(closeSelect);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            customSelects.forEach(closeSelect);
        }
    });

    applyFilters();
});
