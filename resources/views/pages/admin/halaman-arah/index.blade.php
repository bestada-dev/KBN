@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pengaturan')

@section('breadcumbSubtitle', 'Halaman Arah')

@section('content')
    <style>
        .mb-3.test{
                border-color:#dadada;
                border-radius:25px;
                padding:1.8rem 2rem;
                /* margin:2rem */
        }
        nav#halaman-arah a.nav-link.active {
            background: white !important;
            border-bottom: 2px solid #2778c4;
        }
        nav#halaman-arah a:hover {
            color:white !important;
            background:#2778c4 !important;
        }
        nav#halaman-arah a,
        nav#halaman-arah a:active,
        nav#halaman-arah a:visited,
        nav#halaman-arah a:focus {
            font-weight: 500;
            font-size: 13px;    
            color: #2778c4 !important;

        }

        nav#halaman-arah {
            padding: 0px 0 38px;
            display:flex;
            justify-content:center;
            gap:1rem;
            padding-bottom: 0.5rem;
        }
    </style>

    <article style="margin-top:7rem;">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                <a href="{{url('/pelatihan_saya/create')}}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">TambahData</a>
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->

        <div class="TABLE-WITHOUT-SEARCH-BAR p-0" >
            <div class="HEADER">
                <h5>Landing Page</h5>
            </div>
            <div class="p-4 pt-3">
                <!-- Navigation Tabs -->
                <!-- <nav class="nav justify-content-center" id="halaman-arah">
                    <a class="nav-link active" href="#home" data-bs-toggle="tab">Home</a>
                </nav> -->
                <a href="{{ url('/superadmin/landing-page/benefit')}}" style="display: inline-block; padding: 5px 15px; background-color: #2d89ef; color: white; border-radius: 5px; text-decoration: none; margin-left: 1120px;">
                    Edit Benefit
                </a>                
                

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Home Tab Pane -->
                    <div class="tab-pane fade show active" id="home">
                        @include('pages.admin.halaman-arah.component.home')
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal for Image Preview -->
        <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filePreviewModalLabel">Preview File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" style="background: #a8ffa4;    display: flex;    justify-content: center;">
                        <img id="modalImagePreview" style="display: none;max-width: 100%; max-height: 400px;" alt="Image Preview">
                        <video id="modalVideoPreview" style="display: none;max-width:450px" controls>
                            <source id="videoSource" src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p id="noImageText" style="display: none;">Tidak ada gambar untuk ditampilkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection

