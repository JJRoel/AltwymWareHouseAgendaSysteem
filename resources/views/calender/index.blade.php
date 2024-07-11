<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test TOAST UI Calendar</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css">
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f9; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        } 
        #calendar-controls { 
            display: flex; 
            justify-content: space-between; 
            width: 100%; 
            max-width: 1200px; 
            margin: 20px 0; 
            padding: 0 20px; 
        } 
        #calendar-controls button { 
            background-color: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        } 
        #calendar-controls button:hover { 
            background-color: #0056b3; 
        } 
        #calendar { 
            width: 100%; 
            max-width: 1200px; 
            height: 800px; 
            background-color: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            padding: 20px; 
        } 
        .modal { 
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        } 
        .modal-content { 
            background-color: #fefefe; 
            margin: 5% auto; 
            padding: 20px; 
            border: 1px solid #888; 
            width: 80%; 
            border-radius: 10px; 
        } 
        .close { 
            color: #aaa; 
            float: right; 
            font-size: 28px; 
            font-weight: bold; 
        } 
        .close:hover, .close:focus { 
            color: black; 
            text-decoration: none; 
            cursor: pointer; 
        } 
        #filter-container { 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            margin: 20px 0; 
        } 
        #filter-container input { 
            padding: 10px; 
            margin: 5px 0; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
        } 
        #filter-container button { 
            background-color: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        } 
        #filter-container button:hover { 
            background-color: #0056b3; 
        }
    </style>
