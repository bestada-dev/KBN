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
                <a href="{{ url('/superadmin/landing-page')}}" style="display: inline-block; padding: 5px 15px; background-color: #2d89ef; color: white; border-radius: 5px; text-decoration: none; margin-left: 1120px;">
                    Kembali
                </a>                
                

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Home Tab Pane -->
                    <div class="tab-pane fade show active" id="home">
                        @include('pages.admin.halaman-arah.component.content_benefit')
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
    $(document).ready(function() {
    let benefitIndex = {{ count($benefit_list ?? []) }}; // Start with the current count of benefit items.

    // Handle adding new benefit row
    $('#add-benefit-row').on('click', function() {
        // Create a new row
        const newBenefitRow = `
          <div class="col-md-12 dynamic-row mb-3 p-3 rounded border shadow-sm d-flex justify-content-between align-items-center benefit-row" data-index="${benefitIndex}">
            <!-- Benefit ID Hidden Field -->
            <input type="hidden" name="benefit[${benefitIndex}][id]" value="">
        
            <!-- Content (input for the body of the benefit) -->
            <div class="col-md-11 mb-2">
              <label for="content_text_${benefitIndex}" class="form-label">Content Text <b>(*)</b></label>
              <input type="text" class="form-control" name="benefit[${benefitIndex}][content]" id="content_text_${benefitIndex}" placeholder="Write the benefit content">
            </div>
        
            <!-- Remove button -->
            <div class="col-md-1 d-flex justify-content-center align-items-center">
              <button type="button" class="btn btn-danger btn-sm remove-content-row">X</button>
            </div>
          </div>
        `;
        
        // Append the new row to the benefit container
        $('#benefit-container').append(newBenefitRow);
        
        // Increment the index
        benefitIndex++;
    });

    // Handle removing a benefit row
    $(document).on('click', '.remove-content-row', function() {
        $(this).closest('.benefit-row').remove();
    });
});

</script>
@endsection