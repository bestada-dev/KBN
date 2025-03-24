<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;

use App\LogUsersModel;
use App\PropertyModel;
use App\PropertyAttachModel;
use App\PropertyFacilityModel;
use App\CategoryModel;
use App\Http\Controllers\BaseController\UploadImageController;
use App\ZoningModel;
use Exception;

class PropertyController extends UploadImageController
{
    function index(){
        return view('pages.admin.property.index', [
            'category' => CategoryModel::all(),
        ]);
    }

    function create(){
        return view('pages.admin.property.create', [
            'category' => CategoryModel::all(),
            'zoning' => ZoningModel::all(),
        ]);
    }

    function update($id){
        // return PropertyModel::where('id', $id)->with(['getCategory', 'getZoning', 'getFacility', 'getAttachment'])->first();
        $editData = PropertyModel::where('id', $id)->with(['getCategory', 'getZoning', 'getFacility', 'getAttachment'])->first();
        // dd($editData->toArray());
        return view('pages.admin.property.update', [
            'category' => CategoryModel::all(),
            'zoning' => ZoningModel::all(),
            'data_edit' => $editData
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'property_address', 'property_location_link'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $type_status = $request->input('type_status'); // Get the type filter value
            $type_category = $request->input('type_category');

            $data = [];
            $totalData = PropertyModel::with(['getCategory', 'getZoning', 'countViewer'])->count();
            $totalFiltered = $totalData;

            // Start building the query
            $query = PropertyModel::with(['getCategory', 'getZoning', 'countViewer']);

            // Handle search functionality
            $search = $request->input('search.value');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('property_address', 'LIKE', "%{$search}%")
                    ->orWhere('property_location_link', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
                });
            }

            // Filter by status if provided
            if (!empty($type_status)) {
                $query->where('status', $type_status);
            }

            // Filter by category if provided (adjust if 'getCategory' is a method or relationship)
            if (!empty($type_category)) {
                $query->where('category_id', $type_category);
            }

            // Get the filtered results with pagination
            $posts = $query->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

            // Update the total filtered count
            $totalFiltered = $query->count();

            // Populate the data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare and return the JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'function' => __FUNCTION__,
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'zona_id' => 'required',
            'property_address' => 'required|string|max:500',
            'property_location_link' => 'required|string|max:500',
            'block' => 'required|string|max:100',
            'type' => 'required|in:Bounded,General',
            'land_area' => 'required|numeric|min:1',
            'building_area' => 'required|numeric|min:1',
            'type_upload' => 'required|in:link,upload_vidio',
            'url' => 'required_if:type_upload,link|nullable|url',
            'vidio' => 'required_if:type_upload,upload_vidio|nullable|mimes:mp4,webm,ogg|max:51200', // 50MB limit
            'desc' => 'required|string',
            'layout' => 'nullable|mimes:jpeg,png|max:5120', // 5MB limit
            'facility.*' => 'required|string|max:255',
            'detail_photo.*' => 'nullable|mimes:jpeg,png|max:5120', // 5MB limit per photo
        ], [
            'category_id.required' => 'Please select a category for the property.',
            'category_id.exists' => 'The selected category does not exist in the database.',
            'zona_id.required' => 'Please select a zoning area for the property.',
            'zona_id.exists' => 'The selected zoning area is invalid.',
            'property_address.required' => 'The property address is required.',
            'property_address.max' => 'The property address must not exceed 500 characters.',
            'property_location_link.required' => 'Please provide a valid link to the property location.',
            'property_location_link.max' => 'The location link must not exceed 500 characters.',
            'block.required' => 'The block information is required.',
            'block.max' => 'The block name must not exceed 100 characters.',
            'type.required' => 'Please specify whether the property is Bounded or General.',
            'type.in' => 'The type must be either Bounded or General.',
            'land_area.required' => 'Please specify the land area.',
            'land_area.numeric' => 'The land area must be a number.',
            'land_area.min' => 'The land area must be at least 1 square meter.',
            'building_area.required' => 'Please specify the building area.',
            'building_area.numeric' => 'The building area must be a number.',
            'building_area.min' => 'The building area must be at least 1 square meter.',
            'type_upload.required' => 'Please select how you want to provide a virtual tour (Link or Upload Video).',
            'type_upload.in' => 'The virtual tour type must be either Link or Upload Video.',
            'url.required_if' => 'The URL is required when you select "Link" as the type.',
            'url.url' => 'Please provide a valid URL.',
            'vidio.required_if' => 'Please upload a video when you select "Upload Video" as the type.',
            'vidio.mimes' => 'The video format must be MP4, WebM, or OGG.',
            'vidio.max' => 'The video size must not exceed 50 MB.',
            'desc.required' => 'Please provide a description of the property.',
            'layout.mimes' => 'The layout must be in JPEG or PNG format.',
            'layout.max' => 'The layout file size must not exceed 5 MB.',
            'facility.*.required' => 'Each facility must be specified.',
            'facility.*.max' => 'Each facility name must not exceed 255 characters.',
            'detail_photo.*.mimes' => 'Each photo must be in JPEG or PNG format.',
            'detail_photo.*.max' => 'Each photo size must not exceed 5 MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }


        try {
            // Save Property Data
            $property = PropertyModel::create([
                'category_id' => $request->category_id,
                'zona_id' => $request->zona_id,
                'property_address' => $request->property_address,
                'property_location_link' => $request->property_location_link,
                'block' => $request->block,
                'type' => $request->type,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'type_upload' => $request->type_upload,
                'url' => $request->url,
                // 'vidio' => $request->hasFile('vidio') ? $request->file('vidio')->store('videos') : null,
                'vidio' => $request->hasFile('vidio')
                            ? $this->saveToPublic($request->file('vidio'), $this->uploadVideoPath)
                            : null,
                'desc' => $request->desc,
                // 'layout' => $request->hasFile('layout') ? $request->file('layout')->store('layouts') : null,
                'layout' => $request->hasFile('layout')
                            ? $this->saveToPublic($request->file('layout'), $this->uploadLayoutsPath)
                            : null,
                'status' => 'Available', // Default value
                'total_viewer' => 0
            ]);

            foreach ($request->facility as $facility) {
                PropertyFacilityModel::create([
                    'property_id' => $property->id,
                    'facility' => $facility,
                ]);
            }

            if ($request->hasFile('detail_photo')) {
                foreach ($request->file('detail_photo') as $photo) {
                    $fileName = $this->saveToPublic($photo, 'detail_photos');
                    PropertyAttachModel::create([
                        'property_id' => $property->id,
                        'detail_photo' => $fileName,
                    ]);
                }
            }

            LogUsersModel::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s'),
                'type' => "ADD",
                'activity' => 'Added a new Property with the name: ' . $request->property_address . ' in the Property Menu.',
            ]);

