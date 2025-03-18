<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use Illuminate\Http\Request;
use App\Users;
use App\Form;
use App\Project;
use App\LandingPageSetting;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __runValidation($field, $rules){

		$validator = Validator::make($field, $rules);

		if ($validator->fails()) {
			return response()->json([
				'message' => ucfirst($validator->errors()->first()),
                'status'  => false
			], 500);
		}
	}

	public function getForms(Request $request) {
        $project = Project::with('forms')->where('id', $request->project_id)->first();
        $forms = [];
        if($project) {
            $forms = $project->forms;
        }
        return [
            'project' => $project,
            'forms' => $forms,
        ];
    }

    public function getFormsUser(Request $request) {
        $projectId = $request->project_id;
        // dd($projectId);
        $_project = Users::with(['projects.forms'])
                    ->where('users.id', Auth::user()->id) // Specify 'users.id' to avoid ambiguity
                    ->whereHas('projects', function($query) use ($projectId) {
                        $query->where('projects.id', $projectId); // Specify 'projects.id' to avoid ambiguity
                    })
                    ->first();
        $project = $_project->projects->first();
        $forms = [];
        if($project) {
            $forms = $project->forms->toArray();
        }
        return [
            'project' => $project,
            'forms' => $forms,
        ];
    }


    public function getDataOfLandingPage($page_type) {
        // isi dari $page_type = 'home', 'contact-us' ...
        return LandingPageSetting::where('page_type', $page_type)->orderBy('section', 'asc')->get();
    }
}
