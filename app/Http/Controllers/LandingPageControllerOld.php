<?php

namespace App\Http\Controllers;

use App\LandingPageSetting;
use App\Child;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Validator;
use Storage;
use DB;
use Illuminate\Support\Facades\Log;

class LandingPageControllerOld extends Controller
{
    public function index()

    {
        $content = LandingPageSetting::where('page_type', 'home')->get();
        $home = $content;
        $idOfSection2 =  $home[1]['id'];
        $idOfSection4 =  $home[3]['id'];
        $idOfSection8 =  $home[7]['id'];

        $childBagian2 = Child::where('landing_page_setting_id',$idOfSection2)->get();
        // dd($childBagian2);
        $childBagian4 = Child::where('landing_page_setting_id',$idOfSection4)->get();
        $childBagian8 = Child::where('landing_page_setting_id',$idOfSection8)->get();
    
        return view('pages.landing-page.home', get_defined_vars());

    }


    public function aboutUs()

    {
        $content = LandingPageSetting::where('page_type', 'about-us')->get();
        return view('pages.landing-page.about-us', get_defined_vars());

    }


    public function contactUs()

    {
        $content = LandingPageSetting::where('page_type', 'contact-us')->get();
        return view('pages.landing-page.contact-us', get_defined_vars());

    }


    public function productDetail()

    {

        return view('pages.landing-page.product-detail');

    }


    public function productPelatihanPublic()

    {
        $content = LandingPageSetting::where('page_type', 'product-pelatihan-public')->get();
        return view('pages.landing-page.product-pelatihan-public', get_defined_vars());

    }


    public function productPengembanganDiri()

    {
        $content = LandingPageSetting::where('page_type', 'product-pengembangan-diri')->get();
        return view('pages.landing-page.product-pengembangan-diri', get_defined_vars());
    }

    public function searchResult()

    {

        return view('pages.landing-page.search-result');

    }


    // SAVE
        
