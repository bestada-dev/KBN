@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pelatihan')

@section('breadcumbSubtitle', 'Pelatihan Create')

@section('content')
    <style>
        .question-section {
            margin-top: 2%;
            font-family: Arial, sans-serif;
        }

        .paddings {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-top: 9px;
        }

        .question {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .question-number {
            font-size: 18px;
            background-color: #4285f4;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
        }

        .question-text {
            font-size: 16px;
            margin: 0;
            background-color: #e6f1fc;
            padding: 10px;
            border-radius: 4px;
        }

        .options {
            margin-top: 10px;
        }

        .option-row {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .option-radio {
            margin-right: 10px;
        }

        .option-text {
            font-size: 15px;
        }
    </style>
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER">
                <a href="{{ url('company/training') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">{{$data['testUser'] ? $data['testUser']['type_test'] : '-'}}</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
                {{-- <a href="{{url('company/training/update/'.$data->id)}}" id="btn_sunting" class="btn btn-primary ms-auto">Sunting</a> <!-- Tombol Sunting --> --}}
            </div>
            <form id="form_detail_pelatihan" class="p-4 pt-3">
                <h5 class="m-0">Pilihlah satu jawaban yang menurut anda paling benar untuk pertanyaan-pertanyaan di bawah ini.</h5>
                <p style="color: #FF3333; margin-top: 15px">Nilai maksimum yang bisa didapatkan adalah 100.</p>
                <input type="hidden" name="employe_test_id" value="{{ $data->employe_test_id }}" placeholder="id ini untuk mengubah data di table employe_test">
                <input type="hidden" name="employe_test_detail_id" value="{{ Request::segment(4) }}" placeholder="id ini untuk mengubah data di table employe_detail_test">

                <input type="hidden" name="test_id" value="{{ $data->test_id }}">
                <input type="hidden" name="type" value="{{ $data['testUser']['type_test'] }}">
                <div class="question-section">
                    @if (is_iterable($data->getQuestion))
                        @foreach ($data->getQuestion as $index => $getQuestion)
                            <div class="paddings">
                                <div class="question">
                                    <div class="question-number">{{ $index + 1 }}</div>
                                    <p class="question-text">{{ $getQuestion->pertanyaan }}</p>
                                </div>

                                <div class="options">
                                    @foreach ($getQuestion->answer as $idx => $answer)
                                        <div class="option-row">
                                            <input type="radio" id="question_{{ $index }}_answer_{{ $idx }}" name="question[{{ $getQuestion->id }}]" value="{{ $answer->id }}" class="option-radio">
                                            <label for="question_{{ $index }}_answer_{{ $idx }}" class="option-text">{{ $answer->option }}. {{ $answer->answer_text ?? '-' }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" id="submitQuiz" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </article>


@endsection

@section('js')
<script>
    document.getElementById('submitQuiz').addEventListener('click', function() {
        const formElement = document.getElementById('form_detail_pelatihan');
        const formData = new FormData(formElement);

        // Include an additional token parameter
        formData.append('token', getCurrentToken()['token']); // Custom token, if required

        fetch("{{ url('/api/employe/test/submit-test') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                blockUI("Quiz submitted successfully! Your score: " + data.score);
                setTimeout(function() {
                    window.location.href = `{{ url('/employe/test') }}`
                }, 4000);
                // location.reload(); // Reload page to reflect any updates, if necessary
            } else {
                alert("There was an error submitting the quiz.");
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
