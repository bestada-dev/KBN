@extends('layouts.app')

@section('title', 'Masuk')

@section('content')

    <style>



.otp-input-container {
            display: flex;
            justify-content: space-between;
            max-width: 350px;
            margin: 20px auto;
            border-radius: 50px;
        }

        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 24px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .otp-message {
            margin-top: 10px;
            font-size: 14px;
            font-weight: 400;
        }

        .resend-link {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        .error-message {
            color: red;
        }

        .otp-input.error {
            border-color: red;
        }

        .otp-input.success {
            border-color: green;
        }
    </style>

    <b>
        <img src="{{ asset('logo.png') }}" style="align-self: center;margin-bottom:1rem">
        <form id="verifyForm">
            <center>
                <h2 style="font-size: 20px;font-weight:700">Masukan Kode Verifikasi</h2>
                <p style="font-size: 14px;font-weight:400">Masukan 6 digit kode yang dikirim ke email </p>
                <p style="font-size: 14px;font-weight:700">{{ $get_email ? $get_email->email : '-' }}</p>
            </center>
            <input type="hidden" name="user_id" id="user_id" value="{{Request::segment(3)}}">
            <div class="otp-input-container">
                <input type="text" class="otp-input" maxlength="1" id="otp-1">
                <input type="text" class="otp-input" maxlength="1" id="otp-2">
                <input type="text" class="otp-input" maxlength="1" id="otp-3">
                <input type="text" class="otp-input" maxlength="1" id="otp-4">
                <input type="text" class="otp-input" maxlength="1" id="otp-5">
                <input type="text" class="otp-input" maxlength="1" id="otp-6">
            </div>

            <div class="otp-message error-message" id="otp-error"></div>
            <center>
                <p class="otp-message">Tidak mendapatkan kode OTP? <span class="resend-link" id="resend-otp" style="font-size: 17px;font-weight:700">Kirim ulang OTP</span></p>
            </center>
        </form>
    </b>
    <img src="{{ asset('img1.png') }}" style="width:38%;object-fit:cover;">

@endsection

@section('js')

<script>
    $(document).ready(function() {
        var otpInput = $('.otp-input');

        // Handle input keyup event
        otpInput.on('keyup', function(e) {
            var $this = $(this);
            var nextInput = $this.next('.otp-input');
            var prevInput = $this.prev('.otp-input');

            // Move to next input on valid keypress
            if ($this.val() && e.key !== 'Backspace') {
                nextInput.focus();
            } else if (e.key === 'Backspace') {
                prevInput.focus();
            }

            // Check if all inputs are filled
            var otp = '';
            otpInput.each(function() {
                otp += $(this).val();
            });

            if (otp.length === 6) {
                // Validate OTP via AJAX
                checkOTP(otp);
            }
        });

        function checkOTP(otp) {
            $.ajax({
                url:  `{{ url('api/register/validasi_otp') }}`,
                method: 'POST',
                data: {
                    otp: otp,
                    userId : $('#user_id').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        otpInput.addClass('success').removeClass('error');
                        window.location.href = '/forgot-password/update/' + response.user_id;
                    } else {
                        otpInput.addClass('error').removeClass('success');
                        $('#otp-error').text('OTP yang anda masukan tidak sesuai');
                    }
                },
                error: function() {
                    $('#otp-error').text('OTP yang anda masukan tidak sesuai.');
                }
            });
        }

        $('#resend-otp').click(function() {
            // Handle OTP Resend (optional)
            alert('Resend OTP functionality to be implemented');
        });
    });
</script>

@endsection
