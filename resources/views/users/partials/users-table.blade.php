<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-sm font-medium uppercase text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm uppercase text-gray-600">{{ $user->username ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm uppercase text-gray-600">{{ $user->role->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap uppercase text-sm text-gray-900">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">

                                @if (\App\Models\Roles::hasPermission(auth()->user()->role_id, 'roles', 'modify'))
                                    <a href="{{ url('update', $user->id) }}" class="text-green-600 hover:text-green-800"
                                        title="Edit Role">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 4h2m-6.586 9.414l8.586-8.586a2 2 0 112.828 2.828l-8.586 8.586H7v-2.828zM5 19h14" />
                                        </svg>
                                    </a>
                                @endif

                                <form action="{{ url('revoke-access', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('form[action*="revoke-access"]').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const userRow = this.closest('tr');
                const userName = userRow.querySelector('.text-sm.font-medium.uppercase').textContent
                    .trim();
                const formAction = this.action;

                // Confirm deletion
                const confirmResult = await Swal.fire({
                    title: "Revoke Access?",
                    text: `Are you sure you want to revoke ${userName}'s access to the platform?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DC3545",
                    cancelButtonColor: "#6C757D",
                    confirmButtonText: "Yes, revoke access!",
                    cancelButtonText: "Cancel"
                });

                if (!confirmResult.isConfirmed) return;

                // Show loading state
                Swal.fire({
                    title: 'Revoking access...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await axios.delete(formAction, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });

                    Swal.close();

                    if (response.data.success) {
                        // Show success toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer);
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        });

                        await Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });

                        // Remove the user row from the table with fade effect
                        userRow.style.transition = 'opacity 0.3s';
                        userRow.style.opacity = '0';
                        setTimeout(() => {
                            userRow.remove();

                            // Check if table is empty (only header remains)
                            const tbody = document.querySelector('tbody');
                            if (tbody.querySelectorAll('tr').length === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }
                } catch (error) {
                    console.error('Delete error:', error);

                    Swal.close();

                    // Show error toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    await Toast.fire({
                        icon: 'error',
                        title: error.response?.data?.error || 'Failed to revoke access'
                    });
                }
            });
        });
    </script>

</div>
