@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pengembangan')

@section('breadcumbSubtitle', 'Pengembangan Create')

@section('content')
    @include('pages.register.css_upload_file')
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER">
                <a href="{{ url()->previous() }}"><img src="{{ asset('assets/back.png') }}" alt="Back"></a>
                <h5>Kembali</h5>
            </div>
            <form id="form_edit_pengembangan" class="p-4 pt-3" enctype="multipart/form-data">
                <div class="form-group" style="margin-top: 15px;">
                    <label for="">Judul Pelatihan<span style="color: red">(*)</span></label>
                    <input type="text" class="form-control" name="judul_pelatihan" id="judul_pelatihan"
                        value="{{ old('judul_pelatihan', $edit_data->judul_pelatihan) }}">
                    <span class="text-danger error-message" id="judul_pelatihan-error"></span>
                </div>

                <!-- Logo Upload -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="foto" class="form-label">Foto</label>
                    <div class="file-upload-container">
                        <input type="file" class="file-input" id="foto" name="foto"
                            accept="image/png, image/jpeg">
                        <div class="file-upload-box" onclick="document.getElementById('foto').click();">
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
                        <button type="button" class="btn-icon ms-2" id="resetButton">
                            <i class="bi bi-save2"></i>
                        </button>
                    </div>
                    <p class="upload-info">Ukuran maksimum: 5 MB. Format file: PNG atau JPG.</p>
                    <span class="text-danger error-message" id="foto-error"></span>
                    @if ($edit_data->foto)
                        <p>Foto saat ini: <img src="{{ asset('path/to/your/images/' . $edit_data->foto) }}"
                                style="max-width: 100px;"></p>
                    @endif
                </div>

                <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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

                <!-- Video Upload -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="video" class="form-label">Video (Opsional)</label>
                    <div class="file-upload-container">
                        <input type="file" class="file-input" id="video" name="video"
                            accept="video/mp4, video/webm, video/ogg">
                        <div class="file-upload-box" onclick="document.getElementById('video').click();">
                            <div class="file-details">
                                <i class="bi bi-file-earmark"></i>
                                <div>
                                    <span id="videoFileName" class="file-name-size">Pilih file</span>
                                    <span id="videoFileSize" class="file-size"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-icon ms-2" id="viewVideoButton" data-bs-toggle="modal"
                            data-bs-target="#videoPreviewModal">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn-icon ms-2" id="resetVideoButton">
                            <i class="bi bi-save2"></i>
                        </button>
                    </div>
                    <p class="upload-info">Ukuran maksimum: 50 MB. Format file: MP4, WebM, atau Ogg.</p>
                    <span class="text-danger error-message" id="video-error"></span>
                </div>

                <!-- Modal for Video Preview -->
                <div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="videoPreviewModalLabel">Preview Video</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <video id="modalVideoPreview" controls
                                    style="max-width: 100%; max-height: 400px;"></video>
                                <p id="noVideoText" style="display: none;">Tidak ada video untuk ditampilkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <label for="Deskripsi">Deskripsi <span style="color: red">(*)</span></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" cols="10" rows="5">{{ old('deskripsi', $edit_data->deskripsi) }}</textarea>
                    <span class="text-danger error-message" id="deskripsi-error"></span>
                </div>

                <div class="btn-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-default btn-sm btn-block mt-4">Cancel</a>
                    <button type="submit" class="btn btn-main btn-sm btn-block mt-4" id="btn-save">Update</button>
                </div>
            </form>
        </div>
    </article>

@endsection

