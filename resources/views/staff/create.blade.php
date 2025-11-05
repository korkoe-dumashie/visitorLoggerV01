<x-layout>
    <!-- When there is no desire, all things are at peace. - Laozi -->

    <x-slot:heading>
        Register a new Staff
    </x-slot:heading>

    <form action="{{ url('store-staff') }}" method="POST" class="p-4 flex-col flex gap-10 md:p-6 lg:p-8">
        @csrf

        {{-- <div class=""> --}}
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl"> --}}
            <!-- Left Column -->
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-10 w-full">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        First Name <span class="text-red-400 text-lg">*</span>
                    </label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder="Jane"
                        required
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed disabled:bg-gray-100"
                    />
                    @error('first_name')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Other Name -->
                <div>
                    <label for="other_name" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Other Name <span class="text-gray-500">(optional)</span>
                    </label>
                    <input
                        type="text"
                        id="other_name"
                        name="other_name"
                        placeholder="Abla"
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('other_name')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Last Name <span class="text-red-400 text-lg">*</span>
                    </label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder="Doe"
                        required
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('last_name')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee Number -->
                <div>
                    <label for="employee_number" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Employee Number <span class="text-red-400 text-xl">*</span>
                    </label>
                    <input
                        type="text"
                        id="employee_number"
                        name="employee_number"
                        placeholder="GR 1000 25"
                        required
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('employee_number')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Email <span class="text-gray-500">(optional)</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="janedoe@payswitch.com.gh"
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('email')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Phone Number <span class="text-red-400 text-lg">*</span>
                    </label>
                    <input
                        type="text"
                        id="phone_number"
                        name="phone_number"
                        placeholder="0241234567"
                        required
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('phone_number')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            {{-- </div>

            <!-- Right Column -->
            <div class="space-y-8"> --}}
                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Department <span class="text-red-400 text-lg">*</span>
                    </label>
                    <select
                        name="department_id"
                        id="department_id"
                        required
                        class="max:w-full px-4 py-2 text-slate-600 border border-gray-400 rounded-md outline-none focus:border-blue-400 uppercase"
                    >
                        <option value="" selected disabled>Choose Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" class="uppercase">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Title -->
                <div>
                    <label for="job_title" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Job Title <span class="text-red-400 text-lg">*</span>
                    </label>
                    <input
                        type="text"
                        id="job_title"
                        name="job_title"
                        placeholder="Software Engineer"
                        required
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('job_title')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Card Number -->
                <div>
                    <label for="access_card_number" class="block text-lg lg:text-xl font-medium text-black space-y-2">
                        Access Card Number <span class="text-gray-500">(optional)</span>
                    </label>
                    <input
                        type="text"
                        id="access_card_number"
                        name="access_card_number"
                        placeholder="EMP9283"
                        class="max:w-full px-4 py-2 text-base bg-transparent border border-slate-400 rounded-md outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-not-allowed"
                    />
                    @error('access_card_number')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <fieldset>
                        <legend class="block text-lg lg:text-xl font-medium text-black mb-3">
                            Gender <span class="text-red-400 text-xl">*</span>
                        </legend>
                        <div class="flex flex-wrap gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="female"
                                    required
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                />
                                <span class="ml-2 text-lg lg:text-xl font-medium text-gray-900">Female</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="male"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                />
                                <span class="ml-2 text-lg lg:text-xl font-medium text-gray-900">Male</span>
                            </label>
                        </div>
                    </fieldset>
                    @error('gender')
                        <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button (Aligned to bottom-right on larger screens) -->
            {{-- </div> --}}
        </div>
                <div class="flex justify-center w-full  mx-auto">
                    <button
                        type="submit"
                        class="w-1/4  py-2 text-xl font-medium text-white bg-gradient-to-b from-[#247EFC] to-[#0C66E4] rounded-lg shadow-md hover:shadow-lg transition-shadow"
                    >
                        Submit
                    </button>
                </div>
    </form>
</x-layout>
