
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-lg">
        <div id="loginSection">
            <h2 class="text-3xl font-bold text-center text-gray-700">Welcome Back</h2>
            <p class="text-center text-gray-500">Please sign in to your account</p>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="loginEmail" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    <p id="loginEmailError" class="text-red-500 text-sm hidden"></p>
                </div>
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="loginPassword" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    <p id="loginPasswordError" class="text-red-500 text-sm hidden"></p>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">Login</button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-500">Don't have an account? <a href="#" class="text-blue-600 hover:underline" id="showRegister">Register</a></p>
            </div>
        </div>

        <div id="registerSection" class="hidden mt-8">
            <h2 class="text-3xl font-bold text-center text-gray-700">Create an Account</h2>
            <form id="registerForm" class="space-y-4">
                <div>
                    <label for="registerName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="registerName" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    <p id="nameError" class="text-red-500 text-sm hidden"></p>
                </div>
                <div>
                    <label for="registerEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="registerEmail" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    <p id="emailError" class="text-red-500 text-sm hidden"></p>
                </div>
                <div>
                    <label for="registerPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="registerPassword" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    <p id="passwordError" class="text-red-500 text-sm hidden"></p>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">Register</button>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-500">Already have an account? <a href="#" class="text-blue-600 hover:underline" id="showLogin">Login</a></p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Check if authToken is set and redirect if so
            if (localStorage.getItem('authToken')) {
                window.location.href = '/dashboard'; // Redirect to dashboard
            }

            // Toggle between login and register forms
            $('#showRegister').on('click', function(e) {
                e.preventDefault();
                $('#loginSection').addClass('hidden');
                $('#registerSection').removeClass('hidden');
            });

            $('#showLogin').on('click', function(e) {
                e.preventDefault();
                $('#registerSection').addClass('hidden');
                $('#loginSection').removeClass('hidden');
            });

            // Handle registration
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous error messages
                $('#nameError').addClass('hidden');
                $('#emailError').addClass('hidden');
                $('#passwordError').addClass('hidden');

                // Validate inputs
                let isValid = true;

                const name = $('#registerName').val();
                const email = $('#registerEmail').val();
                const password = $('#registerPassword').val();

                if (!name || name.length === 0 || name.length > 255) {
                    $('#nameError').text('Name is required and must not exceed 255 characters.');
                    $('#nameError').removeClass('hidden');
                    isValid = false;
                }

                if (!email || email.length === 0 || !validateEmail(email)) {
                    $('#emailError').text('A valid email is required.');
                    $('#emailError').removeClass('hidden');
                    isValid = false;
                }

                if (!password || password.length < 8) {
                    $('#passwordError').text('Password must be at least 8 characters long.');
                    $('#passwordError').removeClass('hidden');
                    isValid = false;
                }

                if (!isValid) {
                    return; // Stop form submission if validation fails
                }

                $.ajax({
                    url: '/api/register',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        alert('Registration successful!');
                        // Optionally redirect to login or another page
                    },
                    error: function(xhr) {
                        alert('Registration failed: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle login
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous error messages
                $('#loginEmailError').addClass('hidden');
                $('#loginPasswordError').addClass('hidden');

                // Validate inputs
                let isValid = true;

                const email = $('#loginEmail').val();
                const password = $('#loginPassword').val();

                if (!email || email.length === 0 || !validateEmail(email)) {
                    $('#loginEmailError').text('A valid email is required.');
                    $('#loginEmailError').removeClass('hidden');
                    isValid = false;
                }

                if (!password || password.length === 0) {
                    $('#loginPasswordError').text('Password is required.');
                    $('#loginPasswordError').removeClass('hidden');
                    isValid = false;
                }

                if (!isValid) {
                    return; // Stop form submission if validation fails
                }

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        // Store the token in local storage
                        localStorage.setItem('authToken', response.access_token); // Assuming the token is sent in the response

              
                        // Redirect to the dashboard
                        window.location.href = '/dashboard'; // Redirect to a dashboard or another page after login
                    },
                    error: function(xhr) {
                        alert('Login failed: ' + xhr.responseJSON.message);
                    }
                });
            });
        });

        // Helper function to validate email format
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
</body>
</html>