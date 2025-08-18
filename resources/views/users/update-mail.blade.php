<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Created</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 grid h-screen mx-auto place-content-center">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden my-10">
        <div class="p-6">
            <div class="flex flex-col items-center justify-center ">
                <img src="{{ asset('PS-logo.png') }}" alt="" class="lg:w-1/2 md:w-1/3">
                <h1 class="text-2xl font-bold text-blue-400">Account Created</h1>
            </div>        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-700 font-medium">
                Dear {{ $user->name }},
            </p>
        </div>

        <div class="space-y-4 text-gray-700">
            <p class="p-5 text-center">
                Your role on the Visitor Mgt App has been changed to a <span class="">{{ $roleName }}</span>. However, your credentials are still working. You can still log in.
            </p>



        </div>

        <div class="mt-6 border-t pt-4 text-center">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} Payswitch Visitor Management System
            </p>
        </div>
    </div>
</div>
</body>
</html>