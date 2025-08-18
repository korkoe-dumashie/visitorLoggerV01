<x-layout>
  <x-slot:heading>
      Staff Details
  </x-slot:heading>

  <div class="lg:h-[calc(100vh-10rem)] h-[calc(100vh-6.5rem)] lg:flex-col lg:flex overflow-auto scrollbar-hidden m-auto lg:max-w-7xl w-full p-10">
      <div class="bg-white h-fit m-auto w-full rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-8 h-fit">
              <div class="flex items-start justify-between">
                  <div class="space-y-1">
                      <h3 class="text-2xl font-bold text-gray-900">
                          {{ $employees->first_name ?? 'N/A' }} {{ $employees?->other_name }} {{ $employees->last_name ?? 'N/A' }}
                      </h3>
                      <p class="text-lg font-medium text-blue-600">
                          {{ $employees->job_title ?? 'N/A' }}
                      </p>
                  </div>
              </div>

              <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                  <div class="space-y-1">
                    <dt class="text-sm font-medium text-gray-500">Employee Number</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                      {{ $employees->employee_number ?? 'N/A' }}
                    </dd>
                  </div>
                  
                  <div class="space-y-1">
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                      {{ $employees->email ?? 'N/A' }}
                    </dd>
                  </div>
                </div>
                
                <div class="space-y-6">
                  <div class="space-y-1">
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                      {{ $employees->phone_number ?? 'N/A' }}
                    </dd>
                  </div>
                  
                  <div class="space-y-1">
                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                    <dd class="text-lg font-semibold text-gray-900 uppercase">
                      {{ $employees->department->name ?? 'N/A' }}
                    </dd>
                  </div>
                </div>
              </div>
              <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                  <dt class="text-sm font-medium text-gray-500">Employee Status</dt>
                  @if ($employees->employment_status == 'active')
                    
                  <dd class="text-lg font-semibold text-green-500 uppercase">
                    {{ $employees->employment_status ?? 'N/A' }}
                  </dd>

                  @elseif ($employees->employment_status == 'on_leave')
                  <dd class="text-lg font-semibold text-amber-400 uppercase">
                    On Leave
                  </dd>
                  @else
                  <dd class="text-lg font-semibold text-red-400 uppercase">
                    {{ $employees->employment_status ?? 'N/A' }}
                  </dd>
                  @endif

                </div>
                <div class="space-y-1">
                  <dt class="text-sm font-medium text-gray-500">Acess Card Number</dt>
                  <dd class="text-lg font-semibold text-gray-900 uppercase">
                    {{ $employees?->access_card_number ?? 'N/A' }}
                  </dd>
                </div>
              </div>

              <div class="mt-8 pt-8 border-t border-gray-100">
                  <div class="flex justify-end gap-4">
                      <a href="{{ url()->previous() }}" 
                         class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                          Back
                      </a>
                      <a href="{{ url('edit-staff/' . $employees->id) }}" 
                         class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                          Edit Details
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </div>
</x-layout>
