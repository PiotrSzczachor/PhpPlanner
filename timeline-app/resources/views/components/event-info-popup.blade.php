<div class="event-popup" id="event-popup">
    <div class="modal-content" id="event-info-modal">
        <span class="close-button" id="event-info-popup-close">&times;</span>
        <h3 class="popup-header" id="event-info-popup-header">Event Details</h3>
        
        <p id="event-info-popup-description"></p>
        
        <img id="event-info-popup-image" src="" alt="Event Image" style="max-width: 100%; height: auto; margin-bottom: 15px;">
        
        <div style="padding-left: 40px; padding-right: 40px;">
            <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 10px;">
                <p><strong>Start Date: </strong></p>
                <p><span id="event-info-popup-start-date" style="background-color: #f0f0f0; padding: 5px 10px; border-radius: 5px;"></span></p>
            </div>

            <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 10px;">
                <p><strong>End Date: </strong></p>
                <p><span id="event-info-popup-end-date" style="background-color: #f0f0f0; padding: 5px 10px; border-radius: 5px;"></span></p>
            </div>

            <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 10px;">
                <p><strong>Category: </strong></p>
                <div style="display: flex; align-items: center;">
                    <span id="event-info-popup-category-name" style="background-color: #f0f0f0; padding: 5px 10px; border-radius: 5px;"></span>
                    <span id="event-info-popup-category-color" style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin-left: 8px;"></span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .event-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        margin: 100px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .popup-header {
        font-size: 1.5em;
        color: #333;
        margin-bottom: 15px;
    }

    .popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5em;
        cursor: pointer;
        color: #aaa;
    }

    .popup-close:hover {
        color: #ff5c5c;
    }

    #event-info-popup-category-color {
        margin-left: 10px;
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }

    #event-info-popup-description, 
    #event-info-popup-start-date, 
    #event-info-popup-end-date {
        font-size: 1em;
        color: #555;
        margin-bottom: 10px;
    }

    #event-info-popup-description {
        font-style: italic;
    }

    #event-info-popup-image {
        max-width: 100%;
        height: auto;
        margin: 20px 0;
    }

    .event-info {
        margin-top: 10px;
    }

    .popup-close {
        padding: 5px;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }

    .popup-close:hover {
        color: #ff5c5c;
    }
</style>


<script>
    const closeButton = document.getElementById('event-info-popup-close');

    closeButton.addEventListener('click', function() {
        document.getElementById('event-popup').style.display = 'none';
    });
</script>