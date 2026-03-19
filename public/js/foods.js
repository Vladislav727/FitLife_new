document.addEventListener('DOMContentLoaded', () => {
    const config = window.mealTrackerConfig || {};
    const t = config.translations || {};
    const csrfToken = config.csrfToken;
    let currentMeal = 'Breakfast';
    let itemCounter = 1;

    // ── Notification ──
    function showNotification(message, type = 'success') {
        const el = document.getElementById('notification');
        if (!el) return;
        el.textContent = message;
        el.className = `mt-notification ${type}`;
        setTimeout(() => { el.className = 'mt-notification'; }, 4000);
    }

    // ── Meal Tab Selector ──
    document.querySelectorAll('.mt-meal-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.mt-meal-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentMeal = tab.dataset.meal;
        });
    });

    // ── Lookup food via API ──
    async function lookupFood(foodInput, item) {
        const query = foodInput.value.trim();
        if (!query) return;

        const suggestionsEl = item.querySelector('.mt-suggestions');
        suggestionsEl.innerHTML = `<div class="mt-suggestions__loading">${t.searching || 'Searching...'}</div>`;
        suggestionsEl.style.display = 'block';

        try {
            const response = await fetch(config.lookupUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ query }),
            });

            const data = await response.json();

            if (data.success && data.items.length > 0) {
                suggestionsEl.innerHTML = '';
                data.items.forEach(food => {
                    const option = document.createElement('div');
                    option.className = 'mt-suggestions__item';
                    option.innerHTML = `
                        <span class="mt-suggestions__name">${food.name}</span>
                        <span class="mt-suggestions__meta">${food.calories} ${t.kcal || 'kcal'} / ${food.serving_size}${t.g || 'g'}</span>
                    `;
                    option.addEventListener('click', () => {
                        selectFood(item, food, foodInput);
                        suggestionsEl.style.display = 'none';
                    });
                    suggestionsEl.appendChild(option);
                });
            } else {
                suggestionsEl.innerHTML = `<div class="mt-suggestions__empty">${t.no_results || 'No results found'}</div>`;
            }
        } catch {
            suggestionsEl.innerHTML = `<div class="mt-suggestions__empty">${t.error || 'Error'}</div>`;
        }
    }

    // ── Select food from suggestions ──
    function selectFood(item, food, foodInput) {
        foodInput.value = food.name;
        item.dataset.calories = food.calories;
        item.dataset.servingSize = food.serving_size;
        item.dataset.protein = food.protein;
        item.dataset.fat = food.fat;
        item.dataset.carbs = food.carbs;

        updateItemInfo(item);
    }

    // ── Update nutrition info display ──
    function updateItemInfo(item) {
        const qty = parseFloat(item.querySelector('.mt-input--qty').value) || 0;
        const servingSize = parseFloat(item.dataset.servingSize) || 100;
        const ratio = qty / servingSize;

        const cal = Math.round((parseFloat(item.dataset.calories) || 0) * ratio);
        const protein = ((parseFloat(item.dataset.protein) || 0) * ratio).toFixed(1);
        const fat = ((parseFloat(item.dataset.fat) || 0) * ratio).toFixed(1);
        const carbs = ((parseFloat(item.dataset.carbs) || 0) * ratio).toFixed(1);

        const infoEl = item.querySelector('.mt-food-item__info');
        if (cal > 0 || qty > 0) {
            infoEl.style.display = 'flex';
            infoEl.querySelector('.mt-info-tag--cal .val').textContent = cal;
            infoEl.querySelector('.mt-info-tag--protein .val').textContent = protein;
            infoEl.querySelector('.mt-info-tag--fat .val').textContent = fat;
            infoEl.querySelector('.mt-info-tag--carbs .val').textContent = carbs;
        } else {
            infoEl.style.display = 'none';
        }
    }

    // ── Attach listeners to a food item ──
    function attachItemListeners(item) {
        const foodInput = item.querySelector('.mt-input--food');
        const qtyInput = item.querySelector('.mt-input--qty');
        const lookupBtn = item.querySelector('.mt-lookup-btn');
        const removeBtn = item.querySelector('.mt-remove-btn');
        const suggestionsEl = item.querySelector('.mt-suggestions');

        // Debounced auto-lookup on typing
        let debounceTimer;
        foodInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (foodInput.value.trim().length >= 2) {
                    lookupFood(foodInput, item);
                } else {
                    suggestionsEl.style.display = 'none';
                }
            }, 500);
        });

        // Manual lookup button
        lookupBtn.addEventListener('click', () => lookupFood(foodInput, item));

        // Enter key triggers lookup
        foodInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupFood(foodInput, item);
            }
        });

        // Quantity changes recalculate info
        qtyInput.addEventListener('input', () => updateItemInfo(item));

        // Remove item
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                item.remove();
                updateRemoveButtons();
            });
        }

        // Close suggestions on outside click
        document.addEventListener('click', (e) => {
            if (!item.contains(e.target)) {
                suggestionsEl.style.display = 'none';
            }
        });
    }

    // ── Update remove button visibility ──
    function updateRemoveButtons() {
        const items = document.querySelectorAll('#food-items .mt-food-item');
        items.forEach(item => {
            const btn = item.querySelector('.mt-remove-btn');
            if (btn) btn.style.display = items.length > 1 ? 'flex' : 'none';
        });
    }

    // ── Add new food item row ──
    document.getElementById('add-item-btn').addEventListener('click', () => {
        const container = document.getElementById('food-items');
        const div = document.createElement('div');
        div.className = 'mt-food-item';
        div.dataset.index = itemCounter++;
        div.innerHTML = `
            <div class="mt-food-item__row">
                <div class="mt-food-item__input-group">
                    <input type="text" class="mt-input mt-input--food" placeholder="${t.food_placeholder || 'What did you eat?'}" autocomplete="off">
                    <div class="mt-suggestions" style="display:none;"></div>
                </div>
                <input type="number" class="mt-input mt-input--qty" placeholder="${t.g_ml || 'g/ml'}" min="1" max="10000">
                <button type="button" class="mt-lookup-btn" title="${t.search || 'Search'}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
                <button type="button" class="mt-remove-btn" style="display:flex;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="mt-food-item__info" style="display:none;">
                <span class="mt-info-tag mt-info-tag--cal"><span class="val">0</span> ${t.kcal || 'kcal'}</span>
                <span class="mt-info-tag mt-info-tag--protein">P: <span class="val">0</span>${t.g || 'g'}</span>
                <span class="mt-info-tag mt-info-tag--fat">F: <span class="val">0</span>${t.g || 'g'}</span>
                <span class="mt-info-tag mt-info-tag--carbs">C: <span class="val">0</span>${t.g || 'g'}</span>
            </div>
        `;
        container.appendChild(div);
        attachItemListeners(div);
        updateRemoveButtons();
        div.querySelector('.mt-input--food').focus();
    });

    // ── Save meals ──
    document.getElementById('save-btn').addEventListener('click', async () => {
        const items = document.querySelectorAll('#food-items .mt-food-item');
        const payload = [];

        items.forEach(item => {
            const food = item.querySelector('.mt-input--food').value.trim();
            const qty = parseFloat(item.querySelector('.mt-input--qty').value) || 0;
            if (food && qty > 0) {
                payload.push({
                    food,
                    quantity: qty,
                    meal: currentMeal,
                    calories_per_serving: parseFloat(item.dataset.calories) || 0,
                    serving_size: parseFloat(item.dataset.servingSize) || 100,
                    protein: parseFloat(item.dataset.protein) || 0,
                    fat: parseFloat(item.dataset.fat) || 0,
                    carbs: parseFloat(item.dataset.carbs) || 0,
                });
            }
        });

        if (payload.length === 0) {
            showNotification(t.empty_items || 'Add at least one food item', 'error');
            return;
        }

        const saveBtn = document.getElementById('save-btn');
        saveBtn.disabled = true;
        const origHTML = saveBtn.innerHTML;
        saveBtn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/></svg> ${t.saving || 'Saving...'}`;

        try {
            const response = await fetch(config.calculateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ items: payload }),
            });

            const data = await response.json();

            if (data.success) {
                showNotification(t.saved || 'Meal logged!', 'success');

                // Update today's summary
                document.getElementById('sum-calories').textContent = data.daily.calories;
                document.getElementById('sum-protein').textContent = data.daily.protein + (t.g || 'g');
                document.getElementById('sum-fats').textContent = data.daily.fats + (t.g || 'g');
                document.getElementById('sum-carbs').textContent = data.daily.carbs + (t.g || 'g');

                // Add new rows to history table
                const tbody = document.querySelector('.history-table tbody');
                if (tbody) {
                    const noData = tbody.querySelector('.no-data');
                    if (noData) noData.closest('tr').remove();

                    data.logs.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.created_at}</td>
                            <td>${log.meal}</td>
                            <td>${log.food}</td>
                            <td>${log.quantity} ${t.g || 'g'}</td>
                            <td>${log.calories} ${t.kcal || 'kcal'}</td>
                            <td>${log.protein}${t.g || 'g'} / ${log.fats}${t.g || 'g'} / ${log.carbs}${t.g || 'g'}</td>
                            <td>
                                <button class="mt-delete-log" data-id="${log.id}" title="${t.confirm_delete || 'Delete'}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                </button>
                            </td>`;
                        tbody.insertBefore(row, tbody.firstChild);
                    });
                    attachDeleteListeners();
                }

                // Reset form
                document.getElementById('food-items').innerHTML = '';
                const firstItem = document.createElement('div');
                firstItem.className = 'mt-food-item';
                firstItem.dataset.index = '0';
                firstItem.innerHTML = `
                    <div class="mt-food-item__row">
                        <div class="mt-food-item__input-group">
                            <input type="text" class="mt-input mt-input--food" placeholder="${t.food_placeholder || 'What did you eat?'}" autocomplete="off">
                            <div class="mt-suggestions" style="display:none;"></div>
                        </div>
                        <input type="number" class="mt-input mt-input--qty" placeholder="${t.g_ml || 'g/ml'}" min="1" max="10000">
                        <button type="button" class="mt-lookup-btn" title="${t.search || 'Search'}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </button>
                        <button type="button" class="mt-remove-btn" style="display:none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-food-item__info" style="display:none;">
                        <span class="mt-info-tag mt-info-tag--cal"><span class="val">0</span> ${t.kcal || 'kcal'}</span>
                        <span class="mt-info-tag mt-info-tag--protein">P: <span class="val">0</span>${t.g || 'g'}</span>
                        <span class="mt-info-tag mt-info-tag--fat">F: <span class="val">0</span>${t.g || 'g'}</span>
                        <span class="mt-info-tag mt-info-tag--carbs">C: <span class="val">0</span>${t.g || 'g'}</span>
                    </div>
                `;
                document.getElementById('food-items').appendChild(firstItem);
                attachItemListeners(firstItem);
                itemCounter = 1;
            } else {
                showNotification(data.message || t.error || 'Error', 'error');
            }
        } catch (err) {
            showNotification(t.error || 'Network error', 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = origHTML;
        }
    });

    // ── Delete log entry ──
    function attachDeleteListeners() {
        document.querySelectorAll('.mt-delete-log').forEach(btn => {
            btn.removeEventListener('click', handleDelete);
            btn.addEventListener('click', handleDelete);
        });
    }

    async function handleDelete(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;

        try {
            const response = await fetch(`${config.destroyUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                btn.closest('tr').remove();
                showNotification(t.deleted || 'Deleted', 'success');
                // Reload to update summary — simple approach
                window.location.reload();
            }
        } catch {
            showNotification(t.error || 'Error', 'error');
        }
    }

    // ── AJAX Pagination ──
    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', async e => {
                e.preventDefault();
                if (link.classList.contains('disabled')) return;

                const url = link.getAttribute('href');
                const historySection = document.getElementById('history-section');
                const scrollY = window.scrollY;

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newSection = doc.getElementById('history-section');
                    if (newSection && historySection) {
                        historySection.innerHTML = newSection.innerHTML;
                        window.scrollTo(0, scrollY);
                        attachPaginationListeners();
                        attachDeleteListeners();
                    }
                } catch {
                    showNotification(t.error || 'Error loading page', 'error');
                }
            });
        });
    }

    // ── Initialize ──
    document.querySelectorAll('#food-items .mt-food-item').forEach(attachItemListeners);
    attachPaginationListeners();
    attachDeleteListeners();
});
