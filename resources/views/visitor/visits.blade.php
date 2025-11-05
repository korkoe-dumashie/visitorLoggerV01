<x-layout>
    <x-slot:heading>
        Visits
    </x-slot:heading>

    <div id="visitors-table" class="rounded-lg">
        <div class="flex bg-gray-50 px-10 py-4  justify-end">
            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
                <a href="{{ url('check-visitor') }}" class="bg-gradient-to-b px-3 text-base rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4]">Log Visitor</a>
            @endif
        </div>

        <div class="px-10">
        <table class="w-full text-left bg-gray-50 display  text-gray-500 px-10" id="visits">
            <thead class=" text-gray-700 p-10 uppercase bg-gray-50">
                <tr class="">
                    <th scope="col" class="px-4 text-base py-2">Name</th>
                    <th scope="col" class="px-4 text-base py-2">Visiting</th>
                    <th scope="col" class="px-4 text-base py-2">Purpose</th>
                    <th scope="col" class="px-4 text-base py-2">Arrived</th>
                    <th scope="col" class="px-4 text-base py-2">Departed</th>
                    <th scope="col" class="px-4 text-base py-2">Actions</th>
                    {{-- <th scope="col" class="px-6 text-lg py-3"></th> --}}
                </tr>
            </thead>
            <tbody class="bg-gray-50  text-black px-10">
                @foreach ($visitor as $person)
                    <tr class="odd:bg-white even:bg-gray-50 p-10 text-base border-b">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">{{ $person?->full_name }}</td>
                        <td class="px-6 lg:text-xl py-4">{{ $person->visitee ? $person->visitee->first_name . ' ' . $person->visitee->last_name : 'N/A' }}</td>
                        <td class="px-6 py-4 capitalize">
                            @switch($person['purpose'])
                                @case('personal')
                                    <span class="text-green-800 px-2 py-1 rounded-xl bg-green-100">{{ $person->purpose }}</span>
                                    @break
                                @case('interview')
                                    <span class="text-amber-800 px-2 py-1 rounded-xl bg-amber-100">{{ $person->purpose }}</span>
                                    @break
                                @case('official')
                                    <span class="text-red-800 px-2 py-1 rounded-xl bg-red-100">{{ $person->purpose }}</span>
                                    @break
                                @default
                                    <span class="text-blue-800 px-2 py-1 rounded-xl bg-blue-100">{{ $person->purpose }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 text-left py-4 text-black">{{ $person?->created_at?->format('M, D Y : H:i') }}</td>
                        <td class="px-6 py-4 text-left text-black">
                            @if ($person?->departed_at === null)
                                <span class="text-amber-600 font-medium italic">Still Ongoing...</span>
                            @else
                                {{ $person?->departed_at?->format('D, M Y : H:i') }}
                            @endif
                        </td>
                        <td class="px-10 flex gap-14 py-4">
                            <a href="{{ url('visit/' . $person->id) }}" class="font-medium text-blue-600 text-lg hover:underline">View</a>

                            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create') && $person->status === 'ongoing')
                                <a href="{{ route('visitor.departure', ['visitor' => $person->id]) }}" class="font-medium  text-red-500 rounded-lg">Sign Out</a>
                            @else
                                &nbsp;
                            @endif
                        </td>
                        {{-- <td class="px-6 py-4">
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

        </div>
{{--
        <div class="px-6 py-4">
            {{ $visitor->links() }}
        </div> --}}
    </div>

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#visits').DataTable();
            scrollX: true;
        });
    </script>
</x-layout>
