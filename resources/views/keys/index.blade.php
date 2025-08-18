<x-layout>
    <x-slot:heading>
        All Keys   
    </x-slot:heading>

    <main class="lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] bg-gray-50 py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Key Management</h1>
                        <p class="mt-1 text-xl text-gray-500">Manage and track all keys in the system</p>
                    </div>
                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'keys', 'create'))
                        <a 
                            href="{{ url('create-key') }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xl font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                        >
                            <span>Create New Key</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Table Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="keys" class="w-full p-4 divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-4 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                    Key Number
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                    Key Name
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-lg font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y p-4 divide-gray-200 bg-white">
                            @foreach ($keys as $key)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-xl font-medium text-gray-900">{{ $key->key_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xl text-gray-900">{{ $key->key_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($key->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-base font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-base font-medium bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xl font-medium">
                                        @if($key->status === 'active')
                                            <button
                                                type="button"
                                                data-key-id="{{ $key->id }}"
                                                data-key-name="{{ $key->key_name }}"
                                                data-action="deactivate"
                                                class="toggle-key-status inline-flex items-center px-3 py-2 border border-yellow-200 text-xl font-medium rounded-lg text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200"
                                            >
                                                Deactivate
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                data-key-id="{{ $key->id }}"
                                                data-key-name="{{ $key->key_name }}"
                                                data-action="activate"
                                                class="toggle-key-status inline-flex items-center px-3 py-2 border border-green-200 text-xl font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200"
                                            >
                                                Activate
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Empty State --}}
            @if($keys->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <h3 class="mt-2 text-xl font-medium text-gray-900">No keys found</h3>
                    <p class="mt-1 text-xl text-gray-500">Get started by creating a new key.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Hidden forms for PATCH requests -->
    <form id="activate-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <form id="deactivate-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#keys').DataTable();
            
            // Check for success message in session
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif
            
            // Handle toggle key status buttons
            $(document).on('click', '.toggle-key-status', function() {
                const keyId = $(this).data('key-id');
                const keyName = $(this).data('key-name');
                const action = $(this).data('action');
                
                toggleKeyStatus(keyId, keyName, action);
            });
        });

        function toggleKeyStatus(keyId, keyName, action) {
            // Set up the SweetAlert configuration
            const isActivate = action === 'activate';
            const title = isActivate ? 'Activate Key?' : 'Deactivate Key?';
            const html = `Are you sure you want to <strong>${isActivate ? 'activate' : 'deactivate'}</strong> the key: <strong>${keyName}</strong>?`;
            const icon = isActivate ? 'question' : 'warning';
            const confirmButtonText = isActivate ? 'Yes, activate it!' : 'Yes, deactivate it!';
            const confirmButtonColor = isActivate ? '#10B981' : '#FBBF24';
            
            Swal.fire({
                title: title,
                html: html,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show processing state
                    Swal.fire({
                        title: 'Processing...',
                        html: `${isActivate ? 'Activating' : 'Deactivating'} key ${keyName}`,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                            processKeyStatus(keyId, action);
                        }
                    });
                }
            });
        }
        
        async function processKeyStatus(keyId, action) {
            try {
                const url = action === 'activate' 
                    ? `/activate-key/${keyId}` 
                    : `/deactivate-key/${keyId}`;
                    
                const response = await axios.patch(url);
                
                if (response.data.success) {
                    // Reload the page to show updated status
                    window.location.reload();
                } else {
                    throw new Error('Operation failed');
                }
            } catch (error) {
                console.error(error);
                let errorMessage = "Something went wrong.";
                if (error.response && error.response.data && error.response.data.error) {
                    errorMessage = error.response.data.error;
                }
                
                Swal.fire({
                    title: "Error!",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        }
    </script>
</x-layout>