            return response()->json(['message' => 'Property created successfully'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'zona_id' => 'required',
            'property_address' => 'required|string|max:500',
            'property_location_link' => 'required|string|max:500',
            'block' => 'required|string|max:100',
            'type' => 'required|in:Bounded,General',
            'land_area' => 'required|numeric|min:1',
            'building_area' => 'required|numeric|min:1',
            'type_upload' => 'required|in:link,upload_vidio',
            'url' => 'nullable|url',
            'vidio' => 'nullable|mimes:mp4,webm,ogg|max:51200',
            'desc' => 'required|string',
            'layout' => 'nullable|mimes:jpeg,png|max:5120',
            'facility.*' => 'nullable|string|max:255',
            'detail_photo.*' => 'nullable|mimes:jpeg,png|max:5120',
        ],  [
            'category_id.required' => 'Please select a category for the property.',
            'category_id.exists' => 'The selected category does not exist in the database.',
            'zona_id.required' => 'Please select a zoning area for the property.',
            'zona_id.exists' => 'The selected zoning area is invalid.',
            'property_address.required' => 'The property address is required.',
            'property_address.max' => 'The property address must not exceed 500 characters.',
            'property_location_link.required' => 'Please provide a valid link to the property location.',
            'property_location_link.max' => 'The location link must not exceed 500 characters.',
            'block.required' => 'The block information is required.',
            'block.max' => 'The block name must not exceed 100 characters.',
            'type.required' => 'Please specify whether the property is Bounded or General.',
            'type.in' => 'The type must be either Bounded or General.',
            'land_area.required' => 'Please specify the land area.',
            'land_area.numeric' => 'The land area must be a number.',
            'land_area.min' => 'The land area must be at least 1 square meter.',
            'building_area.required' => 'Please specify the building area.',
            'building_area.numeric' => 'The building area must be a number.',
            'building_area.min' => 'The building area must be at least 1 square meter.',
            'type_upload.required' => 'Please select how you want to provide a virtual tour (Link or Upload Video).',
            'type_upload.in' => 'The virtual tour type must be either Link or Upload Video.',
            'url.required_if' => 'The URL is required when you select "Link" as the type.',
            'url.url' => 'Please provide a valid URL.',
            'vidio.required_if' => 'Please upload a video when you select "Upload Video" as the type.',
            'vidio.mimes' => 'The video format must be MP4, WebM, or OGG.',
            'vidio.max' => 'The video size must not exceed 50 MB.',
            'desc.required' => 'Please provide a description of the property.',
            'layout.mimes' => 'The layout must be in JPEG or PNG format.',
            'layout.max' => 'The layout file size must not exceed 5 MB.',
            'facility.*.required' => 'Each facility must be specified.',
            'facility.*.max' => 'Each facility name must not exceed 255 characters.',
            'detail_photo.*.mimes' => 'Each photo must be in JPEG or PNG format.',
            'detail_photo.*.max' => 'Each photo size must not exceed 5 MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Ambil data properti dari database
            $property = PropertyModel::findOrFail($id);

            $originalName = $property->property_address;

            // Update data properti

            if ($request->hasFile('vidio')) {
                // Apakah video lama ada?
                if ($property->vidio) {
                    Storage::delete($this->updateVideoPath . $property->vidio);
                }
                // Simpan video baru
                $newVidio = $this->saveToPublic($request->file('vidio'), $this->uploadVideoPath);
                $property->update(['vidio' => $newVidio]);
            }

            if ($request->hasFile('layout')) {
                // Apakah video lama ada?
                if ($property->layout) {
                    Storage::delete($this->updateLayoutsPath . $property->layout);
                }
                // Simpan video baru
                $newlayout = $this->saveToPublic($request->file('layout'), $this->uploadLayoutsPath);
                $property->update(['layout' => $newlayout]);
            }

            $property->update([
                'category_id' => $request->category_id,
                'zona_id' => $request->zona_id,
                'property_address' => $request->property_address,
                'property_location_link' => $request->property_location_link,
                'block' => $request->block,
                'type' => $request->type,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'type_upload' => $request->type_upload,
                'url' => $request->url,
                'desc' => $request->desc,
            ]);

            // Update fasilitas properti
            PropertyFacilityModel::where('property_id', $property->id)->delete();
            foreach ($request->facility as $facility) {
                PropertyFacilityModel::create([
                    'property_id' => $property->id,
                    'facility' => $facility,
                ]);
            }

            // Update detail foto
            if ($request->hasFile('detail_photo')) {
                foreach ($request->file('detail_photo') as $photo) {
                    $fileName = $this->saveToPublic($photo, $this->uploadDetailPhotoPath);
                    PropertyAttachModel::create([
                        'property_id' => $property->id,
                        'detail_photo' => $fileName,
                    ]);
                }
            }

            LogUsersModel::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s'),
                'type' => "EDIT",
                'activity' => 'Updated Property "' . $originalName . '" to "' . $request->property_address . '" in the Property Menu.',
            ]);

