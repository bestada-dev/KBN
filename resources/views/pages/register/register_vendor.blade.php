@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    @include('pages.register.css_upload_file')
    {{-- Register --}}
    <b>
        <img src="{{ asset('logo.png') }}" style="align-self: center;margin-bottom:1rem">
        <h6>REGISTRASI</h6>
        <form id="registerForm" style="margin-top: -1%">
            @csrf <!-- CSRF token for Laravel -->
            <input type="hidden" name="user_id" id="user_id" value="{{ Request::segment(3) }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email"
                    value="{{ $get_vendor ? $get_vendor->email : '-' }}" placeholder="Masukan Email" disabled>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>

                </div>
                <span class="text-danger error-message" id="password-error"></span>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                        placeholder="Konfirmasi password">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <span class="text-danger error-message" id="confirmPassword-error"></span>
            </div>

            <div class="form-group">
                <label for="role_id">Tipe Pengguna</label>
                <input type="text" class="form-control" name="role_id"
                    value="{{ $get_vendor->role ? $get_vendor->role->role_name : '-' }}" placeholder="Tipe Pengguna"
                    disabled>
                <span class="text-danger error-message" id="role_id-error"></span>
            </div>

            <!-- Logo Upload -->
            <div class="form-group">
                <label for="company_logo" class="form-label">Logo Perusahaan</label>
                <div class="file-upload-container">
                    <input type="file" class="file-input" id="company_logo" name="company_logo" accept="image/png, image/jpeg">
                    <div class="file-upload-box" onclick="document.getElementById('company_logo').click();">
                        <div class="file-details">
                            <i class="bi bi-file-earmark"></i>
                            <div>
                                <span id="fileName" class="file-name-size">Pilih file</span>
                                <span id="fileSize" class="file-size"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-icon ms-2" id="viewButton" data-bs-toggle="modal"
                        data-bs-target="#imagePreviewModal">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn-icon ms-2" id="resetButton">
                        <i class="bi bi-save2"></i>
                    </button>
                </div>
                <p class="upload-info">Ukuran maksimum: 5 MB. Format file: PNG atau JPG.</p>
                <span class="text-danger error-message" id="company_logo-error"></span>
            </div>

            <!-- Modal for Image Preview -->
            <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modalImagePreview" src="#" alt="Preview Gambar"
                                style="max-width: 100%; max-height: 400px;">
                            <p id="noImageText" style="display: none;">Tidak ada gambar untuk ditampilkan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other form fields -->
            <div class="form-group">
                <label for="company_name">Nama Perusahaan</label>
                <input type="text" class="form-control" name="company_name" placeholder="Masukan Nama Perusahaan">
                <span class="text-danger error-message" id="company_name-error"></span>
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
                <label for="address">Alamat</label>
                <textarea name="address" id="address" cols="10" rows="3" class="form-control"></textarea>
                <span class="text-danger error-message" id="address-error"></span>
            </div>

            <!-- Submit button that triggers the click event -->
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



    // document.addEventListener('DOMContentLoaded', function () {
    //     document.getElementById('privonce_id').addEventListener('change', function () {
    //         console.log('asdasddd')
    //         alert('asdasd'); // Tes apakah event listener bekerja
    //         const provinceId = this.value; // Ambil ID provinsi yang dipilih
    //         const cityDropdown = document.getElementById('city_id'); // Element dropdown kota

    //         // Hapus opsi kota sebelumnya
    //         cityDropdown.innerHTML = '<option value="">Pilih Kota</option>';

    //         if (provinceId) {
    //             // Lakukan permintaan AJAX ke server
    //             fetch(`/register/get_city_by_prov/${provinceId}`)
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     // Tambahkan opsi kota ke dropdown
    //                     data.forEach(city => {
    //                         const option = document.createElement('option');
    //                         option.value = city.id; // ID kota
    //                         option.textContent = city.name; // Nama kota
    //                         cityDropdown.appendChild(option);
    //                     });
    //                 })
    //                 .catch(error => {
    //                     console.error('Terjadi kesalahan:', error);
    //                 });
    //         }
    //     });
    // });
</script>

    {{-- untuk popup image  --}}
    <script>
        const company_logo = document.getElementById('company_logo');
        const fileNameDisplay = document.getElementById('fileName');
        const fileSizeDisplay = document.getElementById('fileSize');
        const modalImagePreview = document.getElementById('modalImagePreview');
        const noImageText = document.getElementById('noImageText');
        const resetButton = document.getElementById('resetButton');
        const viewButton = document.getElementById('viewButton');

        // Update file details when a file is selected
        company_logo.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                fileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            }
        });

        // Show image preview when the view button is clicked
        viewButton.addEventListener('click', function() {
            const file = company_logo.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalImagePreview.src = e.target.result; // Set the image source in the modal
                    modalImagePreview.style.display = 'block'; // Show the image in modal
                    noImageText.style.display = 'none'; // Hide "no image" text
                };
                reader.readAsDataURL(file);
            } else {
                modalImagePreview.style.display = 'none'; // Hide the image in modal
                noImageText.style.display = 'block'; // Show "no image" text
            }
        });

        // Reset file input and clear all fields when the reset button is clicked
        resetButton.addEventListener('click', function() {
            company_logo.value = ''; // Clear the file input
            fileNameDisplay.textContent = 'Pilih file';
            fileSizeDisplay.textContent = '';

            // Clear modal image preview and reset state
            modalImagePreview.src = '#';
            modalImagePreview.style.display = 'none';
            noImageText.style.display = 'block'; // Show "no image" text
        });

        // Ensure the image is loaded correctly every time the modal is opened
        $('#imagePreviewModal').on('show.bs.modal', function() {
            const file = company_logo.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalImagePreview.src = e.target.result; // Set the image source in the modal
                    modalImagePreview.style.display = 'block'; // Show the image in modal
                    noImageText.style.display = 'none'; // Hide "no image" text
                };
                reader.readAsDataURL(file);
            } else {
                modalImagePreview.style.display = 'none'; // Hide the image in modal
                noImageText.style.display = 'block'; // Show "no image" text
            }
        });
    </script>

    {{-- untuk toogle passworrd confrim --}}
    <script>
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        toggleConfirmPassword.addEventListener('click', function() {
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
                var formData = new FormData($('#registerForm')[0]);
                var id = $('#user_id').val(); // Get user_id

                $.ajax({
                    url: `/api/register/post-register-vendor/${id}`, // Laravel API route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Data updated successfully');
                            window.location.href = '/';
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
