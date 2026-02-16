<x-layout>
    <x-slot:heading>
        Devices
    </x-slot:heading>

    <div id="device-table" class="w-full flex sm:rounded-lg p-10 flex-col gap-5">
        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
            <div class="flex justify-end">
                <a href="{{url('log')}}" class="bg-gradient-to-b px-10 text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] flex items-center">
                  Log Device
                </a>
            </div>
        @endif

        <table class="w-full text-sm text-left text-gray-500" id="devices">
            <thead class=" text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 text-start py-3">Serial Number</th>
                    <th scope="col" class="px-6 text-sm lg:text-base py-3">Brand</th>
                    <th scope="col" class="px-6 text-sm lg:text-base py-3">Staff</th>
                    <th scope="col" class="px-6 text-sm lg:text-base py-3">Date</th>
                    <th scope="col" class="px-6 text-sm lg:text-base py-3">Time</th>
                    <th scope="col" class="px-6 text-sm lg:text-base py-3">Action</th>
                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($devices as $device)
                <tr class="odd:bg-white even:bg-gray-50 border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900">{{ $device->serial_number }}</td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900">{{ $device->device_brand }}</td>
                    <td class="px-6 py-4 capitalize">
                        {{ $device->employee?->first_name }} {{ $device->employee?->last_name }}
                    </td>
                    <td class="px-6 py-4">{{ $device->created_at?->format('d, M Y') }}</td>
                    <td class="px-6 py-4">{{ $device->created_at?->format('H:i') }}</td>
                    <td class="px-3 py-4">
                        @if (!($device?->status === 'returned' || $device?->status === 'signed_out'))
                            @if ($device->action === 'bringDevice')
                                    <button type="button"
                                        class="signOutDeviceBtn font-medium text-blue-500 p-[5px] rounded-lg border border-blue-400"
                                        data-device-name="{{ $device->employee?->first_name }} {{ $device->employee?->last_name }}"
                                        data-device-serial="{{ $device->serial_number }}"
                                        data-device-id="{{ $device->id }}"
                                        data-employee-id="{{ $device->employee_id }}">
                                        Sign Out
                                    </button>
                            @elseif ($device->action === 'takeDeviceHome')
                                    <button type="button"
                                    class="returnDeviceBtn font-medium text-green-500 p-[5px] rounded-lg border border-green-400"
                                    data-device-name="{{ $device->employee?->first_name }} {{ $device->employee?->last_name }}"
                                    data-device-serial="{{ $device->serial_number }}"
                                    data-device-id="{{ $device->id }}"
                                    data-employee-id="{{ $device->employee_id }}">
                                    Return Device
                                </button>
                            @endif
                        @else
                            <span class="text-gray-400">{{ ucfirst(str_replace('_', ' ', $device->status)) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#devices').DataTable({
            responsive: true,
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });

        // Sign Out Device (existing functionality)
