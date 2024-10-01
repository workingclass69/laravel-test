<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold">Task Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-200">Logout</button>
            </form>
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
                @foreach ($tasks as $task)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $task->title }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $task->description }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $task->category->name }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $task->status }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        <button 
                            class="bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 transition"
                            onclick="openEditModal('{{ $task->id }}', '{{ $task->title }}', '{{ $task->description }}', '{{ $task->category->id }}', '{{ $task->status }}')">
                            Edit
                        </button>
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                            @csrf
                            @method('POST')
                        
                            <button type="submit" class="bg-green-600 text-white py-1 px-3 rounded-md hover:bg-green-700 transition">Update Status</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Create New Task Form -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Create New Task</h2>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="taskTitle" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" name="title" id="taskTitle" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="taskDescription" class="block text-sm font-medium text-gray-700">Task Description</label>
                    <textarea name="description" id="taskDescription" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="taskCategory" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="taskCategory" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskUser" class="block text-sm font-medium text-gray-700">Assign User</label>
                    <select name="user_id" id="taskUser" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">Create Task</button>
            </form>
        </div>

   
        <div id="editTaskModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Edit Task</h2>
                <form id="editTaskForm" action="" method="POST">
                    @csrf
                    @method('PUT') 
                    <div class="mb-4">
                        <label for="editTaskTitle" class="block text-sm font-medium text-gray-700">Task Title</label>
                        <input type="text" name="title" id="editTaskTitle" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                    </div>
                    <div class="mb-4">
                        <label for="editTaskDescription" class="block text-sm font-medium text-gray-700">Task Description</label>
                        <textarea name="description" id="editTaskDescription" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="editTaskCategory" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="editTaskCategory" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">Update Task</button>
                    <button type="button" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition ml-2" onclick="closeEditModal()">Cancel</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function openEditModal(id, title, description, categoryId, status) {
            
            document.getElementById('editTaskForm').action = '/tasks/' + id;
            document.getElementById('editTaskTitle').value = title;
            document.getElementById('editTaskDescription').value = description;
            document.getElementById('editTaskCategory').value = categoryId;
           

            
            document.getElementById('editTaskModal').classList.remove('hidden');
        }

        function closeEditModal() {
           
            document.getElementById('editTaskModal').classList.add('hidden');
        }
    </script>
</body>
</html>