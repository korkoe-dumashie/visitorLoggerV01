<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Error' }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont,"Urbanist", 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="md:w-1/3 w-full flex flex-col items-center justify-center gap-5 mx-auto h-full px-4">
        <img src="{{ asset('icons/error.gif') }}" alt="Error" width="320" height="320"
            class="max-w-full h-auto">

        <h1 class="text-2xl font-bold text-[#201F21] text-center">
            {{ $title ?? 'Something went wrong' }}
        </h1>

        <div class="text-xl font-bold text-center text-[#201F21]">
            {{ $message ?? 'An unexpected error occurred' }}
        </div>

        <div class="text-sm font-bold text-center text-gray-500">
            Please contact support if this issue persists
        </div>

        <div class="flex gap-3 flex-wrap justify-center">
            <a href="{{ url()->current() }}"
                class="w-fit rounded-md h-12 px-6 mt-8 bg-gradient-to-b from-[#247EFC] to-[#0C66E4] text-white flex items-center justify-center no-underline hover:opacity-90 transition-opacity">
                <span>Reload</span>
            </a>

            {{-- <a href="{{ route('logout') }}"
               class="w-fit rounded-md h-12 px-6 mt-8 bg-gradient-to-b from-[#247EFC] to-[#0C66E4] text-white flex items-center justify-center no-underline hover:opacity-90 transition-opacity">
                <span>Restart</span>
            </a> --}}
        </div>
    </div>
</body>

</html>
