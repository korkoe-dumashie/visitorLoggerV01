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
                Your user account for the PaySwitch Visitor Management System has been created. 
                To complete your account setup, please reset your password by clicking the button below.
            </p>

            <div class="text-center my-6">
                <a 
                    href="{{ $resetUrl }}" 
                    class="inline-block bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out"
                >
                    Reset Password
                </a>
            </div>

            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                    <strong>Important:</strong> If you did not request this account creation, 
                    please contact our support team immediately.
                </p>
            </div>
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