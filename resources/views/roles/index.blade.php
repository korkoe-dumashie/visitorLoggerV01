<x-layout>

    <x-slot:heading>
        All Roles   
    </x-slot:heading>


    <main class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] bg-gray-50 p-10 w-full m-auto">
        <section class="max-w-5xl m-auto h-full">
            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'roles', 'create'))
            <div class="flex justify-end mb-6">
                <a href="{{ url('create-key') }}" 
                   class="bg-gradient-to-b from-[#247EFC] to-[#0C66E4] px-6 py-2.5 text-white rounded-lg 
                          flex items-center gap-2 hover:opacity-90 transition-opacity text-lg font-medium">
                    Create New Role
                </a>
            </div>
        @endif
    
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" 
                            class="px-6 py-4 text-left text-lg font-semibold text-gray-900">
                            Role
                        </th>
                        <th scope="col" 
                            class="px-6 py-4 text-left text-lg font-semibold text-gray-900">
                            Description
                        </th>
                        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'roles', 'create'))
                            <th scope="col" class="px-6 py-4 text-right">
                                <span class="sr-only">Actions</span>
                            </th>
                        @endif
                    </tr>
                </thead>
    
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($roles as $role)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-lg uppercase font-medium text-gray-900">
                                    {{ $role->name }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-lg capitalize text-gray-700">
                                    {{ $role->description }}
                                </div>
                            </td>
                            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'roles', 'create'))
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    @if ($role->name!=='admin')
                                    <button type="button" 
                                            data-role-id="{{ $role->id }}" 
                                            data-role-name="{{ $role->name }}"
                                            class="delete-btn inline-flex items-center justify-center rounded-md 
                                                   bg-red-50 px-3 py-2 text-lg font-semibold text-red-600 
                                                   hover:bg-red-100 transition-colors">
                                        Delete Role
                                    </button>
                                        
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            @if(count($roles) === 0)
                <div class="text-center py-12">
                    <p class="text-lg text-gray-500">No roles found</p>
                </div>
            @endif
        </div>
        </section>
      
    </main>
    
    <script>
        
        document.addEventListener("DOMContentLoaded", function(){
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function(){
                    const roleId = this.getAttribute("data-role-id");
                    const roleName = this.getAttribute("data-role-name");
                    console.log(roleId, roleName);
                    confirmDelete(roleId, roleName);
                });
            });
        });

        function confirmDelete(roleId, roleName){
            Swal.fire({
                title: "Delete Role?",
                text: `Are you sure you want to delete the "${roleName}" role?`,
                icon: "warning",
                showCancelButton:true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085D6",
                confirmButtonText: "Yes, delete it!"

            }).then((result)=>{

                if(result.isConfirmed){
                deleteRole(roleId, roleName);
                }
            })
        }



async function deleteRole(roleId, roleName) {
    try {
        const response = await axios.delete(`/delete-role/${roleId}`);
        console.log(JSON.stringify(response, null, 2));
        console.log(response.data.success);

        if (response.data.success === true) {
            await Swal.fire({
                title: "Deleted!",
                text: `The "${roleName}" role has been deleted.`,
                icon: "success",
                confirmButtonText: "OK"
            });

            // Redirect only after the user clicks "OK"
            window.location.href = '/roles';
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