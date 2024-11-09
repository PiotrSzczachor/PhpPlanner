<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline</title>
    <link rel="stylesheet" href="https://unpkg.com/vis-timeline/styles/vis-timeline-graph2.min.css">
    <style>
        #timeline { height: 400px; }
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
    </style>
</head>
<body>
    <div id="timeline"></div>

    <!-- Event Info Popup -->
    <div class="event-popup" id="event-popup">
        <span class="popup-close" id="popup-close">&times;</span>
        <h3 class="popup-header" id="popup-header">Event Details</h3>
        <p id="popup-description"></p>
        <img id="popup-image" src="" alt="Event Image" style="max-width: 100%; height: auto;">
        <p><strong>Start Date: </strong><span id="popup-start-date"></span></p>
        <p><strong>End Date: </strong><span id="popup-end-date"></span></p>
    </div>

    <!-- Add Event Form -->
    <div class="form-container" id="event-form-container">
        <h3>Create New Event</h3>
        <form id="event-form" enctype="multipart/form-data">
            <label for="event-name">Event Name:</label>
            <input type="text" id="event-name" required><br>

            <label for="event-start-date">Start Date:</label>
            <input type="datetime-local" id="event-start-date" required><br>

            <label for="event-end-date">End Date:</label>
            <input type="datetime-local" id="event-end-date" required><br>

            <label for="event-description">Description:</label>
            <textarea id="event-description" required></textarea><br>

            <label for="event-image">Event Image:</label>
            <input type="file" id="event-image" accept="image/*"><br>

            <button type="submit">Add Event</button>
        </form>
        <button id="cancel-event" type="button">Cancel</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js"></script>

    <script>
        $(document).ready(function() {
            let timeline = null;

            function fetchAndRenderTimeline() {
                $.get('/api/events', function(data) {
                    const items = data.map(event => ({
                        id: event.id,
                        content: event.name,
                        start: event.startDate,
                        end: event.endDate,
                        image: event.image
                    }));

                    if (timeline) {
                        timeline.destroy();
                    }

                    const container = document.getElementById('timeline');
                    const options = {
                        editable: true,
                        onRemove: function (item, callback) {
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
                                            image: response.image
                                        };
                                        callback()
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
                $('#popup-header').text(event.content);
                $('#popup-description').text(event.description);
                $('#popup-start-date').text(event.start);
                $('#popup-end-date').text(event.end);
                if (event.image) {
                    $('#popup-image').show();
                    $('#popup-image').attr('src', 'storage/' + event.image);
                } else {
                    $('#popup-image').hide();
                }

                // Show the popup
                $('#event-popup').show();
            }

            // Close the popup when the close button is clicked
            $('#popup-close').click(function() {
                $('#event-popup').hide();
            });

            // Initial call to render the timeline
            fetchAndRenderTimeline();
        });
    </script>
</body>
</html>
