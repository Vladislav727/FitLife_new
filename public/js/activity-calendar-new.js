
document.addEventListener('DOMContentLoaded', () => {
    const calendarPage = document.querySelector('.calendar-page');

    const elements = {

        calendarDays: document.getElementById('calendar-days'),
        currentMonth: document.querySelector('.current-month'),
        prevMonthBtn: document.querySelector('.prev-month'),
        nextMonthBtn: document.querySelector('.next-month'),
        todayBtn: document.getElementById('today-btn'),

        miniCalendarGrid: document.querySelector('.mini-calendar-grid'),
        miniMonthYear: document.querySelector('.mini-month-year'),
        prevMiniBtn: document.querySelector('.prev-mini'),
        nextMiniBtn: document.querySelector('.next-mini'),

        eventsTimeline: document.getElementById('events-timeline'),
        selectedDateTitle: document.getElementById('selected-date-title'),
        eventFilter: document.getElementById('event-filter'),
        upcomingList: document.getElementById('upcoming-list'),

        totalEvents: document.getElementById('total-events'),
        completedEvents: document.getElementById('completed-events'),
        workoutCount: document.getElementById('workout-count'),
        streakCount: document.getElementById('streak-count'),

        eventModal: document.getElementById('event-modal'),
        eventForm: document.getElementById('event-form'),
        modalTitle: document.getElementById('modal-title'),
        eventId: document.getElementById('event-id'),
        eventDate: document.getElementById('event-date'),
        eventDescription: document.getElementById('event-description'),
        openModalBtn: document.getElementById('open-modal-btn'),
        closeModalBtn: document.getElementById('close-modal'),
        cancelBtn: document.getElementById('cancel-btn'),
        addFirstEventBtn: document.getElementById('add-first-event'),

        deleteModal: document.getElementById('delete-modal'),
        deleteEventId: document.getElementById('delete-event-id'),
        closeDeleteModalBtn: document.getElementById('close-delete-modal'),
        cancelDeleteBtn: document.getElementById('cancel-delete-btn'),
        confirmDeleteBtn: document.getElementById('confirm-delete-btn'),

        quickBtns: document.querySelectorAll('.quick-btn'),

        toastContainer: document.getElementById('toast-container'),
    };

    let currentDate = new Date();
    let selectedDate = new Date();
    let allEvents = [];
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const translations = calendarPage?.dataset.calendarTranslations
        ? JSON.parse(calendarPage.dataset.calendarTranslations)
        : {
        noEventsToday: 'No events for this day',
        addFirstEvent: 'Add your first event',
        noUpcoming: 'No upcoming events',
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        today: 'Today',
        noDescription: 'No description'
    };

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const formatDisplayDate = (dateStr) => {
        const date = new Date(dateStr);
        const day = date.getDate();
        const month = translations.months[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    };

    const getMonthName = (date) => {
        return `${translations.months[date.getMonth()]} ${date.getFullYear()}`;
    };

    const getShortMonthName = (date) => {
        return translations.months[date.getMonth()].substring(0, 3);
    };

    const isToday = (date) => {
        const today = new Date();
        return date.getDate() === today.getDate() &&
               date.getMonth() === today.getMonth() &&
               date.getFullYear() === today.getFullYear();
    };

    const isSameDay = (date1, date2) => {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
    };

    const capitalizeFirst = (str) => str.charAt(0).toUpperCase() + str.slice(1);

    const showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <svg class="toast-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                ${type === 'success'
                    ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'
                    : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'
                }
            </svg>
            <span class="toast-message">${message}</span>
            <button class="toast-close">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        `;

        elements.toastContainer.appendChild(toast);

        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.remove();
        });

        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    };

    const fetchEvents = async (startDate, endDate) => {
        try {
            const response = await fetch(`/calendar/events?start=${startDate}&end=${endDate}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            if (!response.ok) throw new Error('Failed to fetch events');
            return await response.json();
        } catch (error) {
            console.error('Error fetching events:', error);
            showToast('Failed to load events', 'error');
            return [];
        }
    };

    const createEvent = async (formData) => {
        try {
            const response = await fetch('/calendar', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Failed to create event');
            return result;
        } catch (error) {
            console.error('Error creating event:', error);
            throw error;
        }
    };

    const updateEvent = async (id, data) => {
        try {
            const response = await fetch(`/calendar/${id}`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Failed to update event');
            return result;
        } catch (error) {
            console.error('Error updating event:', error);
            throw error;
        }
    };

    const deleteEvent = async (id) => {
        try {
            const response = await fetch(`/calendar/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || 'Failed to delete event');
            }
            return true;
        } catch (error) {
            console.error('Error deleting event:', error);
            throw error;
        }
    };

    const loadEvents = async () => {
        const start = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        const end = new Date(currentDate.getFullYear(), currentDate.getMonth() + 2, 0);
        allEvents = await fetchEvents(formatDate(start), formatDate(end));

        renderCalendar();
        renderMiniCalendar();
        renderEventsPanel();
        renderUpcoming();
        updateStats();
    };

    const renderCalendar = () => {
        elements.calendarDays.innerHTML = '';
        elements.currentMonth.textContent = getMonthName(currentDate);

        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

        let startOffset = firstDay.getDay() - 1;
        if (startOffset < 0) startOffset = 6;

        const prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
        for (let i = startOffset - 1; i >= 0; i--) {
            const day = prevMonthLastDay.getDate() - i;
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, day);
            renderDay(date, true);
        }

        for (let day = 1; day <= lastDay.getDate(); day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            renderDay(date, false);
        }

        const totalCells = elements.calendarDays.children.length;
        const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let day = 1; day <= remainingCells; day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, day);
            renderDay(date, true);
        }
    };

    const renderDay = (date, isOtherMonth) => {
        const dateStr = formatDate(date);
        const dayEvents = allEvents.filter(e => e.start === dateStr);

        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day';
        if (isOtherMonth) dayEl.classList.add('other-month');
        if (isToday(date)) dayEl.classList.add('today');
        if (isSameDay(date, selectedDate)) dayEl.classList.add('selected');
        if (dayEvents.length > 0) dayEl.classList.add('has-events');

        dayEl.innerHTML = `
            <span class="day-number">${date.getDate()}</span>
            <div class="day-events">
                ${dayEvents.slice(0, 3).map(event => `
                    <div class="day-event ${event.type} ${event.completed ? 'completed' : ''}" data-id="${event.id}">
                        ${capitalizeFirst(event.type)}
                    </div>
                `).join('')}
                ${dayEvents.length > 3 ? `<div class="more-events">+${dayEvents.length - 3} more</div>` : ''}
            </div>
        `;

        dayEl.addEventListener('click', () => {
            document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
            dayEl.classList.add('selected');
            selectedDate = date;
            renderEventsPanel();

            document.querySelectorAll('.mini-day.selected').forEach(el => el.classList.remove('selected'));
            const miniDay = document.querySelector(`.mini-day[data-date="${dateStr}"]`);
            if (miniDay) miniDay.classList.add('selected');
        });

        dayEl.addEventListener('dblclick', () => {
            openModal(dateStr);
        });

        elements.calendarDays.appendChild(dayEl);
    };

    const renderMiniCalendar = () => {
        elements.miniCalendarGrid.innerHTML = '';
        elements.miniMonthYear.textContent = getMonthName(currentDate);

        const weekdays = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
        weekdays.forEach(day => {
            const header = document.createElement('div');
            header.className = 'mini-weekday';
            header.textContent = day;
            elements.miniCalendarGrid.appendChild(header);
        });

        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

        let startOffset = firstDay.getDay() - 1;
        if (startOffset < 0) startOffset = 6;

        const prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
        for (let i = startOffset - 1; i >= 0; i--) {
            const day = prevMonthLastDay.getDate() - i;
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, day);
            renderMiniDay(date, true);
        }

        for (let day = 1; day <= lastDay.getDate(); day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            renderMiniDay(date, false);
        }

        const totalCells = elements.miniCalendarGrid.children.length;
        const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let day = 1; day <= remainingCells; day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, day);
            renderMiniDay(date, true);
        }
    };

    const renderMiniDay = (date, isOtherMonth) => {
        const dateStr = formatDate(date);
        const hasEvent = allEvents.some(e => e.start === dateStr);

        const dayEl = document.createElement('div');
        dayEl.className = 'mini-day';
        dayEl.dataset.date = dateStr;
        dayEl.textContent = date.getDate();

        if (isOtherMonth) dayEl.classList.add('other-month');
        if (isToday(date)) dayEl.classList.add('today');
        if (isSameDay(date, selectedDate)) dayEl.classList.add('selected');
        if (hasEvent) dayEl.classList.add('has-event');

        dayEl.addEventListener('click', () => {
            selectedDate = date;

            if (date.getMonth() !== currentDate.getMonth()) {
                currentDate = new Date(date.getFullYear(), date.getMonth(), 1);
                renderCalendar();
                renderMiniCalendar();
            } else {

                document.querySelectorAll('.mini-day.selected').forEach(el => el.classList.remove('selected'));
                dayEl.classList.add('selected');

                document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
                const calDay = document.querySelector(`.calendar-day:not(.other-month)`);

                const calDays = document.querySelectorAll('.calendar-day:not(.other-month)');
                calDays.forEach(d => {
                    if (d.querySelector('.day-number').textContent == date.getDate()) {
                        d.classList.add('selected');
                    }
                });
            }

            renderEventsPanel();
        });

        elements.miniCalendarGrid.appendChild(dayEl);
    };

    const renderEventsPanel = () => {
        const dateStr = formatDate(selectedDate);
        const filter = elements.eventFilter.value;
        let dayEvents = allEvents.filter(e => e.start === dateStr);

        if (filter !== 'all') {
            dayEvents = dayEvents.filter(e => e.type === filter);
        }

        elements.selectedDateTitle.textContent = formatDisplayDate(dateStr);

        if (dayEvents.length === 0) {
            elements.eventsTimeline.innerHTML = `
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <p>${translations.noEventsToday}</p>
                    <button class="add-first-event-btn" onclick="document.getElementById('open-modal-btn').click()">
                        ${translations.addFirstEvent}
                    </button>
                </div>
            `;
            return;
        }

        elements.eventsTimeline.innerHTML = dayEvents.map(event => `
            <div class="event-card ${event.type} ${event.completed ? 'completed' : ''} slide-up" data-id="${event.id}">
                <div class="event-card-header">
                    <span class="event-type-badge ${event.type}">${capitalizeFirst(event.type)}</span>
                    <div class="event-actions">
                        <button class="event-action-btn complete" data-id="${event.id}" title="${event.completed ? 'Mark incomplete' : 'Mark complete'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                ${event.completed
                                    ? '<path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path>'
                                    : '<circle cx="12" cy="12" r="10"></circle>'
                                }
                            </svg>
                        </button>
                        <button class="event-action-btn delete" data-id="${event.id}" title="Delete">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="event-description">${event.description || 'No description'}</p>
            </div>
        `).join('');

        elements.eventsTimeline.querySelectorAll('.event-action-btn.complete').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                const event = allEvents.find(ev => ev.id == id);
                if (event) {
                    try {
                        await updateEvent(id, { completed: !event.completed });
                        showToast(event.completed ? 'Event marked as incomplete' : 'Event completed!');
                        await loadEvents();
                    } catch (error) {
                        showToast(error.message, 'error');
                    }
                }
            });
        });

        elements.eventsTimeline.querySelectorAll('.event-action-btn.delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                elements.deleteEventId.value = btn.dataset.id;
                elements.deleteModal.classList.add('active');
            });
        });
    };

    const renderUpcoming = () => {
        const today = new Date();
        const upcoming = allEvents
            .filter(e => new Date(e.start) >= today && !e.completed)
            .sort((a, b) => new Date(a.start) - new Date(b.start))
            .slice(0, 5);

        if (upcoming.length === 0) {
            elements.upcomingList.innerHTML = `<p style="color: var(--cal-text-muted); font-size: 0.8125rem;">${translations.noUpcoming}</p>`;
            return;
        }

        elements.upcomingList.innerHTML = upcoming.map(event => {
            const date = new Date(event.start);
            return `
                <div class="upcoming-item" data-date="${event.start}">
                    <div class="upcoming-date">
                        <span class="date-day">${date.getDate()}</span>
                        <span class="date-month">${getShortMonthName(date)}</span>
                    </div>
                    <div class="upcoming-info">
                        <div class="upcoming-type">${capitalizeFirst(event.type)}</div>
                        <div class="upcoming-desc">${event.description || 'No description'}</div>
                    </div>
                    <span class="upcoming-indicator ${event.type}"></span>
                </div>
            `;
        }).join('');

        elements.upcomingList.querySelectorAll('.upcoming-item').forEach(item => {
            item.addEventListener('click', () => {
                const date = new Date(item.dataset.date);
                selectedDate = date;
                if (date.getMonth() !== currentDate.getMonth() || date.getFullYear() !== currentDate.getFullYear()) {
                    currentDate = new Date(date.getFullYear(), date.getMonth(), 1);
                }
                loadEvents();
            });
        });
    };

    const updateStats = () => {
        const monthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const monthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

        const monthEvents = allEvents.filter(e => {
            const date = new Date(e.start);
            return date >= monthStart && date <= monthEnd;
        });

        const total = monthEvents.length;

        const completed = monthEvents.filter(e => e.completed).length;

        const workoutTypes = ['workout', 'running', 'cycling', 'swimming', 'weightlifting', 'yoga', 'boxing', 'crossfit', 'hiking', 'dance', 'walking', 'meditation'];
        const workouts = monthEvents.filter(e => workoutTypes.includes(e.type)).length;

        let streak = 0;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 0; i < 365; i++) {
            const checkDate = new Date(today);
            checkDate.setDate(checkDate.getDate() - i);
            const dateStr = formatDate(checkDate);
            const hasCompletedWorkout = allEvents.some(e =>
                e.start === dateStr &&
                e.completed &&
                workoutTypes.includes(e.type)
            );
            if (hasCompletedWorkout) {
                streak++;
            } else if (i > 0) {

                break;
            }
        }

        if (elements.totalEvents) elements.totalEvents.textContent = total;
        if (elements.completedEvents) elements.completedEvents.textContent = completed;
        if (elements.workoutCount) elements.workoutCount.textContent = workouts;
        if (elements.streakCount) elements.streakCount.textContent = streak;
    };

    const openModal = (dateStr = null, type = null) => {
        elements.eventForm.reset();
        elements.eventId.value = '';
        elements.modalTitle.textContent = 'Add Event';

        if (dateStr) {
            elements.eventDate.value = dateStr;
        } else {
            elements.eventDate.value = formatDate(selectedDate);
        }

        if (type) {
            const typeRadio = document.querySelector(`input[name="type"][value="${type}"]`);
            if (typeRadio) typeRadio.checked = true;
        }

        elements.eventModal.classList.add('active');
        elements.eventDate.focus();
    };

    const closeModal = () => {
        elements.eventModal.classList.remove('active');

        const customTypeInput = document.getElementById('custom-type-input');
        const customTypeNameInput = document.getElementById('custom-type-name');
        if (customTypeInput) {
            customTypeInput.style.display = 'none';
        }
        if (customTypeNameInput) {
            customTypeNameInput.value = '';
        }
    };

    const closeDeleteModal = () => {
        elements.deleteModal.classList.remove('active');
        elements.deleteEventId.value = '';
    };

    elements.prevMonthBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        loadEvents();
    });

    elements.nextMonthBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        loadEvents();
    });

    elements.prevMiniBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        loadEvents();
    });

    elements.nextMiniBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        loadEvents();
    });

    elements.todayBtn?.addEventListener('click', () => {
        currentDate = new Date();
        selectedDate = new Date();
        loadEvents();
    });

    elements.eventFilter?.addEventListener('change', () => {
        renderEventsPanel();
    });

    elements.openModalBtn?.addEventListener('click', () => openModal());
    elements.addFirstEventBtn?.addEventListener('click', () => openModal());
    elements.closeModalBtn?.addEventListener('click', closeModal);
    elements.cancelBtn?.addEventListener('click', closeModal);

    elements.eventModal?.addEventListener('click', (e) => {
        if (e.target === elements.eventModal) closeModal();
    });

    const customTypeRadio = document.getElementById('custom-type-radio');
    const customTypeInput = document.getElementById('custom-type-input');
    const customTypeNameInput = document.getElementById('custom-type-name');
    const typeRadios = document.querySelectorAll('input[name="type"]');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'custom') {
                customTypeInput.style.display = 'block';
                customTypeNameInput.focus();
            } else {
                customTypeInput.style.display = 'none';
                customTypeNameInput.value = '';
            }
        });
    });

    elements.closeDeleteModalBtn?.addEventListener('click', closeDeleteModal);
    elements.cancelDeleteBtn?.addEventListener('click', closeDeleteModal);

    elements.deleteModal?.addEventListener('click', (e) => {
        if (e.target === elements.deleteModal) closeDeleteModal();
    });

    elements.confirmDeleteBtn?.addEventListener('click', async () => {
        const id = elements.deleteEventId.value;
        if (id) {
            try {
                await deleteEvent(id);
                showToast('Event deleted successfully');
                closeDeleteModal();
                await loadEvents();
            } catch (error) {
                showToast(error.message, 'error');
            }
        }
    });

    elements.eventForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(elements.eventForm);
        const selectedType = formData.get('type');

        if (!selectedType) {
            showToast('Please select an event type', 'error');
            return;
        }

        if (selectedType === 'custom') {
            const customTypeName = formData.get('custom_type')?.trim();
            if (!customTypeName) {
                showToast('Please enter a custom type name', 'error');
                document.getElementById('custom-type-name')?.focus();
                return;
            }

            formData.set('type', customTypeName.toLowerCase().replace(/\s+/g, '_'));
        }

        try {
            await createEvent(formData);
            showToast('Event created successfully!');
            closeModal();

            if (customTypeInput) {
                customTypeInput.style.display = 'none';
                customTypeNameInput.value = '';
            }
            await loadEvents();
        } catch (error) {
            showToast(error.message, 'error');
        }
    });

    elements.quickBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.type;
            openModal(formatDate(selectedDate), type);
        });
    });

    document.addEventListener('keydown', (e) => {

        if (e.key === 'Escape') {
            if (elements.deleteModal.classList.contains('active')) {
                closeDeleteModal();
            } else if (elements.eventModal.classList.contains('active')) {
                closeModal();
            }
        }

        if (!elements.eventModal.classList.contains('active') && !elements.deleteModal.classList.contains('active')) {
            if (e.key === 'ArrowLeft' && e.altKey) {
                currentDate.setMonth(currentDate.getMonth() - 1);
                loadEvents();
            } else if (e.key === 'ArrowRight' && e.altKey) {
                currentDate.setMonth(currentDate.getMonth() + 1);
                loadEvents();
            } else if (e.key === 't' && !e.ctrlKey && !e.metaKey) {

                if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                    currentDate = new Date();
                    selectedDate = new Date();
                    loadEvents();
                }
            } else if (e.key === 'n' && !e.ctrlKey && !e.metaKey) {
                if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                    openModal();
                }
            }
        }
    });

    loadEvents();
});
