<?php

namespace App\Http\Controllers;

use App\BenefitList;
use App\BenefitTitle;
use App\LandingPageSetting;
// use App\PelatihanSayaModel;
use App\Child;
use App\Helpers\VisitorHelper;
use App\MessageModel;
use App\PropertyAttachModel;
use App\PropertyModel;
use App\ZoningModel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Validator;
use Storage;
use DB;
use PhpParser\Builder\Property;

class LandingPageController extends Controller
{
    public function a(Request $request)
    {
        $search = $request->query('search', '');
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $sort = $request->query('sort', 'title1');
        $order = $request->query('order', 'asc');

        $query = LandingPageSetting::query();


        if ($request->has('search') && $request->search !== '') {
            $query->where('title1', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('title1') && $request->title1 !== '') {
            $query->where('title1', $request->title1);
        }

        if ($request->has('title2') && $request->title1 !== '') {
            $query->where('title2', $request->title2);
        }

        $query->orderBy($sort, $order);
        $products = $query->paginate($limit);

        return response()->json($products);
    }

    public function adelete($id)
    {
        // Convert the string to an array
        $idArray = explode(',', $id);
        // Fetch records using whereIn
        $a = LandingPageSetting::whereIn('id', $idArray)->delete();
        return response()->json($a);
    }

    public function search(Request $request)
    {
        $type = $request->input('type'); // Ambil kata kunci pencarian * key Status dari Form*
        $zone = $request->input('zone'); // Ambil kata kunci pencarian
        $landArea = $request->input('land-area-range'); // Ambil kata kunci pencarian
        $buildingArea = $request->input('building-area-range'); // Ambil kata kunci pencarian
        $category = $request->input('category'); // Ambil kata kunci pencarian

        // Cari produk berdasarkan title atau deskripsi yang mengandung kata kunci
        // $products = PelatihanSayaModel::where('judul_pelatihan', 'LIKE', "%{$query}%")
        //                     ->orWhere('deskripsi', 'LIKE', "%{$query}%")
        //                     ->get();
        $products = LandingPageSetting::get();

        $query = PropertyModel::query();
        $category = strtolower($category);

        if ($category == "factory" || $category == "warehouse") {
            if ($category == "factory") {
                $query->where('category_id', '=', 2);
            } else {
                // Code for other category
                // $query->where('category_id', '=', Number);
            }

            if ($landArea != null && $buildingArea != null) {
                $landAreaExplode = explode("-", $landArea);
                $buildingAreaExplode = explode("-", $buildingArea);

                $query->whereRaw('CAST(land_area AS UNSIGNED) BETWEEN ? AND ?', [(int) $landAreaExplode[0], (int) $landAreaExplode[1]])
                    ->whereRaw('CAST(building_area as UNSIGNED) BETWEEN ? AND ?', [(int)$buildingAreaExplode[0], (int)$buildingAreaExplode[1]]);
            }
        } elseif ($category === "industial-land" || $category == "container-yard") {
            //Code for other category
            if ($category === "industial-land") {
                // $query->where('category_id', '=', Number);
            } else {
                // $query->where('category_id', '=', Number);
            }
            if ($landArea != null) {
                $landAreaExplode = explode("-", $landArea);
                $query->whereRaw('CAST(land_area AS UNSIGNED) BETWEEN ? AND ?', [(int)$landAreaExplode[0], (int)$landAreaExplode[1]]);
            }
        }

        if ($zone != null && $zone != "All") {
            $query->where('zona_id', '=', $zone);
        }

        if ($type) {
            $query->where('type', '=', $type);
        }
        $query->where('status', '=', 'Available');

        $result = $query->get()->toArray();

        $zones = ZoningModel::all(['id', 'zone_name']);

        // $adasd = json_encode($dad, JSON_PRETTY_PRINT);

        // dd($result);
        // Kirim hasil pencarian dan kata kunci ke view
        return view('pages.landing-page.search-result', get_defined_vars())
            ->with($result)
            ->with($zones->toArray());
    }



    public function index(Request $request)

    {
        // @ms
        $content = LandingPageSetting::where('page_type', 'home')->get();
        $home = $content;
        $idOfSection5 =  $home[4]['id'];
        $idOfSection6 =  $home[5]['id'];

        // kalo mau tau detail sectionnya ,, liat aja web.php URL : insert-data-dummy
        $section1 = $home[0];
        $section2 = $home[1];
        $section3 = $home[2];
        $section4 = $home[3];
        $section5 = $home[4];
        $section6 = $home[5];
        $section7 = $home[6];
        $section8 = $home[7];

        $childBagian5 = Child::where('landing_page_setting_id', $idOfSection5)->get();
        $childBagian6 = Child::where('landing_page_setting_id', $idOfSection6)->get();
        // dd($childBagian6);
        // endms

        $zonningQuery = ZoningModel::all(['id', 'zone_name']);
        $zoning = $zonningQuery->toArray();
        // dd($result);

        $product_property = PropertyModel::all();
        $benefit_title = BenefitTitle::firstOrFail();
        $benefit_list = BenefitList::all();

        $isSearch = false;

        // ================ SEARCH ==========================
        $type = $request->input('type'); // Ambil kata kunci pencarian * key Status dari Form*
        $zone = $request->input('zone'); // Ambil kata kunci pencarian
        $landArea = $request->input('land-area-range'); // Ambil kata kunci pencarian
        $buildingArea = $request->input('building-area-range'); // Ambil kata kunci pencarian
        $category = $request->input('category'); // Ambil kata kunci pencarian

        // Cari produk berdasarkan title atau deskripsi yang mengandung kata kunci
        // $products = LandingPageSetting::get();
        $query = PropertyModel::query();
        $category = strtolower($category);

        if (isset($category) && $category) {
            $isSearch = true;
        }

        if ($category == "factory" || $category == "warehouse") {
            if ($category == "factory") {
                $query->where('category_id', '=', 2);
            } else {
                // Code for other category
                // $query->where('category_id', '=', Number);
            }

            if ($landArea != null && $buildingArea != null) {
                $landAreaExplode = explode("-", $landArea);
                $buildingAreaExplode = explode("-", $buildingArea);

                $query->whereRaw('CAST(land_area AS UNSIGNED) BETWEEN ? AND ?', [(int) $landAreaExplode[0], (int) $landAreaExplode[1]])
                    ->whereRaw('CAST(building_area as UNSIGNED) BETWEEN ? AND ?', [(int)$buildingAreaExplode[0], (int)$buildingAreaExplode[1]]);
            }
        } elseif ($category === "industial-land" || $category == "container-yard") {
            //Code for other category
            if ($category === "industial-land") {
                // $query->where('category_id', '=', Number);
            } else {
                // $query->where('category_id', '=', Number);
            }
            if ($landArea != null) {
                $landAreaExplode = explode("-", $landArea);
                $query->whereRaw('CAST(land_area AS UNSIGNED) BETWEEN ? AND ?', [(int)$landAreaExplode[0], (int)$landAreaExplode[1]]);
            }
        }

        if ($zone != null && $zone != "All") {
            $query->where('zona_id', '=', $zone);
        }

        if ($type) {
            $query->where('type', '=', $type);
        }
        $query->where('status', '=', 'Available');

        $dataTosent = array_merge(get_defined_vars(), ['zoning' => $zoning, 'isSearch' => $isSearch]);

        if ($isSearch) {
            $resultSearch = $query->get()->toArray();
            $dataTosent['searchResult'] = $resultSearch;
        }

        $benefit_title = BenefitTitle::firstOrFail();
        $benefit_list = BenefitList::all();

        // dd($dataTosent);
        // return view('pages.landing-page.home', $dataTosent);
        return view('pages.landing-page.home', get_defined_vars());
        // ->with($resultSearch)
        // ->with('zoning', $zoning)
        // ->with('search', $isSearch);
        // return view('pages.landing-page.home.index', get_defined_vars())->with('zoning', $result);
    }

    public function post_message(Request $request)
    {
        // Validasi input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string|max:15',
            'message' => 'required|string|max:500',
        ]);
        // Simpan data ke database
        MessageModel::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'message' => $request->message,
        ]);
        // Redirect dengan pesan sukses
        return redirect()->route('home')->with('success', 'Message sent successfully');
    }


    public function productDetail($id)
    {
        // Ambil data produk berdasarkan ID

        // Visitor helper untuk actifity;
        VisitorHelper::add($id);

        $property = PropertyModel::with(['getAttachment', 'getFacility', 'getZoning.strategicLocation', 'getCategory'])
            ->find($id)->toArray();

        $zones = ZoningModel::all(['id', 'zone_name'])->toArray();
        // dd($property);
        // Kirim data produk ke view;
        return view('pages.landing-page.product-detail', get_defined_vars())->with('property', $property)->with($zones);
    }


    public function searchResult()

    {

        return view('pages.landing-page.search-result');
    }


    /**
     * Handle file upload.
     */
    private function handleFileUpload(Request $request, $inputName, $oldFilePath = null)
    {
        if ($request->hasFile($inputName)) {
            // Delete the old file if a new one is uploaded
            if ($oldFilePath) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldFilePath));
            }

            // Store the new file
            $filePath = _uploadFileWithStorage($request->file($inputName), 'home_photos');
            return $filePath;
        }
        // Return the old file path if no new file is uploaded
        return $oldFilePath;
    }

    // SAVE

    public function saveHome(Request $request)
    {
        // dd($request->all());
        // Define validation rules
        $rules = [
            'Section_1_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Section_1_title1_id' => 'required|string',
            'Section_1_title1_en' => 'required|string',
            'Section_1_description1_id' => 'required|string',
            'Section_1_description1_en' => 'required|string',
            // 'Section_1_photo_old' => 'nullable|string',
            // "Section_2_id" => "10"
            'Section_2_id' => 'nullable|integer|exists:landing_page_settings,id',
            "Section_2_title1_id" => 'required|string',
            "Section_2_title1_en" => 'required|string',

            'Section_3_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Section_3_title1_id' => 'required|string',
            'Section_3_title1_en' => 'required|string',
            'Section_3_description1_id' => 'required|string',
            'Section_3_description1_en' => 'required|string',

            'Section_4_id' => 'nullable|integer|exists:landing_page_settings,id',
            "Section_4_title1_id" => 'required|string',
            "Section_4_title1_en" => 'required|string',

            'Section_5_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Section_5_title1_id' => 'required|string',
            'Section_5_title1_en' => 'required|string',
            'Section_5_description1_id' => 'required|string',
            'Section_5_description1_en' => 'required|string',

            "Section_7_id" => 'nullable|integer|exists:landing_page_settings,id',
            'Section_7_title1_id' => 'required|string',
            'Section_7_title1_en' => 'required|string',
            'Section_7_description1_id' => 'required|string',
            'Section_7_description1_en' => 'required|string',
            "Section_7_address" => 'required|string',
            "Section_7_phone" => 'required|string',
            "Section_7_email" => 'required|string',

            "Section_8_id" => 'nullable|integer|exists:landing_page_settings,id',
            "Section_8_whatsapp" => 'required|string',
            "Section_8_instagram" => 'required|string',
            "Section_8_tiktok" => 'required|string',
            "Section_8_facebook" => 'required|string',
            "Section_8_twitter" => 'required|string',

            // 'Home_Bagian_1_title1' => 'required|string',
            // 'Home_Bagian_1_description1' => 'required|string',
            // // 'Home_Bagian_1_photo' => 'nullable|image',
            // 'Home_Bagian_2_title1' => 'required|string',
            // // 'Home_Bagian_2_description1' => 'required|string',
            // 'Home_Bagian_2_Poin_poin_title1.*' => 'required|string',
            // 'Home_Bagian_2_Poin_poin_description1.*' => 'required|string',
            // // 'Home_Bagian_2_Poin_poin_photo.*' => 'nullable|image',
            // 'Home_Bagian_3_title1' => 'required|string',
            // 'Home_Bagian_3_description1' => 'required|string',
            // 'Home_Bagian_4_title1' => 'required|string',
            // 'Home_Bagian_4_title2' => 'required|string',
            // 'Home_Bagian_4_description1' => 'required|string',
            // 'Home_Bagian_4_Poin_poin_title1.*' => 'required|string',
            // 'Home_Bagian_4_Poin_poin_description1.*' => 'required|string',
            // 'Home_Bagian_5_title1' => 'required|string',
            // // 'Home_Bagian_5_description1' => 'required|string',
            // 'Home_Bagian_6_title1' => 'required|string',
            // 'Home_Bagian_6_description1' => 'required|string',
            // 'Home_Bagian_7_title1' => 'required|string',
            // 'Home_Bagian_7_description1' => 'required|string',
            // 'Home_Bagian_8_title1' => 'required|string',
            // // 'Home_Bagian_8_logo.*' => 'nullable|image',
            // 'Home_Bagian_9_whatsapp' => 'required|string',
            // 'Home_Bagian_9_instagram' => 'required|string',
            // 'Home_Bagian_9_tiktok' => 'required|string',
            // 'Home_Bagian_9_facebook' => 'required|string',
            // 'Home_Bagian_9_twitter' => 'required|string',
        ];

        try {
            // Begin a database transaction
            DB::beginTransaction();

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status' => false
                ], 400);
            }

            // dd($request->all());
            // --- Section 1 ---
            $section1Data = $this->handleSection(
                $request,
                1,
                [
                    'title1_id' => $request->input('Section_1_title1_id'),
                    'title1_en' => $request->input('Section_1_title1_en'),
                    'description1_id' => $request->input('Section_1_description1_id'),
                    'description1_en' => $request->input('Section_1_description1_en'),
                    'photo' => $this->handleFileUpload($request, 'Section_1_photo', $request->input('Section_1_photo_old')),
                ]
            );

            // dd($section1Data);

            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_1_id')], $section1Data);

            // --- Section 2 ---
            $section2Data = $this->handleSection(
                $request,
                2,
                [
                    'title1_id' => $request->input('Section_2_title1_id'),
                    'title1_en' => $request->input('Section_2_title1_en'),
                ]
            );
            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_2_id')], $section2Data);


            // --- Section 3 ---
            $section3Data = $this->handleSection(
                $request,
                3,
                [
                    'title1_id' => $request->input('Section_3_title1_id'),
                    'title1_en' => $request->input('Section_3_title1_en'),
                    'description1_id' => $request->input('Section_3_description1_id'),
                    'description1_en' => $request->input('Section_3_description1_en'),
                ]
            );

            // dd($section3Data);
            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_3_id')], $section3Data);


            // --- Section 4 ---
            $section4Data = $this->handleSection(
                $request,
                4,
                [
                    'title1_id' => $request->input('Section_4_title1_id'),
                    'title1_en' => $request->input('Section_4_title1_en'),
                ]
            );
            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_4_id')], $section4Data);


            // --- Section 5 ---
            $section5Data = $this->handleSection(
                $request,
                5,
                [
                    'title1_id' => $request->input('Section_5_title1_id'),
                    'title1_en' => $request->input('Section_5_title1_en'),
                    'description1_id' => $request->input('Section_5_description1_id'),
                    'description1_en' => $request->input('Section_5_description1_en'),
                ]
            );

            $landingPageSetting5 = LandingPageSetting::updateOrCreate(['id' => $request->input('Section_5_id')], $section5Data);


            // --- Handling multiple inputs for Section 2 "Poin Poin" --- 
            $poinPoinTitlesId = $request->input('Section_5_Poin_poin_title1_id', []);
            $poinPoinTitlesEn = $request->input('Section_5_Poin_poin_title1_en', []);
            $poinPoinDescriptionsId = $request->input('Section_5_Poin_poin_description1_id', []);
            $poinPoinDescriptionsEn = $request->input('Section_5_Poin_poin_description1_en', []);
            $poinPoinPhotos = $request->file('Section_5_Poin_poin_photo', []);
            $poinPoinIds = $request->input('Section_5_Poin_poin_id', []);

            // NEW
            // Get existing records
            $existingRecords = Child::where('landing_page_setting_id', $landingPageSetting5->id)
                ->get();

            // Delete records that are not in the new submission
            // Delete old records first
            if (!empty($poinPoinIds)) {
                $recordsToDelete = Child::where('landing_page_setting_id', $landingPageSetting5->id)
                    ->whereNotIn('id', array_filter($poinPoinIds));

                foreach ($recordsToDelete->get() as $record) {
                    if ($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }
                }

                $recordsToDelete->delete();
            } else {
                // If no IDs provided, delete all existing records and their files
                foreach ($existingRecords as $record) {
                    if ($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }
                }
                Child::where('landing_page_setting_id', $landingPageSetting5->id)->delete();
            }

            // Process each title and its corresponding data
            foreach ($poinPoinTitlesId as $key => $title) {
                $poinData = [
                    'landing_page_setting_id' => $landingPageSetting5->id,
                    'title1_id' => $title,
                    'title1_en' => $poinPoinTitlesEn[$key] ?? '',
                    'description1_id' => $poinPoinDescriptionsId[$key] ?? '',
                    'description1_en' => $poinPoinDescriptionsEn[$key] ?? '',
                ];

                // Handle file upload if exists
                if (isset($poinPoinPhotos[$key]) && $poinPoinPhotos[$key] instanceof UploadedFile) {
                    // dd($poinPoinPhotos[$key]);
                    // Delete old file if exists
                    if (isset($poinPoinIds[$key])) {
                        $oldRecord = Child::find($key);
                        if ($oldRecord && $oldRecord->image) {
                            Storage::disk('public')->delete($oldRecord->image);
                        }
                    }

                    // Upload new file
                    $filePath = $this->uploadFile($poinPoinPhotos[$key], 'home_photos');
                    $poinData['image'] = $filePath;
                }
                // dd($poinData);

                Child::updateOrCreate(
                    ['id' => $key ?? null],
                    $poinData
                );
            }


            // --- Section 6 ---
            $section6Data = $this->handleSection(
                $request,
                6,
                [
                    'title1_id' => $request->input('Section_6_title1_id'),
                    'title1_en' => $request->input('Section_6_title1_en'),
                ]
            );
            $landingPageSetting6 = LandingPageSetting::updateOrCreate(['id' => $request->input('Section_6_id')], $section6Data);


            // // --- Handling multiple inputs for Section 6 "Logo" --- 
            $logos = $request->file('Section_6_Logo_logo', []);
            $poinPoinIds = $request->input('Section_6_Logo_id', []);


            // First, delete all existing records that are not in the new submission
            if (!empty($poinPoinIds)) {
                Child::where('landing_page_setting_id', $landingPageSetting6->id)
                    ->whereNotIn('id', array_filter($poinPoinIds))
                    ->delete();
            } else {
                // If no IDs provided, delete all existing records
                Child::where('landing_page_setting_id', $landingPageSetting6->id)->delete();
            }


            // Then create or update new records
            foreach ($logos as $key => $logo) {
                $logoData = [
                    'landing_page_setting_id' => $landingPageSetting6->id,
                ];

                if ($logo instanceof UploadedFile) {
                    // Delete old file if exists
                    if (isset($poinPoinIds[$key])) {
                        $oldRecord = Child::find($key);
                        // dd($key);
                        if ($oldRecord && $oldRecord->image) {
                            Storage::disk('public')->delete($oldRecord->image);
                        }
                    }

                    // Upload new file
                    $filePath = $this->uploadFile($logo, 'home_photos');
                    $logoData['image'] = $filePath;

                    // Create or update record
                    Child::updateOrCreate(
                        ['id' => $key ?? null],
                        $logoData
                    );
                }
            }

            // --- Section 7 ---
            $section7Data = $this->handleSection(
                $request,
                7,
                [
                    'title1_id' => $request->input('Section_7_title1_id'),
                    'title1_en' => $request->input('Section_7_title1_en'),
                    'description1_id' => $request->input('Section_7_description1_id'),
                    'description1_en' => $request->input('Section_7_description1_en'),
                    'address' => $request->input('Section_7_address'),
                    'phone' => $request->input('Section_7_phone'),
                    'email' => $request->input('Section_7_email'),
                ]
            );
            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_7_id')], $section7Data);


            // --- Section 8 ---
            $section8Data = $this->handleSection(
                $request,
                8,
                [
                    'whatsapp' => $request->input('Section_8_whatsapp'),
                    'instagram' => $request->input('Section_8_instagram'),
                    'tiktok' => $request->input('Section_8_tiktok'),
                    'facebook' => $request->input('Section_8_facebook'),
                    'twitter' => $request->input('Section_8_twitter'),
                ]
            );


            LandingPageSetting::updateOrCreate(['id' => $request->input('Section_8_id')], $section8Data);



            // // --- Handling multiple inputs for Section 2 "Poin Poin" --- 
            // $poinPoinTitles = $request->input('Home_Bagian_2_Poin_poin_title1', []);
            // $poinPoinDescriptions = $request->input('Home_Bagian_2_Poin_poin_description1', []);
            // $poinPoinPhotos = $request->file('Home_Bagian_2_Poin_poin_photo', []);
            // $poinPoinIds = $request->input('Home_Bagian_2_Poin_poin_id', []);

            // // OLD
            // // for ($i = 0; $i < count($poinPoinTitles); $i++) {
            // //     $poinData = [
            // //         'landing_page_setting_id' => $landingPageSetting2->id,
            // //         'title1' => $poinPoinTitles[$i],
            // //         'description1' => $poinPoinDescriptions[$i],
            // //     ];

            // //     if (isset($poinPoinPhotos[$i]) && $poinPoinPhotos[$i]->isValid()) {
            // //         $filePath = $this->uploadFile($poinPoinPhotos[$i], 'home_photos');
            // //         $poinData['image'] = $filePath;
            // //     }

            // //     Child::updateOrCreate(['id' => $poinPoinIds[$i] ?? null], $poinData);
            // // }

            // // NEW
            // // Get existing records
            // $existingRecords = Child::where('landing_page_setting_id', $landingPageSetting2->id)
            //     ->get();

            // // Delete records that are not in the new submission
            // // Delete old records first
            // if (!empty($poinPoinIds)) {
            //     $recordsToDelete = Child::where('landing_page_setting_id', $landingPageSetting2->id)
            //         ->whereNotIn('id', array_filter($poinPoinIds));

            //     foreach ($recordsToDelete->get() as $record) {
            //         if ($record->image) {
            //             Storage::disk('public')->delete($record->image);
            //         }
            //     }

            //     $recordsToDelete->delete();
            // } else {
            //     // If no IDs provided, delete all existing records and their files
            //     foreach ($existingRecords as $record) {
            //         if ($record->image) {
            //             Storage::disk('public')->delete($record->image);
            //         }
            //     }
            //     Child::where('landing_page_setting_id', $landingPageSetting2->id)->delete();
            // }

            // // Process each title and its corresponding data
            // foreach ($poinPoinTitles as $key => $title) {
            //     $poinData = [
            //         'landing_page_setting_id' => $landingPageSetting2->id,
            //         'title1' => $title,
            //         'description1' => $poinPoinDescriptions[$key] ?? '',
            //     ];

            //     // Handle file upload if exists
            //     if (isset($poinPoinPhotos[$key]) && $poinPoinPhotos[$key] instanceof UploadedFile) {
            //         // dd($poinPoinPhotos[$key]);
            //         // Delete old file if exists
            //         if (isset($poinPoinIds[$key])) {
            //             $oldRecord = Child::find($key);
            //             if ($oldRecord && $oldRecord->image) {
            //                 Storage::disk('public')->delete($oldRecord->image);
            //             }
            //         }

            //         // Upload new file
            //         $filePath = $this->uploadFile($poinPoinPhotos[$key], 'home_photos');
            //         $poinData['image'] = $filePath;
            //     }
            //     // dd($poinData);

            //     Child::updateOrCreate(
            //         ['id' => $key ?? null],
            //         $poinData
            //     );
            // }


            // // --- Section 3 ---
            // $section3Data = $this->handleSection(
            //     $request,
            //     3,
            //     'Home_Bagian_3_id',
            //     'Home_Bagian_3_title1',
            //     'Home_Bagian_3_description1',
            // );
            // $landingPageSetting3 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_3_id')], $section3Data);

            // // --- Section 4 ---
            // $section4Data = $this->handleSection(
            //     $request,
            //     4,
            //     'Home_Bagian_4_id',
            //     'Home_Bagian_4_title1',
            //     'Home_Bagian_4_title2',
            //     'Home_Bagian_4_description1',
            // );
            // $landingPageSetting4 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_4_id')], $section4Data);

            // // --- Handling multiple inputs for Section 4 "Poin Poin" --- 
            // $poinPoinTitles = $request->input('Home_Bagian_4_Poin_poin_title1', []);
            // $poinPoinDescriptions = $request->input('Home_Bagian_4_Poin_poin_description1', []);
            // $poinPoinIds = $request->input('Home_Bagian_4_Poin_poin_id', []);


            // // OLD
            // // for ($i = 0; $i < count($poinPoinTitles); $i++) {
            // //     $poinData = [
            // //         'landing_page_setting_id' => $landingPageSetting4->id,
            // //         'title1' => $poinPoinTitles[$i],
            // //         'description1' => $poinPoinDescriptions[$i],
            // //     ];


            // //     Child::updateOrCreate(['id' => $poinPoinIds[$i] ?? null], $poinData);
            // // }

            // // NEW
            // // Get all existing records for this section
            // $existingRecords = Child::where('landing_page_setting_id', $landingPageSetting4->id)
            //     ->get();

            // // Delete records that are not in the new submission
            // if (!empty($poinPoinIds)) {
            //     Child::where('landing_page_setting_id', $landingPageSetting4->id)
            //         ->whereNotIn('id', array_filter($poinPoinIds))
            //         ->delete();
            // } else {
            //     // If no IDs provided, delete all existing records
            //     Child::where('landing_page_setting_id', $landingPageSetting4->id)
            //         ->delete();
            // }

            // // Process each title and its corresponding data
            // foreach ($poinPoinTitles as $key => $title) {
            //     // Skip if title is empty
            //     if (empty($title)) {
            //         continue;
            //     }
            //     $poinData = [
            //         'landing_page_setting_id' => $landingPageSetting4->id,
            //         'title1' => $title,
            //         'description1' => $poinPoinDescriptions[$key],
            //     ];

            //     Child::updateOrCreate(
            //         ['id' => $key ?? null],
            //         $poinData
            //     );
            // }


            // // --- Section 5 ---
            // $section5Data = $this->handleSection(
            //     $request,
            //     5,
            //     'Home_Bagian_5_id',
            //     'Home_Bagian_5_title1',
            //     'Home_Bagian_5_description1',
            // );
            // $landingPageSetting5 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_5_id')], $section5Data);

            // // --- Section 6 ---
            // $section6Data = $this->handleSection(
            //     $request,
            //     6,
            //     'Home_Bagian_6_id',
            //     'Home_Bagian_6_title1',
            //     'Home_Bagian_6_description1',
            // );
            // $landingPageSetting6 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_6_id')], $section6Data);

            // // --- Section 7 ---
            // $section7Data = $this->handleSection(
            //     $request,
            //     7,
            //     'Home_Bagian_7_id',
            //     'Home_Bagian_7_title1',
            //     'Home_Bagian_7_description1',
            // );
            // $landingPageSetting7 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_7_id')], $section7Data);

            // // // --- Section 8 ---
            // $section8Data = $this->handleSection(
            //     $request,
            //     8,
            //     'Home_Bagian_8_id',
            //     'Home_Bagian_8_title1',
            // );

            // // unset($section8Data['description1']);
            // // dd($section8Data);
            // $landingPageSetting8 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_8_id')], $section8Data);

            // // // --- Handling multiple inputs for Section 8 "Logo" --- 
            // $logos = $request->file('Home_Bagian_8_Logo_logo', []);
            // $poinPoinIds = $request->input('Home_Bagian_8_Logo_id', []);


            // // First, delete all existing records that are not in the new submission
            // if (!empty($poinPoinIds)) {
            //     Child::where('landing_page_setting_id', $landingPageSetting8->id)
            //         ->whereNotIn('id', array_filter($poinPoinIds))
            //         ->delete();
            // } else {
            //     // If no IDs provided, delete all existing records
            //     Child::where('landing_page_setting_id', $landingPageSetting8->id)->delete();
            // }

            // // Then create or update new records
            // foreach ($logos as $key => $logo) {
            //     $logoData = [
            //         'landing_page_setting_id' => $landingPageSetting8->id,
            //     ];

            //     if ($logo instanceof UploadedFile) {
            //         // Delete old file if exists
            //         if (isset($poinPoinIds[$key])) {
            //             $oldRecord = Child::find($key);
            //             // dd($key);
            //             if ($oldRecord && $oldRecord->image) {
            //                 Storage::disk('public')->delete($oldRecord->image);
            //             }
            //         }

            //         // Upload new file
            //         $filePath = $this->uploadFile($logo, 'home_photos');
            //         $logoData['image'] = $filePath;

            //         // Create or update record
            //         Child::updateOrCreate(
            //             ['id' => $key ?? null],
            //             $logoData
            //         );
            //     }
            // }
            // //    OLD
            // // foreach ($logos as $key => $logo) {
            // //     $logoData = [
            // //         'landing_page_setting_id' => $landingPageSetting8->id,
            // //     ];

            // //     // Check if the item is an instance of UploadedFile
            // //     if ($logo instanceof UploadedFile) {
            // //         $filePath = $this->uploadFile($logo, 'home_photos');
            // //         $logoData['image'] = $filePath;

            // //         // Use the key from the foreach loop to update or create
            // //         Child::updateOrCreate(['id' => $key ?? null], $logoData);
            // //     }
            // // }


            // // --- Section 9 ---
            // $section9Data = [
            //     'section' => 9,
            //     'whatsapp' => $request->input('Home_Bagian_9_whatsapp'),
            //     'instagram' => $request->input('Home_Bagian_9_instagram'),
            //     'tiktok' => $request->input('Home_Bagian_9_tiktok'),
            //     'facebook' => $request->input('Home_Bagian_9_facebook'),
            //     'twitter' => $request->input('Home_Bagian_9_twitter'),
            //     'page_type' => 'home',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ];

            // // dd($request->input('Home_Bagian_9_id'));
            // $landingPageSetting9 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_9_id')], $section9Data);

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'status' => true
            ], 200);
        } catch (Exception $e) {
            // Rollback the transaction if any operation fails
            DB::rollBack();
            return response()->json([
                'message' => ' An error occurred: ' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    private function handleSection(Request $request, int $section, $data)
    {
        $sectionData = [
            'section' => $section,
            'title1_id' => $data['title1_id'] ?? '',
            'title1_en' => $data['title1_en'] ?? '',
            'description1_id' => $data['description1_id'] ?? '',
            'description1_en' => $data['description1_en'] ?? '',
            'photo' => $data['photo'] ?? '',
            'page_type' => 'home',

            'address' => $data['address'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'whatsapp' => $data['whatsapp'] ?? '',
            'tiktok' => $data['tiktok'] ?? '',
            'facebook' => $data['facebook'] ?? '',
            'instagram' => $data['instagram'] ?? '',
            'twitter' => $data['twitter'] ?? '',


            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $sectionData;
    }

    private function uploadFile($file, $directory)
    {
        $filename = uniqid() . '.' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $directory, $filename);
        return $directory . '/' . $filename;
    }
    public function saveAboutUs(Request $request)
    {
        // Define validation rules
        $rules = [
            'About_Us_Bagian_1_title1' => 'required|string',
            'About_Us_Bagian_1_description1' => 'required|string',
            'About_Us_Bagian_1_photo' => 'nullable', // 2MB Max
            'About_Us_Bagian_1_video' => 'nullable', // 20MB Max
            'About_Us_Bagian_2_title1' => 'nullable|string',
            'About_Us_Bagian_2_title2' => 'nullable|string',
            'About_Us_Bagian_2_description1' => 'nullable|string',
            'About_Us_Bagian_2_photo' => 'nullable', // 2MB Max
            'About_Us_Bagian_3_title1' => 'nullable|string',
            'About_Us_Bagian_3_title2' => 'nullable|string',
            'About_Us_Bagian_3_description1' => 'nullable|string',
            'About_Us_Bagian_3_photo' => 'nullable', // 2MB Max
            'About_Us_Bagian_3_video' => 'nullable', // 20MB Max
        ];

        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            // Prepare data for section 1
            $section1Data = [
                'section' => 1,
                'title1' => $request->input('About_Us_Bagian_1_title1'),
                'description1' => $request->input('About_Us_Bagian_1_description1'),
                'page_type' => 'about-us',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // dd($request->all());
            // Handle file uploads for section 1
            if ($request->hasFile('About_Us_Bagian_1_photo')) {
                // dd($request->input('About_Us_Bagian_1_photo'));
                // Delete old photo if it exists
                if ($request->input('About_Us_Bagian_1_photo_old')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $request->input('About_Us_Bagian_1_photo_old')));
                }
                // Store new photo
                $filePath = _uploadFileWithStorage($request->file('About_Us_Bagian_1_photo'), 'about_us_photos');
                $section1Data['photo'] = $filePath; // Add photo path to the data
            }

            if ($request->hasFile('About_Us_Bagian_1_video')) {
                // Delete old video if it exists
                if ($request->input('About_Us_Bagian_1_video_old')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $request->input('About_Us_Bagian_1_video_old')));
                }
                // Store new video
                $filePath = _uploadFileWithStorage($request->file('About_Us_Bagian_1_video'), 'about_us_videos');
                $section1Data['video'] = $filePath; // Add video path to the data
            }

            // Use updateOrCreate for section 1
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('About_Us_Bagian_1_id')], // Identifier for updating
                $section1Data // Data to update or create
            );

            // Prepare data for section 2
            $section2Data = [
                'section' => 2,
                'title1' => $request->input('About_Us_Bagian_2_title1'),
                'title2' => $request->input('About_Us_Bagian_2_title2'),
                'description1' => $request->input('About_Us_Bagian_2_description1'),
                'page_type' => 'about-us',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // dd($section2Data);

            // Handle file uploads for section 2
            if ($request->hasFile('About_Us_Bagian_2_photo')) {
                // Delete old photo if it exists
                if ($request->input('About_Us_Bagian_2_photo_old')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $request->input('About_Us_Bagian_2_photo_old')));
                }
                // Store new photo
                $filePath = _uploadFileWithStorage($request->file('About_Us_Bagian_2_photo'), 'about_us_photos');
                $section2Data['photo'] = $filePath; // Add photo path to the data
            }

            // Use updateOrCreate for section 2
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('About_Us_Bagian_2_id')], // Identifier for updating
                $section2Data // Data to update or create
            );

            // Use updateOrCreate for section 2
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('About_Us_Bagian_2_id')], // Identifier for updating
                $section2Data // Data to update or create
            );

            // Prepare data for section 3
            $section3Data = [
                'section' => 3,
                'title1' => $request->input('About_Us_Bagian_3_title1'),
                'title2' => $request->input('About_Us_Bagian_3_title2'),
                'description1' => $request->input('About_Us_Bagian_3_description1'),
                'page_type' => 'about-us',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Handle file uploads for section 3
            if ($request->hasFile('About_Us_Bagian_3_photo')) {
                // Delete old photo if it exists
                if ($request->input('About_Us_Bagian_3_photo_old')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $request->input('About_Us_Bagian_3_photo_old')));
                }
                // Store new photo
                $filePath = _uploadFileWithStorage($request->file('About_Us_Bagian_3_photo'), 'about_us_photos');
                $section3Data['photo'] = $filePath; // Add photo path to the data
            }

            if ($request->hasFile('About_Us_Bagian_3_video')) {
                // Delete old video if it exists
                if ($request->input('About_Us_Bagian_3_video_old')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $request->input('About_Us_Bagian_3_video_old')));
                }
                // Store new video
                $filePath = _uploadFileWithStorage($request->file('About_Us_Bagian_3_video'), 'about_us_videos');
                $section3Data['video'] = $filePath; // Add video path to the data
            }

            // Use updateOrCreate for section 3
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('About_Us_Bagian_3_id')], // Identifier for updating
                $section3Data // Data to update or create
            );

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'status'  => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function saveContactUs(Request $request)
    {
        // Define validation rules
        $rules = [
            'Contact_Us_Bagian_1_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Contact_Us_Bagian_1_title1' => 'required|string',
            'Contact_Us_Bagian_1_title2' => 'nullable|string',
            'Contact_Us_Bagian_1_description1' => 'required|string',
            'Contact_Us_Bagian_1_address' => 'required|string',
            'Contact_Us_Bagian_1_phone' => 'required|string',
            'Contact_Us_Bagian_1_email' => 'required|email',
            'Contact_Us_Bagian_2_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Contact_Us_Bagian_2_title1' => 'nullable|string',
            'Contact_Us_Bagian_2_description1' => 'nullable|string',
        ];

        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            // Prepare data for section 1
            $section1Data = [
                'section' => 1,
                'title1' => $request->input('Contact_Us_Bagian_1_title1'),
                'title2' => $request->input('Contact_Us_Bagian_1_title2'),
                'description1' => $request->input('Contact_Us_Bagian_1_description1'),
                'address' => $request->input('Contact_Us_Bagian_1_address'),
                'phone' => $request->input('Contact_Us_Bagian_1_phone'),
                'email' => $request->input('Contact_Us_Bagian_1_email'),
                'page_type' => 'contact-us',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 1
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Contact_Us_Bagian_1_id')], // Identifier for updating
                $section1Data // Data to update or create
            );

            // Prepare data for section 2
            $section2Data = [
                'section' => 2,
                'title1' => $request->input('Contact_Us_Bagian_2_title1'),
                'description1' => $request->input('Contact_Us_Bagian_2_description1'),
                'page_type' => 'contact-us',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 2
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Contact_Us_Bagian_2_id')], // Identifier for updating
                $section2Data // Data to update or create
            );

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'status'  => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function saveProductPengembanganDiri(Request $request)
    {
        // Define validation rules
        $rules = [
            'Pengembangan_Diri_Bagian_1_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pengembangan_Diri_Bagian_1_title1' => 'required|string',
            'Pengembangan_Diri_Bagian_1_description1' => 'required|string',
            'Pengembangan_Diri_Bagian_1_title2' => 'nullable|string',
            'Pengembangan_Diri_Bagian_1_description2' => 'nullable|string',
            'Pengembangan_Diri_Bagian_2_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pengembangan_Diri_Bagian_2_title1' => 'nullable|string',
            'Pengembangan_Diri_Bagian_2_description1' => 'nullable|string',
            'Pengembangan_Diri_Bagian_3_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pengembangan_Diri_Bagian_3_title1' => 'nullable|string',
            'Pengembangan_Diri_Bagian_3_description1' => 'nullable|string',
        ];

        // dd($request->all());
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            // Prepare data for section 1
            $section1Data = [
                'section' => 1,
                'title1' => $request->input('Pengembangan_Diri_Bagian_1_title1'),
                'description1' => $request->input('Pengembangan_Diri_Bagian_1_description1'),
                'title2' => $request->input('Pengembangan_Diri_Bagian_1_title2'),
                'description2' => $request->input('Pengembangan_Diri_Bagian_1_description2'),
                'page_type' => 'product-pengembangan-diri',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 1
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pengembangan_Diri_Bagian_1_id')], // Identifier for updating
                $section1Data // Data to update or create
            );

            // Prepare data for section 2
            $section2Data = [
                'section' => 2,
                'title1' => $request->input('Pengembangan_Diri_Bagian_2_title1'),
                'page_type' => 'product-pengembangan-diri',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 2
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pengembangan_Diri_Bagian_2_id')], // Identifier for updating
                $section2Data // Data to update or create
            );

            // Prepare data for section 3
            $section3Data = [
                'section' => 3,
                'title1' => $request->input('Pengembangan_Diri_Bagian_3_title1'),
                'description1' => $request->input('Pengembangan_Diri_Bagian_3_description1'),
                'page_type' => 'product-pengembangan-diri',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 3
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pengembangan_Diri_Bagian_3_id')], // Identifier for updating
                $section3Data // Data to update or create
            );


            return response()->json([
                'message' => 'Data berhasil disimpan',
                'status'  => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function saveProductPelatihanPublic(Request $request)
    {
        // Define validation rules
        $rules = [
            'Pelatihan_Publik_Bagian_1_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pelatihan_Publik_Bagian_1_title1' => 'required|string',
            'Pelatihan_Publik_Bagian_1_description1' => 'required|string',
            'Pelatihan_Publik_Bagian_1_title2' => 'nullable|string',
            'Pelatihan_Publik_Bagian_1_description2' => 'nullable|string',
            'Pelatihan_Publik_Bagian_2_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pelatihan_Publik_Bagian_2_title1' => 'nullable|string',
            'Pelatihan_Publik_Bagian_2_description1' => 'nullable|string',
            'Pelatihan_Publik_Bagian_3_id' => 'nullable|integer|exists:landing_page_settings,id',
            'Pelatihan_Publik_Bagian_3_title1' => 'nullable|string',
            'Pelatihan_Publik_Bagian_3_description1' => 'nullable|string',
        ];

        // dd($request->all());
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            // Prepare data for section 1
            $section1Data = [
                'section' => 1,
                'title1' => $request->input('Pelatihan_Publik_Bagian_1_title1'),
                'description1' => $request->input('Pelatihan_Publik_Bagian_1_description1'),
                'title2' => $request->input('Pelatihan_Publik_Bagian_1_title2'),
                'description2' => $request->input('Pelatihan_Publik_Bagian_1_description2'),
                'page_type' => 'product-pelatihan-public',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 1
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pelatihan_Publik_Bagian_1_id')], // Identifier for updating
                $section1Data // Data to update or create
            );

            // Prepare data for section 2
            $section2Data = [
                'section' => 2,
                'title1' => $request->input('Pelatihan_Publik_Bagian_2_title1'),
                'page_type' => 'product-pelatihan-public',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 2
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pelatihan_Publik_Bagian_2_id')], // Identifier for updating
                $section2Data // Data to update or create
            );

            // Prepare data for section 3
            $section3Data = [
                'section' => 3,
                'title1' => $request->input('Pelatihan_Publik_Bagian_3_title1'),
                'description1' => $request->input('Pelatihan_Publik_Bagian_3_description1'),
                'page_type' => 'product-pelatihan-public',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use updateOrCreate for section 3
            LandingPageSetting::updateOrCreate(
                ['id' => $request->input('Pelatihan_Publik_Bagian_3_id')], // Identifier for updating
                $section3Data // Data to update or create
            );


            return response()->json([
                'message' => 'Data berhasil disimpan',
                'status'  => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function getProductImages($productId)
    {
        try {
            $query = PropertyAttachModel::query()->where('property_id', '=', $productId)->get();
            return response()->json([
                'status' => true,
                'data'  => $query->toArray(),
                'message'   => 'Success retrive image data'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
