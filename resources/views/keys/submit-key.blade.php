<x-layout>

            {{-- {{ dd($keyEvent) }} --}}
    <x-slot:heading>
        Return Key
    </x-slot:heading>


    <main class="w-1/2 flex flex-col gap-4 p-10">
        <aside class="w-fit">


            @php
            
                $key = App\Models\Key::findOrFail($keyEvent->key_number);

            @endphp
            <h4 class="text-xl font-light">You are returning the <span class="text-red-500 font-bold text-xl">{{$key->key_name}}</span> Key.</h4>
        </aside>
        <form action="{{ url('return-key/'.$keyEvent['id']) }}" class="flex w-1/2  gap-y-4 flex-col" method="POST">


            @csrf
            @method('PATCH')
            <h4 class="">Who are you?</h4>
            <select class="p-4 focus:border-blue-300 rounded-md outline-none text-blue-800 border border-gray-400 w-full" id="returned_by" name="returned_by" required >
                <option value="" selected disabled class="">Find your name.</option>
            @foreach ($employees as $employee)
             <option value="{{$employee?->id}}" class="text-lg font-medium text-blue-400">{{$employee?->first_name}} {{$employee?->last_name}}</option>
            @endforeach
          </select>

          <button class="bg-blue-600 text-lg w-1/2 rounded-lg text-white p-3" type="button" onclick="confirmReturn(this)" data-key-id="{{ $keyEvent?->id }}" data-key-name="{{ $key->key_name }}">Return Key</button>
        </form>
    </main>

<script>
    async function confirmReturn(button) {
    const keyId = button.getAttribute("data-key-id");
    const keyName = button.getAttribute("data-key-name");
    const returned_by = document.getElementById("returned_by").value;
    const selectedEmployee = document.querySelector(`#returned_by option[value="${returned_by}"]`);

    if (!returned_by || !selectedEmployee) {
        await Swal.fire({
            title: "Error!",
            text: "Please select your name before returning the key.",
            icon: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    const employeeName = selectedEmployee.textContent.trim();

    const confirmResult = await Swal.fire({
        title: "Confirm Return",
        text: `${employeeName}, are you sure you want to return the "${keyName}" key?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#D33",
        confirmButtonText: "Yes, return it!",
        cancelButtonText: "Cancel"
    });

    if (!confirmResult.isConfirmed) return;

    // Function to request OTP
    async function requestOTP() {
        // Show loading while sending OTP
        Swal.fire({
            title: 'Sending OTP...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // Step 1: Request OTP
            const response = await axios.patch(`/return-key/${keyId}`, {
                returned_by: returned_by,
                _token: "{{ csrf_token() }}"
            });

            Swal.close();

            if (response.data.success) {
                return { success: true, message: response.data.message };
            } else {
                return { 
                    success: false, 
                    message: response.data.message || "Failed to send OTP." 
                };
            }
        } catch (error) {
            console.error(error);
            let errorMessage = "Something went wrong.";
            if (error.response && error.response.data) {
                errorMessage = error.response.data.message || error.response.data.error || "An error occurred.";
            }
            return { success: false, message: errorMessage };
        }
    }

    // Function to show OTP input dialog
    async function showOTPDialog() {
        let countdown = 120;
        let countdownInterval;
        
        return Swal.fire({
            title: "Enter OTP",
            html: `
                <div>
                    <p>${employeeName}, please enter the verification code sent to your phone</p>
                    <input id="swal-input1" class="swal2-input" placeholder="Enter your OTP">
                    <div class="mt-3">
                        <button id="resendBtn" class="swal2-confirm swal2-styled" disabled style="background-color: #6c757d; margin-top: 10px;">
                            Resend OTP (2min)
                        </button>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Verify",
            cancelButtonText: "Cancel",
            allowOutsideClick: false,
            didOpen: () => {
                const resendBtn = document.getElementById('resendBtn');
                
                countdownInterval = setInterval(() => {
                    countdown--;
                    if (countdown <= 0) {
                        resendBtn.disabled = false;
                        resendBtn.style.backgroundColor = '#3085d6';
                        resendBtn.textContent = 'Resend OTP';
                        clearInterval(countdownInterval);
                    } else {
                        resendBtn.textContent = `Resend OTP (${countdown}s)`;
                    }
                }, 1000);
                
                resendBtn.addEventListener('click', async () => {
                    if (!resendBtn.disabled) {
                        // Clear the interval and close current dialog
                        clearInterval(countdownInterval);
                        
                        // Request new OTP
                        const resendResult = await requestOTP();
                        
                        // console.log(resendResult);
                        if (resendResult.success) {
                            
                            await Swal.fire({
                                title: 'OTP Resent',
                                text: 'A new OTP has been sent to your phone',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                            .then(() => {
                                showOTPDialog();
                            });

                            
                            // Force close the current dialog to prevent stacking
                            // Swal.close();
                            
                            // Show a new OTP dialog by returning a special value
                            return 'resend';
                        } else {
                            await Swal.fire({
                                title: 'Error',
                                text: resendResult.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                    return false;
                });
            },
            willClose: () => {
                clearInterval(countdownInterval);
            },
            preConfirm: () => {
                const otp = document.getElementById('swal-input1').value;
                if (!otp) {
                    Swal.showValidationMessage("Please enter the OTP");
                    return false;
                }
                return otp;
            }
        });
    }

    // Function to verify OTP
    async function verifyOTP(otp) {
        // Show loading while verifying OTP
        Swal.fire({
            title: 'Verifying...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const verifyResponse = await axios.post('/confirmKey', {
                otp: otp,
                _token: "{{ csrf_token() }}"
            });

            Swal.close();

            if (verifyResponse.data.success) {
                // Show success message
                await Swal.fire({
                    title: "Success!",
                    text: verifyResponse.data.message,
                    icon: "success",
                    confirmButtonText: "OK"
                });
                
                // Redirect to homepage
                window.location.href = '{{ url("/") }}';
                return true;
            } else {
                await Swal.fire({
                    title: "Error!",
                    text: verifyResponse.data.message || "Invalid OTP. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return false;
            }
        } catch (error) {
            console.error(error);
            let errorMessage = "Something went wrong.";
            if (error.response && error.response.data) {
                errorMessage = error.response.data.message || error.response.data.error || "An error occurred.";
            }

            await Swal.fire({
                title: "Error!",
                text: errorMessage,
                icon: "error",
                confirmButtonText: "OK"
            });
            return false;
        }
    }

    // Initial OTP request
    const initialOtpResult = await requestOTP();
    
    if (!initialOtpResult.success) {
        await Swal.fire({
            title: "Error!",
            text: initialOtpResult.message,
            icon: "error",
            confirmButtonText: "OK"
        });
        return;
    }

    // Main OTP verification loop
    let otpVerified = false;
    while (!otpVerified) {
        const otpDialog = await showOTPDialog();
        
        // User cancelled
        if (otpDialog.isDismissed) {
            return;
        }
        
        // Check if resend button was clicked (handled in the dialog)
        if (otpDialog.value === 'resend') {
            continue; // This will restart the loop and show a new OTP dialog
        }
        
        // Verify the OTP that was entered
        otpVerified = await verifyOTP(otpDialog.value);
    }
}
</script>


</x-layout>