<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline</title>
    <link rel="stylesheet" href="https://unpkg.com/vis-timeline/styles/vis-timeline-graph2.min.css">
    <style>
        body {
            margin: 0px;
            height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding-left: 20px;
            padding-right: 20px;
            height: 7%;
            min-height: 42px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .user-info {
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .user-info img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .header-btns button {
            margin-left: 10px;
            padding: 8px 15px;
            background-color: #ff5733;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
        }
        .header-btns button:hover {
            background-color: #e74c3c;
        }

        #timeline { height: 600px; }
        .event-popup { 
            padding: 20px;
            display: none;
            position: absolute; 
            background-color: white; 
            border: 1px solid black;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }
        .popup-header {
            font-size: 20px;
            font-weight: bold;
        }
        .popup-close {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }
        .form-container { display: none; }
        .form-container input, .form-container textarea { margin: 10px 0; }

        * {
            font-family: "Roboto", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .info-stripe {
            background-color: #f39c12;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #timeline, #timeline * {
                visibility: visible;
            }

            #timeline {
                position: absolute;
                left: 0;
                top: 0;
            }
    }

    </style>
</head>
<body>
    <header>
        <div class="user-info">
            @if(auth()->check())
                <img src="https://www.gravatar.com/avatar/{{ md5(auth()->user()->email) }}" alt="User Image" width="40" height="40">
                <span>{{ auth()->user()->name }} {{ auth()->user()->surname }}</span>
            @else
                <img src="https://www.gravatar.com/avatar/?d=mp" alt="Guest User Icon" width="40" height="40">
                <span>Guest</span>
            @endif
        </div>
        <div class="header-btns">
        <button id="print-btn">Print Timeline</button>
            @if(auth()->check())
                <button id="open-category-modal" class="btn btn-primary">Manage Categories</button>
                <button id="reset-password-btn" class="btn btn-primary" onclick="window.location.href='/change-password'">Reset Password</button>
                <button id="logout-btn">Logout</button>
            @else
                <a href="/" class="btn btn-primary">Login</a>
            @endif
        </div>
    </header>

    <div class="info-stripe">
        <p>To add an event, double-click on the timeline. To open event details, click on an event.</p>
    </div>

    <div id="timeline"></div>

    @include('components.category-popup')
    @include('components.add-event-popup')
    @include('components.event-info-popup')


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js"></script>

    <script>
        $(document).ready(function() {
            let timeline = null;
            const userIsLoggedIn = '{{ auth()->check() }}';

            if (!userIsLoggedIn) {
                $('#open-category-modal').prop('disabled', true);
                $('#open-category-modal').text('Please log in to manage categories');
            }

            function fetchAndRenderTimeline() {
                $.get('/api/events', function(data) {
                    const items = data.map(event => ({
                        id: event.id,
                        content: event.name,
                        start: event.startDate,
                        end: event.endDate,
                        image: event.image,
                        style: `background-color: ${event.category.color}`,
                        editable: userIsLoggedIn ? {updateTime: false, remove: true} : false,
                        data: {event: event}
                    }));

                    if (timeline) {
                        timeline.destroy();
                    }

                    const container = document.getElementById('timeline');
                    const options = {
                        height: "100%",
                        editable: true,
                        onRemove: function (item, callback) {
                            if (!userIsLoggedIn) {
                                alert('You need to log in to delete events.');
                                callback(false);
                                return;
                            }

                            if (confirm("Are you sure you want to delete this event?")) {
                                $.ajax({
                                    url: '/api/events/' + item.id,
                                    type: 'DELETE',
                                    dataType: 'JSON',
                                    data: {
                                        'id': item.id,
                                        '_token': '{{ csrf_token() }}',
                                    },
                                    success: function(response) {
                                        callback();
                                        $('#event-popup').hide();
                                        fetchAndRenderTimeline();
                                    },
                                    error: function() {
                                        alert("There was an error while trying to delete this event.");
                                        callback(false);
                                    }
                                });
                            } else {
                                callback(false); 
                            }
                        },
                        onAdd: function (item, callback) {
                            if (!userIsLoggedIn) {
                                alert('You need to log in to add events.');
                                callback(false);
                                return;
                            }

                            $('#event-form-container').show();
                            $('#event-form')[0].reset();

                            $('#event-form').submit(function(event) {
                                event.preventDefault();

                                const formData = new FormData();
                                formData.append('name', $('#event-name').val());
                                formData.append('startDate', $('#event-start-date').val());
                                formData.append('endDate', $('#event-end-date').val());
                                formData.append('description', $('#event-description').val());
                                formData.append('image', $('#event-image')[0].files[0]);
                                formData.append('category_id', $('#event-category').val());
                                formData.append('_token', '{{ csrf_token() }}');

                                $.ajax({
                                    url: '/api/events',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        const newEvent = {
                                            id: response.id,
                                            content: response.name,
                                            start: response.startDate,
                                            end: response.endDate,
                                            image: response.image,
                                            category: response.category
                                        };
                                        callback();
                                        fetchAndRenderTimeline();
                                        $('#event-form-container').hide();
                                    },
                                    error: function() {
                                        alert('There was an error while adding the event.');
                                    }
                                });

                                $('#event-form-container').hide();
                            });

                            $('#cancel-event').click(function() {
                                $('#event-form-container').hide();
                            });
                        },
                    };

                    timeline = new vis.Timeline(container, new vis.DataSet(items), options);

                    document.getElementById('timeline').onclick = function (event) {
                        var props = timeline.getEventProperties(event);
                        var clickedEvent = items.find(e => e.id === props.item);

                        if (clickedEvent) {
                            showEventPopup(clickedEvent);
                        }
                    };
                });
            }

            function showEventPopup(event) {
                const categoryName = event.data.event.category ? event.data.event.category.name : 'No category';
                const categoryColor = event.data.event.category ? event.data.event.category.color : '#000000';

                document.getElementById('event-info-popup-header').textContent = event.data.event.name;
                document.getElementById('event-info-popup-description').textContent = event.data.event.description;
                document.getElementById('event-info-popup-start-date').textContent = event.data.event.startDate;
                document.getElementById('event-info-popup-end-date').textContent = event.data.event.endDate;
                document.getElementById('event-info-popup-image').src = '/storage/' + event.data.event.image;
                document.getElementById('event-info-popup-category-name').textContent = categoryName;
                document.getElementById('event-info-popup-category-color').style.backgroundColor = categoryColor;

                document.getElementById('event-popup').style.display = 'block';
            }
            if(document.getElementById('popup-close') && document.getElementById('popup-close').onclick != null){
                document.getElementById('popup-close').onclick = function() {
                document.getElementById('event-popup').style.display = 'none';
            };
            }
            

            fetchAndRenderTimeline();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logout-btn');

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'User logged out successfully') {
                                window.location.href = '/';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            }
        });

        document.getElementById('print-btn').addEventListener('click', function() {
            window.print();
        });

    </script>
</body>
</html>
