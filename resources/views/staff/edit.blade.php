<x-layout>
   <x-slot:heading>
      Edit {{ $employee?->first_name }} {{ $employee?->last_name }}'s Details
   </x-slot:heading>

   <div class="lg:h-[calc(100vh-10rem)] overflow-auto scrollbar-hidden m-auto p-10 lg:max-w-7xl w-full">
      <form action="{{ url('update/'. $employee->id ) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100">
         @csrf
         @method('PATCH')

         <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12">
               <!-- Personal Information -->
               <div class="space-y-6">
                  <h2 class="text-lg font-semibold text-gray-900 border-b pb-3">Personal Information</h2>
                  
                  <div class="space-y-4">
                     <div>
                           <label for="first_name" class="block text-lg font-medium text-gray-700">
                              First Name <span class="text-red-500">*</span>
                           </label>
                           <input type="text" 
                              id="first_name" 
                              name="first_name" 
                              value="{{ $employee?->first_name }}" 
                              required 
                              placeholder="Jane"
                              class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                           @error('first_name')
                              <p class="mt-1 text-lg text-red-600">{{ $message }}</p>
                           @enderror
                     </div>

                     <div>
                           <label for="other_name" class="block text-lg font-medium text-gray-700">
                              Other Name <span class="text-gray-500 text-lg">(optional)</span>
                           </label>
                           <input type="text" 
                              id="other_name" 
                              name="other_name" 
                              value="{{ $employee?->other_name }}" 
                              placeholder="Abla"
                              class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                     </div>

                     <div>
                           <label for="last_name" class="block text-lg font-medium text-gray-700">
                              Last Name <span class="text-red-500">*</span>
                           </label>
                           <input type="text" 
                              id="last_name" 
                              name="last_name" 
                              value="{{ $employee?->last_name }}" 
                              required 
                              placeholder="Doe"
                              class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                           @error('last_name')
                              <p class="mt-1 text-lg text-red-600">{{ $message }}</p>
                           @enderror
                     </div>

                     <div>
                           <label class="block text-lg font-medium text-gray-700">
                              Gender <span class="text-red-500">*</span>
                           </label>
                           <div class="mt-2 space-x-6">
                              <label class="inline-flex items-center">
                                 <input type="radio" 
                                       name="gender" 
                                       value="female" 
                                       {{ $employee->gender == 'female' ? 'checked' : '' }}
                                       class="form-radio text-blue-600"
                                 >
                                 <span class="ml-2">Female</span>
                              </label>
                              <label class="inline-flex items-center">
                                 <input type="radio" 
                                       name="gender" 
                                       value="male" 
                                       {{ $employee->gender == 'male' ? 'checked' : '' }}
                                       class="form-radio text-blue-600"
                                 >
                                 <span class="ml-2">Male</span>
                              </label>
                           </div>
                     </div>
                  </div>
               </div>

               <!-- Employment Information -->
               <div class="space-y-6 mt-6 md:mt-0">
                  <h2 class="text-lg font-semibold text-gray-900 border-b pb-3">Employment Information</h2>
                  
                  <div class="space-y-4">
                     <div>
                           <label for="employee_number" class="block text-lg font-medium text-gray-700">
                               Employee Number <span class="text-red-500">*</span>
                           </label>
                           <input type="text" 
                               id="employee_number" 
                               name="employee_number" 
                               value="{{ $employee?->employee_number }}" 
                                
                               placeholder="GR 1000 25"
                               class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                       </div>

                       <div>
                           <label for="department_id" class="block text-lg font-medium text-gray-700">
                               Department <span class="text-red-500">*</span>
                           </label>
                           <select name="department_id" 
                               required 
                               class="mt-1 block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-blue-500 uppercase focus:ring-blue-500 text-xl"
                           >
                               <option value="" disabled>Select Department</option>
                               @foreach ($departments as $department)
                                   <option value="{{ $department->id }}" 
                                       {{ $department->id == $employee->department_id ? 'selected' : '' }}
                                       class="uppercase"
                                   >
                                       {{ $department->name }}
                                   </option>
                               @endforeach
                           </select>
                       </div>

                       <div>
                           <label for="job_title" class="block text-lg font-medium text-gray-700">
                               Job Title <span class="text-red-500">*</span>
                           </label>
                           <input type="text" 
                               id="job_title" 
                               name="job_title" 
                               value="{{ $employee?->job_title }}" 
                               required 
                               placeholder="Software Engineer"
                               class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                       </div>

                       <div>
                           <label for="employment_status" class="block text-lg font-medium text-gray-700">
                               Employment Status <span class="text-red-500">*</span>
                           </label>
                           <select name="employment_status" 
                               required 
                               class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl"
                           >
                               <option value="" disabled>Select Status</option>
                               <option value="active" {{ $employee->employment_status == 'active' ? 'selected' : '' }}>
                                   Employed
                               </option>
                               <option value="on_leave" {{ $employee->employment_status == 'on_leave' ? 'selected' : '' }}>
                                   On Leave
                               </option>
                               <option value="inactive" {{ $employee->employment_status == 'inactive' ? 'selected' : '' }}>
                                   No longer Employed
                               </option>
                           </select>
                       </div>
                   </div>
               </div>

               <!-- Contact Information -->
               <div class="space-y-6 mt-6">
                   <h2 class="text-lg font-semibold text-gray-900 border-b pb-3">Contact Information</h2>
                   
                   <div class="space-y-4">
                       <div>
                           <label for="email" class="block text-lg font-medium text-gray-700">
                               Email <span class="text-gray-500 text-lg">(optional)</span>
                           </label>
                           <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ $employee?->email }}" 
                               placeholder="janedoe@payswitch.com.gh"
                               class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                       </div>

                       <div>
                           <label for="phone_number" class="block text-lg font-medium text-gray-700">
                               Phone Number <span class="text-red-500">*</span>
                           </label>
                           <input type="tel" 
                               id="phone_number" 
                               name="phone_number" 
                               value="{{ $employee?->phone_number }}" 
                               required 
                               placeholder="0241234567"
                               class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                           />
                       </div>
                   </div>
               </div>

               <!-- Access Information -->
               <div class="space-y-6 mt-6">
                   <h2 class="text-lg font-semibold text-gray-900 border-b pb-3">Access Information</h2>
                   
                   <div>
                       <label for="access_card_number" class="block text-lg font-medium text-gray-700">
                           Access Card Number <span class="text-gray-500 text-lg">(optional)</span>
                       </label>
                       <input type="text" 
                           id="access_card_number" 
                           name="access_card_number" 
                           value="{{ $employee?->access_card_number }}" 
                           placeholder="EMP9283"
                           class="mt-1 block w-full rounded-md border-gray-300 border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xl" 
                       />
                   </div>
               </div>
           </div>

           <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex justify-end space-x-4">
            <a href="{{ url()->previous() }}" 
                   class="px-4 py-2 text-lg font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
               >
                   Cancel
               </a>
               <button type="submit"
                   class="px-4 py-2 text-lg font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
               >
                   Update Employee
               </button>
           </div>
       </form>
   </div>
</x-layout>
