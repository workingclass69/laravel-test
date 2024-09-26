<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold">Task Dashboard</h1>
            <button id="logoutButton" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-200">Logout</button>
        </div>
        
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Task</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Description</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Category</th>
                   
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Edit</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-medium text-gray-700">Update Status</th>
                </tr>
            </thead>
            <tbody id="taskList" class="divide-y divide-gray-200">
                <!-- Task items will be dynamically injected here -->
            </tbody>
        </table>

        <!-- Form to create a new task -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Create New Task</h2>
            <form id="createTaskForm">
                <div class="mb-4">
                    <label for="taskTitle" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" id="taskTitle" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="taskDescription" class="block text-sm font-medium text-gray-700">Task Description</label>
                    <textarea id="taskDescription" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="taskCategory" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="taskCategory" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                        <option value="">Select a category</option>
                        <!-- Categories will be populated here -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskUser" class="block text-sm font-medium text-gray-700">Assign User</label>
                    <select id="taskUser" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                        <option value="">Select a user</option>
                        <!-- Users will be populated here -->
                    </select>
                </div>
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">Create Task</button>
            </form>
        </div>
    </div>

    <!-- Modal for editing task -->
    <div id="editTaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Edit Task</h2>
            <form id="editTaskForm">
                <input type="hidden" id="editTaskId">
                <div class="mb-4">
                    <label for="editTaskTitle" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" id="editTaskTitle" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label for="editTaskDescription" class="block text-sm font-medium text-gray-700">Task Description</label>
                    <textarea id="editTaskDescription" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200"></textarea>
                </div>
                <div class="mb-4">
                    <label for="editTaskUser" class="block text-sm font-medium text-gray-700">Assign User</label>
                    <select id="editTaskUser" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200">
                        <!-- Users will be populated here as well for editing -->
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelEdit" class="bg-gray-400 text-white py-2 px-4 rounded-md mr-2 hover:bg-gray-500 transition">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Check if authToken is set, if not redirect to login
            if (!localStorage.getItem('authToken')) {
                window.location.href = '/login';
            }

            // Fetch categories from the API
            $.ajax({
                url: '/api/categories',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                success: function(response) {
                    const categorySelect = $('#taskCategory');
                    response.forEach(category => {
                        categorySelect.append(`
                            <option value="${category.id}">${category.name}</option>
                        `);
                    });
                },
                error: function(xhr) {
                    alert('Failed to fetch categories: ' + xhr.responseJSON.message);
                }
            });

            // Fetch users from the API
            $.ajax({
                url: '/api/users',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                success: function(response) {
                    const userSelect = $('#taskUser');
                    response.forEach(user => {
                        userSelect.append(`
                            <option value="${user.id}">${user.name}</option>
                        `);
                    });

                    // Populate edit modal user select
                    const editUserSelect = $('#editTaskUser');
                    response.forEach(user => {
                        editUserSelect.append(`
                            <option value="${user.id}">${user.name}</option>
                        `);
                    });
                },
                error: function(xhr) {
                    alert('Failed to fetch users: ' + xhr.responseJSON.message);
                }
            });
            

            // Fetch tasks from the API
            $.ajax({
                url: '/api/tasks',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                success: function(response) {
                    const taskList = $('#taskList');
                    response.forEach(task => {
                        taskList.append(`
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">${task.title}</td>
                                <td class="py-2 px-4 border-b border-gray-200">${task.description}</td>
                                <td class="py-2 px-4 border-b border-gray-200">${task.category.name}</td>
                
                                <td class="py-2 px-4 border-b border-gray-200">${task.status}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <button class="editButton bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 transition" data-task-id="${task.id}" data-task-title="${task.title}" data-task-description="${task.description}" data-task-status="${task.status}">Edit</button>
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <button class="updateStatusButton bg-green-600 text-white py-1 px-3 rounded-md hover:bg-green-700 transition" data-task-id="${task.id}">Update Status</button>
                                </td>
                            </tr>
                        `);
                    });
                    // Attach event listener to each edit button
                    $('.editButton').on('click', function() {
                        const taskId = $(this).data('task-id');
                        const taskTitle = $(this).data('task-title');
                        const taskDescription = $(this).data('task-description');
                        const taskStatus = $(this).data('task-status');

                        // Populate modal with current task data
                        $('#editTaskId').val(taskId);
                        $('#editTaskTitle').val(taskTitle);
                        $('#editTaskDescription').val(taskDescription);
                        $('#editTaskStatus').val(taskStatus);

                        // Show the modal
                        $('#editTaskModal').removeClass('hidden');
                    });

                    // Attach event listener to update status button
                    $('.updateStatusButton').on('click', function() {
                        const taskId = $(this).data('task-id');

                        // Make an AJAX request to update the task status
                        $.ajax({
                            url: `/api/tasks/${taskId}/update-status`,
                            type: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
                                'Content-Type': 'application/json'
                            },
                            success: function(response) {
                                alert('Task status updated successfully');
                                location.reload(); // Reload the page to see the updated status
                            },
                            error: function(xhr) {
                                alert('Failed to update task status: ' + xhr.responseJSON.message);
                            }
                        });
                    });
                },
                error: function(xhr) {
                    alert('Failed to fetch tasks: ' + xhr.responseJSON.message);
                }
            });

            // Handle logout button click
            $('#logoutButton').on('click', function() {
                localStorage.removeItem('authToken'); // Remove the auth token
                window.location.href = '/'; // Redirect to the login page
            });

            $('#editTaskForm').on('submit', function(e) {
                e.preventDefault();

                const taskId = $('#editTaskId').val();
                const taskTitle = $('#editTaskTitle').val();
                const taskDescription = $('#editTaskDescription').val();
                const taskStatus = $('#editTaskStatus').val();

                // Send updated task data to the server
                $.ajax({
                    url: `/api/tasks/${taskId}`,
                    type: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
                        'Content-Type': 'application/json',
                    },
                    data: JSON.stringify({
                        title: taskTitle,
                        description: taskDescription,
                        status: taskStatus
                    }),
                    success: function(response) {
                        alert('Task updated successfully');
                        location.reload(); // Reload the page to see the updated tasks
                    },
                    error: function(xhr) {
                        alert('Failed to update task: ' + xhr.responseJSON.message);
                    }
                });
            });

            

            // Handle task form submission (creating a new task)
            $('#createTaskForm').on('submit', function(e) {
                e.preventDefault();

                const taskTitle = $('#taskTitle').val();
                const taskDescription = $('#taskDescription').val();
                const taskCategory = $('#taskCategory').val();
                const taskUser = $('#taskUser').val(); // Get selected user
                const taskStatus = $('#taskStatus').val();

                // Send task data to the server to create a new task
                $.ajax({
                    url: '/api/tasks',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
                        'Content-Type': 'application/json',
                    },
                    data: JSON.stringify({
                        title: taskTitle,
                        description: taskDescription,
                        category_id: taskCategory, // Use category_id to link with the selected category
                        user_id: taskUser, // Use user_id to link with the selected user
                        status: taskStatus
                    }),
                    success: function(response) {
                        alert('Task created successfully');
                        location.reload(); // Reload the page to see the new task
                    },
                    error: function(xhr) {
                        alert('Failed to create task: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle cancel button click (hide the modal)
            $('#cancelEdit').on('click', function() {
                $('#editTaskModal').addClass('hidden');
            });
        });
    </script>
</body>
</html>