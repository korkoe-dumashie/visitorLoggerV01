<x-layout>

    <x-slot:heading>
        
                Users
    </x-slot:heading>

        <div class="lg:h-[calc(100vh-10rem)] h-[calc(100vh-6.5rem)] bg-gray-50">
            <div class="max-w-7xl p-10">
                {{-- Header Section --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                            <p class="mt-1 text-xl text-gray-500">Manage user accounts and their roles</p>
                        </div>
                        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'keys', 'create'))
                            <a 
                                href="{{ url('create-user') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xl font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                            >
                                <span class="mr-2">Add New User</span>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
    
                {{-- Table Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-4 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                        User Details
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'keys', 'create'))
                                        <th scope="col" class="px-6 py-4 text-right text-lg font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-xl font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-xl text-gray-500">{{ $user->email ?? 'No email provided' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 uppercase whitespace-nowrap">
                                            <span class="px-3 py-1 uppsercase inline-flex text-lg leading-5 font-semibold rounded-full 
                                                @if($user->role->name === 'admin')
                                                    bg-purple-100 text-purple-800
                                                @elseif($user->role->name === 'manager')
                                                    bg-blue-100 text-blue-800
                                                @else
                                                    bg-green-100 text-green-800
                                                @endif
                                            ">
                                                {{ ucfirst($user->role->name) }}
                                            </span>
                                        </td>
                                        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'keys', 'delete'))
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-xl font-medium">
                                                @if($user->role->name !== 'admin')
                                                    <div class="flex justify-end space-x-3">
                                                        <a
                                                            href="{{ url('update/'.$user->id) }}"
                                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-md transition-colors duration-200"
                                                        >
                                                            Edit Role
                                                        </a>
                                                        <button
                                                            type="button"
                                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-md transition-colors duration-200"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
    
                {{-- Empty State --}}
                @if($users->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-xl font-medium text-gray-900">No users found</h3>
                        <p class="mt-1 text-xl text-gray-500">Get started by creating a new user.</p>
                    </div>
                @endif
            </div>
        </div>


    <script>




        //change role




        //delete user

        document.addEventListener("DOMContentLoaded", function(){
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function(){
                    const userId = this.getAttribute("data-user-id");
                    const userName = this.getAttribute("data-user-name");
                    console.log(userId, userName);
                    confirmDelete(userId, userName);
                });
            });
        });

        function confirmDelete(userId, userName){
            Swal.fire({
                title: "Delete User?",
                text: `Are you sure you want to revoke ${userName}'s access?`,
                icon: "warning",
                showCancelButton:true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085D6",
                confirmButtonText: "Yes, delete this user!"

            }).then((result)=>{

                if(result.isConfirmed){

                    console.log("Confirmed");
                deleteUser(userId, userName);
                }
            })
        }
        
async function deleteUser(userId, userName) {
    try {
        const response = await axios.delete(`/revoke-access/${userId}`);
        console.log(JSON.stringify(response, null, 2));
        // console.log(response.data.success);

        if (response.data.success === true) {

            console.log("Delete modal.")
            await Swal.fire({
                title: "Deleted!",
                text: `The user, ${userName} has been deleted.`,
                icon: "success",
                confirmButtonText: "OK"
            });

            // Redirect only after the user clicks "OK"
            window.location.href = '/users';
        }
    } catch (error) {
        console.log(error);
        let errorMessage = "Something went wrong.";
        if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error;
        }
        await Swal.fire("Error!", errorMessage, "error");
    }
}




    </script>
</x-layout>