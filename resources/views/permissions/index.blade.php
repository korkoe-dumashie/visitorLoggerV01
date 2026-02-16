<x-layout>
    <x-slot:heading>
        Manage Permissions
    </x-slot:heading>

    <div class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] bg-gray-50 py-8 overflow-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Manage Permissions</h2>
                            <p class="text-sm text-gray-600 mt-1">Role: <span class="font-medium uppercase">{{ $role->name }}</span></p>
                        </div>
                        <a href="{{ route('users', ['tab' => 'roles']) }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Back to Roles
                        </a>
                    </div>
                </div>

                <form action="{{ url('getPermissions', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Module
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Create
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        View
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Modify
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($modules as $module)
                                    @php
                                        $permission = $permissions->get($module->id);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-sm font-medium text-gray-900 uppercase">{{ $module->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $module->id }}][can_create]"
                                                   value="1"
                                                   {{ $permission && $permission->can_create ? 'checked' : '' }}
                                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $module->id }}][can_view]"
                                                   value="1"
                                                   {{ $permission && $permission->can_view ? 'checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $module->id }}][can_modify]"
                                                   value="1"
                                                   {{ $permission && $permission->can_modify ? 'checked' : '' }}
                                                   class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $module->id }}][can_delete]"
                                                   value="1"
                                                   {{ $permission && $permission->can_delete ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                        <a href="{{ route('users', ['tab' => 'roles']) }}"
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Save Permissions</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layout>
