<x-layout>
   <x-slot:heading>Sign In</x-slot:heading>

   <main class="lg:h-[calc(100vh-5rem)] 3xl:h-full py-10 h-[calc(100vh-6.5rem)] overflow-auto scrollbar-hidden w-fit m-auto">
       <div>
           <!-- Visitor Info Card -->
           <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden">
               <div class="bg-gray-100 p-1">
                   <div class="p-6 sm:p-8">
                       <div class="flex items-start justify-between">
                           <div class="space-y-6 flex-1">
                               <h2 class="text-3xl font-bold text-blue-900">{{ $visitor->full_name }}</h2>
                               <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                   <div>
                                       <p class="text-sm text-gray-500">Company</p>
                                       <p class="text-xl font-medium text-blue-900">{{ $visitor->company_name }}</p>
                                   </div>
                                   <div>
                                       <p class="text-sm text-gray-500">Email</p>
                                       <p class="text-xl font-medium text-blue-900">{{ $visitor->email }}</p>
                                   </div>
                                   <div>
                                       <p class="text-sm text-gray-500">Phone Number</p>
                                       <p class="text-xl font-medium text-blue-900">{{ $visitor->phone_number }}</p>
                                   </div>
                               </div>
                           </div>
                           <img src="{{ asset('profile.svg') }}" alt="" class="w-24 h-24 sm:w-32 sm:h-32 object-cover">
                       </div>
                   </div>
               </div>
           </div>

           <form action="{{ url('visit') }}" method="post" class="space-y-8">
               @csrf
               <!-- Hidden Fields -->
               <div class="hidden">
                   <input type="hidden" name="full_name" value="{{ $visitor->full_name }}">
                   <input type="hidden" name="company_name" value="{{ $visitor->company_name }}">
                   <input type="hidden" name="email" value="{{ $visitor->email }}">
                   <input type="hidden" name="phone_number" value="{{ $visitor->phone_number }}">
               </div>

               <!-- Visitee Selection -->
               <div class="bg-white rounded-lg">
                   <label for="employee" class="block text-xl font-medium text-gray-900 mb-3">Who are you visiting?</label>
                   <select name="employee" id="employee" class="w-full rounded-lg border-gray-300 shadow-md p-4 text-lg focus:border-blue-500 focus:ring-blue-500" required>
                       <option value="" selected disabled>Select visitee</option>
                       @foreach ($employees as $employee)
                           <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                       @endforeach
                   </select>
                   @error('employee')
                       <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Purpose Selection -->
               <div class="bg-white rounded-lg">
                   <label for="purpose" class="block text-xl font-medium text-gray-900 mb-3">Purpose of Visit <span class="text-red-500">*</span></label>
                   <select name="purpose" id="purpose" class="w-full rounded-lg border-gray-300 shadow-md p-4 text-lg focus:border-blue-500 focus:ring-blue-500" required>
                       <option value="" selected disabled>Select purpose</option>
                       <option value="official">Official</option>
                       <option value="interview">Interview</option>
                       <option value="personal">Personal</option>
                       <option value="other">Other</option>
                   </select>
                   @error('purpose')
                       <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Devices Section -->
               <div class="bg-white rounded-lg shadow-md">
                   <div class="mb-4">
                       <label class="block text-xl font-medium text-gray-900 mb-3">Do you have any electronic devices?</label>
                       <div class="flex gap-6 p-4">
                           <label class="inline-flex items-center">
                               <input type="radio" name="hasDevice" value="yes" class="form-radio text-blue-600" required>
                               <span class="ml-2 text-gray-700">Yes</span>
                           </label>
                           <label class="inline-flex items-center">
                               <input type="radio" name="hasDevice" value="no" class="form-radio text-blue-600">
                               <span class="ml-2 text-gray-700">No</span>
                           </label>
                       </div>
                   </div>
                   <div id="deviceInputsSection" class="hidden space-y-4">
                       <div id="deviceInputsContainer">
                           <div class="device-block grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg relative">
                               <div>
                                   <label class="block text-lg font-medium text-gray-700 mb-1">Device Name</label>
                                   <input type="text" name="devices[0][name]" placeholder="e.g., HP Elitebook" id="devices" class="devices w-full rounded-md border-gray-300 shadow-md p-4 focus:border-blue-500 focus:ring-blue-500">
                               </div>
                               <div>
                                   <label class="block text-lg font-medium text-gray detall-700 mb-1">Serial Number</label>
                                   <input type="text" name="devices[0][serial]" placeholder="e.g., SN123456" id="devices" class="devices w-full rounded-md border-gray-300 shadow-md p-4 focus:border-blue-500 focus:ring-blue-500">
                               </div>
                               <button type="button" class="remove-device-button absolute -top-2 -right-1 w-6 h-6 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">×</button>
                           </div>
                       </div>
                       <button type="button" id="addDeviceButton" class="inline-flex items-center text-lg text-blue-600 hover:text-blue-700"><span class="mr-1">+</span> Add another device</button>
                   </div>
                   @error('devices')
                       <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Companions Section -->
               <div class="bg-white rounded-lg shadow-md">
                   <div class="mb-4">
                       <label class="block text-xl font-medium text-gray-900 mb-3">Did you come with companions?</label>
                       <div class="flex gap-6 p-4">
                           <label class="inline-flex items-center">
                               <input type="radio" name="hasCompany" value="yes" class="form-radio text-blue-600" required>
                               <span class="ml-2 text-gray-700">Yes</span>
                           </label>
                           <label class="inline-flex items-center">
                               <input type="radio" name="hasCompany" value="no" class="form-radio text-blue-600">
                               <span class="ml-2 text-gray-700">No</span>
                           </label>
                       </div>
                   </div>
                   <div id="companionsInputsSection" class="hidden space-y-4">
                       <div id="companionsInputsContainer">
                           <div class="companion-block grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg relative">
                               <div>
                                   <label class="block text-lg font-medium text-gray-700 mb-1">Full Name</label>
                                   <input type="text" name="companions[0][name]" placeholder="e.g., John Doe" id="companions" class="w-full companions rounded-md border-gray-300 shadow-md p-4 focus:border-blue-500 focus:ring-blue-500">
                               </div>
                               <div>
                                   <label class="block text-lg font-medium text-gray-700 mb-1">Phone Number</label>
                                   <input type="text" name="companions[0][phone_number]" placeholder="e.g., 0201234567" id="companions" class="w-full companions rounded-md border-gray-300 shadow-md p-4 focus:border-blue-500 focus:ring-blue-500">
                               </div>
                               <button type="button" class="remove-companion-button absolute -top-2 -right-1 w-6 h-6 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">×</button>
                           </div>
                       </div>
                       <button type="button" id="addPersonButton" class="inline-flex items-center text-lg text-blue-600 hover:text-blue-700"><span class="mr-1">+</span> Add another person</button>
                   </div>
                   @error('companions')
                       <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Submit Button -->
               <div class="flex justify-end">
                   <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">Complete Sign In</button>
               </div>
           </form>
       </div>
   </main>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
       $(document).ready(function() {
           let lastRemovedDeviceBlock = null;
           let lastRemovedCompanionBlock = null;

           function updateDeviceIndices() {
               $('#deviceInputsContainer .device-block').each(function(index) {
                   $(this).find('input').each(function() {
                       const name = $(this).attr('name');
                       if (name) {
                           const updatedName = name.replace(/devices\[\d+\]/, `devices[${index}]`);
                           $(this).attr('name', updatedName);
                       }
                   });
               });
           }

           function updateCompanionIndices() {
               $('#companionsInputsContainer .companion-block').each(function(index) {
                   $(this).find('input').each(function() {
                       const name = $(this).attr('name');
                       if (name) {
                           const companionName = name.replace(/companions\[\d+\]/, `companions[${index}]`);
                           $(this).attr('name', companionName);
                       }
                   });
               });
           }

           $('input[type=radio][name=hasDevice]').change(function() {
               if (this.value == 'yes') {
                   $('#deviceInputsSection').show();
                   $('#addDeviceButton').off('click').on('click', function() {
                       const newDeviceBlock = $('#deviceInputsContainer .device-block').first().clone();
                       newDeviceBlock.find('input').val('');
                       $('#deviceInputsContainer').append(newDeviceBlock);
                       updateDeviceIndices();
                   });
               } else {
                   $('#deviceInputsSection').hide();
                   $('#deviceInputsContainer .device-block:gt(0)').remove();
                   $('#deviceInputsContainer .device-block input').val('');
                   updateDeviceIndices();
               }
           });

           $('input[type=radio][name=hasCompany]').change(function() {
               if (this.value == 'yes') {
                   $('#companionsInputsSection').show();
                   $('#addPersonButton').off('click').on('click', function() {
                       const newCompanionBlock = $('#companionsInputsContainer .companion-block').first().clone();
                       newCompanionBlock.find('input').val('');
                       $('#companionsInputsContainer').append(newCompanionBlock);
                       updateCompanionIndices();
                   });
               } else {
                   $('#companionsInputsSection').hide();
                   $('#companionsInputsContainer .companion-block:gt(0)').remove();
                   $('#companionsInputsContainer .companion-block input').val('');
                   updateCompanionIndices();
               }
           });

           $('#deviceInputsContainer').on('click', '.remove-device-button', function() {
               if ($('#deviceInputsContainer .device-block').length > 1) {
                   const blockToRemove = $(this).closest('.device-block');
                   lastRemovedDeviceBlock = blockToRemove.clone();
                   blockToRemove.remove();
                   updateDeviceIndices();
               }
           });

           $('#companionsInputsContainer').on('click', '.remove-companion-button', function() {
               if ($('#companionsInputsContainer .companion-block').length > 1) {
                   const blockToRemove = $(this).closest('.companion-block');
                   lastRemovedCompanionBlock = blockToRemove.clone();
                   blockToRemove.remove();
                   updateCompanionIndices();
               }
           });
       });
   </script>
</x-layout>