@section('js')

    {{-- script show / hide inputan link dan post data  --}}
    <script>
        $(document).ready(function() {

            const maxFileSizeImage = 5 * 1024 * 1024; // 5 MB
            const maxFileSizeVideo = 1 * 1024 * 1024 * 1024; // 1 GB

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form_edit_pengembangan').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                __isBtnSaveOnProcessing($('#form_edit_pengembangan #btn-save'), true);

                // Reset error messages
                $('.error-message').text('');

                // Validation
                let isValid = true;

                // Required Fields Validation
                if ($('#judul_pelatihan').val() === '') {
                    $('#judul_pelatihan-error').text('Judul Pelatihan is required.');
                    isValid = false;
                }

                if ($('#deskripsi').val() === '') {
                    $('#deskripsi-error').text('Deskripsi is required.');
                    isValid = false;
                }

                // Check if foto exists
                const imageFile = $('#foto')[0].files[0]; // Get the file input
                const fotoExists = "{{ $edit_data->foto }}" !== '';
                if (imageFile) {
                    // Check if file size exceeds 5 MB
                    if (imageFile.size > maxFileSizeImage) {
                        $('#foto-error').text('File size exceeds 5 MB.');
                        isValid = false;
                    }
                    // Check if the file type is either JPEG or PNG
                    if (!['image/jpeg', 'image/png'].includes(imageFile.type)) {
                        $('#foto-error').text('Only JPG and PNG files are allowed.');
                        isValid = false;
                    }
                } else if (!fotoExists) {
                    $('#foto-error').text('Foto is required.'); // If no file selected and no existing photo
                    isValid = false;
                }

                // Video Validation (optional)
                const videoFile = $('#video')[0].files[0];
                if (videoFile) {
                    if (videoFile.size > maxFileSizeVideo) {
                        $('#video-error').text('File size exceeds 1 GB.');
                        isValid = false;
                    }
                    if (videoFile.type !== 'video/mp4') {
                        $('#video-error').text('Only MP4 files are allowed for videos.');
                        isValid = false;
                    }
                }

                // If validation fails, reset button and stop the submission
                if (!isValid) {
                    __isBtnSaveOnProcessing($('#form_edit_pengembangan #btn-save'), false);
                    return;
                }

                // Prepare FormData for AJAX submission
                const formData = new FormData(this);
                formData.append('token', getCurrentToken()['token'])
                console.log('formData = ', formData);

                // AJAX Request
                $.ajax({
                    url: '{{ url('/api/vendor/pengembangan_diri/edit/' . $edit_data->id) }}', // Change to your endpoint for updating
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        blockUI('Data Berhasil Disimpan');
                        setTimeout(function() {
                            window.location.href =
                            '{{ url('/vendor/pengembangan_diri') }}'; // Adjust this URL as needed
                        }, 2000); // 2000 milliseconds = 2 seconds
                    },
                    error: function(xhr) {
                        __isBtnSaveOnProcessing($('#form_edit_pengembangan #btn-save'), false);
                        blockUI('Ops.. something went wrong!', _.ERROR);
                    }
                });
            });
        });
    </script>

    {{-- untuk popup image  --}}
    <script>
        const foto = document.getElementById('foto');
        const fileNameDisplay = document.getElementById('fileName');
        const fileSizeDisplay = document.getElementById('fileSize');
        const modalImagePreview = document.getElementById('modalImagePreview');
        const noImageText = document.getElementById('noImageText');
        const resetButton = document.getElementById('resetButton');
        const viewButton = document.getElementById('viewButton');

        // Update file details when a file is selected
        foto.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                fileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            }
        });

        // Show image preview when the view button is clicked
        viewButton.addEventListener('click', function() {
            const file = foto.files[0];
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
            foto.value = ''; // Clear the file input
            fileNameDisplay.textContent = 'Pilih file';
            fileSizeDisplay.textContent = '';

            // Clear modal image preview and reset state
            modalImagePreview.src = '#';
            modalImagePreview.style.display = 'none';
            noImageText.style.display = 'block'; // Show "no image" text
        });

        // Ensure the image is loaded correctly every time the modal is opened
        $('#imagePreviewModal').on('show.bs.modal', function() {
            const file = foto.files[0];
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

    {{-- ini untuk vidio --}}
    <script>
        const videoInput = document.getElementById('video');
        const videoFileNameDisplay = document.getElementById('videoFileName');
        const videoFileSizeDisplay = document.getElementById('videoFileSize');
        const modalVideoPreview = document.getElementById('modalVideoPreview');
        const noVideoText = document.getElementById('noVideoText');
        const resetVideoButton = document.getElementById('resetVideoButton');
        const viewVideoButton = document.getElementById('viewVideoButton');

        // Set initial video from storage if available
        const initialVideoPath = "{{ $edit_data->vidio ? asset('storage/' . $edit_data->vidio) : '' }}";
        if (initialVideoPath) {
            modalVideoPreview.src = initialVideoPath;
            modalVideoPreview.style.display = 'block';
            noVideoText.style.display = 'none';
        }

        // Update file details when a video file is selected
        videoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                videoFileNameDisplay.textContent = file.name;
                videoFileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;

                // Load the selected video in the preview modal
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalVideoPreview.src = e.target.result;
                    modalVideoPreview.style.display = 'block';
                    noVideoText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Show video preview when the view button is clicked
        viewVideoButton.addEventListener('click', function() {
            const file = videoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalVideoPreview.src = e.target.result;
                    modalVideoPreview.style.display = 'block';
                    noVideoText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else if (initialVideoPath) {
                // Use the initial stored video if no new file is selected
                modalVideoPreview.src = initialVideoPath;
                modalVideoPreview.style.display = 'block';
                noVideoText.style.display = 'none';
            } else {
                modalVideoPreview.style.display = 'none';
                noVideoText.style.display = 'block';
            }
        });

        // Reset file input and clear all fields when the reset button is clicked
        resetVideoButton.addEventListener('click', function() {
            videoInput.value = ''; // Clear the file input
            videoFileNameDisplay.textContent = 'Pilih file';
            videoFileSizeDisplay.textContent = '';

            // Reset modal video preview to initial storage video or hide if none
            if (initialVideoPath) {
                modalVideoPreview.src = initialVideoPath;
                modalVideoPreview.style.display = 'block';
                noVideoText.style.display = 'none';
            } else {
                modalVideoPreview.src = '#';
                modalVideoPreview.style.display = 'none';
                noVideoText.style.display = 'block';
            }
        });
    </script>




@endsection
