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
                <label for="">Nama Perusahaan</label>
                <input type="text" class="form-control" name="company_name" id="company_name" value="" placeholder="Masukan Nama Perusahaan">
                <span class="text-danger error-message" id="company_name-error"></span>
            </div>

            <div class="form-group">
                <label for="">Tipe Pengguna</label>
                <input type="text" class="form-control" name="role_id" id="role_id" value="{{ $get_perusahaan->role ? $get_perusahaan->role->role_name : '-' }}" placeholder="Tipe Pengguna" disabled>
                <span class="text-danger error-message" id="role_id-error"></span>
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" id="email" value="{{ $get_perusahaan ? $get_perusahaan->email : '-' }}" placeholder="Masukan Email" disabled>
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
                <label for="privonce_id">Provinsi</label>
                <select name="privonce_id" id="privonce_id" class="form-select">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($get_prov as $prov)
                        <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                    @endforeach
                </select>
                <span class="text-danger error-message" id="privonce_id-error"></span>
            </div>

            <div class="form-group">
                <label for="city_id">Kota</label>
                <select name="city_id" id="city_id" class="form-select">
                    <option value="">Pilih Kota</option>
                </select>
                <span class="text-danger error-message" id="city_id-error"></span>
            </div>

            <div class="form-group">
                <label for="">Alamat</label>
                <textarea name="address" id="" cols="10" rows="5" class="form-control"></textarea>
                <span class="text-danger error-message" id="address-error"></span>
            </div>



            <!-- Register button -->
            <button type="button" id="submitButton" class="btn btn-main btn-block w-100 mt-4">Daftar</button>
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
        $(document).ready(function () {
            $('#privonce_id').change(function () {
                const provinceId = $(this).val(); // Ambil ID provinsi yang dipilih
                const cityDropdown = $('#city_id'); // Dropdown kota
                const loadingScreen = $('#loading-screen'); // Loading screen element

                // Kosongkan dropdown kota sebelumnya
                cityDropdown.html('<option value="">Pilih Kota</option>');

                if (provinceId) {
                    // Tampilkan loading screen
                    loadingScreen.show();

                    // Lakukan permintaan AJAX
                    $.ajax({
                        url: `/register/get_city_by_prov/${provinceId}`, // Endpoint untuk mendapatkan data kota
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            // Tambahkan opsi kota ke dropdown dengan kategori
                            data.forEach(city => {
                                const optionText = `${city.type} ${city.name}`; // Nama kategori dan nama kota
                                cityDropdown.append(new Option(optionText, city.id));
                            });
                        },
                        error: function (xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        },
                        complete: function () {
                            // Sembunyikan loading screen setelah selesai
                            loadingScreen.hide();
                        }
                    });
                }
            });
        });
    </script>


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
                var formData = new FormData($('#registerPerusahaanForm')[0]);
                var id = $('#user_id').val(); // Get user_id

                $.ajax({
                    url: `/api/register/post-register-perusahaan/${id}`, // Laravel API route
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
