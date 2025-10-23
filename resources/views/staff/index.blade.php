<x-layout>

    <x-slot:heading>
        Staff
    </x-slot:heading>

    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'create'))

<div class="flex justify-self-end gap-4 p-10">
    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'create'))
    <a href="{{url('create-staff')}}" class="bg-gradient-to-b px-10 text-xl flex items-center rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4]">
        New Staff
    </a>

    <a href="{{route('staff.export')}}" class="bg-gradient-to-b px-10 text-xl flex items-center rounded-lg py-2 text-white from-green-600 to-green-700">
        Export
    </a>

    <a href="{{route('staff.import')}}" class="bg-gradient-to-b px-10 text-xl flex items-center rounded-lg py-2 text-white from-purple-600 to-purple-700">
        Import
    </a>

    <a href="{{route('staff.export.template')}}" class="bg-gradient-to-b px-10 text-xl flex items-center rounded-lg py-2 text-white from-gray-600 to-gray-700">
        Download Template
    </a>
</div>
@endif
  @endif
    <div class=" overflow-x-auto sm:rounded-lg p-10">
        <table class="w-full text-left text-gray-500" id="staff">
            <thead class="text-black uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 text-lg lg:text-xl py-3">
                    Name
                    </th>
                    <th scope="col" class="px-6 text-lg lg:text-xl py-3">
                        Dept
                    </th>
                    <th scope="col" class="px-6 text-lg lg:text-xl py-3">
                        Role
                    </th>

                    <th scope="col" class="px-6 text-lg lg:text-xl py-3">
                      Action
                    </th>
                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($employees as $staff)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <!-- Full Name -->
                        <th scope="row" class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                            {{ $staff?->first_name }} {{ $staff?->last_name }}
                        </th>

                        <!-- Department -->
                        <td class="px-6 py-4 text-black uppercase">
                            {{ $staff->department->name ?? 'N/A' }} <!-- Handle null department -->
                        </td>

                        <!-- Job Title -->
                        <td class="px-6 text-black py-4">
                            {{ $staff->job_title }}
                        </td>

                        <!-- View Link -->
                        <td class="px-6 flex gap-20 items-center py-4">
                            <a
                                class="font-medium text-blue-600 text-lg hover:underline"
                                href="{{ url('staff/' . $staff->id) }}"
                            >
                                View
                            </a>
                            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'modify'))
                            <a
                                class="text-lg font-medium hover:underline rounded-lg text-red-600 "
                                href="{{ url('edit-staff/' . $staff->id) }}"
                            >
                                Edit
                            </a>
                            @endif
                        </td>
                        {{-- @if(\App\Models\User::hasPermission(auth()->id(), 'staff', 'create'))
                        <!-- Assign Role -->
                        <td class="px-3 py-4">
                            <a href="#" class="font-medium text-red-500 p-[5px] rounded-lg border border-red-400">
                                Assign Role
                            </a>
                        </td>

                        @endif --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="px-6 py-4 text-blue-200">
            {{ $employees->links() }}
          </div> --}}

    </div>



    <script>
      $(document).ready( function () {
    $('#staff').DataTable();
} );
    </script>


</x-layout>