@section('js')
<script>
$(document).ready(function () {
            // Handle button click to trigger the datepicker
            $('.datepicker-trigger').on('click', function () {
                var $input = $(this).siblings('.init-datepicker');
                $input.datepicker('show');
            });

            setupFileUploadHandlers();

            $(`#home-form`).validate({
                    ...rulesValidateGlobal,
                    rules: getValidationRules(),
                    messages: getValidationMessages(),
                    submitHandler: function (form, e) {
                        e.preventDefault();

                        __isBtnSaveOnProcessing( __getId('btn-save') ,true);
                        let allFilesValid = true;
                                            // $('input[type="file"]').each(function () {
                        //     if (!$(this).val()) {
                        //         allFilesValid = false;
                        //         $(this).closest('.dropify-wrapper').addClass('has-error');
                        //         $(this).closest('.dropify-wrapper').siblings('.help-inline.text-danger').remove();
                        //         $('<span class="help-inline text-danger">This field is required</span>').insertAfter($(this).closest('.dropify-wrapper'));
                        //     }
                        // });
                        if (allFilesValid && $(form).valid()) {
                            var formData = new FormData(form);
                            formData.append('token', getCurrentToken()['token'])
                            $.ajax({
                                url: $(form).attr('action'),
                                method: $(form).attr('method'),
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function ({message}) {
                                    blockUI(message)
                                    __isBtnSaveOnProcessing( __getId('btn-save') ,false);
                                    setTimeout(function() {
                                        window.location.href =
                                            `{{ route('halaman-arah') }}`
                                    }, 1000); // 2000 milliseconds = 2 seconds
                                    
                                },
                                error: function ({message}) {

                                    blockUI(message, _.ERROR)
                                }
                            });
                        } else {
                            // Optionally focus on the first invalid element
                            $(form).find('.has-error').first().focus();
                        }
                    }
                });


            function getValidationRules() {
                const rules = {};

                function addRules(fieldId, field) {
                    const fieldRules = {};
                    if (field.required) fieldRules.required = true;
                    if (field.pattern) fieldRules.pattern = field.pattern;
                    // console.log('RULESSSS',fieldId, fieldRules)

                    rules[fieldId] = fieldRules;
                }

                // Home
                addRules("Home_Bagian_1_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                // addRules("Home_Bagian_1_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addRules("Home_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addRules("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                // addRules("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":1});
                addRules("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addRules("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                // addRules("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":2});
                addRules("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addRules("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                // addRules("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":3});
                addRules("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addRules("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                // addRules("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":4});
                addRules("Home_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_3_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_4_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_4_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_4_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":5});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":5});
                addRules("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":6});
                addRules("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":6});
                addRules("Home_Bagian_5_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                // addRules("Home_Bagian_5_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_6_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_6_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_7_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_7_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_8_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                // addRules("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":1});
                // addRules("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":2});
                // addRules("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":3});
                addRules("Home_Bagian_9_whatsapp", {"label":"Whatsapp","name":"whatsapp","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_9_instagram", {"label":"Instagram","name":"instagram","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_9_tiktok", {"label":"Tiktok","name":"tiktok","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_9_facebook", {"label":"Facebook","name":"facebook","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Home_Bagian_9_twitter", {"label":"Twitter","name":"twitter","type":"text","required":true,"value":"","gridWidth":12});

                // About Us
                addRules("About_Us_Bagian_1_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("About_Us_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                // addRules("About_Us_Bagian_1_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                // addRules("About_Us_Bagian_1_video", {"label":"Video","name":"video","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addRules("About_Us_Bagian_2_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("About_Us_Bagian_2_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("About_Us_Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                // addRules("About_Us_Bagian_2_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addRules("About_Us_Bagian_3_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("About_Us_Bagian_3_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("About_Us_Bagian_3_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                // addRules("About_Us_Bagian_3_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                // addRules("About_Us_Bagian_3_video", {"label":"Video","name":"video","type":"file_original","required":true,"maxSizeInMB":100,"allowedExtensions":".mp4, .webm","value":null,"gridWidth":12});

                   // Pelatihan Pengembangan Diri
                addRules("Pelatihan_Pengembangan_Diri_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_1_description1", {"label":"Deskripsi 1","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_1_description2", {"label":"Deskripsi 2","name":"description2","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_2_description1", {"label":"Deskripsi (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Pengembangan_Diri_Bagian_3_description1", {"label":"Deskripsi  (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});

                   
                // Pelatihan Publik
                addRules("Pelatihan_Publik_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_1_description1", {"label":"Deskripsi 1","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_1_description2", {"label":"Deskripsi 2","name":"description2","type":"textarea","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_2_description1", {"label":"Deskripsi (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Pelatihan_Publik_Bagian_3_description1", {"label":"Deskripsi  (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
               
                // Contact
                addRules("Contact_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Contact_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Contact_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addRules("Contact_Bagian_1_address", {"label":"Alamat","name":"address","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Contact_Bagian_1_address", {"label":"No. Telpon","name":"phone","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Contact_Bagian_1_email", {"label":"Email","name":"email","type":"email","required":true,"value":"","gridWidth":12});
                addRules("Contact__Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addRules("Contact__Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                
                addRules("Home_Bagian_10_benefit_title_1", {"label":"Title","name":"title","type":"text","required":true,"value":"","gridWidth":12});
                                                
                    return rules;
            }


            function getValidationMessages() {
                const messages = {};


                function addMessages(fieldId, field) {
                    const fieldMessages = {};
                    if (field.required) fieldMessages.required = 'This field is required';
                    if (field.pattern) fieldMessages.pattern = field.validationMessage ?? 'Invalid format';
                    // console.log('MESAGESSS',fieldId, fieldMessages)
                    messages[fieldId] = fieldMessages;
                }

                // Home
                addMessages("Home_Bagian_1_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_1_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addMessages("Home_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_2_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addMessages("Home_Bagian_2_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addMessages("Home_Bagian_2_Poin_poin_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":4});
                addMessages("Home_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_3_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_4_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_4_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_4_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":4});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":5});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":5});
                addMessages("Home_Bagian_4_Poin_poin_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":6});
                addMessages("Home_Bagian_4_Poin_poin_description1", {"label":"Deskripsi","name":"description1","type":"text","required":true,"value":"","gridWidth":12,"forElementId":6});
                addMessages("Home_Bagian_5_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_5_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_6_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_6_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_7_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_7_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_8_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":1});
                addMessages("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":2});
                addMessages("Home_Bagian_8_logo", {"label":"Logo","name":"logo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":{"id":null,"value":null},"gridWidth":12,"forElementId":3});
                addMessages("Home_Bagian_9_whatsapp", {"label":"Whatsapp","name":"whatsapp","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_9_instagram", {"label":"Instagram","name":"instagram","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_9_tiktok", {"label":"Tiktok","name":"tiktok","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_9_facebook", {"label":"Facebook","name":"facebook","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Home_Bagian_9_twitter", {"label":"Twitter","name":"twitter","type":"text","required":true,"value":"","gridWidth":12});


                 // About Us
                 addMessages("About_Us_Bagian_1_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_1_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addMessages("About_Us_Bagian_1_video", {"label":"Video","name":"video","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addMessages("About_Us_Bagian_2_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_2_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_2_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addMessages("About_Us_Bagian_3_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_3_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_3_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("About_Us_Bagian_3_photo", {"label":"Foto","name":"photo","type":"file_original","required":true,"maxSizeInMB":10,"allowedExtensions":".png, .jpeg, .jpg","value":null,"gridWidth":12});
                addMessages("About_Us_Bagian_3_video", {"label":"Video","name":"video","type":"file_original","required":true,"maxSizeInMB":100,"allowedExtensions":".mp4, .webm","value":null,"gridWidth":12});

                   // Pelatihan Pengembangan Diri
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_1_description1", {"label":"Deskripsi 1","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_1_description2", {"label":"Deskripsi 2","name":"description2","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_2_description1", {"label":"Deskripsi (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Pengembangan_Diri_Bagian_3_description1", {"label":"Deskripsi  (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});

                   
                // Pelatihan Publik
                addMessages("Pelatihan_Publik_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_1_description1", {"label":"Deskripsi 1","name":"description1","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_1_description2", {"label":"Deskripsi 2","name":"description2","type":"textarea","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_2_description1", {"label":"Deskripsi (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_3_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Pelatihan_Publik_Bagian_3_description1", {"label":"Deskripsi  (Opsional)","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
               
                // Contact
                addMessages("Contact_Bagian_1_title1", {"label":"Judul 1","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Contact_Bagian_1_title2", {"label":"Judul 2","name":"title2","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Contact_Bagian_1_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                addMessages("Contact_Bagian_1_address", {"label":"Alamat","name":"address","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Contact_Bagian_1_address", {"label":"No. Telpon","name":"phone","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Contact_Bagian_1_email", {"label":"Email","name":"email","type":"email","required":true,"value":"","gridWidth":12});
                addMessages("Contact__Bagian_2_title1", {"label":"Judul","name":"title1","type":"text","required":true,"value":"","gridWidth":12});
                addMessages("Contact__Bagian_2_description1", {"label":"Deskripsi","name":"description1","type":"textarea","required":false,"value":"","gridWidth":12});
                   
                    return messages;
            }


            // function populateField(data, fieldId, type) {
            //     if (type === 'select') {
            //         const select = $("#" + fieldId);
            //         select.html(elm_choose('Choose')); // Clear previous options
            //         data.forEach(item => {
            //             select.append(new Option(item.name, item.id));
            //         });
            //     } else {
            //         const container = $("#" + fieldId + "-container");
            //         const options = data.map(item => `
            //             <div class="form-check">
            //                 <input class="form-check-input" type="${type}" name="${fieldId}[]" id="${fieldId}_${item.id}" value="${item.id}">
            //                 <label class="form-check-label" for="${fieldId}_${item.id}">${item.name}</label>
            //             </div>
            //         `).join('');
            //         container.html(options);
            //     }
            // }
            function setCheckboxValue(name, value) {
                // Select all checkboxes with the given name
                const checkboxes = document.querySelectorAll(`input[name="${name}"][value="${value}"]`);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;

                    // Dispatch a change event if needed
                    const event = new Event('change');
                    checkbox.dispatchEvent(event);
                });
            }


            function populateField(data, fieldId, type, fieldObject, section, groupIndexCurrent) {
                debugger

                // Function to get values from the field
                function getIdPrimaryKeyFromInputHidden(section) {
                    for (const field of section.children[groupIndexCurrent]) {
                        if (field.type === 'hidden') {
                            return field.value;
                        }
                    }
                    return null; // Return null if no hidden field is found
                }


                const newFieldId = (section.isGrouped ? `${fieldId}_${fieldObject.forElementId}` : fieldId);
                const newName = (section.isGrouped
                    ? `${section.title}_${fieldId}[${getIdPrimaryKeyFromInputHidden(section) || null}][]`
                    : type === 'checkbox'
                        ? `${section.title}_${fieldId}[]`
                        : `${section.title}_${fieldId}`);

                if (type === 'select') {
                    // const select = $("#" + fieldId);
                    const select = $("#" + newFieldId);

                    select.html(elm_choose()); // Clear previous options
                    data.forEach(item => {
                        select.append(new Option(item.name, item.id));
                    });
                    select.val(fieldObject.value);
                    select.change();
                } else if (type === 'checkbox' || type === 'radio') {
                    // const container = $("#" + fieldId + "-container");
                    const container = $("#" + newFieldId + "-container");
                    // newName = type === 'checkbox' ? newName : newName;
                    // Create unique IDs for each option
                    const options = data.map(item => `
                <div class="form-check">
                    <input class="form-check-input" type="${type}" name="${newName}" id="${newFieldId}_${item.id}" value="${item.id}" required="${fieldObject.required}">
                    <label class="form-check-label" for="${newFieldId}_${item.id}">${item.name}</label>
                </div>
            `).join('');
                    container.html(options);

                    if (type === 'checkbox') {
                        const selectedValues = fieldObject.value; // Replace with actual values you want to check
                        // debugger;
                        selectedValues.forEach(value => {
                            $(`#${newFieldId}_${value}`).prop('checked', true);
                        });
                    } else if (type === 'radio') {
                        const selectedValue = fieldObject.value; // Replace with the actual value you want to select
                        $(`#${newFieldId}_${selectedValue}`).prop('checked', true);
                    }

                    // Trigger change events if necessary
                    // const errMsg = `<span id="$fieldId }}" class="help-inline text-danger" style="display:none">This field is required.</span>`
                    // container.append(errMsg);
                    container.find('input').change();
                }

            }


            function updateRemoveButton(sectionId) {
                const $section = $('#' + sectionId);
                const $removeButton = $section.find('.remove-field');
                const rowCount = $section.find('.dynamic-row').length;

                // Enable or disable the remove button
                if (rowCount <= 1) {
                    $removeButton.prop('disabled', true);
                } else {
                    $removeButton.prop('disabled', false);
                }
            }

            function removeRow(sectionId) {
                const $section = $('#' + sectionId);
                const $rows = $section.find('.dynamic-row');

                if ($rows.length > 1) {
                    $rows.last().remove();
                    updateRemoveButton(sectionId); // Update remove button state after removing a row
                } else {
                    alert('Cannot remove the last field.');
                }
            }

            // Initial check on page load
            $('button.remove-field').each(function () {
                const sectionId = $(this).data('section');
                updateRemoveButton(sectionId);
            });

            $(document).on('click', '.add-field', function () {
                const sectionId = $(this).data('section');
                addRow(sectionId);
            });


            $(document).on('click', '.remove-field', function () {
                const sectionId = $(this).data('section');
                const $row = $(this).closest('.row');
                $row.remove(); // Get the index of the row
                updateRemoveButton(sectionId); // Update remove button state after removing a row
            });


            function addRow(sectionId) {
                // debugger;
                const $section = $('#' + sectionId);
                const uniqueSuffix = Date.now() + Math.floor(Math.random() * 1000); // Ensure uniqueness
                // Find the first row and clear Select2 from existing selects
                const $firstRow = $section.find('.row').first();

                debugger;
                $firstRow.find('select').each(function () {
                    const $select = $(this);
                    if ($select.data('select2')) {
                        $select.select2('destroy'); // Destroy existing Select2 instances
                    } else {
                        console.log("Select2 is not initialized on this select element.");
                    }
                });


                const $row = $firstRow.clone(); // Clone the row


                /*
                 * NANTI BENERIN Dropifnya masih agak bug *
                 *
                 */
                // bindDropifyEvents(true);
                //
                // // Clear Dropify instance and reset default file for the new row
                // $firstRow.find('.dropify').each(function() {
                //     const drInstance = $(this).data('dropify');
                //     if (drInstance) {
                //         drInstance.settings.defaultFile = null; // Set defaultFile to null
                //         drInstance.resetPreview();
                //         drInstance.clearElement();
                //         drInstance.init(); // Reinitialize Dropify
                //     } else {
                //         // Initialize Dropify for the first time if not initialized
                //         $(this).dropify({
                //             defaultFile: null // Set defaultFile to null
                //         });
                //     }
                // });
                //
                // setDefaultFile();

                // debugger;
                $firstRow.find('select').each(function () {
                    $(this).select2(); // Reinitialize Select2
                });


                // Update error message spans with unique IDs
                $row.find('.help-inline.text-danger').each(function () {
                    const $this = $(this);
                    const newErrorId = `${$this.attr('id')}-${uniqueSuffix}`;
                    $this.attr('id', newErrorId);
                    $this.attr('aria-describedby', newErrorId);

                });


                // Buat file upload dengan type file_original kalo di add row filenamenya suka kebawa
                $row.find('.file-name-size').each(function () {
                    const $this = $(this);
                    $this.html('Pilih file')
                });
                $row.find('.file-size').each(function () {
                    const $this = $(this);
                    $this.html('')
                });
                $row.find('.file-input').each(function () {
                    const $this = $(this);
                    $this.attr('defaultValue', '')
                    $this.attr('value', '')
                    $this.attr('required',true)
                });

                // Clear values and ensure unique IDs

                // Ensure unique IDs and names for cloned elements
                $row.find('input, textarea, select').each(function () {
                    const $this = $(this);
                    const name = $this.attr('name');
                    const id = $this.attr('id');

                    // set aria-describedby untuk validasi sesuai
                    const newErrorId = `${$this.attr('id')}-${uniqueSuffix}`;
                    $this.attr('aria-describedby', newErrorId);

                    // Clear values
                    $this.val('');

                    // Remove validation error messages
                    $this.removeClass('has-error');
                    $this.siblings().filter('.help-inline').text('').css('display', 'none');
                    // $this.siblings().remove()

                    // Update name attribute to ensure unique names
                    if (name) {
                        const newName = name.replace(/\[\d*\]/, `[${uniqueSuffix}]`);
                        $this.attr('name', newName);
                    }

                    // Update id attribute to ensure unique IDs
                    if (id) {
                        const newId = `${id}-${uniqueSuffix}`;
                        $this.attr('id', newId);
                        $this.siblings(`label[for="${id}"]`).attr('for', newId);
                    }
                });

                // Append the new row
                $section.find('#' + sectionId + '-add-here').before($row);

                // Reinitialize validation rules


                    $(`#home-form`).validate().settings.rules = getValidationRules();
                    $(`#home-form`).validate().settings.messages = getValidationMessages();


                // Initialize Select2 only on new select elements
                // Initialize Select2 only on new select elements
                $row.find('select').each(function () {
                    const $select = $(this);
                    const selectName = $select.attr('name');
                    const masterKey = $select.data('master'); // Assuming you use data-master attribute for this

                    $select.html(elm_choose()); // Set default option

                    // Fetch data from localStorage using the master key and populate the select element
                    const localData = localStorage.getItem(masterKey);
                    if (localData) {
                        const data = JSON.parse(localData);
                        data.forEach(item => {
                            $select.append(new Option(item.name, item.id));
                        });
                    } else {
                        // Optionally, handle fetching from server if data is not available in localStorage
                        fetch(masterKey, customPost())
                            .then(res => res.json())
                            .then(data => {
                                localStorage.setItem(masterKey, JSON.stringify(data));
                                data.forEach(item => {
                                    $select.append(new Option(item.name, item.id));
                                });
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                                // alert('There was an error fetching the data.');
                            });
                    }

                    initializeSelect2($select);
                });

                // Update remove button state after adding a row
                updateRemoveButton(sectionId);
                initializeDatepicker();
                setupFileUploadHandlers();
            }

            // Load data into fields dynamically
            function loadFieldData(masterKey, fieldId, type, fieldObject, section, groupIndexCurrent = null) {
                let localData = localStorage.getItem(masterKey);
                if (localData) {
                    // debugger;
                    populateField(JSON.parse(localData), fieldId, type, fieldObject, section, groupIndexCurrent);
                } else {
                    fetch(masterKey, customPost())
                        .then(res => res.json())
                        .then(data => {
                            localStorage.setItem(masterKey, JSON.stringify(data));
                            populateField(data, fieldId, type, fieldObject, section, groupIndexCurrent);
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            alert('There was an error fetching the data.');
                        });
                }
            }
                                                                                                                                                                                                                                                                                                
            
        });
    </script>
@endsection