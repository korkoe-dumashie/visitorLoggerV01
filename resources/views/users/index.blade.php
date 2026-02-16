<x-layout>
    <x-slot:heading>
        User Management
    </x-slot:heading>

    <div class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] bg-gray-50 py-8 overflow-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header with Tabs -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage system users and their permissions</p>
                    </div>

                    @if ($activeTab === 'users')
                        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 w-fit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add User</span>
                        </a>
                    @else
                        <a href="{{ route('roles.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2 w-fit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add Role</span>
                        </a>
                    @endif
                </div>

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('users', ['tab' => 'users']) }}"
                           class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Users
                        </a>
                        <a href="{{ route('users', ['tab' => 'roles']) }}"
                           class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'roles' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Roles & Permissions
                        </a>
                    </nav>
                </div>

                <!-- Search and Filter -->
                <form method="GET" action="{{ route('users') }}" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">

                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ $activeTab === 'users' ? 'Search users or roles...' : 'Search roles...' }}"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    @if ($activeTab === 'users')
                        <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>
                @if($users->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-xl font-medium text-gray-900">No users found</h3>
                        <p class="mt-1 text-xl text-gray-500">Get started by creating a new user.</p>
                    </div>
                @endif

            <!-- Users Table -->
            @if ($activeTab === 'users')
                @include('users.partials.users-table')
            @endif

            <!-- Roles Table -->
            @if ($activeTab === 'roles')
                @include('users.partials.roles-table')
            @endif

        </div>
    </div>
</x-layout>
