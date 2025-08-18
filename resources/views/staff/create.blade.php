<x-layout>
    <!-- When there is no desire, all things are at peace. - Laozi -->
    {{-- @foreach ($employees as $employee) --}}


    <x-slot:heading>
        Register a new Staff.
    </x-slot:heading>

    {{-- <aside class=""> --}}
        <form action="{{url('store-staff')}}" method="POST" class="flex p-5 w-full overflow-hidden">
            @csrf


            <aside class="w-1/2">
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="first_name" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       First Name  <span class="text-red-400 text-lg">*</span>
                       </label>
                       <input type="text" placeholder="Jane" id="first_name" name="first_name" required class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-dark-6 outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default disabled:bg-gray-2 disabled:border-gray-2" />
                    </div>
                    @error('vehicle_number')
                    <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                    @enderror
                 </div>
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="other_name" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       Other Name <span class="text-gray-500">(optional)</span>
                       </label>
                       <input type="text" placeholder="Abla" id="other_name" name="other_name" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-dark-6 outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                    </div>
                    @error('other_name')
                    <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                    @enderror
                 </div>
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="last_name" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       Last Name  <span class="text-red-400 text-lg">*</span>
                       </label>
                       <input type="text" placeholder="Doe" id="last_name" name="last_name" required class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-dark-6 outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                    </div>
                    @error('last_name')
                    <div class="text-red-500 italic font-normal text-lg lg:text-xl">{{ $message }}</div>
                    @enderror
                 </div>
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="employee_number" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       Employee Number   <span class="text-red-400 text-xl">*</span>
                       </label>
                       <input type="text" placeholder="GR 1000 25" id="employee_number" required name="employee_number" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-black outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                    </div>
                    @error('employee_number')
                    <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                    @enderror
                 </div>
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="email" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       Email <span class="text-gray-500">(optional)</span>
                       </label>
                       <input type="text" placeholder="janedoe@payswitch.com.gh" id="email" name="email" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 active:border-blue-400 focus:border-blue-400 outline-none transition  disabled:cursor-default" />
                    </div>
                    @error('email')
                    <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                    @enderror
                 </div>
                <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                    <div class="mb-12">
                       <label for="phone_number" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                       Phone Number  <span class="text-red-400 text-lg">*</span>
                       </label>
                       <input type="text" placeholder="0241234567" id="phone_number" required name="phone_number" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-dark-6 outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                    </div>
                    @error('vehicle_number')
                    <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                    @enderror
                 </div>

                 

            </aside>


            <aside class="w-1/2">
                             <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                <div class="mb-12">
                   <label for="purpose" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                   Department <span class="text-red-400 text-lg">*</span>
                   </label>
                <select class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border uppercase border-gray-400 w-1/2" name="department_id" required >
                      <option value="" selected disabled class="">Choose Department</option>
                  @foreach ($departments as $deparment)
                   <option value="{{$deparment->id}}" class="uppercase">{{$deparment->name}}</option>
                  @endforeach
                </select>
             </div>
             </div>


            <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                <div class="mb-12">
                   <label for="job_title" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                   Job Title  <span class="text-red-400 text-lg">*</span>
                   </label>
                   <input type="text" placeholder="Software Engineer" id="job_title" required name="job_title" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-black outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                </div>
                @error('vehicle_number')
                <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                @enderror
             </div>
            <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                <div class="mb-12">
                   <label for="access_card_number" class="mb-[10px] block lg:text-xl text-lg font-medium text-black">
                   Access Card Number <span class="text-gray-500">(optional)</span>
                   </label>
                   <input type="text" placeholder="EMP9283" id="access_card_number"  name="access_card_number" class="w-full bg-transparent rounded-md border border-slate-400 py-5 px-5 text-dark-6 outline-none transition focus:border-blue-400 active:border-blue-400 disabled:cursor-default" />
                </div>
                @error('vehicle_number')
                <div class="text-red-500 italic font-normal text-sm">{{ $message }}</div>
                @enderror
             </div>
             <div class="w-full px-4 md:w-1/2 lg:w-1/2">
                <h3 class="mb-4 font-semibold lg:text-xl text-lg text-gray-900">Gender  <span class="text-red-400 text-xl">*</span></h3>
             <div class="flex w-full md:w-1/2 lg:w-1/2">
                <div class="flex items-center me-4">
                    <input id="inline-radio" type="radio" value="female" name="gender" required class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="inline-radio" class="ms-2 text-lg lg:text-xl font-medium text-gray-900 ">Female</label>
                </div>
                <div class="flex items-center">
                    <input id="inline-2-radio" type="radio" value="male" name="gender" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label for="inline-2-radio" class="ms-2 text-lg lg:text-xl font-medium text-gray-900">Male</label>
                </div>
             </div>             
             <div class="flex items-baseline justify-end">
                <button type="submit"
                    class="bg-gradient-to-b px-10 text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center">
                    Submit
                    </button>
            </div>
             </div>

             

            </aside>

        </form>
    {{-- </aside> --}}





</x-layout>