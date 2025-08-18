<x-layout>
    <x-slot:heading>
        Devices
    </x-slot:heading>

    <div id="device-table" class="w-full flex sm:rounded-lg p-10 flex-col gap-5">
        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
            <div class="flex justify-end">
                <a href="{{url('log')}}" class="bg-gradient-to-b px-10 text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center">
                  Log Device
                </a>
            </div>
        @endif
       
        <table class="w-full text-sm text-left text-gray-500" id="devices">
            <thead class=" text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 text-lg text-start lg:text-xl py-3">Serial Number</th>
                    <th scope="col" class="px-6 lg:text-xl py-3">Brand</th>
                    <th scope="col" class="px-6 lg:text-xl py-3">Staff</th>
                    <th scope="col" class="px-6 lg:text-xl py-3">Date</th>
                    <th scope="col" class="px-6 lg:text-xl py-3">Time</th>
                    <th scope="col" class="px-6 lg:text-xl py-3">Action</th>
                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($devices as $device)
                <tr class="odd:bg-white even:bg-gray-50 border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $device->serial_number }}</td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $device->device_brand }}</td>
                    <td class="px-6 py-4 uppercase">
                        {{ $device->employee?->first_name }} {{ $device->employee?->last_name }}
                    </td>
                    <td class="px-6 py-4">{{ $device->created_at?->format('d, M Y') }}</td>
                    <td class="px-6 py-4">{{ $device->created_at?->format('H:i') }}</td>
                    <td class="px-3 py-4">
                        @if (!($device?->status === 'returned' || $device?->status === 'signed_out'))
                            @if ($device->action === 'bringDevice')
                                <button type="button" 
                                    class="signOutDeviceBtn font-medium text-blue-500 p-[5px] rounded-lg border border-blue-400"
                                    data-device-name="{{ $device->employee?->first_name }} {{ $device->employee?->last_name }}"
                                    data-device-serial="{{ $device->serial_number }}"
                                    data-device-id="{{ $device->id }}">
                                    Sign Out
                                </button>
                            @elseif ($device->action === 'takeDeviceHome')
                                <button type="button" 
                                    class="returnDeviceBtn font-medium text-green-500 p-[5px] rounded-lg border border-green-400"
                                    data-device-name="{{ $device->employee?->first_name }} {{ $device->employee?->last_name }}"
                                    data-device-serial="{{ $device->serial_number }}"
                                    data-device-id="{{ $device->id }}">
                                    Return Device
                                </button>
                            @endif
                        @else
                            <span class="text-gray-400">{{ ucfirst(str_replace('_', ' ', $device->status)) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            $('#devices').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: 5 } // Disable sorting on action column
                ]
            });

            // Attach event listeners to all sign out and return device buttons
            document.querySelectorAll('.signOutDeviceBtn, .returnDeviceBtn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    

                    const employeeName = this.getAttribute('data-device-name');
                    const deviceSerial = this.getAttribute('data-device-serial');
                    const deviceId = this.getAttribute('data-device-id');
                    const isReturn = this.classList.contains('returnDeviceBtn');
                    const actionText = isReturn ? 'return' : 'sign out';
                    
                    const result = await Swal.fire({
                        title: `Confirm ${actionText}?`,
                        text: `${employeeName}, are you sure you want to ${actionText} this device with serial number ${deviceSerial}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, ${actionText}!`
                    });
                    
                    if (result.isConfirmed) {
                        try {
                            await signOutDevice(deviceId);
                        } catch (error) {
                            console.error('Error occurred:', error);
                        }
                    }
                });
            });
            
            async function signOutDevice(deviceId) {
                try {
                    // Setup axios with CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                    axios.defaults.headers.common['Accept'] = 'application/json';
                    
                    // Send request
                    const response = await axios.patch(`/sign-out-device/${deviceId}`);
                    
                    // Handle success
                    await Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success'
                    });
                    
                    // Reload the page to reflect changes
                    location.reload();
                    
                } catch (error) {
                    // Handle error
                    await Swal.fire({
                        title: 'Error!',
                        text: error.response?.data?.message || 'Something went wrong',
                        icon: 'error'
                    });
                }
            }
        });
    </script>
</x-layout>