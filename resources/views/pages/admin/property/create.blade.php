@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'pengembangan')

@section('breadcumbSubtitle', 'pengembangan Create')

@section('content')
    @include('pages.register.css_upload_file')
    @include('pages.admin.property.style_property')
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER">
                <a href="{{ url('/superadmin/property') }}"><img src="{{ asset('assets/back.png') }}" alt="Back"></a>
                <h5>Kembali</h5>
            </div>
            <form id="form_create_pengembangan" class="p-4 pt-3" enctype="multipart/form-data">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{url('/superadmin/property')}}">List Property</a></li>
                      <li class="breadcrumb-item"><span>Create Property</span></li>
                    </ol>
                </nav>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="category_id">Category<span style="color: red">(*)</span></label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Select Category</option>
                        @foreach ($category as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-message" id="category_id-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="zona_id">Zoning<span style="color: red">(*)</span></label>
                    <select name="zona_id" id="zona_id" class="form-select">
                        <option value="">Select Zoning</option>
                        @foreach ($zoning as $zon)
                            <option value="{{ $zon->id }}">{{ $zon->zone_name }} - ({{ $zon->address }})</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-message" id="zona_id-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="property_address">Property Address<span style="color: red">(*)</span></label>
                    <textarea name="property_address" id="property_address" class="form-control" cols="10" rows="5"
                        placeholder="Enter Location"></textarea>
                    <span class="text-danger error-message" id="property_address-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="property_location_link">Property Location Point Link<span
                            style="color: red">(*)</span></label>
                    <textarea name="property_location_link" id="property_location_link" class="form-control" cols="10" rows="3"
                        placeholder="Enter Property Location Point Link"></textarea>
                    <span class="text-danger error-message" id="property_location_link-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="block">Block<span style="color: red">(*)</span></label>
                    <input type="text" name="block" id="block" class="form-control" placeholder="Enter Block">
                    <span class="text-danger error-message" id="block-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="type">Type<span style="color: red">(*)</span></label>
                    <div style="margin-top: 5px;">
                        <label>
                            <input type="radio" class="radio" name="type" value="Bounded">
                            Bonded
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="radio" class="radio" name="type" value="General">
                            Non Bonded
                        </label>
                    </div>
                    <span class="text-danger error-message" id="type-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="land_area">Land Area<span style="color: red">(*)</span></label>
                    <div style="margin-top: 5px;">
                        <div style="position: relative;">
                            <input type="text" class="form-control" name="land_area" id="land_area"
                                placeholder="Enter Land Area" style="padding-right: 40px; width: 100%;" />
                            <span
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #555;">
                                M²
                            </span>
                        </div>
                    </div>
                    <span class="text-danger error-message" id="land_area-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="building_area">Building Area<span style="color: red">(*)</span></label>
                    <div style="margin-top: 5px;">
                        <div style="position: relative;">
                            <input type="text" class="form-control" name="building_area" id="building_area"
                                placeholder="Enter Building Area" style="padding-right: 40px; width: 100%;" />
                            <span
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #555;">
                                M²
                            </span>
                        </div>
                    </div>
                    <span class="text-danger error-message" id="building_area-error"></span>
                </div>

                <div id="container-multiple"
                    style="border: 1px solid #ced4da;padding: 15px;border-radius: 5px;margin-top: 20px;">
                    <div class="form-group">
                        <label for="facility">Facility<span style="color: red">(*)</span></label>
                        <div class="row">
                            <div class="col-md-11">
                                <input type="text" name="facility[]" id="facility" class="form-control"
                                    placeholder="Enter Facility">
                                <span class="text-danger error-message" id="facility-error"></span>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger btn-sm delete-btn" type="button" disabled>
                                    <i class="bi bi-trash"></i>&nbsp;&nbsp; Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end" style="margin-top: 15px">
                    <button class="btn btn-success btn-sm" type="button" id="addMoreBtn">
                        <i class="bi bi-plus"></i> Add More
                    </button>
                </div>


                <div class="form-group" style="margin-top: 20px;">
                    <label for="type_upload">Type<span style="color: red">(*)</span></label>
                    <div style="margin-top: 5px;">
                        <label>
                            <input type="radio" class="radio" name="type_upload" value="link">
                            Link
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="radio" class="radio" name="type_upload" value="upload_vidio">
                            Upload Vidio
                        </label>
                    </div>
                    <span class="text-danger error-message" id="type-error"></span>
                </div>

                <div class="form-group" id="show_if_type_link" style="margin-top: 15px; display: none;">
                    <label for="url">Virtual Tour Link / Youtube Link<span style="color: red">(*)</span></label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="Enter url">
                    <span class="text-danger error-message" id="url-error"></span>
                </div>

                <div class="form-group" id="show_if_type_upload_vidio" style="margin-top: 15px; display: none;">
                    <label for="vidio" class="form-label">Video</label>
                    <div class="file-upload-container">
                        <input type="file" class="file-input" id="vidio" name="vidio" accept="video/mp4, video/webm, video/ogg">
                        <div class="file-upload-box" onclick="document.getElementById('vidio').click();">
                            <div class="file-details">
                                <i class="bi bi-file-earmark"></i>
                                <div>
                                    <span id="videoFileName" class="file-name-size">Pilih file</span>
                                    <span id="videoFileSize" class="file-size"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-icon ms-2" id="viewVideoButton" data-bs-toggle="modal" data-bs-target="#videoPreviewModal">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn-icon ms-2" id="resetVideoButton">
                            <i class="bi bi-save2"></i>
                        </button>
                    </div>
                    <p class="upload-info">Ukuran maksimum: 50 MB. Format file: MP4, WebM, atau Ogg.</p>
                    <span class="text-danger error-message" id="vidio-error"></span>
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
                                    style="max-width: 100%; max-height: 400px; display: none;"></video>
                                <p id="noVideoText" style="display: none;">Tidak ada video untuk ditampilkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <label for="desc">Description <span style="color: red">(*)</span></label>
                    <textarea name="desc" id="desc" class="form-control" cols="10" rows="5"></textarea>
                    <span class="text-danger error-message" id="desc-error"></span>
                </div>
                <div class="form-group" style="margin-top: 15px">
                    <label for="desc">Description <span style="color: red">(*)</span></label>
                    <textarea name="desc" id="desc" class="form-control" cols="10" rows="5"></textarea>
                    <span class="text-danger error-message" id="desc-error"></span>
                </div>

                <!-- Logo Upload -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="layout" class="form-label">Layout</label>
                    <div class="file-upload-container">
                        <input type="file" class="file-input" id="layout" name="layout"
                            accept="image/png, image/jpeg">
                        <div class="file-upload-box" onclick="document.getElementById('layout').click();">
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
                    <span class="text-danger error-message" id="layout-error"></span>
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

                <!-- Logo Upload -->
                <div id="detail-foto-container" class="detail-foto-wrapper">
                    <div class="detail-foto-item">
                        <div class="form-group">
                            <label for="detail_photo_1" class="form-label">Detail Photo</label>
                            <div class="file-upload-container">
                                <input type="file" class="file-input" id="detail_photo_1" name="detail_photo[]"
                                    accept="image/png, image/jpeg">
                                <div class="file-upload-box" onclick="document.getElementById('detail_photo_1').click();">
                                    <div class="file-details">
                                        <i class="bi bi-file-earmark"></i>
                                        <div>
                                            <span class="file-name-size">Pilih file</span>
                                            <span class="file-size"></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn-icon view-button" data-bs-toggle="modal"
                                    data-bs-target="#imagePreviewModal">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn-icon reset-button">
                                    <i class="bi bi-save2"></i>
                                </button>
                            </div>
                            <p class="upload-info">Ukuran maksimum: 5 MB. Format file: PNG atau JPG.</p>
                            <span class="text-danger error-message"></span>
                        </div>
                    </div>
                </div>

                <!-- Modal Preview -->
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
                                    class="modal-image-preview">
                                <p id="noImageText" class="no-image-text">Tidak ada gambar untuk ditampilkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn btn-success btn-sm" type="button" id="addMoreBtnDetailFoto">
                        <i class="bi bi-plus"></i> Add More
                    </button>
                    <button class="btn btn-danger btn-sm delete-detail-foto-btn" type="button" id="deleteDetailFotoBtn"
                        disabled>
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>



                <div class="btn-footer">
                    <a onclick="onCancel()" class="btn btn-default btn-sm btn-block mt-4">Cancel</a>
                    <button type="submit" class="btn btn-main btn-sm btn-block mt-4" id="btn-save">Save</button>
                </div>
            </form>
        </div>
    </article>

@endsection

@section('js')

<script>
    function onCancel() {
        return __swalConfirmation(async (data) => {
            try {
                // console.log(data);
                // $('#formRequestModal').modal('hide');
                    window.location.href='{{ url('/superadmin/property') }}'
            } catch (error) {
                console.error(error);
                blockUI('Ops.. something went wrong!', _.ERROR)
            }
        }, 'Are you sure?', 'You want the cancel to adding Property', 'Yes', 'No')

    }

    $(document).ready(function () {
        $('#form_create_pengembangan').on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('token', getCurrentToken()['token'])

            $.ajax({
                url: `{{ url('api/superadmin/property/store') }}`, // Adjust to your route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.error-message').text('');
                },
                success: function (response) {
                    blockUI(response.message);
                    // window.location.reload();
                    setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/property') }}`
                        }, 1000); // 3000 milidetik = 3 detik
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('#' + key + '-error').text(value[0]);
                        });
                    } else {
                        let errorMessage = xhr.responseJSON?.message || 'An unexpected error occurred.';
                        blockUI(errorMessage, _.ERROR);
                    }
                }
            });
        });
    });
