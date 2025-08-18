<x-layout>

    <x-slot:heading>
        Create a New Department
    </x-slot:heading>

    <main class="">
        <form action="{{url('store-department')}}" method="post" class="flex flex-col mx-auto justify-center items-center gap-4">

            @csrf
    <div class="flex flex-col">
        <label for="dept_name" class="">Department ID</label>
        <input type="text" name="name" id="dept_name" class="border border-gray-300 p-2 rounded-lg " placeholder="HR">
    </div>
    <div class="">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Create</button>
    </div>
        </form>

    </main>

</x-layout>