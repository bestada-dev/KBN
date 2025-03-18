@extends('layouts.app')

@section('title', 'Masuk')

@section('content')

    <b>
        <img src="{{ asset('logo.png') }}" style="align-self: center;margin-bottom:1rem">
        <h6>REGISTRASI</h6>
        <form id="verifyForm">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group" id="emailGroup">
                    <span class="input-group-text"><img src="{{ asset('assets/business-card.png') }}"></span>
                    <input type="email" class="form-control form-control-sm" id="email"
                        placeholder="Masukan Alamat Email" aria-describedby="emailHelp">
                </div>
                <div id="emailError" class="error-message"></div>
            </div>

            <button type="submit" class="btn btn-main btn-block w-100 mt-4">Daftar</button>
            <label class="form-label">Sudah punya akun? <a href="{{ url('/') }}">Masuk disini</a></label>
        </form>
    </b>
    <img src="{{ asset('img1.png') }}" style="width:38%;object-fit:cover;">

@endsection

@section('js')

    <script>
        // Form submission and validation
        __getId('verifyForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const emailInput = __getId('email');
            const emailError = __getId('emailError');
            const emailGroup = __getId('emailGroup');

            // Clear previous errors
            emailError.textContent = '';
            emailGroup.classList.remove('error');

            // Validate email field
            let isValid = true;
            if (!emailInput.value) {
                emailError.textContent = 'Harap masukan email yang benar';
                emailGroup.classList.add('error');
                isValid = false;
            }

            if (!isValid) return;

            // AJAX login process
            const formData = {
                email: emailInput.value,
            };

            const loginButton = event.submitter;
            loginButton.innerHTML = ___iconLoading('#006BB7');
            loginButton.disabled = true;

            try {
                // AJAX request to server for email verification
                const response = await fetch('/api/forgot-password/cek-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();

                if (result.success) {
                    // Check role_id and redirect accordingly
                    const roleId = result.role_id;
                    const user_id = result.user_id;
                    window.location.href = '/forgot-password/form-otp/' + user_id;
                } else {
                    // Handle email not found in database
                    console.log(result.message);
                    emailError.textContent = result.message;
                    emailGroup.classList.add('error');
                }

            } catch (error) {
                console.error('An error occurred:', error);
                emailError.textContent = 'Terjadi kesalahan pada server.';
            } finally {
                loginButton.innerHTML = 'Login';
                loginButton.disabled = false;
            }
        });
    </script>


@endsection