            return response()->json(['message' => 'Property updated successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    protected function saveToPublic($file, $folder)
    {
        $fileName = $file->getClientOriginalName();
        $file->move(public_path($folder), $fileName);
        return $fileName;
    }

    function delete(Request $request) {
        DB::beginTransaction();
        try {

            $getProp = PropertyModel::whereIn('id', [$request->ids])->get();
            LogUsersModel::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s'),
                'type' => "DELETED",
                'activity' => 'Deleted Property "' . $getProp[0]['property_address'] . '" from the Property Menu.',
            ]);
            PropertyAttachModel::where('property_id', $request->ids)->delete();

            PropertyFacilityModel::where('property_id', $request->ids)->delete();

            $propertyDeleted = PropertyModel::where('id', $request->ids)->delete();

            if (!$propertyDeleted) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Properti tidak ditemukan.'], 404);
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Properti dan lampirannya berhasil dihapus.']);

        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    function deleteImage(Request $request) {
        $detailFoto = PropertyAttachModel::where('id', $request->ids)->first();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "DELETED",
            'activity' => 'Deleted Property Image "' . $detailFoto->detail_photo . '" from the Property Menu.',
        ]);

        if ($detailFoto) {
            $filePath = public_path($this->updateDetailPhotoPath . $detailFoto->detail_photo);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $detailFoto->delete();
            return response()->json(['status' => true, 'message' => 'Gambar berhasil dihapus.']);
        }

        // Jika gambar tidak ditemukan
        return response()->json(['error' => 'Image not found.']);
    }

