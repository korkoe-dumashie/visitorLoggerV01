<x-layout>

    <x-slot:heading>
        Check Visitor
    </x-slot:heading>

    <main class="p-10 flex w-full flex-col lg:py-96 py-48 justify-center m-auto items-center">
        <form method="POST"
         {{-- action="{{ url('find-visitor') }}" --}}
         id="find-visitor"
          class="flex-col flex gap-10 justify-center items-center w-full">
            @csrf
            <div class="flex-col flex gap-2">
                <label for="first_name" class=" block text-base lg:text-xl  font-medium text-black">
                 Phone Number<span class="text-red-400">*</span>
                </label>
                <input type="tel" name="phone_number"  pattern="[0-9]{10}" 
                minlength="10" 
                maxlength="10"  required id="phone_number" placeholder="024 000 0000" class="w-full bg-transparent rounded-md text-2xl border border-slate-400 py-5 px-5 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-gray-2 disabled:border-gray-2" />
             </div>
             <button class="bg-gradient-to-b px-10  text-2xl w-fit rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4]" type="submit">Submit</button>
        </form>
    </main>



    <script>
    document.getElementById('find-visitor').addEventListener('submit', async function (e) {
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
        let response = await axios.post("{{ route('find-visitor') }}", {
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
            // Function to show OTP input dialog with resend button
            async function showOTPDialog() {
                let countdown = 60;
                let countdownInterval;
                
                return Swal.fire({
                    icon: "info",
                    title: "Enter OTP",
                    html: `
                        <div>
                            <p>A code has been sent to your phone</p>
                            <input id="swal-input-otp" class="swal2-input" placeholder="Enter your OTP">
                            <div class="mt-3">
                                <button id="resendOtpBtn" class="swal2-confirm swal2-styled" disabled style="background-color: #6c757d; margin-top: 10px;">
                                    Resend OTP (60s)
                                </button>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Verify",
                    cancelButtonText: "Cancel",
                    allowOutsideClick: false,
                    didOpen: () => {
                        const resendBtn = document.getElementById('resendOtpBtn');
                        
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
                                // Clear the interval
                                clearInterval(countdownInterval);
                                
                                // Request new OTP
                                try {
                                    // Show loading spinner during OTP resend
                                    Swal.fire({
                                        title: 'Sending OTP...',
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });
                                    
                                    // Resend OTP request
                                    let resendResponse = await axios.post("{{ route('find-visitor') }}", {
                                        phone_number: phone_number
                                    }, {
                                        headers: {
                                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                                        }
                                    });
                                    
                                    Swal.close();
                                    
                                    if (resendResponse.data.success) {
                                        await Swal.fire({
                                            title: 'OTP Resent',
                                            text: 'A new OTP has been sent to your phone',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        });
                                        
                                        // Restart dialog with new countdown
                                        // Swal.close();
                                        showOTPDialog();
                                    } else {
                                        await Swal.fire({
                                            title: 'Error',
                                            text: resendResponse.data.message || 'Failed to resend OTP',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                        
                                        // Restart dialog with new countdown
                                        Swal.close();
                                        showOTPDialog();
                                    }
                                } catch (error) {
                                    console.error("Error:", error);
                                    
                                    await Swal.fire({
                                        title: 'Error',
                                        text: error.response?.data?.message || 'An unexpected error occurred',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    
                                    // Restart dialog with new countdown
                                    Swal.close();
                                    showOTPDialog();
                                }
                            }
                            return false;
                        });
                    },
                    willClose: () => {
                        clearInterval(countdownInterval);
                    },
                    preConfirm: () => {
                        const otp = document.getElementById('swal-input-otp').value;
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
                // Show loading spinner during OTP verification
                Swal.showLoading();
                
                try {
                    let verifyResponse = await axios.post("{{ route('verify-otp') }}", {
                        otp: otp
                    }, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    });

                    let result = verifyResponse.data;
                    
                    if (!result.success) {
                        Swal.hideLoading();
                        return { success: false, message: result.message };
                    }
                    
                    return { success: true, message: result.message, redirect: result.redirect };
                } catch (error) {
                    Swal.hideLoading();
                    return {
                        success: false,
                        message: error.response?.data?.message || "An error occurred. Please try again."
                    };
                }
            }

            // Main OTP verification loop
            let otpVerified = false;
            while (!otpVerified) {
                const otpDialog = await showOTPDialog();
                
                // User cancelled
                if (otpDialog.isDismissed) {
                    break;
                }
                
                // Verify the OTP that was entered
                const verifyResult = await verifyOTP(otpDialog.value);
                
                if (verifyResult.success) {
                    otpVerified = true;
                    
                    await Swal.fire({
                        icon: "success",
                        title: "OTP Verified",
                        text: verifyResult.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Show spinner during redirect
                    Swal.fire({
                        title: 'Redirecting...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            window.location.href = verifyResult.redirect;
                        }
                    });
                } else {
                    await Swal.fire({
                        icon: "error",
                        title: "Verification Failed",
                        text: verifyResult.message,
                        confirmButtonText: "Try Again"
                    });
                }
            }
        } else if (data.redirect) {
            // Handle first-time visitor scenario
            Swal.fire({
                icon: "info",
                title: "Welcome!",
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
                icon: "error",
                title: "Error",
                text: data.message || "An unexpected error occurred.",
                timer: 2000,
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