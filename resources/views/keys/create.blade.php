<x-layout>

    <x-slot:heading>
        Pick a Key
    </x-slot:heading>


    <aside class="p-10">
        <form action="{{url('log-key')}}" class="w-full" method="POST">

            @csrf
            <div class="w-full md:w-1/2 lg:w-1/3">



                <div class="mb-12 w-full relative">                
                    @if (session()->has('error'))
                    <p class="">{{ session()->get('error') }}</p>


                    <div class="flex items-center absolute top-0 gap-4 justify-evenly h-fit  bottom-0 lg:left-[700px] w-fit left-1/2 lg:w-[25rem]  p-4 rounded-lg shadow-md ">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" class="bg-red-100 rounded-xl" height="40" viewBox="0 0 48 48">
                            <linearGradient id="hbE9Evnj3wAjjA2RX0We2a_OZuepOQd0omj_gr1" x1="7.534" x2="27.557" y1="7.534" y2="27.557" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f44f5a"></stop><stop offset=".443" stop-color="#ee3d4a"></stop><stop offset="1" stop-color="#e52030"></stop></linearGradient><path fill="url(#hbE9Evnj3wAjjA2RX0We2a_OZuepOQd0omj_gr1)" d="M42.42,12.401c0.774-0.774,0.774-2.028,0-2.802L38.401,5.58c-0.774-0.774-2.028-0.774-2.802,0	L24,17.179L12.401,5.58c-0.774-0.774-2.028-0.774-2.802,0L5.58,9.599c-0.774,0.774-0.774,2.028,0,2.802L17.179,24L5.58,35.599	c-0.774,0.774-0.774,2.028,0,2.802l4.019,4.019c0.774,0.774,2.028,0.774,2.802,0L42.42,12.401z"></path><linearGradient id="hbE9Evnj3wAjjA2RX0We2b_OZuepOQd0omj_gr2" x1="27.373" x2="40.507" y1="27.373" y2="40.507" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#a8142e"></stop><stop offset=".179" stop-color="#ba1632"></stop><stop offset=".243" stop-color="#c21734"></stop></linearGradient><path fill="url(#hbE9Evnj3wAjjA2RX0We2b_OZuepOQd0omj_gr2)" d="M24,30.821L35.599,42.42c0.774,0.774,2.028,0.774,2.802,0l4.019-4.019	c0.774-0.774,0.774-2.028,0-2.802L30.821,24L24,30.821z"></path>
                            </svg>

                        <h3 class="">{{ session()->get('error') }}</h3>

                        <button type="button" onclick="this.parentElement.style.display='none'">
                            <img src="{{ asset('x.svg') }}" alt="" class="w-5 h-5">
                        </button>
                    </div>
                @endif

                <div class="flex-col flex gap-4">
                    <label for="" class="block text-base font-medium text-black">
                        Who are you?
                        </label>
                    <select id="staff" class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="picked_by" required >
                        <option value="" selected disabled class="">Select your name</option>
                        @foreach ($employees as $employee)
                    <option value="{{$employee->id}}" id="employeeName" class="text-blue-500 lg:text-xl text-lg">{{$employee->first_name}} {{$employee->last_name}}</option>
                        @endforeach
                    </select>


                      <label for="" class="block text-base font-medium text-black">
                        Key you are picking.
                        </label>
                      <select id="keys" class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="key_number" required >
                        <option value="" selected disabled class="">Select Key</option>
                        @foreach ($keys as $key)
                         <option value="{{$key->id}}" id="keyName" class=" odd:text-blue-500 even:text-green-500">{{$key->key_name}}</option>
                        @endforeach
                      </select>

                   </div>
                </div>

                <div class="">
                    <button type="submit"
                        class="bg-gradient-to-b px-10 text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center">
                        Pick Key
                        </button>
                </div>
             </div>
        </form>
    </aside>


    
</x-layout>


<script>
    // Use an event listener instead of inline onclick
document.addEventListener('DOMContentLoaded', function() {
    const keyForm = document.querySelector('form[action*="log-key"]');
    const staff = document.getElementById('staff');
    const selectedStaff = staff.options[staff.selectedIndex];
    const keys = document.getElementById('keys');
    const selectedKey = keys.options[keys.selectedIndex];
    const keyName = selectedKey ? selectedKey.textContent.trim() : '';
    const employeeName = selectedKey ? selectedKey.textContent.trim() : '';

    console.log(keyName);
    console.log(employeeName);
    if (keyForm) {
        keyForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // This prevents the form from submitting normally
            
            // Get form data
            const formData = new FormData(this);

            // For debugging - show form data
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Wait for confirmation before proceeding
            const result = await Swal.fire({
                title: "Confirm Key Pickup",
                text: `Are you sure you want to pick this key?`,
                icon: "info",
                showCancelButton: true, 
                confirmButtonColor: "#28A745",
                cancelButtonColor: "#D33",
                confirmButtonText: "Yes, pick it!",
                cancelButtonText: "Cancel"
            });

            // If user cancels, don't proceed
            if (!result.isConfirmed) {
                return;
            }

            // Submit using axios if confirmed
            try {
                const response = await axios.post(this.action, formData);
                
                if (response.data.success) {
                    // Show SweetAlert on success
                    await Swal.fire({
                        title: 'Key Pickup Successful👍',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    
                    // Redirect to homepage
                    window.location.href = 'keys';
                } else{
                    await Swal.fire({
                        title: 'Key picked!',
                        text: response.data.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    window.location.href = '/';
                }
            } catch (error) {
                // Handle errors
                let errorMessage = 'An error occurred';
                if (error.response && error.response.data && error.response.data.error) {
                    errorMessage = error.response.data.error;
                }
                
                await Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});
</script>