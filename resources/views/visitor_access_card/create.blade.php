<x-layout>

    <x-slot:heading>
        Create a New Visitor Access Card
    </x-slot:heading>

    <main class="">
        <form action="{{url('store-access-card')}}" method="post" class="flex flex-col mx-auto justify-center items-center gap-4">

            @csrf
    <div class="flex flex-col">
        <label for="card_number" class="">Card ID</label>
        <input type="text" name="card_number" id="card_number" class="border border-gray-300 p-2 rounded-lg" placeholder="PS-VS-001">
    </div>
    <div class="">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Create</button>
    </div>
        </form>

    </main>

</x-layout>