// Sign Out Device (with OTP verification)
document.querySelectorAll('.signOutDeviceBtn').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();

        const employeeName = this.getAttribute('data-device-name');
        const deviceSerial = this.getAttribute('data-device-serial');
        const deviceId = this.getAttribute('data-device-id');
        const employeeId = this.getAttribute('data-employee-id');

        // Step 1: Confirm sign out
        const confirmResult = await Swal.fire({
            title: 'Confirm Sign Out',
            text: `${employeeName}, are you sure you want to sign out the device with serial number ${deviceSerial}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, sign out!',
            cancelButtonText: 'Cancel'
        });

        if (!confirmResult.isConfirmed) return;

        // Step 2: Ask for phone number
        const phoneResult = await Swal.fire({
            title: 'Enter Phone Number',
            html: `
                <p>Please enter your phone number for verification</p>
                <input id="swal-signout-phone-input" class="swal2-input"
                       placeholder="0241234567"
                       type="tel"
                       pattern="[0-9]{10}"
                       maxlength="10">
            `,
            showCancelButton: true,
            confirmButtonText: 'Send OTP',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const phone = document.getElementById('swal-signout-phone-input').value;
                if (!phone || phone.length !== 10) {
                    Swal.showValidationMessage('Please enter a valid 10-digit phone number');
                    return false;
                }
                return phone;
            }
        });

        if (!phoneResult.isConfirmed) return;

        const phone_number = phoneResult.value;
        const formattedPhone = phone_number.replace(/^0/, '233');

        // Request OTP for sign out
        async function requestSignOutOTP() {
            Swal.fire({
                title: 'Sending OTP...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await axios.post(`/request-signout-otp/${deviceId}`, {
                    phone_number: formattedPhone,
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
                    errorMessage = error.response.data.message || "An error occurred.";
                }
                return { success: false, message: errorMessage };
            }
        }

        // Show OTP dialog for sign out
        async function showSignOutOTPDialog() {
            let countdown = 120;
            let countdownInterval;

            return Swal.fire({
                title: "Enter OTP",
                html: `
                    <div>
                        <p>${employeeName}, please enter the verification code sent to ${formattedPhone}</p>
                        <input id="swal-signout-otp-input" class="swal2-input" placeholder="Enter your OTP">
                        <div class="mt-3">
                            <button id="resendSignOutOtpBtn" class="swal2-confirm swal2-styled" disabled style="background-color: #6c757d; margin-top: 10px;">
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
                    const resendBtn = document.getElementById('resendSignOutOtpBtn');

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

                            const resendResult = await requestSignOutOTP();

                            if (resendResult.success) {
                                await Swal.fire({
                                    title: 'OTP Resent',
                                    text: 'A new OTP has been sent to your phone',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                showSignOutOTPDialog();
                            } else {
                                await Swal.fire({
                                    title: 'Error',
                                    text: resendResult.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                showSignOutOTPDialog();
                            }
                        }
                        return false;
                    });
                },
                willClose: () => {
                    clearInterval(countdownInterval);
                },
                preConfirm: () => {
                    const otp = document.getElementById('swal-signout-otp-input').value;
                    if (!otp) {
                        Swal.showValidationMessage("Please enter the OTP");
                        return false;
                    }
                    return otp;
                }
            });
        }

        // Verify sign out OTP
        async function verifySignOutOTP(otp) {
            Swal.fire({
                title: 'Verifying...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const verifyResponse = await axios.post('/verify-signout-otp', {
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
                    location.reload();
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
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#D33",
                        confirmButtonText: "Yes, proceed anyway",
                        cancelButtonText: "Cancel"
                    });

                    if (skipResult.isConfirmed) {
                        return await skipSignOutVerification();
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

        // Skip sign out verification
        async function skipSignOutVerification() {
            Swal.fire({
                title: 'Processing...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await axios.post('/skip-signout-verification', {
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
                    location.reload();
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

        // Main OTP flow for sign out
        const initialOtpResult = await requestSignOutOTP();

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
            const otpDialog = await showSignOutOTPDialog();

            if (otpDialog.isDismissed) {
                return;
            }

            const verifyResult = await verifySignOutOTP(otpDialog.value);
            if (verifyResult.success) {
                otpVerified = true;
            }
        }
    });
});

        // Return Device (new functionality with OTP)
        document.querySelectorAll('.returnDeviceBtn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();

                const employeeName = this.getAttribute('data-device-name');
                const deviceSerial = this.getAttribute('data-device-serial');
                const deviceId = this.getAttribute('data-device-id');
                const employeeId = this.getAttribute('data-employee-id');

                // Step 1: Confirm return
                const confirmResult = await Swal.fire({
                    title: 'Confirm Return',
                    text: `${employeeName}, are you sure you want to return the device with serial number ${deviceSerial}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28A745',
                    cancelButtonColor: '#D33',
                    confirmButtonText: 'Yes, return it!',
                    cancelButtonText: 'Cancel'
                });

                if (!confirmResult.isConfirmed) return;

                // Step 2: Ask for phone number
                const phoneResult = await Swal.fire({
                    title: 'Enter Phone Number',
                    html: `
                        <p>Please enter your phone number for verification</p>
                        <input id="swal-phone-input" class="swal2-input"
                               placeholder="0241234567"
                               type="tel"
                               pattern="[0-9]{10}"
                               maxlength="10">

                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Send OTP',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const phone = document.getElementById('swal-phone-input').value;
                        if (!phone || phone.length !== 10) {
                            Swal.showValidationMessage('Please enter a valid 10-digit phone number');
                            return false;
                        }
                        return phone;
                    }
                });

                if (!phoneResult.isConfirmed) return;

                const phone_number = phoneResult.value;
                const formattedPhone = phone_number.replace(/^0/, '233');

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
                        const response = await axios.post(`/request-device-otp/${deviceId}`, {
                            phone_number: formattedPhone,
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
                            errorMessage = error.response.data.message || "An error occurred.";
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
                                <input id="swal-otp-input" class="swal2-input" placeholder="Enter your OTP">
                                <div class="mt-3">
                                    <button id="resendOtpBtn" class="swal2-confirm swal2-styled" disabled style="background-color: #6c757d; margin-top: 10px;">
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
                            const otp = document.getElementById('swal-otp-input').value;
                            if (!otp) {
                                Swal.showValidationMessage("Please enter the OTP");
                                return false;
                            }
                            return otp;
                        }
                    });
                }

                // Verify OTP
                async function verifyDeviceOTP(otp) {
                    Swal.fire({
                        title: 'Verifying...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const verifyResponse = await axios.post('/verify-device-otp', {
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
                            location.reload();
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
                                return await skipDeviceVerification();
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
                async function skipDeviceVerification() {
                    Swal.fire({
                        title: 'Processing...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await axios.post('/skip-device-verification', {
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
                            location.reload();
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

                // Main OTP flow
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

                    const verifyResult = await verifyDeviceOTP(otpDialog.value);
                    if (verifyResult.success) {
                        otpVerified = true;
                    }
                }
            });
        });

        async function signOutDevice(deviceId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                axios.defaults.headers.common['Accept'] = 'application/json';

                const response = await axios.patch(`/sign-out-device/${deviceId}`);

                await Swal.fire({
                    title: 'Success!',
                    text: response.data.message,
                    icon: 'success'
                });

                location.reload();

            } catch (error) {
                await Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Something went wrong',
                    icon: 'error'
                });
            }
        }
    });
</script>
</x-layout>
