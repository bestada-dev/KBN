@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Evaluasi Level 3')

@section('breadcumbSubtitle', 'Evaluasi Level 3 Create')

@section('content')
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER">
                <a href="{{ url()->previous() }}"><img src="{{ asset('assets/back.png') }}" alt="Back"></a>
                <h5>Kembali</h5>
            </div>
            <style>
                .form-container {
                    border: 1px solid black;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-radius: 10px;
                }

                h4 {
                    margin-bottom: 20px;
                }

                hr {
                    border: 1px solid black;
                }

                .container {
                    width: 100vw;
                    padding-left: 0;
                    padding-right: 0;
                    margin-left: 0;
                    margin-right: 0;
                }

                .col-md-6 {
                    flex: 0 0 50%;
                    max-width: 50%;
                }

                .form-number {
                    font-size: 18px;
                    font-weight: bold;
                    color: black;
                }

                .remove-form-btn {
                    margin-left: 10px;
                }
            </style>
            <form id="form_create_rvaluasi" class="p-4 pt-3">
                <div class="form-group" style="margin-top: 15px;">
                    <label for="pelatihan" class="form-label">Tipe Pelatihan</label>
                    <select class="form-select" name="tipe_pelatihan" id="pelatihan_id">
                        <option value="">Pilih Pelatihan</option>
                        <option value="Pelatihan">Pelatihan Public</option>
                        <option value="Pengembangan">Pengembangan Diri</option>
                    </select>
                    <span class="text-danger error-message" id="pelatihan_id-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="judul_pelatihan_id" class="form-label">Judul Pelatihan</label>
                    <select class="form-select" name="judul_pelatihan_id" id="judul_pelatihan_id">
                        <option value="">Pilih Pelatihan</option>
                    </select>
                    <span class="text-danger error-message" id="judul_pelatihan_id-error"></span>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h4>Daftar Pertanyaan</h4>
                        <!-- Pertanyaan Form Template -->
                        <div class="form-container">
                            <div id="form-list">
                                <div class="row align-items-center deleted-forms">
                                    <div class="col d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <label for="question-1" class="form-label">Pertanyaan</label>
                                            <textarea class="form-control" id="pertanyaan" name="pertanyaan[]" id="question-1" rows="4" placeholder="Masukkan pertanyaan"></textarea>
                                            <span class="text-danger error-message" id="question-1-error"></span>
                                        </div>
                                        <button type="button" class="btn btn-danger remove-form-btn ms-2"><i
                                                class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-form-btn" class="btn btn-primary">Tambah Pertanyaan</button>

                    </div>
                </div>

                <div class="btn-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-default btn-sm btn-block mt-4">Cancel</a>
                    <button type="submit" class="btn btn-main btn-sm btn-block mt-4" id="btn-save">Save</button>
                </div>
            </form>

        </div>
    </article>

@endsection

@section('js')
    {{-- untuk mengubah judul sesuai pelatihan --}}
    <script>
        function onPelatihanChange() {
            var pelatihanId = $('#pelatihan_id').val();
            var judulPelatihanSelect = $('#judul_pelatihan_id');

            judulPelatihanSelect.empty().append('<option value="">Pilih Pelatihan</option>');
            $.ajax({
                url: `{{ url('/superadmin/evaluasi_level_3/get_judul_pelatihan') }}`,
                type: 'GET',
                data: {
                    pelatihan: pelatihanId
                },
                success: function(response) {
                    // console.log(response);
                    $.each(response, function(index, judulPelatihan) {
                        judulPelatihanSelect.append('<option value="' + judulPelatihan.id + '">' +
                            judulPelatihan.judul_pelatihan + '</option>');
                    });
                },
                error: function(xhr) {
                    console.log('Error fetching judul pelatihan:', xhr);
                }
            });
        }

        $('#pelatihan_id').on('change', onPelatihanChange);
    </script>

    <script>
        $(document).ready(function() {
            $('#form_create_rvaluasi').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                __isBtnSaveOnProcessing($('#form_create_rvaluasi #btn-save'), true);

                $('.error-message').text(''); // Clear all previous error messages

                let isValid = true;

                // Validation for selected training type
                if ($('#pelatihan_id').val() === '') {
                    $('#pelatihan_id-error').text('Tipe pelatihan harus dipilih');
                    isValid = false;
                }

                // Validation for selected training title
                if ($('#judul_pelatihan_id').val() === '') {
                    $('#judul_pelatihan_id-error').text('Judul pelatihan harus dipilih');
                    isValid = false;
                }

                // Validation for questions
                $('textarea[name="pertanyaan[]"]').each(function(index) {
                    if ($(this).val().trim() === '') {
                        $(`#question-${index + 1}-error`).text('Pertanyaan tidak boleh kosong');
                        isValid = false;
                    }
                });

                // If validation fails, revert button state and exit
                if (!isValid) {
                    __isBtnSaveOnProcessing($('#form_create_rvaluasi #btn-save'), false);
                    return;
                }

                // Prepare form data for submission
                const formData = new FormData(this);
                formData.append('token', getCurrentToken()['token']); // Include CSRF token

                $.ajax({
                    url: '{{ url('/api/superadmin/evaluasi_level_3/store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        blockUI('Data Berhasil Disimpan');
                        // Optionally redirect after saving
                        // setTimeout(() => {
                        //     window.location.href = '{{ url('/superadmin/evaluasi_level_3') }}';
                        // }, 1500);
                    },
                    error: function(xhr) {
                        __isBtnSaveOnProcessing($('#form_create_rvaluasi #btn-save'), false);
                        console.log(xhr.responseJSON.error);

                        if (xhr.status === 400 && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}-error`).text(errors[key][0]);
                            });
                        } else {
                            blockUI(xhr.responseJSON.error, _.ERROR);
                        }
                    }
                });
            });



            // Function to add new form
            let formIndex = 1;

            $('#add-form-btn').click(function() {
                formIndex++;
                var newForm = `
                        <div class="row align-items-center deleted-forms">
                            <div class="col d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <label for="question-` + formIndex + `" class="form-label">Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan[]" id="question-` + formIndex + `" rows="4" placeholder="Masukkan pertanyaan"></textarea>
                                    <span class="text-danger error-message" id="question-` + formIndex + `-error"></span>
                                </div>
                                <button type="button" class="btn btn-danger remove-form-btn ms-2">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>`;
                $('#form-list').append(newForm);
                updateFormNumbers();
            });

            // Function to remove a form
            $(document).on('click', '.remove-form-btn', function() {
                if ($('.deleted-forms').length > 1) {
                    $(this).closest('.deleted-forms').remove();
                    updateFormNumbers();
                } else {
                    alert('Tidak bisa menghapus semua form.');
                }
            });

            // Update form numbers
            function updateFormNumbers() {
                $('.form-number').each(function(index) {
                    $(this).text(index + 1);
                });
            }


        });
    </script>

@endsection
