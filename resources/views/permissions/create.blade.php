<x-layout>
    <x-slot:heading>
       Permissions Mgt
    </x-slot:heading>

    <main class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] flex flex-col justify-center  w-full bg-gray-50 py-8 overflow-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center space-x-3 mb-8">
                <h1 class="text-2xl font-semibold text-gray-900">Add Permissions</h1>
            </div>

            <form action="{{ url('store-permission') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Role Selection -->
                <div class="space-y-2">
                    <label for="role" class="block text-lg font-medium text-gray-700">Select Role</label>
                    <div class="relative">
                        <select name="role" id="role" class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors duration-200 appearance-none text-gray-900 pr-10">
                            <option value="" selected disabled>Choose a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" class="uppercase">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Permissions Grid -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-900 border-b pb-3">Permissions</h2>
                    
                    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-8">
                        <!-- Visits Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Visits Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="visits[]" value="view_visits" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Visits</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="visits[]" value="create_visits" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Visits</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="visits[]" value="modify_visits" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Visits</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="visits[]" value="delete_visits" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Visits</span>
                                </label>
                            </div>
                        </div>

                        <!-- Logs Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Logs Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="logs[]" value="view_logs" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Logs</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="logs[]" value="create_logs" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Logs</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="logs[]" value="modify_logs" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Logs</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="logs[]" value="delete_logs" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Logs</span>
                                </label>
                            </div>
                        </div>

                        <!-- Staff Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Staff Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="staff[]" value="view_staff" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Staff</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="staff[]" value="create_staff" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Staff</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="staff[]" value="modify_staff" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Staff</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="staff[]" value="delete_staff" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Staff</span>
                                </label>
                            </div>
                        </div>

                        <!-- Keys Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Keys Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="keys[]" value="view_keys" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Keys</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="keys[]" value="create_keys" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Keys</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="keys[]" value="modify_keys" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Keys</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="keys[]" value="delete_keys" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Keys</span>
                                </label>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Settings Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="settings[]" value="view_settings" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Settings</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="settings[]" value="create_settings" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Settings</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="settings[]" value="modify_settings" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Settings</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="settings[]" value="delete_settings" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Settings</span>
                                </label>
                            </div>
                        </div>

                        <!-- Reports Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Reports Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="reports[]" value="view_reports" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Reports</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="reports[]" value="create_reports" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Reports</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="reports[]" value="modify_reports" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Reports</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="reports[]" value="delete_reports" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Reports</span>
                                </label>
                            </div>
                        </div>

                        <!-- Departments Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Departments Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="departments[]" value="view_departments" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Departments</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="departments[]" value="create_departments" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Departments</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="departments[]" value="modify_departments" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Departments</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="departments[]" value="delete_departments" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Departments</span>
                                </label>
                            </div>
                        </div>

                        <!-- Roles Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">Roles Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="view_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Roles</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="create_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Roles</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="modify_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Roles</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="delete_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Roles</span>
                                </label>
                            </div>
                        </div>
                        <!-- User Section -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <h3 class="font-medium text-gray-900 mb-3">User Management</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="user[]" value="view_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">View Users</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="user[]" value="create_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Create Users</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="user[]" value="modify_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Modify Users</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="user[]" value="delete_roles" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Delete Users</span>
                                </label>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-b from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center space-x-2">
                        <span class="text-base font-medium">Save Permissions</span>
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layout>
