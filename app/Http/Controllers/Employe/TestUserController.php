<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Log;

use App\AnswerModel;
use App\QuestionModel;
use App\EmployeTestModel;
use App\EmployeTestDetailModel;

class TestUserController extends Controller
{
    function index(){
        // return EmployeTestModel::with('getPelatihan.getTestByPelatihanAndPengembangan')
        // ->where('user_id', Auth::user()->id)->get();
        return view('pages.employe.test.index');
    }

    function detail($id){
        // return 'asd';
        // return EmployeTestModel::with(['getPelatihan', 'getDetail.testUser'])->where('id', $id)->first();
        return view('pages.employe.test.detail', [
            'edit_data' => EmployeTestModel::with(['getPelatihan', 'getDetail.testUser'])->where('id', $id)->first()
        ]);
    }

    function detailPertanyaan($id){
        // return EmployeTestDetailModel::with(['testUser', 'getQuestion.answer'])->where('id', $id)->first();
        return view('pages.employe.test.detail_pertanyaan', [
            'data' => EmployeTestDetailModel::with(['testUser', 'getQuestion.answer'])->where('id', $id)->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $search = $request->input('search.value');
            $type_pelatihan = $request->input('type_pelatihan');
            $status_pelatihan = $request->input('status_pelatihan');

            // Query dasar
            $query = EmployeTestModel::with('getPelatihan.getTestByPelatihanAndPengembangan')
                                     ->where('user_id', Auth::user()->id);

            // Filter berdasarkan tipe pelatihan
            if (!empty($type_pelatihan)) {
                $query->whereHas('getPelatihan', function ($q) use ($type_pelatihan) {
                    $q->where('type', 'LIKE', "%{$type_pelatihan}%");
                });
            }

            // Filter berdasarkan status pelatihan
            if (!empty($status_pelatihan)) {
                $query->where('status', 'LIKE', "%{$status_pelatihan}%");
            }

            // Pencarian umum
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhereHas('getPelatihan', function ($q) use ($search) {
                          $q->where('id_pelatihan', 'LIKE', "%{$search}%")
                            ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Hitung total data yang difilter
            $totalFiltered = $query->count();

            // Ambil data dengan pagination
            $posts = $query->offset($start)
                           ->limit($limit)
                           ->orderBy($order, $dir)
                           ->get();

            // Siapkan data untuk JSON
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval(EmployeTestModel::where('user_id', Auth::user()->id)->count()),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $posts
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function getByJudulPelatihanAndCompany(Request $request)
    {
        $judulPelatihanId = $request->input('pelatihan_id');
        $companyId = $request->input('company_id');

        $results = EmployeTestModel::findByJudulPelatihanAndCompany($judulPelatihanId, $companyId);

        return response()->json($results);
    }

    public function submitTest(Request $request) {
        // return 'asdasd';
        // $type = $request->input('type');
        // return $request->all();
        $score = 0;
        $totalQuestions = 0;

        // Loop through each question's answer submitted
        foreach ($request->input('question') as $questionId => $answerId) {
            // Find the answer and check if it is correct
            $answer = AnswerModel::where('id', $answerId)->where('question_id', $questionId)->first();

            if ($answer && $answer->is_correct) {
                $score++;
            }
            $totalQuestions++;
        }

        // Calculate percentage score out of 100
        $finalScore = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0;

        // Find the Employee Test Record
        $employeeTest = EmployeTestModel::where('id', $request->employe_test_id)->firstOrFail(); // Adjust condition as needed

        // Check if it's a pre-test or post-test, then update the appropriate field
        $type = $request->input('type'); // Expect 'pre-test' or 'post-test' from the request
        if ($type === 'Pre Test') {
            $employeeTest->nilai_pre_test = $finalScore;
            $employeeTest->status_pre_test = 1; // jika 1 sudah jika null atau 0 belum
            $employeeTest->status = 'Belum Post Test';
        } elseif ($type === 'Post Test') {
            $employeeTest->nilai_post_test = $finalScore;
            $employeeTest->status_post_test = 1; // jika 1 sudah jika null atau 0 belum
            $employeeTest->status = 'Belum Pre Test';
        }



        $employeeTest->save();

        // Update or create a detail record to mark the test as finished
        EmployeTestDetailModel::where('id', $request->employe_test_detail_id)->update([
            'nilai' => $finalScore,
            'is_finish' => 1,
        ]);

        $cekStatusAhir = EmployeTestModel::where('id', $request->employe_test_id)->firstOrFail();

        // Perbaikan kondisi logika
        if ($cekStatusAhir->status_pre_test > 0 && $cekStatusAhir->status_post_test > 0) {
            $cekStatusAhir->update([
                'status' => 'Selesai'
            ]);
        }

        Log::info('Update detail test :', ['nilai' => $finalScore, 'is_finish' => 1]);

        // Return the score as a response
        return response()->json([
            'success' => true,
            'score' => $finalScore
        ]);
    }
}
