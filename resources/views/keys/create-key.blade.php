<x-layout>

    <x-slot:heading>
        Create New Key    
    </x-slot:heading>

    <div class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] flex flex-col justify-center items-center w-full m-auto bg-gray-50 py-8">
        <div class="w-full lg:w-1/3  px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="mb-8">
                {{-- <h1 class="text-2xl font-bold text-gray-900">Create New Key</h1> --}}
                <p class="mt-1 text-xl text-center text-gray-500">Add a new key to the management system</p>
            </div>

            {{-- Form Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ url('store-key') }}" method="POST" class="p-8">
                    @csrf
                    <div class="space-y-6">
                        {{-- Key ID Field --}}
                        <div>
                            <label for="key_number" class="block text-xl font-medium text-gray-700">
                                Key ID <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input
                                    type="text"
                                    name="key_number"
                                    id="key_number"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('key_number') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="PS-KY-001"
                                    value="{{ old('key_number') }}"
                                    required
                                >
                                @error('key_number')
                                    <div class="mt-1 text-xl text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <p class="mt-2 text-xl text-gray-500">Enter a unique identifier for the key</p>
                        </div>

                        {{-- Key Name Field --}}
                        <div>
                            <label for="key_name" class="block text-xl font-medium text-gray-700">
                                Key Name <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                    type="text"
                                    name="key_name"
                                    id="key_name"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('key_name') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Sales Office"
                                    value="{{ old('key_name') }}"
                                    required
                                >
                                @error('key_name')
                                    <div class="mt-1 text-xl text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <p class="mt-2 text-xl text-gray-500">Provide a descriptive name for the key</p>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a 
                                href="{{ url()->previous() }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-xl font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xl font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                            >
                                Create Key
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layout>