<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Checklist;

use Laravel\Lumen\Routing\Controller as BaseController;

class ItemController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = array_map(function($x){ return array_values($x)[0]; }, $request->input('data'));
        $checklist = Checklist::whereIn('item_id', $data)
            ->where('is_completed', 1)
            ->get();
        return response()->json(['data' => $checklist]);
    }

    public function incomplete(Request $request)
    {
        $data = array_map(function($x){ return array_values($x)[0]; }, $request->input('data'));
        $checklist = Checklist::whereIn('item_id', $data)
            ->where('is_completed', 0)
            ->get();
        return response()->json(['data' => $checklist]);
    }

    public function create(Request $request)
    {
        $data = $request->input('data');
    }

    public function update(Request $request)
    {
        # code...
    }

    public function delete($checklistId)
    {
        $checklist = Checklist::where('id', $checklistId)->delete();
        if ($checklist == 1) {
           return response(null, 204);
        }else {
            return response()->json(['status' => 404, 'error' => 'Not Found'], 404);
        }
    }
}
