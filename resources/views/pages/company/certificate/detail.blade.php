@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pelatihan')

@section('breadcumbSubtitle', 'Pelatihan Create')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Racing+Sans+One&display=swap" rel="stylesheet">

<style>
    article .TABLE-WITHOUT-SEARCH-BAR{
        width:1024px;
        height:650px;
        overflow-x:auto;
        margin:0 auto;
    }
.racing-sans-one-regular {
  font-family: "Racing Sans One", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.crimson-pro-400 {
  font-family: "Crimson Pro", serif;
  font-optical-sizing: auto;
  font-weight: 400;
  font-style: normal;
}

.crimson-pro-600 {
  font-family: "Crimson Pro", serif;
  font-optical-sizing: auto;
  font-weight: 600;
  font-style: normal;
}

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .actions {
            display: flex;
            align-items: center;
        }
        .actions button {
            background-color: #e0e0e0;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-left: 10px;
        }
        #zoomLevel {
            margin-left: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        #certificateContainer {
            background-color: white;
            width: 1024px;
            height: 764px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            transform-origin: center center;
            transition: transform 0.2s ease;
            background-size: cover;
    background-repeat: no-repeat;
    background-size: 100% 100%;
        }
        .certificate {
            padding: 20px 60px;
            box-sizing: border-box;
            text-align: center;
        }
        .certificate h2 {
            font-size: 48px;
            margin: 0;
        }
        .certificate h3 {
            font-size: 24px;
            margin: 10px 0;
        }
        .certificate p {
            font-size: 18px;
            margin: 5px 0;
        }
        .certificate .name {
            font-size: 48px;
            font-weight: bold;
            text-transform:uppercase;
            color: #d4af37;
            margin: 20px 0;
    text-decoration: underline;
    /* text-underline-offset: 8px; */
        }
        .certificate .details {
            font-size: 18px;
            margin: 20px 0;
        }
        .certificate .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        .certificate .signatures .signature {
            text-align: center;
            display: flex;
    flex-direction: column;
    gap: 3rem;
        }
        .certificate .signatures .signature p:first-child {
            font-weight:500;
        }
        .certificate .logos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .certificate .logos img {
            width: 130px;
        }
        .certificate .qr-code {
            text-align: center;
            margin-top: -20px;
        }
        .certificate .qr-code img {
            width: 100px;
        }
    </style>
<article style="
    /* background: black; */
">
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0 test" style="background: rgb(232, 232, 232); --darkreader-inline-bgimage: initial; --darkreader-inline-bgcolor:#25282a;" data-darkreader-inline-bgimage="" data-darkreader-inline-bgcolor="">
            <div class="HEADER d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3">
                <a href="{{ url()->previous() }}" style="margin-right:auto">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Pratinjau Sertifikat</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
                </div>
                <div class="actions d-flex justify-content-end w-full" style="
    /* width: 60%; */
