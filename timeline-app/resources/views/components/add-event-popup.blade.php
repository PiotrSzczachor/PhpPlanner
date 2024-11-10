<div class="form-container" id="event-form-container">
    <div class="modal-content">
        <h2>Create New Event</h2>

        <form id="event-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event-name">Event Name:</label>
                <input type="text" id="event-name" class="form-control" required><br>
            </div>

            <div class="form-group">
                <label for="event-start-date">Start Date:</label>
                <input type="datetime-local" id="event-start-date" class="form-control" required><br>
            </div>

            <div class="form-group">
                <label for="event-end-date">End Date:</label>
                <input type="datetime-local" id="event-end-date" class="form-control" required><br>
            </div>

            <div class="form-group">
                <label for="event-description">Description:</label>
                <textarea id="event-description" class="form-control" required></textarea><br>
            </div>

            <div class="form-group">
                <label for="event-image">Event Image:</label>
                <input type="file" id="event-image" class="form-control" accept="image/*"><br>
            </div>

            <label for="event-category">Category:</label>
            <select id="event-category" class="form-control" required>
                <option value="">Select a Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select><br>

            <button type="submit" class="btn btn-primary">Add Event</button>
            <button id="cancel-event" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>

<!-- Styling for the popup modal -->
<style>
    /* Modal overlay */
    .form-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    /* Modal content */
    .modal-content {
        background-color: #fefefe;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        margin: 100px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .close-button {
        float: right;
        font-size: 1.5em;
        cursor: pointer;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 5px;
    }

    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
    }

    /* Cancel button */
    #cancel-event {
        margin-top: 10px;
        background-color: #f0f0f0;
        color: #333;
        font-size: 0.9em;
    }

    #cancel-event:hover {
        background-color: #e2e2e2;
    }

    /* Add margin to the button */
    button[type="submit"] {
        margin-top: 20px;
    }

    /* Additional styling for form elements */
    textarea.form-control {
        resize: vertical;
        height: 100px;
    }

    /* Modal container */
    .form-container .modal-content {
        width: 450px;
    }

    .form-container select.form-control {
        height: 40px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>