</script>

    {{-- ini untuk detail image multiple --}}
    <script>
        const container = document.getElementById('detail-foto-container');
        const addMoreBtn = document.getElementById('addMoreBtnDetailFoto');
        const deleteBtn = document.getElementById('deleteDetailFotoBtn');

        let itemCount = 1; // Counter for dynamic IDs

        // Function to update delete button state
        function updateDeleteButtonState() {
            deleteBtn.disabled = container.children.length <= 1;
        }

        // Add More Button Click
        addMoreBtn.addEventListener('click', function() {
            itemCount++;

            const newItem = document.createElement('div');
            newItem.classList.add('detail-foto-item');

            newItem.innerHTML = `
                <div class="form-group">
                    <label for="detail_photo_${itemCount}" class="form-label">Detail Photo</label>
                    <div class="file-upload-container">
                        <input type="file" class="file-input" id="detail_photo_${itemCount}" name="detail_photo[]" accept="image/png, image/jpeg">
                        <div class="file-upload-box" onclick="document.getElementById('detail_photo_${itemCount}').click();">
                            <div class="file-details">
                                <i class="bi bi-file-earmark"></i>
                                <div>
                                    <span class="file-name-size">Pilih file</span>
                                    <span class="file-size"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-icon view-button" data-bs-toggle="modal" data-bs-target="#imagePreviewModal">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn-icon reset-button">
                            <i class="bi bi-save2"></i>
                        </button>
                    </div>
                    <p class="upload-info">Ukuran maksimum: 5 MB. Format file: PNG atau JPG.</p>
                    <span class="text-danger error-message"></span>
                </div>`;

            container.appendChild(newItem);
            attachEventListeners(newItem); // Attach event listeners to new item
            updateDeleteButtonState();
        });

        // Delete Button Click
        deleteBtn.addEventListener('click', function() {
            if (container.children.length > 1) {
                container.lastElementChild.remove();
                updateDeleteButtonState();
            }
        });

        // Function to attach event listeners to a detail-foto-item
        function attachEventListeners(item) {
            const fileInput = item.querySelector('.file-input');
            const fileNameDisplay = item.querySelector('.file-name-size');
            const fileSizeDisplay = item.querySelector('.file-size');
            const viewButton = item.querySelector('.view-button');
            const resetButton = item.querySelector('.reset-button');

            // File input change event
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    fileNameDisplay.textContent = file.name;
                    fileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;
                }
            });

            // View button click event
            viewButton.addEventListener('click', function() {
                const file = fileInput.files[0];
                const modalImagePreview = document.getElementById('modalImagePreview');
                const noImageText = document.getElementById('noImageText');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        modalImagePreview.src = e.target.result;
                        modalImagePreview.style.display = 'block';
                        noImageText.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    modalImagePreview.style.display = 'none';
                    noImageText.style.display = 'block';
                }
            });

            // Reset button click event
            resetButton.addEventListener('click', function() {
                fileInput.value = '';
                fileNameDisplay.textContent = 'Pilih file';
                fileSizeDisplay.textContent = '';
            });
        }

        // Attach event listeners to the initial item
        attachEventListeners(container.querySelector('.detail-foto-item'));

        // Update delete button state on load
        updateDeleteButtonState();
    </script>

    {{-- untuk popup image  --}}
    <script>
        const layout = document.getElementById('layout');
        const fileNameDisplay = document.getElementById('fileName');
        const fileSizeDisplay = document.getElementById('fileSize');
        const modalImagePreview = document.getElementById('modalImagePreview');
        const noImageText = document.getElementById('noImageText');
        const resetButton = document.getElementById('resetButton');
        const viewButton = document.getElementById('viewButton');

        // Update file details when a file is selected
        layout.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                fileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            }
        });

        // Show image preview when the view button is clicked
        viewButton.addEventListener('click', function() {
            const file = layout.files[0];
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
            layout.value = ''; // Clear the file input
            fileNameDisplay.textContent = 'Pilih file';
            fileSizeDisplay.textContent = '';

            // Clear modal image preview and reset state
            modalImagePreview.src = '#';
            modalImagePreview.style.display = 'none';
            noImageText.style.display = 'block'; // Show "no image" text
        });

        // Ensure the image is loaded correctly every time the modal is opened
        $('#imagePreviewModal').on('show.bs.modal', function() {
            const file = layout.files[0];
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
        const videoInput = document.getElementById('vidio');
const videoFileNameDisplay = document.getElementById('videoFileName');
const videoFileSizeDisplay = document.getElementById('videoFileSize');
const modalVideoPreview = document.getElementById('modalVideoPreview');
const noVideoText = document.getElementById('noVideoText');
const resetVideoButton = document.getElementById('resetVideoButton');
const viewVideoButton = document.getElementById('viewVideoButton');

// Update file details when a video file is selected
videoInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const fileSizeMB = file.size / 1024 / 1024;
        if (fileSizeMB > 50) {
            alert('Ukuran file melebihi 50 MB. Silakan pilih file yang lebih kecil.');
            videoInput.value = '';
            videoFileNameDisplay.textContent = 'Pilih file';
            videoFileSizeDisplay.textContent = '';
            return;
        }

        videoFileNameDisplay.textContent = file.name;
        videoFileSizeDisplay.textContent = `• ${fileSizeMB.toFixed(2)} MB`;
    }
});

