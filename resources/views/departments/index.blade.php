<x-layout>
    <x-slot:heading>
        All Departments
    </x-slot:heading>

    <main class="w-full mx-auto p-10">
        <div class="mb-8 sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Departments</h2>
                <p class="mt-1 text-xl
                 text-gray-600">Manage your organization's departments</p>
            </div>
            
            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'departments', 'create'))
                <div class="mt-4 sm:mt-0">
                    <a href="{{ url('create-department') }}" 
                       class="inline-flex items-center px-4 py-2 text-xl
                        font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Create Department
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="departments" class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th scope="col" class="px-6 py-4 text-xl
                             font-semibold text-gray-900 text-left">
                                Department Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-xl
                             font-semibold text-gray-900 text-left">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($departments as $department)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium uppercase text-gray-900">{{ $department->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xl
                                ">
                                    <div class="flex items-center gap-3">
                                        {{-- <a href="{{ url('edit-department/' . $department->id) }}" 
                                           class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                            Edit
                                        </a> --}}
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $department->id }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#departments').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Search departments:",
                    lengthMenu: "Show _MENU_ departments",
                    info: "Showing _START_ to _END_ of _TOTAL_ departments",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: "<'flex items-center justify-between mb-4'<'flex items-center'l><'flex items-center'f>>" +
                     "<'overflow-x-auto'tr>" +
                     "<'flex items-center justify-between mt-4'<'flex items-center'i><'flex items-center'p>>",
            });
        });

        function confirmDelete(departmentId) {
            if (confirm('Are you sure you want to delete this department?')) {
                window.location.href = `/delete-department/${departmentId}`;
            }
        }
    </script>
</x-layout>