">
            <button onclick="zoomIn()" class="btn btn-xs bg-transparent p-2 "><i class="fas fa-search-plus"></i></button>
            <button onclick="zoomOut()" class="btn btn-xs bg-transparent p-2"><i class="fas fa-search-minus"></i></button>
            <span id="zoomLevel">90%</span>
            <button onclick="downloadCertificate(this)" class="btn btn-xs bg-info py-1 px-2">Download</button>
        </div>
            </div>
    <div id="certificateContainer" style="transform: scale(0.9);margin: unset;padding: unset;width: 100%;background-image:url({{ asset('assets/cert.jpg') ?? '' }}">
        <div class="certificate">
            <div class="logos">
                <img alt="Kinarya Alihdaya Mandiri Logo" src="{{ asset('assets/for-landing-page/logo.png')}}" width="150">
                <img alt="{{ $data->pelatihan->getVendor->admin_name }}" src="{{ Storage::url($data->pelatihan->getVendor->company_logo) }}" width="">
            </div>
            <h2 class="crimson-pro-600">CERTIFICATE</h2>
            <h3  class="crimson-pro-600">OF PARTICIPATION</h3>
            <p>{{ $data->certificate_number }}</p>
            <p>This certificate is proudly presented to</p>
            <div class="name racing-sans-one-regular">{{ $data->user->admin_name }}</div>
            <div class="details">For participating in the Training of Trainer <br> held by {{ $data->pelatihan->getVendor->admin_name }} on 
            {{ \Carbon\Carbon::parse($data->pelatihan->getVendor->tanggal_mulai)->translatedFormat('j') }} - {{ \Carbon\Carbon::parse($data->pelatihan->getVendor->tanggal_akhir)->translatedFormat('j F Y') }}.</div>
            <div class="signatures">
                <div class="signature crimson-pro-600">
                    <p>{{ $data->director ?? 'ARIADI NURATMOJO' }}</p>
                    <p>Direktur PT. KAM</p>
                </div>
                <div class="signature  crimson-pro-600">
                    <p> {{ $data->pelatihan->getVendor->admin_name }} </p>
                    <p>Trainer</p>
                </div>
            </div>
            <div class="qr-code">
                <!-- <img alt="QR Code" src="{{ asset('assets/qr-cert.png') }}" width="200"> -->
                <img src="{{ route('generateQrCode', $data->id) }}" alt="Certificate QR Code">
            </div>
        </div>
    </div>



    </div></article>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    let scale = 0.9;

    function updateZoomLevel() {
        document.getElementById('zoomLevel').textContent = `${Math.round(scale * 100)}%`;
    }

    function zoomIn() {
        if(scale !== 1) {
        scale += 0.1;
    }
        document.getElementById('certificateContainer').style.transform = `scale(${scale})`;
        updateZoomLevel();
    }

    function zoomOut() {
        if (scale > 0.1) {
            scale -= 0.1;
            document.getElementById('certificateContainer').style.transform = `scale(${scale})`;
            updateZoomLevel();
        }
    }
    function downloadCertificate(e) {
        debugger;
        e.innerHTML = 'Loading ...'
    const certificateContainer = document.getElementById('certificateContainer');

    // Get certificate ID and Name from elements
    const certificateId = document.querySelector(".certificate p").textContent.trim();
    const participantName = document.querySelector(".certificate .name").textContent.trim();

    // Construct filename in the format "CertificateID - ParticipantName.png"
    const filename = `${certificateId} - ${participantName}.png`;

    // Temporarily reset scale for download
    const originalScale = scale;
    scale = 1;
    certificateContainer.style.transform = `scale(${scale})`;

    html2canvas(certificateContainer, {
        scale: 3, // Increase the scale for higher resolution
        useCORS: true, // Allow CORS for external images
        // width: certificateContainer.offsetWidth, // Increase canvas width
        // height: certificateContainer.offsetHeight, // Increase canvas height
        width:1024,
        heigth:650,
        scrollX: 0,
        scrollY: 0,
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = filename;
        link.href = canvas.toDataURL("image/png", 1.0); // Set quality to maximum
        link.click();

        // Revert scale to previous zoom level
        scale = originalScale;
        certificateContainer.style.transform = `scale(${scale})`;

        e.innerHTML = 'Download'
        updateZoomLevel();
    });
}

    // function downloadCertificate(this) {
    //     const certificateContainer = document.getElementById('certificateContainer');

    //     // Get certificate ID and Name from elements
    //     const certificateId = document.querySelector(".certificate p").textContent.trim();
    //     const participantName = document.querySelector(".certificate .name").textContent.trim();

    //     // Construct filename in the format "CertificateID - ParticipantName.png"
    //     const filename = `${certificateId} - ${participantName}.png`;

    //     // Temporarily reset scale for download
    //     const originalScale = scale;
    //     scale = 1;
    //     certificateContainer.style.transform = `scale(${scale})`;

    //     html2canvas(certificateContainer).then(canvas => {
    //         const link = document.createElement('a');
    //         link.download = filename;
    //         link.href = canvas.toDataURL();
    //         link.click();

    //         // Revert scale to previous zoom level
    //         scale = originalScale;
    //         certificateContainer.style.transform = `scale(${scale})`;
    //         updateZoomLevel();
    //     });
    // }
</script>

@endsection
