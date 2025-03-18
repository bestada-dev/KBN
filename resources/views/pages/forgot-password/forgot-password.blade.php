@extends('layouts.app')

@section('title', 'Masuk')

@section('content')

    {{-- Register --}}
    <b>
        <img src="{{ asset('logo.png') }}" style="align-self: center;margin-bottom:1rem">
        <h6>REGISTRASI</h6>
        <form id="registerPerusahaanForm">
            @csrf <!-- CSRF token for Laravel -->
            <input type="hidden" name="user_id" id="user_id" value="{{ Request::segment(3) }}">

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
            <!-- Register button -->
            <button type="button" id="submitButton" class="btn btn-main btn-block w-100 mt-4">Ubah Password</button>
        </form>
    </b>
    <img src="{{ asset('img1.png') }}" style="width:38%;object-fit:cover;">

    <div id="loading-screen" style="display: none;">
        <p>Sedang memuat data...</p>
    </div>

    <style>
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            font-size: 1.5em;
            color: #333;
        }

    </style>


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
            $('#submitButton').on('click', function (e) {
                e.preventDefault(); // Prevent default form submission behavior

                // Clear previous errors
                $('.form-control').removeClass('is-invalid');
                $('.error-message').text(''); // Clear previous error messages

                // Create form data
                var formData = new FormData($('#registerPerusahaanForm')[0]);
                var id = $('#user_id').val(); // Get user_id

                $.ajax({
                    url: `/api/forgot-password/change-password/${id}`, // Laravel API route for password update
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            blockUI('Password berhasil diubah.');

                            // Redirect or reload page after success
                            setTimeout(function () {
                                window.location.href = `{{ url('/') }}`; // Redirect to home page
                            }, 2000); // Wait for 2 seconds
                        }
                    },
                    error: function (xhr) {
                        // Handle validation errors
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {
                                // Highlight error fields
                                var input = $('[name="' + key + '"]');
                                input.addClass('is-invalid'); // Highlight error field

                                // Display error message
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else {
                            console.log("Unexpected error:", xhr);
                        }
                    }
                });
            });

        })
    </script>

@endsection
