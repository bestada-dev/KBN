@extends('layouts.app')

@section('title', 'Masuk')

@section('content')

    {{-- Register --}}
    <b>
        <img src="{{ asset('logo.png') }}" style="align-self: center;margin-bottom:1rem">
        <h6>REGISTRASI</h6>
        <form id="registerEmployeForm">
            @csrf <!-- CSRF token for Laravel -->
            <input type="hidden" name="user_id" id="user_id" value="{{ Request::segment(3) }}">
            <div class="form-group">
                <label for="">Nama Perusahaan</label>
                <input type="text" class="form-control" name="company_name" id="company_name" value="{{ $get_employe ? $get_employe->company_name : '-' }}" placeholder="Masukan Nama Perusahaan" disabled>
                <span class="text-danger error-message" id="company_name-error"></span>
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" id="email" value="{{ $get_employe ? $get_employe->email : '-' }}" placeholder="Masukan Email" disabled>
                <span class="text-danger error-message" id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <span class="text-danger error-message" id="password-error"></span>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Konfirmasi password">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <span class="text-danger error-message" id="confirmPassword-error"></span>
            </div>



            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" name="employe_name" id="employe_name" value="" placeholder="Nama Karyawan">
                <span class="text-danger error-message" id="employe_name-error"></span>
            </div>

            <div class="form-group">
                <label for="">NIK</label>
                <input type="text" class="form-control" name="nik" id="nik" value="" placeholder="NIK Karyawan">
                <span class="text-danger error-message" id="nik-error"></span>
            </div>





            <!-- Register button -->
            <button type="button" id="submitButton" class="btn btn-main btn-block w-100 mt-4">Daftar</button>
        </form>
    </b>
    <img src="{{ asset('img1.png') }}" style="width:38%;object-fit:cover;">


@endsection

@section('js')
    <script>
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        toggleConfirmPassword.addEventListener('click', function () {
            // Toggle the type attribute between 'password' and 'text'
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);

            // Toggle icon between eye and eye-slash
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    </script>
    <script>
        // Functionality to toggle password visibility
        __getId('togglePassword').addEventListener('click', function() {
            const passwordInput = __getId('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });


    </script>

    {{-- untuk update data register --}}
    <script>
        $(document).ready(function() {
            $('#submitButton').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                // Clear previous errors
                $('.form-control').removeClass('is-invalid');
                $('.error-message').text(''); // Clear previous error messages

                // Create form data
                var formData = new FormData($('#registerEmployeForm')[0]);
                var id = $('#user_id').val(); // Get user_id

                $.ajax({
                    url: `/api/register/post-register-employe/${id}`, // Laravel API route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            blockUI('Anda Berhasil Register');

                            // Menunggu 3 detik sebelum reload halaman
                            setTimeout(function() {
                                // Memastikan modal sudah tertutup sebelum reload
                                if (!$('#formRequestModal').hasClass(
                                    'show')) { // Cek jika modal sudah tidak ditampilkan
                                    // Menutup modal dengan dispatch event

                                    // Reload halaman
                                    window.location.href = `{{ url('/') }}`
                                }
                            }, 2000); // 3000 milidetik = 3 detik
                        }
                    },
                    error: function(xhr) {
                        // Check if there are any errors
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            console.log(errors); // Log errors for debugging

                            $.each(errors, function(key, value) {
                                console.log(`Key: ${key}, Value: ${value[0]}`); // Log key-value pairs for debugging

                                // Construct the ID for the corresponding error message span
                                var errorSpanId = key + '-error';

                                // Target the input field by name
                                var input = $('[name="' + key + '"]');
                                input.addClass('is-invalid'); // Highlight error field

                                // Set the error message in the corresponding span
                                $('#' + errorSpanId).text(value[0]); // Display the first error message

                                // Debugging: check if the span exists and log
                                if ($('#' + errorSpanId).length) {
                                    console.log(`Error span found for ${key}: ${value[0]}`);
                                } else {
                                    console.log(`Error span NOT found for ${key}`);
                                }
                            });
                        } else {
                            console.log("Unexpected error response:", xhr); // Log unexpected errors
                        }
                    }
                });
            });
        })
    </script>

@endsection