    public function saveHome(Request $request)
    {
        // dd($request->all());
        // Define validation rules
        $rules = [
            'Home_Bagian_1_title1' => 'required|string',
            'Home_Bagian_1_description1' => 'required|string',
            // 'Home_Bagian_1_photo' => 'nullable|image',
            'Home_Bagian_2_title1' => 'required|string',
            // 'Home_Bagian_2_description1' => 'required|string',
            'Home_Bagian_2_Poin_poin_title1.*' => 'required|string',
            'Home_Bagian_2_Poin_poin_description1.*' => 'required|string',
            // 'Home_Bagian_2_Poin_poin_photo.*' => 'nullable|image',
            'Home_Bagian_3_title1' => 'required|string',
            'Home_Bagian_3_description1' => 'required|string',
            'Home_Bagian_4_title1' => 'required|string',
            'Home_Bagian_4_title2' => 'required|string',
            'Home_Bagian_4_description1' => 'required|string',
            'Home_Bagian_4_Poin_poin_title1.*' => 'required|string',
            'Home_Bagian_4_Poin_poin_description1.*' => 'required|string',
            'Home_Bagian_5_title1' => 'required|string',
            // 'Home_Bagian_5_description1' => 'required|string',
            'Home_Bagian_6_title1' => 'required|string',
            'Home_Bagian_6_description1' => 'required|string',
            'Home_Bagian_7_title1' => 'required|string',
            'Home_Bagian_7_description1' => 'required|string',
            'Home_Bagian_8_title1' => 'required|string',
            // 'Home_Bagian_8_logo.*' => 'nullable|image',
            'Home_Bagian_9_whatsapp' => 'required|string',
            'Home_Bagian_9_instagram' => 'required|string',
            'Home_Bagian_9_tiktok' => 'required|string',
            'Home_Bagian_9_facebook' => 'required|string',
            'Home_Bagian_9_twitter' => 'required|string',
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

            // --- Section 1 ---
            $section1Data = $this->handleSection(
                $request,
                1,
                'Home_Bagian_1_id',
                'Home_Bagian_1_title1',
                'Home_Bagian_1_description1',
                'Home_Bagian_1_photo'
            );

            if (isset($section1Data['image']) && $section1Data['image']) {
                $section1Data['photo'] = $section1Data['image'];
                unset($section1Data['image']);
            }

            
            $landingPageSetting1 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_1_id')], $section1Data);

            // --- Section 2 ---
            $section2Data = $this->handleSection(
                $request,
                2,
                'Home_Bagian_2_id',
                'Home_Bagian_2_title1',
                'Home_Bagian_2_description1',
            );
            $landingPageSetting2 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_2_id')], $section2Data);

            // --- Handling multiple inputs for Section 2 "Poin Poin" --- 
            $poinPoinTitles = $request->input('Home_Bagian_2_Poin_poin_title1', []);
            $poinPoinDescriptions = $request->input('Home_Bagian_2_Poin_poin_description1', []);
            $poinPoinPhotos = $request->file('Home_Bagian_2_Poin_poin_photo', []);
            $poinPoinIds = $request->input('Home_Bagian_2_Poin_poin_id', []);

            // OLD
            // for ($i = 0; $i < count($poinPoinTitles); $i++) {
            //     $poinData = [
            //         'landing_page_setting_id' => $landingPageSetting2->id,
            //         'title1' => $poinPoinTitles[$i],
            //         'description1' => $poinPoinDescriptions[$i],
            //     ];

            //     if (isset($poinPoinPhotos[$i]) && $poinPoinPhotos[$i]->isValid()) {
            //         $filePath = $this->uploadFile($poinPoinPhotos[$i], 'home_photos');
            //         $poinData['image'] = $filePath;
            //     }

            //     Child::updateOrCreate(['id' => $poinPoinIds[$i] ?? null], $poinData);
            // }

            // NEW
            // Get existing records
            $existingRecords = Child::where('landing_page_setting_id', $landingPageSetting2->id)
            ->get();

            // Delete records that are not in the new submission
             // Delete old records first
            if (!empty($poinPoinIds)) {
                // $recordsToDelete = Child::where('landing_page_setting_id', $landingPageSetting2->id)
                //                     ->whereNotIn('id', array_filter($poinPoinIds));
                
                // foreach ($recordsToDelete->get() as $record) {
                //     if ($record->image) {
                //         Storage::disk('public')->delete($record->image);
                //     }
                // }
                
                // $recordsToDelete->delete();
            } else {
                // If no IDs provided, delete all existing records and their files
                // foreach ($existingRecords as $record) {
                //     if ($record->image) {
                //         Storage::disk('public')->delete($record->image);
                //     }
                // }
                // Child::where('landing_page_setting_id', $landingPageSetting2->id)->delete();
            }

   // Assuming $childIds contains the IDs of existing Child records that correspond to $poinPoinPhotos
//    Log::info($poinPoinPhotos);
// This array should map to the correct IDs for existing Child records
            $childIds = Child::pluck('id'); // Example IDs

            foreach ($poinPoinPhotos as $key => $photo) {
                $poinData = [
                    'landing_page_setting_id' => $landingPageSetting2->id,
                    'title1' => $poinPoinTitles[$key] ?? '',
                    'description1' => $poinPoinDescriptions[$key] ?? '',
                ];

                // Find the existing record by key
                $oldRecord = Child::find($key);

                // Log the old image path for debugging
                if ($oldRecord) {
                    Log::info("Old Image for ID $key: " . $oldRecord->image);
                }

                // Check if a file is present and is an instance of UploadedFile
                if ($photo instanceof UploadedFile) {
                    // Delete the old image if it exists
                    if ($oldRecord && $oldRecord->image) {
                        Storage::disk('public')->delete($oldRecord->image);
                        Log::info("Deleted old image for ID $key: " . $oldRecord->image);
                    }

                    // Upload the new file and store the path
                    $filePath = $this->uploadFile($photo, 'home_photos');
                    $poinData['image'] = $filePath;

                    // Log the uploaded file path
                    Log::info("Uploaded new image for ID $key: $filePath");
                } else {
                    // If no file is uploaded, retain the old image
                    $poinData['image'] = $oldRecord ? $oldRecord->image : null;
                    Log::info("No new file for ID $key, keeping old image if exists");
                }

                // Use updateOrCreate with a unique key
                Child::updateOrCreate(
                    ['id' => $key],
                    $poinData
                );

                // Log the final data array for debugging
                Log::info("Final data for ID $key:", $poinData);
            }


            

            // --- Section 3 ---
            $section3Data = $this->handleSection(
                $request,
                3,
                'Home_Bagian_3_id',
                'Home_Bagian_3_title1',
                'Home_Bagian_3_description1',
            );
            $landingPageSetting3 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_3_id')], $section3Data);

            // --- Section 4 ---
            $section4Data = $this->handleSection(
                $request,
                4,
                'Home_Bagian_4_id',
                'Home_Bagian_4_title1',
                'Home_Bagian_4_title2',
                'Home_Bagian_4_description1',
            );
            $landingPageSetting4 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_4_id')], $section4Data);

            // --- Handling multiple inputs for Section 4 "Poin Poin" --- 
            $poinPoinTitles = $request->input('Home_Bagian_4_Poin_poin_title1', []);
            $poinPoinDescriptions = $request->input('Home_Bagian_4_Poin_poin_description1', []);
            $poinPoinIds = $request->input('Home_Bagian_4_Poin_poin_id', []);


            // OLD
            // for ($i = 0; $i < count($poinPoinTitles); $i++) {
            //     $poinData = [
            //         'landing_page_setting_id' => $landingPageSetting4->id,
            //         'title1' => $poinPoinTitles[$i],
            //         'description1' => $poinPoinDescriptions[$i],
            //     ];


            //     Child::updateOrCreate(['id' => $poinPoinIds[$i] ?? null], $poinData);
            // }
            
            // NEW
             // Get all existing records for this section
            $existingRecords = Child::where('landing_page_setting_id', $landingPageSetting4->id)
            ->get();

            // Delete records that are not in the new submission
            if (!empty($poinPoinIds)) {
                Child::where('landing_page_setting_id', $landingPageSetting4->id)
                ->whereNotIn('id', array_filter($poinPoinIds))
                ->delete();
            } else {
                // If no IDs provided, delete all existing records
                Child::where('landing_page_setting_id', $landingPageSetting4->id)
                ->delete();
            }

          // Process each title and its corresponding data
            foreach ($poinPoinTitles as $key => $title) {
                // Skip if title is empty
                if (empty($title)) {
                    continue;
                }
                $poinData = [
                    'landing_page_setting_id' => $landingPageSetting4->id,
                    'title1' => $title,
                    'description1' => $poinPoinDescriptions[$key],
                ];

                Child::updateOrCreate(
                    ['id' => $key ?? null],
                    $poinData
                );

            }
            

            // --- Section 5 ---
            $section5Data = $this->handleSection(
                $request,
                5,
                'Home_Bagian_5_id',
                'Home_Bagian_5_title1',
                'Home_Bagian_5_description1',
            );
            $landingPageSetting5 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_5_id')], $section5Data);

            // --- Section 6 ---
            $section6Data = $this->handleSection(
                $request,
                6,
                'Home_Bagian_6_id',
                'Home_Bagian_6_title1',
                'Home_Bagian_6_description1',
            );
            $landingPageSetting6 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_6_id')], $section6Data);

            // --- Section 7 ---
            $section7Data = $this->handleSection(
                $request,
                7,
                'Home_Bagian_7_id',
                'Home_Bagian_7_title1',
                'Home_Bagian_7_description1',
            );
            $landingPageSetting7 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_7_id')], $section7Data);

            // // --- Section 8 ---
            $section8Data = $this->handleSection(
                $request,
                8,
                'Home_Bagian_8_id',
                'Home_Bagian_8_title1',
            );

            // unset($section8Data['description1']);
            // dd($section8Data);
            $landingPageSetting8 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_8_id')], $section8Data);

            // // --- Handling multiple inputs for Section 8 "Logo" --- 
            $logos = $request->file('Home_Bagian_8_Logo_logo', []);
            $poinPoinIds = $request->input('Home_Bagian_8_Logo_id', []);
           

            // First, delete all existing records that are not in the new submission
            if (!empty($poinPoinIds)) {
                Child::where('landing_page_setting_id', $landingPageSetting8->id)
                    ->whereNotIn('id', array_filter($poinPoinIds))
                    ->delete();
            } else {
                // If no IDs provided, delete all existing records
                Child::where('landing_page_setting_id', $landingPageSetting8->id)->delete();
            }

            // Then create or update new records
            foreach ($logos as $key => $logo) {
                $logoData = [
                    'landing_page_setting_id' => $landingPageSetting8->id,
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
        //    OLD
            // foreach ($logos as $key => $logo) {
            //     $logoData = [
            //         'landing_page_setting_id' => $landingPageSetting8->id,
            //     ];
            
            //     // Check if the item is an instance of UploadedFile
            //     if ($logo instanceof UploadedFile) {
            //         $filePath = $this->uploadFile($logo, 'home_photos');
            //         $logoData['image'] = $filePath;
            
            //         // Use the key from the foreach loop to update or create
            //         Child::updateOrCreate(['id' => $key ?? null], $logoData);
            //     }
            // }

     
            // --- Section 9 ---
            $section9Data = [
                'section' => 9,
                'whatsapp' => $request->input('Home_Bagian_9_whatsapp'),
                'instagram' => $request->input('Home_Bagian_9_instagram'),
                'tiktok' => $request->input('Home_Bagian_9_tiktok'),
                'facebook' => $request->input('Home_Bagian_9_facebook'),
                'twitter' => $request->input('Home_Bagian_9_twitter'),
                'page_type' => 'home',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // dd($request->input('Home_Bagian_9_id'));
            $landingPageSetting9 = LandingPageSetting::updateOrCreate(['id' => $request->input('Home_Bagian_9_id')], $section9Data);

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

    private function handleSection(Request $request, int $section, string $idField, string $titleField = null, string $descriptionField =null, string $photoField = null)
    {
        $sectionData = [
            'section' => $section,
            'title1' => $request->input($titleField, null),
            'description1' => $request->input($descriptionField, null),
            'page_type' => 'home',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($photoField) {
            if ($request->hasFile($photoField)) {
                $filePath = $this->uploadFile($request->file($photoField), 'home_photos');
                $sectionData['image'] = $filePath;
            }
        }

        return $sectionData;
    }

    public function uploadFile($file, $directory)
    {
        // Generate a unique name for the file
        $uniqueName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($directory, $uniqueName, 'public');
        
        return $filePath;
    }
    
    public function saveAboutUs(Request $request) {
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

    public function saveContactUs(Request $request) {
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

    public function saveProductPengembanganDiri(Request $request) {
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

    public function saveProductPelatihanPublic(Request $request) {
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
}
