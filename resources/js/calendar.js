export default function (initialMonth, initialYear, initialEvents, todayStr, interviewsList = [], applicationsList = []) {
    return {
        month: initialMonth,
        year: initialYear,
        events: initialEvents,
        today: todayStr,
        showModal: false,
        selectedDate: '',
        selectedDayEvents: [],
        reminderFormOpen: false,
        editingReminder: null,
        reminderForm: { title: '', description: '', time: '09:00', remindable_type: '', remindable_id: '' },
        interviewsList,
        applicationsList,
        dayNames: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],

        get linkableOptions() {
            return [
                { id: '', type: '', label: 'None' },
                ...this.interviewsList.map(i => ({ ...i, label: 'Interview: ' + i.label })),
                ...this.applicationsList.map(a => ({ ...a, label: 'Application: ' + a.label })),
            ];
        },

        get selectedLinkableType() {
            const match = this.linkableOptions.find(o => String(o.id) === String(this.reminderForm.remindable_id));
            return match ? match.type : '';
        },

        get calendarDays() {
            const firstDay = new Date(this.year, this.month - 1, 1).getDay();
            const daysInMonth = new Date(this.year, this.month, 0).getDate();
            const daysInPrevMonth = new Date(this.year, this.month - 1, 0).getDate();
            const cells = [];
            const paddingStart = firstDay === 0 ? 6 : firstDay - 1;

            for (let i = paddingStart - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const dateStr = this._fmt(this.month === 1 ? 12 : this.month - 1, day, this.month === 1 ? this.year - 1 : this.year);
                cells.push({ day, date: dateStr, isCurrentMonth: false, events: this._eventsForDate(dateStr) });
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = this._fmt(this.month, day, this.year);
                cells.push({ day, date: dateStr, isCurrentMonth: true, events: this._eventsForDate(dateStr) });
            }

            const remaining = 7 - (cells.length % 7);
            if (remaining < 7) {
                for (let day = 1; day <= remaining; day++) {
                    const dateStr = this._fmt(this.month === 12 ? 1 : this.month + 1, day, this.month === 12 ? this.year + 1 : this.year);
                    cells.push({ day, date: dateStr, isCurrentMonth: false, events: this._eventsForDate(dateStr) });
                }
            }

            return cells;
        },

        _fmt(month, day, year) {
            return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        },

        _eventsForDate(date) {
            const found = this.events.find(e => e.date === date);
            return found ? found.items : [];
        },

        prevMonth() {
            if (this.month === 1) { this.month = 12; this.year--; }
            else { this.month--; }
            this._fetch();
        },

        nextMonth() {
            if (this.month === 12) { this.month = 1; this.year++; }
            else { this.month++; }
            this._fetch();
        },

        goToToday() {
            const now = new Date();
            this.month = now.getMonth() + 1;
            this.year = now.getFullYear();
            this._fetch();
        },

        _fetch() {
            fetch(`/calendar/events?month=${this.month}&year=${this.year}`)
                .then(r => r.json())
                .then(data => { this.events = data; });
        },

        openDay(cell) {
            this.selectedDate = cell.date;
            this.selectedDayEvents = cell.events || [];
            this.reminderFormOpen = false;
            this.editingReminder = null;
            this.reminderForm = { title: '', description: '', time: '09:00', remindable_type: '', remindable_id: '' };
            this.showModal = true;
        },

        formatDate(dateStr) {
            const d = new Date(dateStr + 'T12:00:00');
            return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        },

        completeReminder(event) {
            fetch(`/calendar/reminders/${event.id}/complete`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            }).then(r => {
                if (r.ok) {
                    this.selectedDayEvents = this.selectedDayEvents.filter(e => !(e.id === event.id && e.type === 'reminder'));
                    this._fetch();
                }
            });
        },

        editReminder(event) {
            this.editingReminder = event;
            this.reminderForm = {
                title: event.title,
                description: event.description || '',
                time: event.time || '09:00',
                remindable_type: event.remindable_type || '',
                remindable_id: event.remindable_id || '',
            };
            this.reminderFormOpen = true;
        },

        cancelEdit() {
            this.editingReminder = null;
            this.reminderForm = { title: '', description: '', time: '09:00', remindable_type: '', remindable_id: '' };
            this.reminderFormOpen = false;
        },

        submitReminder() {
            const remindAt = this.selectedDate + ' ' + (this.reminderForm.time || '09:00') + ':00';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            if (this.editingReminder) {
                this.updateReminder(remindAt, csrfToken);
            } else {
                this.createReminder(remindAt, csrfToken);
            }
        },

        createReminder(remindAt, csrfToken) {
            const remindableType = this.selectedLinkableType;
            const remindableId = this.reminderForm.remindable_id;

            const payload = {
                title: this.reminderForm.title,
                description: this.reminderForm.description,
                remind_at: remindAt,
            };

            if (remindableType && remindableId) {
                payload.remindable_type = remindableType;
                payload.remindable_id = remindableId;
            }

            fetch('/calendar/reminders', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            }).then(r => {
                if (!r.ok) {
                    return r.json().then(err => { throw new Error(JSON.stringify(err)); });
                }
                return r.json();
            }).then(data => {
                if (data.success) {
                    const linked = remindableType && remindableId
                        ? this.linkableOptions.find(o => o.type === remindableType && String(o.id) === String(remindableId))
                        : null;

                    this.selectedDayEvents.push({
                        id: data.reminder.id,
                        title: data.reminder.title,
                        description: data.reminder.description,
                        date: this.selectedDate,
                        time: this.reminderForm.time || '09:00',
                        type: 'reminder',
                        url: null,
                        company: null,
                        remindable_type: data.reminder.remindable_type || null,
                        remindable_id: data.reminder.remindable_id || null,
                        remindable_label: linked ? linked.label : null,
                    });
                    this.reminderFormOpen = false;
                    this.reminderForm = { title: '', description: '', time: '09:00', remindable_type: '', remindable_id: '' };
                    this._fetch();
                }
            }).catch(err => {
                alert('Error saving reminder: ' + err.message);
            });
        },

        updateReminder(remindAt, csrfToken) {
            const remindableType = this.selectedLinkableType;
            const remindableId = this.reminderForm.remindable_id;

            const payload = {
                title: this.reminderForm.title,
                description: this.reminderForm.description,
                remind_at: remindAt,
            };

            if (remindableType && remindableId) {
                payload.remindable_type = remindableType;
                payload.remindable_id = remindableId;
            }

            fetch(`/calendar/reminders/${this.editingReminder.id}`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            }).then(r => {
                if (!r.ok) {
                    return r.json().then(err => { throw new Error(JSON.stringify(err)); });
                }
                return r.json();
            }).then(data => {
                if (data.success) {
                    const linked = remindableType && remindableId
                        ? this.linkableOptions.find(o => o.type === remindableType && String(o.id) === String(remindableId))
                        : null;

                    const idx = this.selectedDayEvents.findIndex(e => e.id === this.editingReminder.id && e.type === 'reminder');
                    if (idx !== -1) {
                        this.selectedDayEvents[idx] = {
                            ...this.selectedDayEvents[idx],
                            title: data.reminder.title,
                            description: data.reminder.description,
                            time: this.reminderForm.time || '09:00',
                            remindable_type: data.reminder.remindable_type || null,
                            remindable_id: data.reminder.remindable_id || null,
                            remindable_label: linked ? linked.label : null,
                        };
                    }
                    this.editingReminder = null;
                    this.reminderFormOpen = false;
                    this.reminderForm = { title: '', description: '', time: '09:00', remindable_type: '', remindable_id: '' };
                    this._fetch();
                }
            }).catch(err => {
                alert('Error updating reminder: ' + err.message);
            });
        },

        deleteReminder(event) {
            if (!confirm('Delete this reminder?')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch(`/calendar/reminders/${event.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            }).then(r => {
                if (r.ok) {
                    this.selectedDayEvents = this.selectedDayEvents.filter(e => !(e.id === event.id && e.type === 'reminder'));
                    this._fetch();
                }
            });
        },
    };
}
