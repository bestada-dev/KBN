<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MediaCategory;
use DB;
use Auth;
use App\Project;

class MasterController extends Controller
{
    public function getRoles()
    {
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'User'],
            ['id' => 3, 'name' => 'Guest']
        ];
        return response()->json($roles);
    }

    public function getCategoryMedia()
    {
        $categories = MediaCategory::all();
        return response()->json($categories);
    }

    public function getTypeMedia()
    {
        $types = DB::table('media_types')->get();
        return response()->json($types);
    }

    public function getEducationTypes()
    {
        $educationTypes = [
            ['id' => 1, 'name' => 'SD'],
            ['id' => 2, 'name' => 'SMP'],
            ['id' => 3, 'name' => 'SMK'],
            ['id' => 4, 'name' => 'Kuliah']
        ];
        return response()->json($educationTypes);
    }

    public function getMarriedStatus()
    {
        $statuses = [
            ['id' => 1, 'name' => 'Done'],
            ['id' => 2, 'name' => 'Not yet'],
        ];
        return response()->json($statuses);
    }

    public function getEmployeeTypes()
    {
        $employeeTypes = [
            ['id' => 1, 'name' => 'Pegawai Tetap'],
            ['id' => 2, 'name' => 'Kontrak'],
            ['id' => 3, 'name' => 'Magang']
        ];
        return response()->json($employeeTypes);
    }

    public function getStatuses()
    {
        $statuses = [
            ['id' => 1, 'name' => 'Close'],
            ['id' => 2, 'name' => 'Makesure'],
            ['id' => 3, 'name' => 'Bimbang']
        ];
        return response()->json($statuses);
    }

    public function getAllProjects()
    {
        try {
            
            $user = Auth::user();
            if(!$user->is_admin) {
                $projects = $user->projects()->select('projects.id', 'name')->get();
            } else {
                $projects = Project::select('id', 'name')->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getFormsByProject($projectId)
    {
        try {
            // Find the project by ID
            $project = Project::findOrFail($projectId);
    
            // Get forms related to the project
            $forms = $project->forms()->get();
    
            return response()->json([
                'status' => 'success',
                'data' => $forms
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    



    public function getYears()
    {
        $startYear = 2023;
        $currentYear = date('Y');
        $years = [];

        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = [
                'id' => $year,
                'name' => (string) $year
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $years
        ], 200);

    }

    public function getMonths()
    {
        $months = [
            ['id' => 1, 'name' => 'January'],
            ['id' => 2, 'name' => 'February'],
            ['id' => 3, 'name' => 'March'],
            ['id' => 4, 'name' => 'April'],
            ['id' => 5, 'name' => 'May'],
            ['id' => 6, 'name' => 'June'],
            ['id' => 7, 'name' => 'July'],
            ['id' => 8, 'name' => 'August'],
            ['id' => 9, 'name' => 'September'],
            ['id' => 10, 'name' => 'October'],
            ['id' => 11, 'name' => 'November'],
            ['id' => 12, 'name' => 'December'],
        ];

        return response()->json([
            'status' => 'success',
            'data' => $months
        ], 200);
    }



}
