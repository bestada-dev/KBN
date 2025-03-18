@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Admin')

@section('breadcumbSubtitle', 'Admin List')

@section('content')

    <article class="">

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER">
                <a href="{{ url('/superadmin/master/zonning') }}"><img src="{{ asset('assets/back.png') }}" alt="Back"></a>
                <h5>Back</h5>
            </div>


            <form id="form_edit_zoning" class="p-4 pt-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{url('/superadmin/master/zonning')}}">List Zoning</a></li>
                      <li class="breadcrumb-item"><span>Update Zoning</span></li>
                    </ol>
                </nav>

                <div class="form-group" style="margin-top: 15px">
                    <label for="zone_name" class="form-label required-label">Zone Name <b>(*)</b></label>
                    <input type="text" class="form-control form-control-sm" name="zone_name" value="{{$data_edit->zone_name}}" id="zone_name"
                        placeholder="Enter Zone Name">
                    <div id="zone_name_error" class="error-message"></div>
                </div>
                <div class="form-group" style="margin-top: 15px">
                    <label for="address" class="form-label required-label">Address <b>(*)</b></label>
                    <textarea name="address" id="address" class="form-control form-control-sm" cols="10" rows="3"
                        placeholder="Enter Address">{{$data_edit->address}}</textarea>
                    <div id="address_error" class="error-message"></div>
                </div>
                <div class="form-group" style="margin-top: 15px">
                    <label for="link_map" class="form-label required-label">Link Map <b>(*)</b></label>
                    <textarea name="link_map" id="link_map"  class="form-control form-control-sm" cols="10" rows="5">{{$data_edit->link_map}}</textarea>
                    <div id="link_map_error" class="error-message"></div>
                </div>

                @foreach ($data_edit->strategicLocation as $val)
                    <div id="form-container">
                        <div class="row form-row" style="margin-top: 5px">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-top: 15px">
                                    <label for="strategic_location" class="form-label required-label">Strategic Location
                                        <b>(*)</b></label>
                                    <input type="text" class="form-control form-control-sm" name="strategic_location[]"
                                        value="{{ $val->strategic_location }}" placeholder="Masukan Strategic Location">
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-top: 15px">
                                    <label for="distance" class="form-label required-label">Distance <b>(*)</b></label>
                                    <div class="input-group" style="max-width: 100%;">
                                        <!-- Dropdown -->
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"
                                            id="dropdownMenuButton_{{ $loop->index }}">
                                            {{ $val->distance_type }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $loop->index }}">
                                            <li><a class="dropdown-item" href="#" data-value="KM">KM</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="M">M</a></li>
                                        </ul>
                                        <!-- Input Hidden for distance_type -->
                                        <input type="hidden" name="distance_type[]" value="{{ $val->distance_type }}">
                                        <!-- Input Number -->
                                        <input type="number" class="form-control" placeholder="Enter amount"
                                            name="distance[]" value="{{ $val->distance }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end" style="margin-top: 5px">
                                <button class="btn btn-danger btn-sm btn-remove-form" type="button">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Add More Button -->
                <div class="col-md-12 d-flex justify-content-end" style="margin-top: 15px">
                    <button class="btn btn-success btn-sm" type="button" id="addMoreBtn"><i class="bi bi-plus"></i> Add
                        More</button>
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
                        window.location.href='{{ url('/superadmin/master/zonning') }}'
                } catch (error) {
                    console.error(error);
                    blockUI('Ops.. something went wrong!', _.ERROR)
                }
            }, 'Are you sure?', 'You want the cancel to adding Update', 'Yes', 'No')

        }

        $(document).ready(function() {
            const defaultDistanceType = "KM";

            // Event handler untuk dropdown
            $(document).on('click', '.dropdown-menu a', function(e) {
                e.preventDefault();

                // Ambil teks dan nilai dari item yang dipilih
                const selectedText = $(this).text();
                const selectedValue = $(this).data('value');

                // Perbarui tombol dropdown
                const dropdownButton = $(this).closest('.input-group').find('.dropdown-toggle');
                dropdownButton.text(selectedText);

                // Perbarui input hidden
                const hiddenInput = $(this).closest('.input-group').find('input[name="distance_type[]"]');
                hiddenInput.val(selectedValue);
            });

            // Tambahkan form baru
            $('#addMoreBtn').on('click', function() {
                // Clone form pertama tanpa referensi langsung ke data asli
                let newForm = $('.form-row').first().clone(false, false);

                // Reset nilai input pada form baru
                newForm.find('input').each(function() {
                    $(this).val(''); // Kosongkan semua input
                });
                newForm.find('input[name="distance_type[]"]').val(
                defaultDistanceType); // Set nilai default "KM"
                newForm.find('.dropdown-toggle').text(defaultDistanceType); // Set label dropdown ke "KM"

                // Tampilkan tombol remove pada form baru
                newForm.find('.btn-remove-form').show();

                // Tambahkan form baru ke akhir container
                newForm.appendTo('#form-container');

                // Toggle visibility tombol remove
                toggleRemoveButton();
            });

            // Hapus form
            $(document).on('click', '.btn-remove-form', function() {
                $(this).closest('.form-row').remove();
                toggleRemoveButton();
            });

            // Tampilkan / sembunyikan tombol remove
            function toggleRemoveButton() {
                if ($('.form-row').length <= 1) {
                    $('.btn-remove-form').hide();
                } else {
                    $('.btn-remove-form').show();
                }
            }

            // Inisialisasi tombol remove saat pertama kali halaman dimuat
            toggleRemoveButton();

            $('#form_edit_zoning').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous error messages
                $(".error-message").text("");

                var formData = new FormData(this); // Use FormData to handle file uploads or complex data
                formData.append('token', getCurrentToken()['token']); // Append token if needed

                $.ajax({
                    url: `{{ url('api/superadmin/master/zoning/update') }}/{{$data_edit->id}}`,
                    type: 'POST', // Use POST for compatibility if PUT causes issues
                    data: formData,
                    processData: false, // Prevent jQuery from automatically transforming the data
                    contentType: false, // Allow content type to be set as FormData's default
                    success: function (response) {
                        if (response.success) {
                            blockUI(response.message); // Show success message
                            setTimeout(function () {
                                window.location.href = "{{ url('/superadmin/master/zonning') }}";
                            }, 3000);
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            if (errors.zone_name) {
                                $('#zone_name_error').text(errors.zone_name[0]);
                            }
                            if (errors.address) {
                                $('#address_error').text(errors.address[0]);
                            }
                            if (errors.link_map) {
                                $('#link_map_error').text(errors.link_map[0]);
                            }
                            if (errors["strategic_location.*"]) {
                                $("input[name='strategic_location[]']").each(function (index) {
                                    $(this).siblings('.error-message').text(errors["strategic_location.*"][index]);
                                });
                            }
                            if (errors["distance.*"]) {
                                $("input[name='distance[]']").each(function (index) {
                                    $(this).siblings('.error-message').text(errors["distance.*"][index]);
                                });
                            }
                            if (errors["distance_type.*"]) {
                                $("input[name='distance_type[]']").each(function (index) {
                                    $(this).siblings('.error-message').text(errors["distance_type.*"][index]);
                                });
                            }
                        } else {
                            alert("An unexpected error occurred. Please try again.");
                        }
                    }
                });
            });
        });
    </script>
@endsection
