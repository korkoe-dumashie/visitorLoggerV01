<x-layout>

    <x-slot:heading>
       New Role
    </x-slot:heading>


    <main class="p-10 flex-col flex gap-4">
        <h4 class="text-lg lg:text-3xl font-semibold">Add Role</h4>

        <hr>

        <form action="{{ url('store-role') }}" method="POST" class="flex-col flex gap-4">
            @csrf
            <div class="flex-col flex gap-2">
                <label for="name" class="text-lg lg:text-2xl">Name</label>
                <input type="text" class="p-4 border border-gray-200 rounded-lg" required name="name">
            </div>
            <div class="flex-col flex gap-2">
                <label for="description" class="text-lg lg:text-2xl">
                    Description
                </label>
                <textarea name="description" id="description" rows="4" class="border w-full rounded-md bg-white px-3.5 py-2 text-base "></textarea>
            </div>

            <div class="">
                <button class="bg-gradient-to-b px-10  text-xl w-fit rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4]" type="submit">Save</button>
            </div>
        </form>
    </main>
</x-layout>