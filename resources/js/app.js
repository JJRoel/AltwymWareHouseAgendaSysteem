import './bootstrap';
import { Calendar } from '@toast-ui/calendar';
import '@toast-ui/calendar/dist/toastui-calendar.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            defaultView: 'month',
            usageStatistics: false,
            // Other options can be added here
        });

        // Example to create a schedule
        calendar.createEvents([
            {
                id: '1',
                calendarId: '1',
                title: 'Meeting',
                category: 'time',
                dueDateClass: '',
                start: '2023-07-20T10:30:00+09:00',
                end: '2023-07-20T12:30:00+09:00',
            },
        ]);
    }
});
