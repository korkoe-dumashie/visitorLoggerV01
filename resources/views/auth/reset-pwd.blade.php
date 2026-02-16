<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @vite('resources/css/app.css')
    <title>PaySwitch Logger</title>
</head>

<body class="flex w-full justify-center items-center h-screen">

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg absolute top-10">
    <ul>
        @foreach ($errors->all() as $error)
            <li class="font-bold">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<main class="flex flex-col items-center gap-12 lg:gap-24">
    <img src="{{ asset('logo.png') }}" class="lg:w-56 w-40 md:w-60">

    <section class="md:p-12 p-10 bg-[#f5f5f5] lg:w-[520px] rounded-3xl space-y-10">
        <h1 class="text-center font-bold lg:text-3xl md:text-2xl text-xl">
            Reset your Password
        </h1>

        <form action="{{ route('newUser.store') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="username" value="{{ $username }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            {{-- Password --}}
            <div>
                <label class="text-[#44546f] font-semibold">Password</label>
                <div class="flex items-center bg-white border rounded px-2">
                    <input type="password" name="password" id="password"
                           required class="w-full outline-none p-2"
                           placeholder="New Password">

                    <svg class="toggle-password cursor-pointer"
                         data-target="#password"
                         width="24" height="24"
                         viewBox="0 0 24 24" fill="none">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"
                              stroke="#555" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3"
                                stroke="#555" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="text-[#44546f] font-semibold">Confirm Password</label>
                <div class="flex items-center bg-white border rounded px-2">
                    <input type="password" name="password_confirmation"
                           id="password_confirmation"
                           required class="w-full outline-none p-2"
                           placeholder="Confirm Password">

                    <svg class="toggle-password cursor-pointer"
                         data-target="#password_confirmation"
                         width="24" height="24"
                         viewBox="0 0 24 24" fill="none">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"
                              stroke="#555" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3"
                                stroke="#555" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <button type="submit"
                    class="w-full rounded p-3 bg-gradient-to-b from-[#247efc] to-[#0c66e4] text-white">
                Create Account
            </button>
        </form>
    </section>
</main>

<script>
$(function () {
    $(".toggle-password").on("click", function () {
        let input = $($(this).data("target"));
        let isHidden = input.attr("type") === "password";
        input.attr("type", isHidden ? "text" : "password");
    });
});
</script>

</body>
</html>
