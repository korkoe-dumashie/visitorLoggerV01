{{-- resources/views/errors/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Urbanist', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
<div class="md:w-1/3 w-full flex flex-col items-center justify-center gap-5 mx-auto h-full px-4">

 @php $is419 = request()->attributes->get('exception') instanceof \Symfony\Component\HttpKernel\Exception\HttpException && request()->attributes->get('exception')->getStatusCode() === 419 @endphp
  @if(file_exists(public_path('icons/error.gif')))
        <img src="{{ asset('icons/error.gif') }}" alt="Error" width="320" height="320"
             class="max-w-full h-auto">
    @endif

    <h1 class="text-2xl font-bold text-[#201F21] text-center">
        @yield('title')
    </h1>

    <div class="text-xl font-bold text-center text-[#201F21]">
        @yield('message')
    </div>

    <div class="text-sm font-medium text-center text-gray-500">
        Please contact support if this issue persists.
    </div>

    <div class="flex gap-3 flex-wrap justify-center mt-8">


<button onclick="{{ $is419 ? 'location.reload()' : 'history.back()' }}"
        class="w-fit rounded-md h-12 px-6 bg-gradient-to-b from-[#247EFC] to-[#0C66E4]
               text-white flex items-center justify-center no-underline
               hover:opacity-90 transition-opacity cursor-pointer">
    <span>{{ $is419 ? 'Refresh Page' : 'Back' }}</span>
</button>
    </div>

</div>
</body>
</html>
