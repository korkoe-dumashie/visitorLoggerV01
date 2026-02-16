<x-layout>
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

        <form id="submit-key"  method="POST" class="flex w-full gap-y-4 flex-col">
            @csrf
            @method('PATCH')
            <h4 class="">Who are you?</h4>
            <select class="p-4 focus:border-blue-300 rounded-md outline-none text-blue-800 border border-gray-400 w-full" id="returned_by" name="returned_by" required>
                <option value="" selected disabled>Find your name.</option>
                @foreach ($employees as $employee)
                    <option value="{{$employee?->id}}">{{$employee?->first_name}} {{$employee?->last_name}}</option>
                @endforeach
            </select>

            <div class="flex flex-col">
                <label for="phone_number">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number"
                       class="p-4 focus:border-blue-300 rounded-md outline-none text-blue-800 border border-gray-400 w-full"
                       placeholder="0241234567"
                       pattern="[0-9]{10}"
                       minlength="10"
                       maxlength="10"
                       required>
            </div>

            <button class="bg-blue-600 text-lg w-1/2 rounded-lg text-white p-3"
                    type="submit"
                    data-key-id="{{ $keyEvent?->id }}"
                    data-key-name="{{ $key->key_name }}">
                Return Key
            </button>
        </form>
    </main>

    <script>
    document.getElementById('submit-key').addEventListener('submit', async function(e) {
        e.preventDefault();

        const keyId = this.querySelector('button').getAttribute('data-key-id');
        const keyName = this.querySelector('button').getAttribute('data-key-name');
        const returned_by = document.getElementById('returned_by').value;
        const phoneInput = document.getElementById('phone_number');
        const phone_number = phoneInput.value;
        const selectedEmployee = document.querySelector(`#returned_by option[value="${returned_by}"]`);

        // Validation
        if (!returned_by || !selectedEmployee) {
            await Swal.fire({
                title: "Error!",
                text: "Please select your name before returning the key.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        if (!phone_number) {
            await Swal.fire({
                title: "Error!",
                text: "Please enter your phone number before returning the key.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        const employeeName = selectedEmployee.textContent.trim();

        // Confirm return
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

        // Format phone number
        let formattedPhone = phone_number
            .replace(/\s+/g, '')
            .replace(/^0/, '233')
            .replace(/[^\d]/g, '')
            .slice(0, 12);

        // Request OTP
        async function requestOTP() {
            Swal.fire({
                title: 'Sending OTP...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await axios.patch(`/return-key/${keyId}`, {
                    returned_by: returned_by,
                    phone_number: formattedPhone,
                    _token: "{{ csrf_token() }}"
                });

                console.log(response);

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

        // Show OTP dialog
        async function showOTPDialog() {
            let countdown = 120;
            let countdownInterval;

            return Swal.fire({
                title: "Enter OTP",
                html: `
                    <div>
                        <p>${employeeName}, please enter the verification code sent to ${formattedPhone}</p>
                        <input id="swal-input-otp" class="swal2-input" placeholder="Enter your OTP">
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
                            clearInterval(countdownInterval);

                            const resendResult = await requestOTP();

                            if (resendResult.success) {
                                await Swal.fire({
                                    title: 'OTP Resent',
                                    text: 'A new OTP has been sent to your phone',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                showOTPDialog();
                            } else {
                                await Swal.fire({
                                    title: 'Error',
                                    text: resendResult.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
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

        // Verify OTP
        async function verifyOTP(otp) {
            Swal.fire({
                title: 'Verifying...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const verifyResponse = await axios.post("{{ route('confirmKey') }}", {
                    otp: otp,
                    _token: "{{ csrf_token() }}"
                });

                Swal.close();

                if (verifyResponse.data.success) {
                    await Swal.fire({
                        title: "Success!",
                        text: verifyResponse.data.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                    window.location.href = '{{ url("/") }}';
                    return { success: true };
                }
            } catch (error) {
                console.error('Full error:', error);

                if (error.response?.data?.max_attempts_reached) {
                    const skipResult = await Swal.fire({
                        title: "Maximum Attempts Reached",
                        text: "You've reached the maximum OTP attempts. Would you like to proceed without verification?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#28A745",
                        cancelButtonColor: "#D33",
                        confirmButtonText: "Yes, proceed anyway",
                        cancelButtonText: "Cancel"
                    });

                    if (skipResult.isConfirmed) {
                        return await skipVerification();
                    }
                    return { success: false };
                }

                await Swal.fire({
                    title: "Error!",
                    text: error.response?.data?.message || "Invalid OTP. Please try again.",
                    icon: "error",
                    confirmButtonText: "Try Again"
                });
                return { success: false };
            }
        }

        // Skip verification
        async function skipVerification() {
            Swal.fire({
                title: 'Processing...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await axios.post('/skip-otp-verification', {
                    _token: "{{ csrf_token() }}"
                });

                Swal.close();

                if (response.data.success) {
                    await Swal.fire({
                        title: "Success!",
                        text: response.data.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                    window.location.href = '{{ url("/") }}';
                    return { success: true };
                }
            } catch (error) {
                Swal.close();
                await Swal.fire({
                    title: "Error!",
                    text: error.response?.data?.message || "Failed to process request.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return { success: false };
            }
        }

        // Main flow
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

        // OTP verification loop
        let otpVerified = false;
        while (!otpVerified) {
            const otpDialog = await showOTPDialog();

            if (otpDialog.isDismissed) {
                return;
            }

            const verifyResult = await verifyOTP(otpDialog.value);
            if (verifyResult.success) {
                otpVerified = true;
            }
        }
    });
    </script>
</x-layout>
