@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'test')

@section('breadcumbSubtitle', 'test Create')

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
                    position: relative;
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
                    position: absolute;
                    top: 0;
                    right: 0;
                    margin: 10px;
                }
            </style>
            <form id="form_edit_test" class="p-4 pt-3">
                <input type="hidden" id="test_id" value="{{ $edit_data->id }}">
                <div class="form-group" style="margin-top: 15px;">
                    <label for="pelatihan" class="form-label">Tipe Pelatihan</label>
                    <select class="form-select" name="pelatihan" id="pelatihan_id">
                        <option value="">Pilih Pelatihan</option>
                        <option value="Pelatihan" {{ $edit_data->pelatihan === 'Pelatihan' ? 'selected' : '' }}>Pelatihan
                            Public</option>
                        <option value="Pengembangan" {{ $edit_data->pelatihan === 'Pengembangan' ? 'selected' : '' }}>
                            Pengembangan Diri</option>
                    </select>
                    <span class="text-danger error-message" id="pelatihan_id-error"></span>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="judul_pelatihan" class="form-label">Judul Pelatihan</label>
                    <select class="form-select" name="judul_pelatihan" id="judul_pelatihan">
                        <option value="">Pilih judul pelatihan</option>
                        @foreach ($judulPelatihanList as $judul)
                        <option value="{{ $judul->id }}"
                            {{ $judul->id == $edit_data->judul_pelatihan ? 'selected' : '' }}>
                            {{ $judul->judul_pelatihan }}
                        </option>
                        @endforeach
                    </select>
                    <span class="text-danger error-message" id="judul_pelatihan-error"></span>
                </div>


                <div class="form-group" style="margin-top: 15px;">
                    <label for="type_test" class="form-label">Tipe Tes</label>
                    <select class="form-select" name="type_test" id="type_test">
                        <option value="">Pilih Tipe</option>
                        <option value="Pre Test" {{ $edit_data->type_test === 'Pre Test' ? 'selected' : '' }}>Pre Test
                        </option>
                        <option value="Post Test" {{ $edit_data->type_test === 'Post Test' ? 'selected' : '' }}>Post Test
                        </option>
                    </select>
                    <span class="text-danger error-message" id="type_test-error"></span>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h4>Daftar Pertanyaan</h4>
                        <div id="form-list">
                            @if (is_iterable($edit_data->question))
                                @foreach ($edit_data->question as $index => $question)
                                    <div class="form-container">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="form-number">{{ $index + 1 }}</span>
                                            <button type="button" class="btn btn-danger remove-form-btn">Hapus
                                                Pertanyaan</button>
                                        </div>
                                         <!-- Hidden input to pass the question ID -->
                                        <input type="hidden" name="question_ids[]" value="{{ $question->id }}">
                                        <div class="mb-3 mt-3">
                                            <label for="question-{{ $index + 1 }}" class="form-label">Pertanyaan</label>
                                            <textarea class="form-control" name="pertanyaan[]" id="question-{{ $index + 1 }}" rows="2"
                                                placeholder="Masukkan pertanyaan">{{ $question->pertanyaan }}</textarea>
                                            <span class="text-danger error-message"
                                                id="question-{{ $index + 1 }}-error"></span>
                                        </div>
                                        <div class="row">
                                            @foreach ($question->answer as $answer)
                                                <div class="col-md-6 mb-3">
                                                    <label
                                                        for="option-{{ strtolower($answer->option) }}-{{ $index + 1 }}"
                                                        class="form-label">Opsi Jawaban
                                                        {{ strtoupper($answer->option) }}</label>
                                                    <input type="text" class="form-control" name="jawaban[]"
                                                        id="option-{{ strtolower($answer->option) }}-{{ $index + 1 }}"
                                                        placeholder="Masukkan opsi jawaban"
                                                        value="{{ $answer->answer_text }}">
                                                    <span class="text-danger error-message"
                                                        id="option-{{ strtolower($answer->option) }}-{{ $index + 1 }}-error"></span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mb-3">
                                            <label for="answer-key-{{ $index + 1 }}" class="form-label">Kunci
                                                Jawaban</label>
                                            <label for="answer-key-{{ $index + 1 }}" class="form-label">Kunci
                                                Jawaban</label>
                                            <select class="form-select" name="kunci_jawaban[{{ $index }}]"
                                                id="answer-key-{{ $index + 1 }}">
                                                <option value="">Pilih kunci jawaban</option>
                                                @foreach ($question->answer as $answer)
                                                    <!-- Loop through answers to find the correct one -->
                                                    <option value="{{ strtolower($answer->option) }}"
                                                        {{ $answer->is_correct ? 'selected' : '' }}>
                                                        {{ strtoupper($answer->option) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-message"
                                                id="answer-key-{{ $index + 1 }}-error"></span>
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            @endif
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
        console.log("Edit Data Judul Pelatihan ID:", '{{ $edit_data->judul_pelatihan }}');
        function onPelatihanChange() {
            var pelatihanId = $('#pelatihan_id').val();
            var judulPelatihanSelect = $('#judul_pelatihan');

            // Clear the previous options
            judulPelatihanSelect.empty().append('<option value="">Pilih Pelatihan</option>');

            $.ajax({
                url: `{{ url('/vendor/test/get_judul_pelatihan') }}`,
                type: 'GET',
                data: {
                    pelatihan: pelatihanId
                },
                success: function(response) {
                    $.each(response, function(index, judulPelatihan) {
                        // Append each judul_pelatihan to the select
                        judulPelatihanSelect.append('<option value="' + judulPelatihan.id + '">' +
                            judulPelatihan.judul_pelatihan + '</option>');
                    });

                    // Set the selected value for judul_pelatihan if it exists
                    if ('{{ $edit_data->judul_pelatihan }}') {
                        judulPelatihanSelect.val('{{ $edit_data->judul_pelatihan }}');
                    }
                },
                error: function(xhr) {
                    console.log('Error fetching judul pelatihan:', xhr);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            onPelatihanChange();
            $('#pelatihan_id').on('change', onPelatihanChange);

            $('#form_edit_test').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                __isBtnSaveOnProcessing($('#form_edit_test #btn-save'), true);

                $('.error-message').text(''); // Clear all previous error messages

                let isValid = true;

                // Validation for selected training type
                if ($('#pelatihan_id').val() === '') {
                    $('#pelatihan_id-error').text('Tipe pelatihan harus dipilih');
                    isValid = false;
                }

                // Validation for selected training title
                if ($('#judul_pelatihan').val() === '') {
                    $('#judul_pelatihan-error').text('Judul pelatihan harus dipilih');
                    isValid = false;
                }

                // Validation for selected test type
                if ($('#type_test').val() === '') {
                    $('#type_test-error').text('Tipe tes harus dipilih');
                    isValid = false;
                }

                // Validation for questions
                $('textarea[name="pertanyaan[]"]').each(function(index) {
                    if ($(this).val().trim() === '') {
                        $(`#question-${index + 1}-error`).text('Pertanyaan tidak boleh kosong');
                        isValid = false;
                    }
                });

                // Validation for answer options
                $('input[name="jawaban[]"]').each(function(index) {
                    if ($(this).val().trim() === '') {
                        $(`#option-${String.fromCharCode(97 + (index % 4))}-${Math.floor(index / 4) + 1}-error`)
                            .text('Opsi jawaban tidak boleh kosong');
                        isValid = false;
                    }
                });

                // Validation for answer keys
                $('select[name="kunci_jawaban[]"]').each(function(index) {
                    if ($(this).val() === '') {
                        $(`#answer-key-${index + 1}-error`).text('Kunci jawaban harus dipilih');
                        isValid = false;
                    }
                });

                // If validation fails, revert button state and exit
                if (!isValid) {
                    __isBtnSaveOnProcessing($('#form_edit_test #btn-save'), false);
                    return;
                }

                // Prepare form data for submission
                const id = $('#test_id').val();
                const formData = new FormData(this);
                formData.append('token', getCurrentToken()['token']); // Include CSRF token

                $.ajax({
                    url: `{{ url('/api/vendor/test/edit/') }}/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        blockUI('Data Berhasil Disimpan');
                        // Optionally redirect after saving
                        setTimeout(() => {
                            window.location.href = '{{ url('/vendor/test') }}';
                        }, 1500);
                    },
                    error: function(xhr) {
                        __isBtnSaveOnProcessing($('#form_edit_test #btn-save'), false);

                        if (xhr.status === 400 && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}-error`).text(errors[key][0]);
                            });
                        } else {
                            blockUI('Ops.. something went wrong!', _.ERROR);
                        }
                    }
                });
            });



            // Function to add new form
            let formIndex = 1; // Initialize form index

            $('#add-form-btn').click(function() {
                formIndex++;
                var newForm = `
        <div class="form-container">
            <div class="d-flex justify-content-between align-items-center">
                <span class="form-number">` + formIndex + `</span>
                <button type="button" class="btn btn-danger remove-form-btn">Hapus Pertanyaan</button>
            </div>
            <div class="mb-3 mt-3">
                <label for="question-` + formIndex + `" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question-` + formIndex + `" rows="2" name="pertanyaan[]" placeholder="Masukkan pertanyaan"></textarea>
                <span class="text-danger error-message" id="question-` + formIndex + `-error"></span>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="option-a-` + formIndex + `" class="form-label">Opsi Jawaban A</label>
                    <input type="hidden" name="alphabetic[]" value="A">
                    <input type="text" class="form-control" id="option-a-` + formIndex + `" name="jawaban[]" placeholder="Masukkan opsi jawaban">
                    <span class="text-danger error-message" id="option-a-` + formIndex + `-error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="option-b-` + formIndex + `" class="form-label">Opsi Jawaban B</label>
                    <input type="hidden" name="alphabetic[]" value="B">
                    <input type="text" class="form-control" id="option-b-` + formIndex + `" name="jawaban[]" placeholder="Masukkan opsi jawaban">
                    <span class="text-danger error-message" id="option-b-` + formIndex + `-error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="option-c-` + formIndex + `" class="form-label">Opsi Jawaban C</label>
                    <input type="hidden" name="alphabetic[]" value="C">
                    <input type="text" class="form-control" id="option-c-` + formIndex + `" name="jawaban[]" placeholder="Masukkan opsi jawaban">
                    <span class="text-danger error-message" id="option-c-` + formIndex + `-error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="option-d-` + formIndex + `" class="form-label">Opsi Jawaban D</label>
                    <input type="hidden" name="alphabetic[]" value="D">
                    <input type="text" class="form-control" id="option-d-` + formIndex + `" name="jawaban[]" placeholder="Masukkan opsi jawaban">
                    <span class="text-danger error-message" id="option-d-` + formIndex + `-error"></span>
                </div>
            </div>
            <div class="mb-3">
                <label for="answer-key-` + formIndex + `" class="form-label">Kunci Jawaban</label>
                <select class="form-select" name="kunci_jawaban[]" id="answer-key-` + formIndex + `">
                    <option value="">Pilih kunci jawaban</option>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
                <span class="text-danger error-message" id="answer-key-` + formIndex + `-error"></span>
            </div>
            <hr>
        </div>
    `;
                $('#form-list').append(newForm);
                updateFormNumbers();
            });

            // Function to remove a form
            $(document).on('click', '.remove-form-btn', function() {
                if ($('.form-container').length > 1) {
                    $(this).closest('.form-container').remove();
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
