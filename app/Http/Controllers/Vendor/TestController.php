<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use DB;

use App\TestModel;
use App\QuestionModel; // Import Question model
use App\AnswerModel; // Import Answer model
use Exception; // Import the Exception class
use App\PelatihanSayaModel;

class TestController extends Controller
{
    function index(){
        return view('pages.vendor.test.index');
    }

    function create(){
        return view('pages.vendor.test.create');
    }

    function detail($id){
        // return TestModel::with(['pelatihan', 'question.answer'])->where('id', $id)->first();
        return view('pages.vendor.test.detail', [
            'edit_data' => TestModel::with(['pelatihan', 'question.answer'])->where('id', $id)->first(),
            'judulPelatihanList' => PelatihanSayaModel::get(),
        ]);
    }

    function update($id){
        // return TestModel::with(['pelatihan', 'question.answer'])->where('id', $id)->first();
        return view('pages.vendor.test.update', [
            'edit_data' => TestModel::with(['pelatihan', 'question.answer'])->where('id', $id)->first(),
            'judulPelatihanList' => PelatihanSayaModel::get(),
        ]);
    }

    public function getJudulPelatihan(Request $request)
    {
        $pelatihan = $request->input('pelatihan');

        // Daftar judul pelatihan
        // $judulPelatihan = [];

        // if ($pelatihan == 'Pelatihan') {
        //     $judulPelatihan = [
        //         ['id' => 1, 'nama' => 'Pelatihan Kepemimpinan'],
        //         ['id' => 2, 'nama' => 'Pelatihan Manajemen Proyek'],
        //     ];
        // } elseif ($pelatihan == 'Pengembangan') {
        //     $judulPelatihan = [
        //         ['id' => 3, 'nama' => 'Pengembangan Diri dan Soft Skill'],
        //         ['id' => 4, 'nama' => 'Komunikasi Efektif'],
        //     ];
        // }

        $judulPelatihan = PelatihanSayaModel::where('kategori', $pelatihan)->get();

        // Mengembalikan respons sebagai JSON
        return response()->json($judulPelatihan);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'code', 'judul_pelatihan'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = TestModel::with('pelatihan')->count();
            $totalFiltered = $totalData;
            $posts = '';
            // return $request->input('type');
            if (empty($request->input('search.value'))) {
                // dd('asdasd');
                $query = TestModel::with('pelatihan');

                $searchPelatihan = $request->input('pelatihan');
                if (!empty($searchPelatihan) && $searchPelatihan != 'all') {
                    $searchPelatihan = $request->input('pelatihan');
                    $query->where('pelatihan', $searchPelatihan);
                }

                $searchType = $request->input('type');
                if (!empty($searchType) && $searchType != 'all') {
                    $searchType = $request->input('type');
                    $query->where('type_test', $searchType);
                }

                $posts = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {
                // dd('$search');
                $search = $request->input('search.value');
                $searchPelatihan = $request->input('pelatihan');
                $searchType = $request->input('type');

                $tb = TestModel::with('pelatihan')
                ->when($search, function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%")
                      ->orWhereHas('pelatihan', function ($q2) use ($search) {
                          $q2->where('judul_pelatihan', 'LIKE', "%{$search}%");
                      });
                });

                if ($searchPelatihan) {
                    $tb->where('pelatihan', $searchPelatihan);
                }

                if ($searchType) {
                    $tb->where('type_test', $searchType);
                }

                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $tb->count();
            }

            // dd($posts);

            if (!empty($posts)) {

                $no = $start + 1;

                foreach ($posts as $a) {
                    // dd($a);
                    $d = $a;

                    $data[] = $d;
                    // $d['judul'] = $a->pelatihan ? $a->pelatihan : '-';
                }
            }
            // dd('asdasd = ', $data);
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    // public function dataTable(Request $request)
    // {
    //     try {
    //         $columns = [null, 'code', 'judul_pelatihan'];
    //         $limit = $request->input('length');
    //         $start = $request->input('start');
    //         $order = $columns[$request->input('order.0.column')] ?? 'id';
    //         $dir = $request->input('order.0.dir') ?? 'DESC';

    //         $data = [];
    //         $totalData = TestModel::with('pelatihan')->count();
    //         $totalFiltered = $totalData;

    //         // Ambil nilai filter dari request
    //         $search = $request->input('search.value');
    //         $pelatihan = $request->input('pelatihan');
    //         $typeTest = $request->input('type_test');

    //         // Filter query utama
    //         $query = TestModel::with('pelatihan')
    //             ->when($search, function ($q) use ($search) {
    //                 $q->where('id', 'LIKE', "%{$search}%")
    //                 ->orWhere('code', 'LIKE', "%{$search}%")
    //                 ->orWhereHas('pelatihan', function ($q2) use ($search) {
    //                     $q2->where('judul_pelatihan', 'LIKE', "%{$search}%");
    //                 });
    //             })
    //             ->when($pelatihan, function ($q) use ($pelatihan) {
    //                 $q->whereHas('pelatihan', function ($q2) use ($pelatihan) {
    //                     $q2->where('kategori', 'LIKE', "%{$pelatihan}%"); // Gunakan LIKE agar lebih fleksibel
    //                 });
    //             })
    //             ->when($typeTest, function ($q) use ($typeTest) {
    //                 $q->where('type_test', 'LIKE', "%{$typeTest}%"); // Gunakan LIKE agar lebih fleksibel
    //             });

    //         $totalFiltered = $query->count();

    //         // Ambil data dengan batas dan urutan
    //         $posts = $query->offset($start)
    //             ->limit($limit)
    //             ->orderBy($order, $dir)
    //             ->get();

    //         // Format hasil data
    //         if (!empty($posts)) {
    //             $no = $start + 1;
    //             foreach ($posts as $a) {
    //                 $d = $a;
    //                 $data[] = $d;
    //             }
    //         }

    //         $json_data = [
    //             "draw" => intval($request->input('draw')),
    //             "recordsTotal" => intval($totalData),
    //             "recordsFiltered" => intval($totalFiltered),
    //             "data" => $data
    //         ];

    //         return response()->json($json_data);

    //     } catch (Exception $e) {
    //         return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);
    //     }
    // }


    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'pelatihan' => 'required|string',
            'judul_pelatihan' => 'required|string',
            'type_test' => 'required|string',
            'pertanyaan.*' => 'required|string',
            'jawaban.*' => 'required|string',
            'kunci_jawaban.*' => 'required|in:a,b,c,d',
        ]);

        // // Check if validation fails
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 400);
        // }

        try {

            $lastTest = TestModel::orderBy('pelatihan', 'desc')->first();

            if ($lastTest) {
                $lastPelatihanCode = (int)substr($lastTest->pelatihan, -4);
                $nextSequenceNumber = $lastPelatihanCode + 1;
            } else {
                $nextSequenceNumber = 1;
            }

            $pelatihanCode = str_pad($nextSequenceNumber, 4, '0', STR_PAD_LEFT);
            $test = TestModel::create([
                'code' =>$pelatihanCode,
                'pelatihan' => $request->pelatihan,
                'judul_pelatihan' => $request->judul_pelatihan,
                'type_test' => $request->type_test,
                'is_locked' => 1,
            ]);

            foreach ($request->pertanyaan as $index => $pertanyaan) {
                $question = QuestionModel::create([
                    'test_id' => $test->id,
                    'pertanyaan' => $pertanyaan,
                ]);

                $options = ['A', 'B', 'C', 'D'];
                for ($i = 0; $i < 4; $i++) {
                    AnswerModel::create([
                        'question_id' => $question->id,
                        'option' => $options[$i],
                        'answer_text' => $request->jawaban[$index * 4 + $i],
                        'is_correct' => ($request->kunci_jawaban[$index] === strtolower($options[$i])) ? true : false,
                    ]);
                }
            }

            return response()->json(['message' => 'Data Berhasil Disimpan'], 200);
        } catch (Exception $e) {
            // Log the exception message
            \Log::error('Error saving test data: ' . $e->getMessage());

            // Return a JSON response with a 500 status code
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        // Validation for incoming data
        $validator = Validator::make($request->all(), [
            'pelatihan' => 'required|string',
            'judul_pelatihan' => 'required|string',
            'type_test' => 'required|string',
            'pertanyaan' => 'required|array',
            'pertanyaan.*' => 'required|string',
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string',
            'kunci_jawaban' => 'required|array',
            'kunci_jawaban.*' => 'required|in:a,b,c,d',
            'question_ids' => 'sometimes|array', // For existing questions, can be absent for new questions
            'question_ids.*' => 'sometimes|integer', // Optional validation for question IDs
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $test = TestModel::findOrFail($id);

            // Update the test details
            $test->update([
                'pelatihan' => $request->pelatihan,
                'judul_pelatihan' => $request->judul_pelatihan,
                'type_test' => $request->type_test,
            ]);

            // Get the original questions from the database
            $existingQuestions = QuestionModel::where('test_id', $id)->pluck('id')->toArray();

            // Identify which questions were not submitted (and should be deleted)
            $submittedQuestionIds = $request->question_ids ?? [];
            $questionsToDelete = array_diff($existingQuestions, $submittedQuestionIds);

            // Delete questions that were removed from the form
            if (!empty($questionsToDelete)) {
                QuestionModel::whereIn('id', $questionsToDelete)->delete();
            }

            // Loop through each question to update or create new ones
            foreach ($request->pertanyaan as $index => $pertanyaan) {
                // Handle both existing and new questions
                if (isset($request->question_ids[$index])) {
                    // Update existing question
                    $question = QuestionModel::findOrFail($request->question_ids[$index]);
                    $question->update(['pertanyaan' => $pertanyaan]);
                } else {
                    // Create a new question
                    $question = QuestionModel::create([
                        'test_id' => $id, // Assuming you link questions to the test via 'test_id'
                        'pertanyaan' => $pertanyaan,
                    ]);
                }

                // Update or create answers for the question
                $options = ['A', 'B', 'C', 'D'];
                for ($i = 0; $i < 4; $i++) {
                    $answerText = $request->jawaban[$index * 4 + $i] ?? null;

                    if ($answerText) {
                        // Find or create the answer for this question
                        AnswerModel::updateOrCreate(
                            ['question_id' => $question->id, 'option' => $options[$i]],
                            ['answer_text' => $answerText, 'is_correct' => ($request->kunci_jawaban[$index] === strtolower($options[$i]))]
                        );
                    }
                }
            }

            return response()->json(['message' => 'Data Berhasil Diperbarui'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (Exception $e) {
            \Log::error('Error updating test data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        try {
            // Retrieve the test IDs from the request
            $testIds = $request->ids;
            $pushTestId = [];
            $getTest = TestModel::where('id', $testIds)->get();
            for ($i=0; $i < count($getTest) ; $i++) {
                array_push($pushTestId, $getTest[$i]['id']);
            }

            $pushQuestionID = [];
            $getAnswer = QuestionModel::whereIn('test_id', $pushTestId)->get();

            for ($j=0; $j <count($getAnswer) ; $j++) {
                array_push($pushQuestionID, $getAnswer[$j]['id']);
            }

            AnswerModel::whereIn('question_id', $pushQuestionID)->delete();
            QuestionModel::whereIn('test_id', $pushTestId)->delete();
            TestModel::whereIn('id', $testIds)->delete();

            // return [
            //     'id untuk hapus question' => $pushTestId,
            //     'id untuk hapus answer ' => $pushQuestionID
            // ];


            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (Exception $e) {
            \Log::error('Error deleting tests: ' . $e->getMessage());
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
    }




}
