<x-layout>

    <x-slot:heading>
        Settings
    </x-slot:heading>


    <main class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] overflow-auto scrollbar-hidden max-w-7xl p-10">
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-10">

            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-5">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset("credit-card.svg") }}" alt="" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl lg:text-2xl font-medium text-gray-900">Visitor Access Cards</h3>
                </div>
                <div class="flex flex-col w-full space-y-5">
                    <a href="{{ url('access-cards') }}" 
                    class="inline-flex justify-center items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">All Cards</a>

                    <a href="{{ url('create-access-card') }}" class="inline-flex justify-center items-center px-4 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 transition-colors">New Card</a>
                </div>
            
            </div>
    
            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-5">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset("building.svg") }}" alt="" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl lg:text-2xl font-medium text-gray-900">Departments</h3>
                </div>
                <div class="flex flex-col w-full space-y-5">
                <a href="{{ url('departments') }}" class="inline-flex justify-center items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">All Department</a>

                <a href="{{ url('create-department') }}" class="inline-flex justify-center items-center px-4 py-2 border border-green-500 text-green-500 rounded-lg hover:bg-green-50 transition-colors">New Department</a>

                </div>
            </div>
    
            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-5">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset('key-round.svg') }}" alt="" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl lg:text-2xl font-medium text-gray-900">Keys</h3>
                </div>
                <div class="flex flex-col w-full space-y-5">
                    <a href="{{ url('all_keys') }}" class="inline-flex justify-center items-center px-4 py-2 bg-violet-500 text-white rounded-lg hover:bg-violet-600 transition-colors">All Keys</a>

                    <a href="{{ url('create-key') }}" class="inline-flex justify-center items-center px-4 py-2 border border-violet-500 text-violet-500 rounded-lg hover:bg-violet-50 transition-colors">New Key</a>
                </div>
            </div>
    
            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset('users-black.svg') }}" alt="" class="w-8 h-8">
                    </div>
                <h3 class="text-xl lg:text-2xl font-medium text-gray-900">Staff</h3>
                </div>
                <div class="flex flex-col w-full space-y-3">
            <a href="{{ url('staff') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">All Staff</a>
            <a href="{{ url('create-staff') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg hover:bg-gray-50 transition-colors">New Staff</a></div>
            </div>


            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset('user-plus.svg') }}" alt="" class="w-8 h-8">
                    </div>
                <h3 class="text-xl lg:text-2xl font-medium text-gray-900">User/Admins</h3>
                </div>
                <div class="flex flex-col w-full space-y-3">

                <a href="{{ url('users') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-b from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-colors">All User</a>
                
                <a href="{{ url('create-user') }}" class="inline-flex justify-center items-center px-4 py-2 hover:bg-blue-50 border text-blue-700 border-blue-700 rounded-lg  transition-colors">New User</a></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset('shield.svg') }}" alt="" class="h-8 w-8">
                    </div>
                <h3 class="text-xl lg:text-xl font-medium text-gray-900">Create a New Role</h3>
                </div>
                <div class="flex flex-col w-full space-y-3">
                    <a href="{{ url('roles') }}" 
                   class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-b from-red-300 to-red-500 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-colors">
                    Roles
                </a>
                <a href="{{ url('create-role') }}" 
                   class="inline-flex justify-center items-center px-4 py-2 border border-[#DC2626] text-[#DC2626]  rounded-lg hover:bg-red-50 transition-colors">
                    New Role
                </a>
            </div>
                
            </div>

            {{-- <div class="bg-white rounded-xl shadow-sm border space-y-5 border-blue-100 p-10 hover:shadow-md transition-shadow">
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="p-3 bg-blue-50 rounded-full">
                        <img src="{{ asset('lock.svg') }}" alt="" class="h-8 w-8">
                    </div>
                    <h3 class="text-xl lg:text-xl font-medium text-gray-900">Manage Permissions</h3>
                </div>
                <div class="flex flex-col w-full space-y-3">
                    <a href="{{ url('permissions') }}" 
                       class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-b from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-colors">
                        All Permissions
                    </a>
                    <a href="{{ url('create-permission') }}" 
                       class="inline-flex justify-center items-center px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                        New Permission
                    </a>
                </div>
            </div> --}}
        </section>


       
        
    </main>
</x-layout>