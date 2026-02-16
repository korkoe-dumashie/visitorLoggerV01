<x-layout>

    <x-slot:heading>
        Assign a new User
    </x-slot:heading>

    <section class="flex-col flex lg:w-1/2 p-10 gap-4">
        <form id="createUserForm" action="{{ url('assign-user') }}" method="POST" class="flex-col flex gap-4">
            @csrf

            <label for="" class="block text-3xl font-medium text-black">
                Staff
            </label>
            <div class="flex-col flex gap-4">
                <select class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="employee_id" required>
                    <option value="" selected disabled>Choose a staff</option>
                    @foreach ($employees as $employee)
                        <option value="{{$employee->id}}" class="odd:bg-transparent even:bg-white/50">
                            {{$employee->first_name}} {{$employee->last_name}}
                        </option>
                    @endforeach
                </select>

                <select class="p-4 focus:border-blue-300 rounded-md outline-none text-slate-500 border border-gray-400 w-1/2" name="role_id" required>
                    <option value="" selected disabled>Choose their role?</option>
                    @foreach ($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>

                <button type="submit" id="submitBtn" class="bg-gradient-to-b px-10 w-fit text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center gap-2">
                    <span id="btnText">Create User</span>
                    <svg id="loader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </section>

    <script>
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loader = document.getElementById('loader');
            const formData = new FormData(form);

            // Show loader, disable button
            btnText.textContent = 'Creating...';
            loader.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || '/users';
                } else {
                    alert(data.message || 'An error occurred');
                    resetButton();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                resetButton();
            });

            function resetButton() {
                btnText.textContent = 'Create User';
                loader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    </script>

</x-layout>
