<?php

use Illuminate\Support\Facades\Route;
use App\LandingPageSetting;
use App\Child;
use Illuminate\Support\Facades\Storage;


Route::GET('/insert-data-dummy', function() {

    try {

    // Delete all folders and files within the `public` disk
    $directories = Storage::disk('public')->directories();

    foreach ($directories as $directory) {
        Storage::disk('public')->deleteDirectory($directory);
    }

    Child::query()->delete();
    LandingPageSetting::query()->delete();

    $home = [
        [
            'section' => 1,
            'title1_en' => 'Shared Space in Your Town',
            'description1_en' => "Find Industrial Estate and Get Your Dream Space",
            'title1_id' => 'Ruang Bersama di Kota Anda',
        'title1' => 'Ruang Bersama di Kota Anda',
            'description1_id' =>"Temukan Kawasan Industri dan Dapatkan Ruang Impian Anda",
            'description1' =>"Temukan Kawasan Industri dan Dapatkan Ruang Impian Anda",
            'photo' => 'assets/for-landing-page/bzLhe8CI0hRdA64R8VyniRzOFEJKleldNB72Lotbgb8lut3TA.jpg',
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 2,
            'title1_en' => 'A Comfortable Place For You',
            'title1_id' => "Tempat Nyaman Untuk Anda",
        'title1' => "Tempat Nyaman Untuk Anda",
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 3,
            'title1_en' => 'Property With VR',
            'description1_en' => "Our designer cleverly made a lot of beautiful property of room that inspire you",
            'title1_id' => "Properti dengan VR",
        'title1' => "Properti dengan VR",
            'description1_id' => "Desainer kami dengan cerdas menciptakan banyak properti ruangan indah yang menginspirasi Anda.",
            'description1' => "Desainer kami dengan cerdas menciptakan banyak properti ruangan indah yang menginspirasi Anda.",
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 4,
            'title1_en' => "Our Popular properties",
            'title1_id' => "Properti Populer Kami",
        'title1' => "Properti Populer Kami",
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 5,
            'title1_en' => 'What do we offer',
            'description1_en' => "We offer a range of innovative designs and tailored solutions that bring your vision to life, creating spaces that inspire and elevate your lifestyle.",
            'title1_id' => "Apa yang Kami Tawarkan",
        'title1' => "Apa yang Kami Tawarkan",
            'description1_id' => "Kami menawarkan berbagai desain inovatif dan solusi yang disesuaikan untuk mewujudkan visi Anda, menciptakan ruang yang menginspirasi dan meningkatkan gaya hidup Anda.",
            'description1' => "Kami menawarkan berbagai desain inovatif dan solusi yang disesuaikan untuk mewujudkan visi Anda, menciptakan ruang yang menginspirasi dan meningkatkan gaya hidup Anda.",
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 6,
            'title1_en' => "Our Tenants",
            'title1_id' => "Penghuni Kami",
        'title1' => "Penghuni Kami",
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 7,
            'title1_id' => 'Hubungi Kami',
        'title1' => 'Hubungi Kami',
            'title1_en' => 'Contact Us',
            'description1_id' => "Hubungi kami hari ini untuk bantuan personal, pertanyaan, atau dukungan. Kami siap membantu dan berharap dapat terhubung dengan Anda!",
            'description1' => "Hubungi kami hari ini untuk bantuan personal, pertanyaan, atau dukungan. Kami siap membantu dan berharap dapat terhubung dengan Anda!",
            'description1_en' =>"Get in touch with us today for personalized assistance, inquiries, or support. We're here to help and look forward to connecting with you!",
            'address' => 'Cakung, Jakarta',
            'phone' => '+1234567890',
            'email' => 'info@kbn.com',
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'section' => 8,
            'whatsapp' => '08986688585',
            'facebook' => 'https://www.facebook.com/Kinarya%20Alih%20Mandiri',
            'instagram' => 'https://www.instagram.com/@kinaryaAlihDayaMandiri',
            'twitter' => 'https://www.x.com/@kinaryaAlihDayaMandiri',
            'tiktok' => 'https://www.tiktok.com/@kinaryaAlihDayaMandiri',
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];

 
    foreach($home as $i) {
        LandingPageSetting::create($i);
    }


    // CHILD

    $home = LandingPageSetting::where('page_type', 'home')->get();
    $idOfSection5 =  $home[4]['id'];
    $idOfSection6 =  $home[5]['id'];

    $childHomeBagian5 = [
            [
                'landing_page_setting_id' => $idOfSection5,
                'image' => 'assets/for-landing-page/chat.png',
                'title1_id' => 'Sewa Kantor',
            'title1' => 'Sewa Kantor',
                'title1_en' => 'Offices Mieten',
                'description1_id' => "Temukan ruang kantor fleksibel dan modern yang sesuai dengan kebutuhan bisnis Anda, dirancang untuk menginspirasi produktivitas dan mendorong pertumbuhan.",
                'description1' => "Temukan ruang kantor fleksibel dan modern yang sesuai dengan kebutuhan bisnis Anda, dirancang untuk menginspirasi produktivitas dan mendorong pertumbuhan.",
                'description1_en' => "Discover flexible and modern office spaces that cater to your business needs, designed to inspire productivity and foster growth.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'landing_page_setting_id' => $idOfSection5,
                'image' => 'assets/for-landing-page/lock.png',
                'title1_id' => 'Relokasi Kantor',
            'title1' => 'Relokasi Kantor',
                'title1_en' => 'Office Relokation',
                'description1_id' => "Kelola relokasi kantor Anda dengan mulus melalui layanan ahli kami, memastikan transisi yang lancar ke ruang kerja yang baru, efisien, dan menginspirasi.",
                'description1' => "Kelola relokasi kantor Anda dengan mulus melalui layanan ahli kami, memastikan transisi yang lancar ke ruang kerja yang baru, efisien, dan menginspirasi.",
                'description1_en' => "Seamlessly manage your office relocation with our expert services, ensuring a smooth transition to a new, efficient, and inspiring workspace.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'landing_page_setting_id' => $idOfSection5,
                'image' => 'assets/for-landing-page/pellan.png',
                'title1_id' => 'Manajemen Kantor',
            'title1' => 'Manajemen Kantor',
                'title1_en' => 'Office Management',
                'description1_id' => "Tingkatkan efisiensi tempat kerja Anda dengan layanan manajemen kantor komprehensif kami, dirancang untuk merampingkan operasi dan menciptakan lingkungan yang produktif serta terorganisir.",
                'description1' => "Tingkatkan efisiensi tempat kerja Anda dengan layanan manajemen kantor komprehensif kami, dirancang untuk merampingkan operasi dan menciptakan lingkungan yang produktif serta terorganisir.",
                'description1_en' => "Enhance your workplace efficiency with our comprehensive office management services, designed to streamline operations and create a productive, organized environment.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'landing_page_setting_id' => $idOfSection5,
                'image' => 'assets/for-landing-page/bulet.png',
                'title1_id' => 'Pembersihan Kantor',
            'title1' => 'Pembersihan Kantor',
                'title1_en' => 'Office Cleaning',
                'description1_id' => "Rasakan ruang kerja yang bersih dan sehat dengan layanan pembersihan kantor profesional kami, yang disesuaikan untuk menjaga kebersihan, kesegaran, dan suasana yang menyambut setiap hari.",
                'description1' => "Rasakan ruang kerja yang bersih dan sehat dengan layanan pembersihan kantor profesional kami, yang disesuaikan untuk menjaga kebersihan, kesegaran, dan suasana yang menyambut setiap hari.",
                'description1_en' => "Experience a spotless and healthy workspace with our professional office cleaning services, tailored to maintain a clean, fresh, and welcoming environment every day.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
    ];

    $childHomeBagian6 = [
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p1.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p2.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p3.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p4.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p1.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p2.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p3.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p4.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p1.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p2.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p3.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'landing_page_setting_id' => $idOfSection6,
            'image' => 'assets/for-landing-page/p4.png',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];



    foreach ($childHomeBagian5 as $i) {
        Child::create($i);
    }
    foreach ($childHomeBagian6 as $i) {
        Child::create($i);
    }

    } catch (\Throwable $th) {
        dd($th);
    }
});