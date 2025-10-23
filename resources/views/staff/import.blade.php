<x-layout>
    <x-slot:heading>
        Import Staff
    </x-slot:heading>

    <div class="max-w-2xl mx-auto p-10">
        <form action="{{route('staff.import.post')}}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="importFile">
                    Select CSV File
                </label>
                <input type="file" name="importFile" id="importFile" accept=".csv" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('importFile')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Import Employees
                </button>
                <a href="{{url('staff')}}" class="text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>

        @if(session('import_errors'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p class="font-bold">Import Errors:</p>
                <ul class="list-disc list-inside">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layout>