// Show video preview when the view button is clicked
viewVideoButton.addEventListener('click', function() {
    const file = videoInput.files[0];
    if (!file) {
        modalVideoPreview.style.display = 'none';
        noVideoText.style.display = 'block';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        modalVideoPreview.src = e.target.result;
        modalVideoPreview.style.display = 'block';
        noVideoText.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// Reset file input and clear all fields when the reset button is clicked
resetVideoButton.addEventListener('click', function() {
    videoInput.value = '';
    videoFileNameDisplay.textContent = 'Pilih file';
    videoFileSizeDisplay.textContent = '';
    modalVideoPreview.src = '';
    modalVideoPreview.style.display = 'none';
    noVideoText.style.display = 'block';
});

// Ensure the video is loaded correctly every time the modal is opened
$('#videoPreviewModal').on('show.bs.modal', function() {
    const file = videoInput.files[0];
    if (!file) {
        modalVideoPreview.style.display = 'none';
        noVideoText.style.display = 'block';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        modalVideoPreview.src = e.target.result;
        modalVideoPreview.style.display = 'block';
        noVideoText.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
    </script>

    {{-- untuk add more facility --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('container-multiple');
            const addMoreBtn = document.getElementById('addMoreBtn');

            function updateDeleteButtons() {
                const deleteButtons = container.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.disabled = deleteButtons.length <= 1; // Disable if only one input remains
                });
            }

            addMoreBtn.addEventListener('click', function() {
                const formGroup = document.createElement('div');
                formGroup.classList.add('form-group', 'mt-3');
                formGroup.innerHTML = `
                    <div class="row">
                        <div class="col-md-11">
                            <input type="text" name="facility[]" class="form-control" placeholder="Enter Facility">
                            <span class="text-danger error-message"></span>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-danger btn-sm delete-btn" type="button">
                                <i class="bi bi-trash"></i>&nbsp;&nbsp; Delete
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(formGroup);

                // Update delete buttons after adding a new input
                updateDeleteButtons();
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
                    const formGroup = e.target.closest('.form-group');
                    container.removeChild(formGroup);

                    // Update delete buttons after removal
                    updateDeleteButtons();
                }
            });

            // Initialize delete buttons on page load
            updateDeleteButtons();
        });
    </script>

    {{-- show hide link dan vidio  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="type_upload"]');
        const linkSection = document.getElementById('show_if_type_link');
        const videoSection = document.getElementById('show_if_type_upload_vidio');

        // Helper function to toggle visibility
        function toggleSections() {
            const selectedType = document.querySelector('input[name="type_upload"]:checked');
            if (selectedType) {
                if (selectedType.value === 'link') {
                    linkSection.style.display = 'block';
                    videoSection.style.display = 'none';
                } else if (selectedType.value === 'upload_vidio') {
                    linkSection.style.display = 'none';
                    videoSection.style.display = 'block';
                }
            }
        }

    // Initial toggle on page load
    toggleSections();

    // Add change event listeners to radio buttons
    radioButtons.forEach(radio => {
        radio.addEventListener('change', toggleSections);
    });
});

    </script>


@endsection
