




<x-layout>

    {{-- {{ dd($keyEvent) }} --}}
<x-slot:heading>
Keys
</x-slot:heading>

    <div class="keys p-10 flex-col flex gap-4">


        @if(session('success'))

        @php
            $imageUrl = asset('PS-logo.png');
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // let title = 'Success!';
                // let text = 'The operation completed successfully.';
                let imageUrl = `{{ $imageUrl }}`;
                
                @if(session('success_type') == 'visitor_departure')
                    let title = 'Good Bye!';
                    let text = 'Thank you for visiting us today. We hope to see you again soon!';
                @elseif(session('success_type') == 'visitor_arrival')
                    let title = 'Welcome to PaySwitch!';
                    let text = 'We are happy to have you. Enjoy your visit';
                @elseif(session('success_type') == 'key_pickup')
                    let title = 'Key Logged!';
                    let text = 'The key pickup has been recorded successfully.';
                @elseif(session('success_type') == 'device_logged')
                    let title = 'Device Logged!';
                    let text = 'The device has been logged successfully.';
                @endif
                
                Swal.fire({
                    title: title,
                    text: text,
                    imageUrl: imageUrl,
                    imageWidth: 150,
                    imageHeight: 100,
                    // icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to homepage after they dismiss the alert
                    window.location.href = '{{ url("/") }}';
                });
            });
        </script>
        @endif

        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'keys', 'view'))
        <div class="flex justify-end items-center">
            <a href="{{ url('pick-key') }}" class="bg-gradient-to-b px-10 lg:text-xl text-lg rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4]">Log Key</a>
        </div>
        
@endif
        <table class="min-w-full divide-y divide-gray-200" id="keys">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Key</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Picked By</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Picked at</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Returned by</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Returned at</th>
                    <th scope="col" class="px-6 py-3 text-left text-lg lg:text-xl font-medium text-black uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($keys as $event)
                    @php
                        $employee = \App\Models\Employee::find($event->returned_by);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-lg lg:text-xl font-medium text-gray-900">
                            {{ $event->key?->key_name ?? "Deleted" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg lg:text-xl text-gray-500">
                            {{ $event?->employee?->first_name}} {{ $event?->employee?->last_name }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap capitalize text-lg lg:text-xl text-gray-500">
                            @switch($event?->status)
                                @case('picked')
                                    <span class="px-3 inline-flex text-lg lg:text-xl leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $event?->status }}
                                    </span>
                                    @break
                                @case('returned')
                                    <span class="px-3 inline-flex text-lg lg:text-xl leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $event?->status }}
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-lg lg:text-xl leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Unknown
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg lg:text-xl text-gray-500">
                            {{ $event?->created_at}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg lg:text-xl text-gray-500">
                            {{ $employee ? $employee->first_name . " " . $employee->last_name : 'Not Submitted yet'}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg lg:text-xl text-gray-500">
                            {{ $event?->returned_at ?? "Not Submitted yet" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-lg lg:text-xl font-medium">
                            @if($event?->status === 'picked')
                                <a href="{{ url('submit-key/'. $event?->id) }}" class="bg-gradient-to-b px-5 w-fit text-xl rounded-lg py-2 text-white 
                      from-red-500 to-red-700 flex items-center" >
                                    Submit Key
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
    // ✅ Fix DataTable Initialization
    $(document).ready(function () {
        $("#keys").DataTable({
            scrollX: true,
            responsive: true,
        });
    });
// });


    </script>
    
</x-layout>