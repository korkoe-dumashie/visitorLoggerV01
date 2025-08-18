<x-layout>


    <script>
        // Show toast notification if there's a flash message
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>

    <x-slot:heading>
        Visitor Access Cards
    </x-slot:heading>
    <main class="p-10 w-full mx-auto">
        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'create'))
            <div class="mb-8 flex justify-end">
                <a href="{{url('create-access-card')}}" 
                   class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <span class="mr-2">+</span>
                    Create New Card
                </a>
            </div>
        @endif
    
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table id="accessCards" class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th scope="col" class="px-6 py-4 text-lg font-semibold text-gray-900 text-left">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-4 text-lg font-semibold text-gray-900 text-left">
                                Created at
                            </th>
                            <th scope="col" class="px-6 py-4 text-lg font-semibold text-gray-900 text-left">
                                Status
                            </th>
                            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'modify'))
                                <th scope="col" class="px-6 py-4 text-lg font-semibold text-gray-900 text-left">
                                    Actions
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($visitorAccessCards as $card)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-lg font-medium text-gray-900">
                                    {{ $card?->card_number }}
                                </td>
                                <td class="px-6 py-4 text-lg text-gray-600 uppercase">
                                    {{ $card?->created_at?->format('Y-m-d') }}
                                </td>
                                @switch($card['status'])
                                    @case('available')
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-base font-medium bg-green-100 text-green-800">
                                                Available
                                            </span>
                                        </td>
                                        @break
                                    @case('unavailable')
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-base font-medium bg-yellow-100 text-yellow-800">
                                                Unavailable
                                            </span>
                                        </td>
                                        @break
                                @endswitch

                                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'modify'))
                                    <td class="px-6 py-4">
                                        @if ($card?->active === 'enabled')
                                        <button 
                                        type="button"
                                        data-card-id="{{ $card->id }}"
                                        data-card-number="{{ $card->card_number }}"
                                        data-action="disable"
                                        class="disable-card-btn inline-flex items-center px-3 py-1.5 border border-red-300 text-lg font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                        Disable
                                    </button>
                                        @elseif ($card?->active === 'disabled')
                                        <button
                                        type="button"
                                        data-card-id="{{ $card->id }}"
                                        data-card-number="{{ $card->card_number }}"
                                        data-action="enable"
                                        class="enable-card-btn inline-flex items-center px-3 py-1.5 border border-blue-300 text-lg font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        Enable
                                    </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <form id="enable-form" method="POST" class="hidden" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
    
    <form id="disable-form" method="POST" class="hidden" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
</x-layout>

<script>
    $(document).ready(function() {
       // Initialize DataTable
       $('#accessCards').DataTable({
           responsive: true,
           pageLength: 10,
           language: {
               search: "Search cards:",
               lengthMenu: "Show _MENU_ entries",
               info: "Showing _START_ to _END_ of _TOTAL_ cards",
               paginate: {
                   first: "First",
                   last: "Last",
                   next: "Next",
                   previous: "Previous"
               }
           }
       });
       
       // Check for success message in session and show toast if present
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
       
       // Handle disable button clicks
       $(document).on('click', '.disable-card-btn', function() {
           const cardId = $(this).data('card-id');
           const cardNumber = $(this).data('card-number');
           
           Swal.fire({
               title: 'Disable Access Card?',
               html: `Are you sure you want to <strong>disable</strong> access card <strong>${cardNumber}</strong>?<br><br>This will mark the card as unavailable.`,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#d33',
               cancelButtonColor: '#6c757d',
               confirmButtonText: 'Yes, disable it!',
               cancelButtonText: 'Cancel'
           }).then((result) => {
               if (result.isConfirmed) {
                   // Show processing state
                   Swal.fire({
                       title: 'Processing...',
                       html: `Disabling access card ${cardNumber}`,
                       allowOutsideClick: false,
                       allowEscapeKey: false,
                       didOpen: () => {
                           Swal.showLoading();
                       }
                   });
                   
                   // Submit form with PATCH method
                   const form = $('#disable-form');
                   form.attr('action', "{{ url('disable-access-card') }}/" + cardId);
                   form.submit();
               }
           });
       });
       
       // Handle enable button clicks
       $(document).on('click', '.enable-card-btn', function() {
           const cardId = $(this).data('card-id');
           const cardNumber = $(this).data('card-number');
           
           Swal.fire({
               title: 'Enable Access Card?',
               html: `Are you sure you want to <strong>enable</strong> access card <strong>${cardNumber}</strong>?<br><br>This will mark the card as available.`,
               icon: 'question',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#6c757d',
               confirmButtonText: 'Yes, enable it!',
               cancelButtonText: 'Cancel'
           }).then((result) => {
               if (result.isConfirmed) {
                   // Show processing state
                   Swal.fire({
                       title: 'Processing...',
                       html: `Enabling access card ${cardNumber}`,
                       allowOutsideClick: false,
                       allowEscapeKey: false,
                       didOpen: () => {
                           Swal.showLoading();
                       }
                   });
                   
                   // Submit form with PATCH method
                   const form = $('#enable-form');
                   form.attr('action', "{{ url('enable-access-card') }}/" + cardId);
                   form.submit();
               }
           });
       });
   });
</script>