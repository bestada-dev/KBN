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
                .question-section {
                    margin-top: 2%;
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
                }

                .options {
                    margin-top: 10px;
                }

                .option-row {
                    display: flex;
                    justify-content: space-between;
                    margin: 5px 0;
                }

                .option-letter {
                    font-weight: bold;
                    width: 20px;
                }

                .option-text {
                    width: calc(50% - 30px);
                    text-align: left;
                }

                .answer {
                    margin-top: 20px;
                }

                .paddings {
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                    margin-top: 9px;
                }
            </style>
            <form id="form_edit_test" class="p-4 pt-3">
                <div class="question-container">
                    <p style="font-weight:700;font-size:20px">Pelatihan Publik</p>
                    <p>
                        <span style="font-weight:500;font-size:14px;color:#757575">Post Test</span>
                        <span style="font-weight:500;font-size:14px;color:#0A0A0A"> &nbsp;•&nbsp; Pelatihan TeknologiInformasi</span>
                    </p>

                    <div class="question-section">
                        <p style="font-weight:700;font-size:20px">Daftar Pertanyaan</p>
                        @if (is_iterable($edit_data->question))
                            @foreach ($edit_data->question as $index => $question)
                                <div class="paddings">
                                    <div class="question">
                                        <span class="question-number">{{ $index + 1 }}</span>
                                        <p class="question-text">{{ $question->pertanyaan }}</p>
                                    </div>

                                    <div class="options">
                                        <p><strong>Opsi Jawaban</strong></p>
                                        <div class="option-row">
                                            @foreach ($question->answer as $index => $answer)
                                                <span class="option-letter">{{ $answer->option ?? chr(65 + $index) }}</span>
                                                <span class="option-text">{{ $answer->answer_text ?? '-' }}</span>

                                                @if (($index + 1) % 2 == 0)
                                                    </div><div class="option-row">
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="answer">
                                        <p><strong>Kunci Jawaban</strong></p>
                                        <p>
                                            @foreach ($question->answer as $answer)
                                                @if ($answer->is_correct)
                                                    {{ strtolower($answer->option) }}. {{ $answer->text }}
                                                @endif
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </form>

        </div>
    </article>

@endsection

@section('js')

@endsection