</head>
<body>
    <div id="calendar-controls">
        <button id="prevMonthBtn">Previous Month</button>
        <button id="nextMonthBtn">Next Month</button>
    </div>
    <div id="calendar"></div>
    <div id="filter-container">
        <input type="text" id="filterUserId" placeholder="Enter User ID">
        <button id="filterBtn">Filter</button>
    </div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nieuw Evenement</h2>
            <form id="eventForm">
                <label for="userId">User ID:</label><br>
                <input type="text" id="userId" name="userId"><br>
                <label for="itemId">Item ID:</label><br>
                <select id="itemId" name="itemId"></select><br>
                <label for="description">Beschrijving:</label><br>
                <textarea id="description" name="description"></textarea><br>
                <label for="startDate">Startdatum:</label><br>
                <input type="datetime-local" id="startDate" name="startDate"><br>
                <label for="endDate">Einddatum:</label><br>
                <input type="datetime-local" id="endDate" name="endDate"><br><br>
                <button type="button" id="saveEvent">Opslaan</button>
            </form>
        </div>
    </div>
    <div id="readOnlyEventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Bekijk Evenement</h2>
            <form id="readOnlyEventForm">
                <label for="readOnlyUserId">User ID:</label><br>
                <input type="text" id="readOnlyUserId" name="userId" readonly><br>
                <label for="readOnlyItemId">Item ID:</label><br>
                <input type="text" id="readOnlyItemId" name="itemId" readonly><br>
                <label for="readOnlyDescription">Beschrijving:</label><br>
                <textarea id="readOnlyDescription" name="description"></textarea><br>
                <label for="readOnlyStartDate">Startdatum:</label><br>
                <input type="datetime-local" id="readOnlyStartDate" name="startDate"><br>
                <label for="readOnlyEndDate">Einddatum:</label><br>
                <input type="datetime-local" id="readOnlyEndDate" name="endDate"><br><br>
                <button type="button" id="updateEvent">Update</button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log("DOM fully loaded and parsed");

        const Calendar = tui.Calendar;
        let calendar;
        let allEvents = [];
        let selectedEvent = null;

        function initCalendar(date) {
            console.log("Initializing calendar");

            calendar = new Calendar('#calendar', {
                defaultView: 'month',
                useCreationPopup: true,
                useDetailPopup: false, // Disable default detail popup
                calendars: [{
                    id: '1',
                    name: 'My Calendar',
                    color: '#ffffff',
                    bgColor: '#9e5fff',
                    dragBgColor: '#9e5fff',
                    borderColor: '#9e5fff'
                }],
                date: date
            });

            fetch('/bookings')
                .then(response => response.json())
                .then(bookings => {
                    allEvents = bookings.map(booking => ({
                        id: booking.id,
                        calendarId: '1',
                        title: `Item ${booking.item_id}`,
                        category: 'time',
                        start: booking.start_date,
                        end: booking.end_date,
                        description: booking.description,
                        userId: String(booking.user_id)
                    }));
                    console.log("Bookings fetched: ", allEvents);
                    calendar.createEvents(allEvents);
                })
                .catch(error => console.error('Error fetching bookings:', error));

            calendar.on('selectDateTime', function(event) {
                console.log("DateTime selected", event);
                const selectedStart = event.start;
                const selectedEnd = event.end;
                clearForm();
                document.getElementById('startDate').value = formatDateForInput(selectedStart);
                document.getElementById('endDate').value = formatDateForInput(selectedEnd);
                loadGroups();
                selectedEvent = null;
                modal.style.display = 'block';
            });

            // Handle custom popup for clicked schedule
            calendar.on('clickSchedule', function(event) {
                const clickedEvent = event.schedule;
                console.log("Event clicked: ", clickedEvent);
                if (clickedEvent) {
                    selectedEvent = clickedEvent;
                    fillReadOnlyFormWithEventDetails(clickedEvent);
                    readOnlyModal.style.display = 'block'; // Ensure readOnlyModal is correctly referenced
                } else {
                    console.error('Clicked event is undefined.');
                }
            });


            calendar.on('beforeUpdateSchedule', function(event) {
                console.log("Before update schedule: ", event);
                selectedEvent = event.schedule;
                fillReadOnlyFormWithEventDetails(event.schedule);
                readOnlyModal.style.display = 'block';
            });

            calendar.on('beforeDeleteSchedule', function(event) {
                console.log("Before delete schedule: ", event);
                const schedule = event.schedule;
                deleteEvent(schedule);
            });

        }

        const modal = document.getElementById('eventModal');
        const readOnlyModal = document.getElementById('readOnlyEventModal');
        const closeModalElements = document.getElementsByClassName('close');

        function loadGroups() {
            console.log("Loading groups");

            fetch('/groups')
                .then(response => response.json())
                .then(groups => {
                    const itemIdSelect = document.getElementById('itemId');
                    itemIdSelect.innerHTML = '';
                    groups.forEach(group => {
                        const option = document.createElement('option');
                        option.value = group.id;
                        option.textContent = group.name;
                        itemIdSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching groups:', error));
        }

        function clearForm() {
            console.log("Clearing form");

            document.getElementById('userId').value = '';
            document.getElementById('itemId').innerHTML = '';
            document.getElementById('description').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
        }

        function fillReadOnlyFormWithEventDetails(event) {
            console.log("Filling read-only form with event details: ", event);

            document.getElementById('readOnlyUserId').value = event.userId;
            document.getElementById('readOnlyItemId').value = event.item_id; // Changed to correctly display item_id
            document.getElementById('readOnlyDescription').value = event.description;
            document.getElementById('readOnlyStartDate').value = formatDateForInput(new Date(event.start));
            document.getElementById('readOnlyEndDate').value = formatDateForInput(new Date(event.end));
        }

        function fillReadOnlyFormWithEventDetails(event) {
            console.log("Filling read-only form with event details: ", event);

            document.getElementById('readOnlyUserId').value = event.userId;
            document.getElementById('readOnlyItemId').value = `Item ${event.item_id}`; // Adjusted to match `item_id`
            document.getElementById('readOnlyDescription').value = event.description;
            document.getElementById('readOnlyStartDate').value = formatDateForInput(new Date(event.start));
            document.getElementById('readOnlyEndDate').value = formatDateForInput(new Date(event.end));
        }


        document.getElementById('saveEvent').addEventListener('click', function() {
            console.log("Save button pressed");

            const userId = document.getElementById('userId').value;
            const itemId = document.getElementById('itemId').value;
            const description = document.getElementById('description').value;
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);

            console.log("Form values: ", { userId, itemId, description, startDate, endDate });

            if (userId && itemId && startDate && endDate) {
                const schedule = {
                    user_id: userId,
                    item_id: itemId,
                    description: description,
                    start_date: formatDateForDatabase(startDate),
                    end_date: formatDateForDatabase(endDate)
                };

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (selectedEvent) {
                    // Edit existing event
                    console.log("Editing existing event: ", selectedEvent.id);

                    fetch(`/bookings/${selectedEvent.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(schedule)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => Promise.reject(text));
                        }
                        return response.json();
                    })
                    .then(data => {
                        const editedEvent = {
                            id: data.id,
                            calendarId: '1',
                            title: `Item ${data.item_id}`,
                            category: 'time',
                            start: data.start_date,
                            end: data.end_date,
                            description: data.description,
                            userId: String(data.user_id)
                        };
                        const index = allEvents.findIndex(e => e.id === data.id);
                        allEvents[index] = editedEvent;
                        calendar.updateEvent(editedEvent.id, editedEvent.calendarId, editedEvent);
                        modal.style.display = 'none';
                        refreshPage();
                    })
                    .catch(error => {
                        console.error('Error updating booking:', error);
                        alert('Error updating booking: ' + error);
                    });
                } else {
                    // Create new event
                    console.log("Creating new event");

                    fetch('/bookings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(schedule)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => Promise.reject(text));
                        }
                        return response.json();
                    })
                    .then(data => {
                        const newEvent = {
                            id: data.id,
                            calendarId: '1',
                            title: `Item ${data.item_id}`,
                            category: 'time',
                            start: data.start_date,
                            end: data.end_date,
                            description: data.description,
                            userId: String(data.user_id)
                        };
                        allEvents.push(newEvent);
                        calendar.createEvents([newEvent]);
                        modal.style.display = 'none';
                        refreshPage();
                    })
                    .catch(error => {
                        console.error('Error creating booking:', error);
                        alert('Error creating booking: ' + error);
                    });
                }
            } else {
                alert("Vul alle velden in.");
            }
        });

        document.getElementById('updateEvent').addEventListener('click', function() {
            console.log("Update button pressed");

            const description = document.getElementById('readOnlyDescription').value;
            const startDate = new Date(document.getElementById('readOnlyStartDate').value);
            const endDate = new Date(document.getElementById('readOnlyEndDate').value);

            console.log("Read-only form values: ", { description, startDate, endDate });

            if (description && startDate && endDate) {
                const schedule = {
                    description: description,
                    start_date: formatDateForDatabase(startDate),
                    end_date: formatDateForDatabase(endDate)
                };

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (selectedEvent) {
                    console.log("Updating existing event: ", selectedEvent.id);

                    fetch(`/bookings/${selectedEvent.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(schedule)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => Promise.reject(text));
                        }
                        return response.json();
                    })
                    .then(data => {
                        const editedEvent = {
                            id: data.id,
                            calendarId: '1',
                            title: `Item ${data.item_id}`,
                            category: 'time',
                            start: data.start_date,
                            end: data.end_date,
                            description: data.description,
                            userId: String(data.user_id)
                        };
                        const index = allEvents.findIndex(e => e.id === data.id);
                        allEvents[index] = editedEvent;
                        calendar.updateEvent(editedEvent.id, editedEvent.calendarId, editedEvent);
                        readOnlyModal.style.display = 'none';
                        refreshPage();
                    })
                    .catch(error => {
                        console.error('Error updating booking:', error);
                        alert('Error updating booking: ' + error);
                    });
                }
            } else {
                alert("Vul alle velden in.");
            }
        });

        function deleteEvent(event) {
            console.log("Deleting event: ", event);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/bookings/${event.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => Promise.reject(text));
                }
                return response.json();
            })
            .then(data => {
                allEvents = allEvents.filter(e => e.id !== event.id);
                calendar.deleteEvent(event.id, event.calendarId);
                alert('Event deleted successfully');
            })
            .catch(error => {
                console.error('Error deleting booking:', error);
                alert('Error deleting booking: ' + error);
            });
        }


        // Close modal when the close button is clicked
        Array.from(closeModalElements).forEach(function(element) {
            element.onclick = function() {
                modal.style.display = "none";
                readOnlyModal.style.display = "none";
                refreshPage();
            };
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                refreshPage();
            } else if (event.target == readOnlyModal) {
                readOnlyModal.style.display = "none";
                refreshPage();
            }
        };

        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        function formatDateForDatabase(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        function refreshPage() {
            localStorage.setItem('currentMonth', calendar.getDate().toString());
            location.reload();
        }

        const prevMonthBtn = document.getElementById('prevMonthBtn');
        const nextMonthBtn = document.getElementById('nextMonthBtn');

        prevMonthBtn.addEventListener('click', function() {
            calendar.prev();
            saveCurrentMonth();
        });

        nextMonthBtn.addEventListener('click', function() {
            calendar.next();
            saveCurrentMonth();
        });

        function saveCurrentMonth() {
            localStorage.setItem('currentMonth', calendar.getDate().toString());
        }

        const savedMonth = localStorage.getItem('currentMonth');
        if (savedMonth) {
            initCalendar(new Date(savedMonth));
            localStorage.removeItem('currentMonth');
        } else {
            initCalendar(new Date());
        }

        document.getElementById('filterBtn').addEventListener('click', function() {
            const userId = document.getElementById('filterUserId').value;
            if (userId) {
                const filteredEvents = allEvents.filter(event => event.userId === userId);
                calendar.clear();
                calendar.createEvents(filteredEvents);
            } else {
                calendar.clear();
                calendar.createEvents(allEvents);
            }
        });
    });
    </script>
</body>
</html>
