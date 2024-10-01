<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-lg">
        
        @if(session('showRegister') !== true)
        <div id="loginSection">
            <h2 class="text-3xl font-bold text-center text-gray-700">Welcome Back</h2>
            <p class="text-center text-gray-500">Please sign in to your account</p>

            <form action="{{ route('loginWeb') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="loginPassword" name="password" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">Login</button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-500">Don't have an account? 
                    <a href="{{ route('showRegister') }}" class="text-blue-600 hover:underline">Register</a>
                </p>
            </div>
        </div>
        @endif

        @if(session('showRegister') === true)
        <div id="registerSection" class="mt-8">
            <h2 class="text-3xl font-bold text-center text-gray-700">Create an Account</h2>
            <form action="{{ route('registerWeb') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="registerName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="registerName" name="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="registerEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="registerEmail" name="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="registerPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="registerPassword" name="password" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">Register</button>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-500">Already have an account? 
                    <a href="{{ route('showLogin') }}" class="text-blue-600 hover:underline">Login</a>
                </p>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