    // ini yg gambar nya ikit ke hapus
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'category_id' => 'required',
    //         'zona_id' => 'required',
    //         'property_address' => 'required|string|max:255',
    //         'property_location_link' => 'required|string|max:255',
    //         'block' => 'required|string|max:100',
    //         'type' => 'required|in:Bounded,General',
    //         'land_area' => 'required|numeric|min:1',
    //         'building_area' => 'required|numeric|min:1',
    //         'type_upload' => 'required|in:link,upload_vidio',
    //         'url' => 'required_if:type_upload,link|nullable|url',
    //         'vidio' => 'required_if:type_upload,upload_vidio|nullable|mimes:mp4,webm,ogg|max:51200',
    //         'desc' => 'required|string',
    //         'layout' => 'nullable|mimes:jpeg,png|max:5120',
    //         'facility.*' => 'required|string|max:255',
    //         'detail_photo.*' => 'nullable|mimes:jpeg,png|max:5120',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
    //         // Ambil data properti dari database
    //         $property = PropertyModel::findOrFail($id);

    //         // Update gambar layout jika ada perubahan
    //         if ($request->hasFile('layout')) {
    //             if ($property->layout && Storage::exists('public/' . $property->layout)) {
    //                 // Hapus layout lama
    //                 Storage::delete('public/' . $property->layout);
    //             }
    //             // Simpan layout baru
    //             $property->layout = $this->saveToPublic($request->file('layout'), 'layouts');
    //         }

    //         // Update video jika ada perubahan
    //         if ($request->hasFile('vidio')) {
    //             if ($property->vidio && Storage::exists('public/' . $property->vidio)) {
    //                 // Hapus video lama
    //                 Storage::delete('public/' . $property->vidio);
    //             }
    //             // Simpan video baru
    //             $property->vidio = $this->saveToPublic($request->file('vidio'), 'videos');
    //         }

    //         // Update properti lainnya
    //         $property->update([
    //             'category_id' => $request->category_id,
    //             'zona_id' => $request->zona_id,
    //             'property_address' => $request->property_address,
    //             'property_location_link' => $request->property_location_link,
    //             'block' => $request->block,
    //             'type' => $request->type,
    //             'land_area' => $request->land_area,
    //             'building_area' => $request->building_area,
    //             'type_upload' => $request->type_upload,
    //             'url' => $request->url,
    //             'desc' => $request->desc,
    //         ]);

    //         // Update fasilitas properti
    //         PropertyFacilityModel::where('property_id', $property->id)->delete();
    //         foreach ($request->facility as $facility) {
    //             PropertyFacilityModel::create([
    //                 'property_id' => $property->id,
    //                 'facility' => $facility,
    //             ]);
    //         }

    //         // Update detail foto
    //         if ($request->hasFile('detail_photo')) {
    //             foreach ($request->file('detail_photo') as $index => $photo) {
    //                 $existingPhoto = $property->attachments[$index] ?? null;

    //                 if ($existingPhoto) {
    //                     if (Storage::exists('public/' . $existingPhoto->detail_photo)) {
    //                         // Hapus foto lama
    //                         Storage::delete('public/' . $existingPhoto->detail_photo);
    //                     }

    //                     // Simpan foto baru dan update database
    //                     $fileName = $this->saveToPublic($photo, 'detail_photos');
    //                     $existingPhoto->update(['detail_photo' => $fileName]);
    //                 } else {
    //                     // Jika tidak ada foto lama, tambahkan yang baru
    //                     $fileName = $this->saveToPublic($photo, 'detail_photos');
    //                     PropertyAttachModel::create([
    //                         'property_id' => $property->id,
    //                         'detail_photo' => $fileName,
    //                     ]);
    //                 }
    //             }
    //         }

    //         return response()->json(['message' => 'Property updated successfully'], 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }


}
