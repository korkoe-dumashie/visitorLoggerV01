<x-layout>

    <x-slot:heading>
        Check Visitor
    </x-slot:heading>
    <main class="lg:h-[calc(100vh-20rem)] h-[calc(100vh-9rem)] relative place-content-center grid m-auto w-full items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-lg">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900">Visitor Check-out</h2>
                    <p class="mt-2 text-lg] text-gray-600">Please enter your phone number to sign out</p>
                </div>
    
                <!-- Form -->
                <form 
                    method="POST"
                    action="{{ url('confirmExit') }}"
                    id="confirm-exit"
                    class="space-y-6"
                >
                    @csrf
                    
                    <!-- Phone Number Input -->
                    <div class="space-y-2">
                        <label 
                            for="phone_number" 
                            class="block text-lg font-medium text-gray-700"
                        >
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="tel" 
                                name="phone_number"
                                id="phone_number"
                                pattern="[0-9]{10}"
                                minlength="10"
                                maxlength="10"
                                required
                                placeholder="024 000 0000"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    text-gray-900 text-lg
                                    transition duration-200"
                            />
                        </div>
                    </div>
    
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <a 
                            href="{{ url('check-visitor') }}"
                            class="inline-flex justify-center items-center px-4 py-3 rounded-lg
                                   bg-gradient-to-b from-emerald-500 to-emerald-600
                                   text-white text-base font-medium
                                   hover:from-emerald-600 hover:to-emerald-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
                                   transition-all duration-200 shadow-sm hover:shadow"
                        >
                            Sign In
                        </a>
                        <button 
                            type="submit"
                            class="inline-flex justify-center items-center px-4 py-3 rounded-lg
                                   bg-gradient-to-b from-blue-500 to-blue-600
                                   text-white text-base font-medium
                                   hover:from-blue-600 hover:to-blue-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                                   transition-all duration-200 shadow-sm hover:shadow"
                        >
                            Depart
                        </button>
                    </div>
                </form>
            </div>
    
            <!-- Help Text -->
            <p class="mt-4 text-center text-base text-gray-600">
                Need assistance? Contact the front desk
            </p>
        </div>
    </main>
    


    <script>
    

    document.getElementById('confirm-exit').addEventListener('submit', async function (e) {
    e.preventDefault();

    // Show initial loading spinner
    Swal.fire({
        title: 'Processing...',
        // html: '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    let phoneNumber = document.getElementById('phone_number');
    let phone_number = phoneNumber.value
        .replace(/\s+/g, '')  // Remove all whitespace
        .replace(/^0/, '233') // Replace leading 0 with country code 233
        .replace(/[^\d]/g, '') // Remove any non-digit characters
        .slice(0, 12);  // Limit to max 12 characters
    
    console.log(phone_number);
    try {
        let response = await axios.post("{{ route('confirmExit') }}", {
            phone_number: phone_number
        }, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        });

        let data = response.data;
        // Close the loading spinner
        Swal.close();

        if (data.success) {
            Swal.fire({
                icon: "info",
                title: "Siging out!",
                text: data.message,
                timer: 2000, // Auto close after 2 seconds
                showConfirmButton: false
            }).then(() => {
                // Show spinner during redirect
                Swal.fire({
                    title: 'Redirecting...',
                    // html: '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        window.location.href = data.redirect;
                    }
                });
            });

        } else {
            // Handle other error scenarios
            Swal.fire({
                icon: "warning",
                title: "You are not signed in. Please sign in",
                text: data.message,
                timer: 2000, // Auto close after 2 seconds
                showConfirmButton: false
            });
        }

    } catch (error) {
        // Close loading spinner on error
        Swal.close();
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: error.response?.data?.message || "An unexpected error occurred. Please try again.",
            timer: 2000,
            showConfirmButton: false
        });
    }
});
    </script>
    

</x-layout>