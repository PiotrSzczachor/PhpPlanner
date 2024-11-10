<div id="category-modal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Manage Categories</h2>

        <form id="add-category-form">
            <div class="form-group">
                <label for="category-name">Category Name:</label>
                <input type="text" id="category-name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="category-color">Color:</label>
                <input type="color" id="category-color" class="form-control" required>
            </div>

            <button type="button" onclick="addCategory()" class="btn btn-primary">Add Category</button>
        </form>

        <h3>Existing Categories</h3>
        <ul id="category-list">
            @foreach ($categories as $category)
                <li>
                    <span class="category-name" style="color: black;">
                        {{ $category->name }}
                    </span>
                    <div class="category-actions">
                        <span class="category-color" style="background-color: {{ $category->color }};"></span>
                        <button onclick="deleteCategory({{ $category->id }})" class="btn btn-danger btn-sm">Delete</button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
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

    .close-button {
        float: right;
        font-size: 1.5em;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
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

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-sm {
        font-size: 0.8em;
        padding: 4px 8px;
        margin-left: 8px;
    }

    h2, h3 {
        color: #333;
    }

    ul#category-list {
        list-style: none;
        padding: 0;
    }

    ul#category-list li {
        margin: 8px 0;
        padding: 8px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-name {
        font-weight: bold;
    }

    .category-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .category-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .btn-sm {
        margin-left: 12px;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('category-modal');
        const openButton = document.getElementById('open-category-modal');
        const closeButton = document.querySelector('.close-button');


        if(openButton)
        openButton.addEventListener('click',  function() {
            modal.style.display = 'block';
        });

        closeButton.onclick = function() {
            modal.style.display = 'none';
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    });

    function addCategory() {
        const name = document.getElementById('category-name').value;
        const color = document.getElementById('category-color').value;

        $.ajax({
            url: '/api/categories',
            type: 'POST',
            data: {
                name: name,
                color: color,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Category added successfully!');
                location.reload();
            },
            error: function() {
                alert('Failed to add category.');
            }
        });
    }

    function deleteCategory(categoryId) {
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: '/api/categories/' + categoryId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Category deleted successfully!');
                    location.reload();
                },
                error: function() {
                    alert('Failed to delete category.');
                }
            });
        }
    }
</script>
