@extends('layouts.app')

@section('title', 'Login ')

@section('content')

{{-- LOGIN --}}
<img src="{{ asset('Image1.png') }}" style="width:38%;object-fit:cover; margin-top:4rem;">
<b>
    <center>
        <p style="font-weight:700;font-size:32px; font-style:Volkhov">Login</p>
    </center>
    <h6 style="color: #006633; font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</h6>
    <form id="loginForm">

        <!-- Email input -->
        <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group" id="emailGroup">
            <input type="email" class="form-control form-control-sm" id="email" placeholder="Masukan Alamat Email" aria-describedby="emailHelp">
        </div>
        <div id="emailError" class="error-message"></div>
        </div>

        <!-- Password input -->
        <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group" id="passwordGroup">
            <input type="password" class="form-control form-control-sm" id="password" placeholder="Masukan Kata SAndi" aria-describedby="passwordHelp">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
        </div>
        <div id="passwordError" class="error-message"></div>
            <div id="passwordHelp" class="form-text mt-2">
                {{-- <a href="{{ url('/forgot-password') }}" class="text-decoration-none float-end">Lupa Kata Sandi</a> --}}
            </div>
        </div>

        <!-- Login button -->
        <button type="submit" class="btn btn-main btn-block w-100 mt-4" style=" background-color:#00954A">Login</button>
    </form>
</b>

@endsection

@section('js')

<script>

    // Functionality to toggle password visibility
    __getId('togglePassword').addEventListener('click', function() {
      const passwordInput = __getId('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.querySelector('i').classList.toggle('bi-eye');
      this.querySelector('i').classList.toggle('bi-eye-slash');
    });


    // Form submission and validation
    __getId('loginForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      const emailInput = __getId('email');
      const passwordInput = __getId('password');
      const emailError = __getId('emailError');
      const passwordError = __getId('passwordError');
      const emailGroup = __getId('emailGroup');
      const passwordGroup = __getId('passwordGroup');

      // Clear previous errors
      emailError.textContent = '';
      passwordError.textContent = '';
      emailGroup.classList.remove('error');
      passwordGroup.classList.remove('error');

      // Validate email and password fields
      let isValid = true;
      if (!emailInput.value) {
        emailError.textContent = 'Harap masukan email pegawai';
        emailGroup.classList.add('error');
        isValid = false;
      }
      if (!passwordInput.value) {
        passwordError.textContent = 'Harap masukan kata sandi pegawai';
        passwordGroup.classList.add('error');
        isValid = false;
      }

      if (!isValid) return;

      // AJAX login process
      const formData = {
        email: emailInput.value,
        password: passwordInput.value
      };

      const loginButton = event.submitter;
      loginButton.innerHTML = ___iconLoading('#006BB7');
      loginButton.disabled = true;

      try {
      // setTimeout(() => { // timeout nya dimatiin dulu kalo udah disambungin ke VERSI DINAMIS
        // VERSI DINAMIS
        const response = await fetch(`{{ url('api/login') }}`, porpertyPOST(formData));
        const result = await response.json();
        // VERSI STATIS
        // result = {
        //   // ######### case kalo berhasil #########
        //   status:true,
        //   // ######### case kalo belum terdaftar #########
        //   // status:false,
        //   // message:"Email not registered",
        //   // ######### case kalo kata sandi tidak cocok #########
        //   // status:false,
        //   // message:"Incorrect password",
        // }
        debugger
        if (result.status) {
          // Redirect or perform actions on successful login
            if(result.data){
                window.location = '/checkLogin/' + result.access_token + '_' + result.token;
            }
        } else {
          loginButton.innerHTML = 'Login';
          loginButton.disabled = false;
          if (result.message === 'Email yang Anda masukkan belum terdaftar.') {
            emailError.textContent = 'Email belum terdaftar';
            emailGroup.classList.add('error');
          } else if (result.message === 'Kata sandi yang Anda masukkan salah.') {
            passwordError.textContent = 'Kata sandi tidak cocok';
            passwordGroup.classList.add('error');
          } else {
            emailError.textContent = result.message;
            emailGroup.classList.add('error');
          }
        }
        // }, 2000)
      } catch (error) {
        console.error('An error occurred:', error);
        loginButton.innerHTML = 'Login';
        loginButton.disabled = false;
      }
    });


</script>

@endsection
