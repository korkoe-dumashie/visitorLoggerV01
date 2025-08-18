<x-layout>
    <x-slot:heading>
        Log your Device
    </x-slot:heading>


    <div class="lg:h-[calc(100vh-5rem)] scrollbar-hidden h-[calc(100vh-6.5rem)] bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl h-full flex-col flex justify-center m-auto">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Log your Device</h1>
                <p class="mt-2 text-lg text-gray-600">Please fill in the details below to log your device</p>
            </div>

            <form action="{{ url('log-device') }}" method="POST" class="bg-white shadow-sm rounded-xl border border-gray-200 p-8">
                @csrf
                <div class="space-y-8">
                    {{-- Serial Number --}}
                    <div class="space-y-2">
                        <label for="serial_number" class="block text-lg font-medium text-gray-700">
                            Serial Number <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input
                            type="text"
                            name="serial_number"
                            id="serial_number"
                            placeholder="e.g., 5ECHOE44EKND"
                            value="{{ old('serial_number') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('serial_number') border-red-500 @enderror"
                            required
                        >
                        @error('serial_number')
                            <p class="mt-1 text-lg text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Brand --}}
                    <div class="space-y-2">
                        <label for="brand" class="block text-lg font-medium text-gray-700">
                            Brand <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input
                            type="text"
                            name="device_brand"
                            id="brand"
                            placeholder="e.g., MacBook"
                            value="{{ old('device_brand') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('device_brand') border-red-500 @enderror"
                            required
                        >
                        @error('device_brand')
                            <p class="mt-1 text-lg text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Employee Selection --}}
                    <div class="space-y-2">
                        <label for="employee_id" class="block text-lg font-medium text-gray-700">
                            Who are you? <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select
                            name="employee_id"
                            id="employee_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors @error('employee_id') border-red-500 @enderror"
                            required
                        >
                            <option value="w-1/2" disabled {{ old('employee_id') ? '' : 'selected' }}>Select Staff Member</option>
                            @foreach($employees as $employee)
                                <option class="w-1/2" value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <p class="mt-1 text-lg text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Personal Device Checkbox --}}
                    <div class="space-y-2">
                        <label for="brand" class="block text-lg font-medium text-gray-700">
                            Is this your personal machine? <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="gap-10 flex">
                            <label for="" class="flex gap-1">
                            <input
                            type="radio"
                            name="is_personal"
                            id="is_personal"
                            placeholder="e.g., MacBook"
                            value="1"
                            class=""
                            required
                            >
                            Yes
                            </label>
                            <label for="" class="flex gap-1">
                            <input
                            type="radio"
                            name="is_personal"
                            id="is_personal"
                            placeholder="e.g., MacBook"
                            value="0"
                            class=""
                            >
                            No
                            </label>
                        </div>
                        @error('is_personal')
                            <p class="mt-1 text-lg text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Action Selection --}}
                    <div class="space-y-4">
                        <label class="block text-lg font-medium text-gray-700">
                            What are you doing? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative">
                                <input
                                    type="radio"
                                    name="action"
                                    value="takeDeviceHome"
                                    {{ old('action') == 'takeDeviceHome' ? 'checked' : '' }}
                                    class="sr-only peer"
                                    required
                                >
                                <div class="w-full p-4 text-center border rounded-lg cursor-pointer transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 border-gray-300 hover:bg-gray-50 text-gray-700">
                                    Taking device home
                                </div>
                            </label>
                            <label class="relative">
                                <input
                                    type="radio"
                                    name="action"
                                    value="bringDevice"
                                    {{ old('action') == 'bringDevice' ? 'checked' : '' }}
                                    class="sr-only peer"
                                    required
                                >
                                <div class="w-full p-4 text-center border rounded-lg cursor-pointer transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 border-gray-300 hover:bg-gray-50 text-gray-700">
                                    Bringing device to work
                                </div>
                            </label>
                        </div>
                        @error('action')
                            <p class="mt-1 text-lg text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                    >
                        Log Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>