<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Project;
use App\ProjectUser;
use App\Media;
use App\Information;
use DB;

class PagesController extends Controller
{
    public function dashboard()
    {
    	return view('pages.dashboard');
    }

    /***************** PER LOGINAN ***************/

    public function forgotPassword()
    {
    	return view('pages.forgot-password');
    }

    public function resetPasswordAfterLogin($param)
    {
        try{
            $REQ = Users::where('token', $param)->first();
            if(!$REQ || !$REQ->is_new){
                return view('errors.404');
            }
            return view('pages.create-new-password');

        } catch (Exception $e){
            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);
        }
    }

    /***************** USERS ***************/

    public function users()
    {
    	return view('pages.users.index');
    }

    public function formUsers($id = null)
    {
        $projects = Project::get();
        $user = null;
        $isEdit = false;

        if ($id) {
            $user = Users::with(['projects' => function ($query) {
                $query->select(['projects.id', 'name']); // Select required columns from projects
            }])->find($id);

            if (!$user) {
                return redirect()->route('pages.users.form')->withErrors('User not found.');
            }

            $user->projects_id = $user->projects->pluck('id')->toArray();
            $isEdit = true;
        }

        return view('pages.users.form', get_defined_vars());
    }

    /***************** INFORMATION ***************/

    public function information()
    {
    	return view('pages.information.index');
    }

    public function formInformation($id = null)
    {
        $information = null;
        $isEdit = false;

        if ($id) {
            $information = Information::find($id);
            if (!$information) {
                return redirect()->route('pages.information.form')->withErrors('Information not found.');
            }

            $isEdit = true;
        }

        return view('pages.information.form', get_defined_vars());
    }

    /***************** USER REPORT ***************/
    public function listProjectUser(Request $request)
    {
        // Ambil nama proyek dari input pencarian
        $search = $request->input('search', '');

        // Ambil user dan proyeknya
        $cekProjectByUser = Users::where('id', Session::get('data')['user']['id'])
                                ->with(['projects' => function ($query) use ($search) {
                                    if ($search) {
                                        $query->where('name', 'like', '%' . $search . '%');
                                    }
                                }])
                                ->first();
        $projects = $cekProjectByUser->projects;
        $lastProject = $projects->sortByDesc('created_at')->first();

        // Hitung nomor urut berikutnya
        $nextNumber = '001';
        if ($lastProject) {
            // Ambil nomor urut proyek terakhir
            $lastNumber = (int) substr($lastProject->project_number, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return view('pages.user.report.index', [
            'cekProjectByUser' => $cekProjectByUser,
            'no' => $nextNumber,
            'search' => $search, // Kirimkan parameter pencarian ke view
        ]);

    }

    function detailProject(Request $request) {
        return view('pages.user.report.detail-project');
    }

    /***************** MEDIA ***************/
    public function mediaUser()
    {
        $defaultCategoryMedia = DB::table('media_categories')->first()->id;
        $categoryMedia = DB::table('media_categories')->get();
    	return view('pages.user.media.index', get_defined_vars());
    }


    public function mediaAdmin()
    {
        $categoryMedia = DB::table('media_categories')->get();
    	return view('pages.media.media-list.index', get_defined_vars());
    }

    public function mediaRequest()
    {
        $categoryMedia = DB::table('media_categories')->get();
        return view('pages.media.media-request.index', get_defined_vars());
    }

    public function createMedia($id = null)
    {
        $media = null;
        $isEdit = false;

        if ($id) {
            $media = Information::find($id);
            if (!$media) {
                return redirect()->route('pages.media.form')->withErrors('Media not found.');
            }

            $isEdit = true;
        }

// dd($arrayFieldNames);
return view('pages.media.create', get_defined_vars());

    }


    public function editMedia($id = null)
    {
        $media = null;
        $isEdit = false;

        if ($id) {
            $media = Information::find($id);
            if (!$media) {
                return redirect()->route('pages.media.form.create')->withErrors('Media not found.');
            }

            $isEdit = true;
        }

// dd($arrayFieldNames);
        return view('pages.media.edit', get_defined_vars());
    }

}
