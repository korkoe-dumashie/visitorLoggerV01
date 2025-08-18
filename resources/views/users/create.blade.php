<x-layout>

    <x-slot:heading>
        Assign a new User
    </x-slot:heading>



    <section class="flex-col flex   lg:w-1/3 p-10 gap-4">
        <form action="{{ url('assign-user') }}" method="POST" class="flex-col flex gap-4">
            @csrf


            <label for="" class="block text-3xl font-medium text-black">
                Staff
                </label>
                <div class="flex-col flex gap-4">
                   <select class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="employee_id" required >
                         <option value="" selected disabled class="">Choose a staff</option>
                     @foreach ($employees as $employee)
                      <option value="{{$employee->id}}" class=" odd:bg-transparent even:bg-white/50">{{$employee->first_name}} {{$employee->last_name}}</option>
                     @endforeach
                   </select>

                   
                   <select class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="role_id" required >
                         <option value="" selected disabled class="">Choose their role?</option>
                     @foreach ($roles as $role)
                      <option value="{{$role->id}}" class="">{{$role->name}}</option>
                     @endforeach
                   </select>


                   <button type="submit" class="bg-gradient-to-b px-10 w-fit text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center">Assign role</button>



        </form>
    </section>

</x-layout>