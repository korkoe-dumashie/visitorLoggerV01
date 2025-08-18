<x-layout class="bg-gray-50">

    <x-slot:heading>
        Update Details
    </x-slot:heading>

    <div class="h-[calc(100vh-1rem)] grid place-items-center w-full bg-gray-50 py-10 px-8">
    <main class="w-fit">
        <section class="bg-white shadow-lg  rounded-2xl p-10">
            <div class="flex items-center justify-center mb-8">
                {{-- <div class=""> --}}

                    <h3 class="text-2xl font-semibold text-gray-900">Change {{ $user->name }}'s role</h3>
                {{-- </div> --}}
            </div>
            <form action="{{ url('user/'.$user->id) }}" class="space-y-6" method="POST">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div class="relative">
                <select name="role" class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors duration-200 appearance-none text-gray-900 pr-10" id="">
                    <option value="uppercase text-lg lg:text-xl text-black" selected disabled class="">Choose a role</option>
                    @foreach ($roles as $role )
                    <option value="{{ $role->id }}" data-user-name="{{ $user->name }}" class="uppercase text-lg lg:text-xl text-black">{{ $role->name }}</option>
                        
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                      <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </div>
                </div>
                  <div class="flex justify-center">
                    <button id="update-role-btn" class="bg-gradient-to-b lg:px-10 px-6 lg:text-xl text-lg rounded-lg lg:py-2 py-3 text-white from-[#247EFC] to-[#0C66E4]" type="button">Update role</button>
                  </div>
            </div>

            </form>
        </section>
    </main>
    </div>
</x-layout>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.getElementById("update-role-btn");
        const select = document.querySelector('select[name="role"]');
    
        button.addEventListener('click', function () {
            const selectedValue = select.value;
            const userName = "{{ $user->name }}";
    
            if (selectedValue) {
                Swal.fire({
                    title: "Change Role?",
                    text: `Are you sure you want to change ${userName}'s role?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085D6",
                    confirmButtonText: "Yes, change role!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateRole(selectedValue);
                    }
                });
            } else {
                Swal.fire('Error', 'Please select a role before submitting.', 'error');
            }
        });
    
        async function updateRole(roleId) {
            try {
                const userId = "{{ $user->id }}";
                const csrfToken = document.querySelector('input[name="_token"]').value;
    
                const response = await axios.patch(`/user/${userId}`, {
                    role: roleId
                }, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
    
                if (response.data.success === true) {
                    await Swal.fire("Success!", response.data.message || "User's role has been updated.", "success");
                    window.location.reload();
                } else {
    // Handle partial success or warning cases
                    await Swal.fire("Notice", response.data.message || "Action completed with notices.", "info");
                    window.location.reload();
                }
            } catch (error) {
                console.error(error);
                const errorMsg = error?.response?.data?.message || "Something went wrong.";
                await Swal.fire("Error", errorMsg, "error");
            }
        }
    });
    </script>
    