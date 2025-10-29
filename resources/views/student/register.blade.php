<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Registration</title>
    </head>
    <body class="min-h-screen bg-purple-200 flex items-center justify-center py-30">

        <div class="bg-white shadow-md rounded-lg py-12 px-20 w-full max-w-xl">
            <h1 class="text-3xl text-purple-600 text-center font-bold mb-6">Registration</h1>

            <form method="POST" action="{{ route('register') }}" >
                @csrf

                <div class="mb-4">
                    <label for="firstname" class="block text-gray-600">First Name</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 w-full rounded-md">
                </div>

                <div class="mb-4">
                    <label for="middlename" class="block text-gray-600">Middle Name(if any)</label>
                    <input type="text" id="middlename" name="middlename" placeholder="Optional if you do not have any" class="focus:outine-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 w-full rounded-md">
                </div>


                <div class="mb-4">
                    <label for="surname" class="block text-gray-600">Surname</label>
                    <input type="text" id="surname" name="surname" placeholder="Enter surname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 w-full rounded-md">
                </div>

                <div class="mb-4">
                    <label for='gender' class="block text-gray-600">Gender</label>
                    <select id="gender" name="gender" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 w-full rounded-md">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>


                <div class="mb-4">
                    <label for="email" class="block text-gray-600">Email</label>
                    <input type="email" id="email" name="email" placeholder="eg.123@gmail.com" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 rounded-md">
                </div>

                <div class="mb-4">
                     <label for="password" class="block text-gray-600">Password</label>
                    <input type="password" id="password" minlength=8 required autocomplete="new-password" name="password" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-600">Phone Number</label>
                    <input type="number" id="phone" name="phone" placeholder="eg.0240000000" required minlength="10" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 w-full rounded-md">
                </div>


                <div class="mb-4">
                     <label for="confirm" >Re-enter Password</label>
                    <input type="password" id="confirm" name="password_confirmation" minlength=8 required autocomplete="new-password" placeholder="Confirm password" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-6 p-4 rounded-md">

                </div>

                <button type="submit" class="">Register</button>

            </form>
        </div>

    </body>
</html>
