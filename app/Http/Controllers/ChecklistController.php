<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Checklist;
use App\Item;

use Laravel\Lumen\Routing\Controller as BaseController;

class ChecklistController extends BaseController
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
        $data = $request->input('data.attributes');
        $checklist = new Checklist;
        $checklist->object_domain = $data['object_domain'];
        $checklist->object_id = $data['object_id'];
        $checklist->description = $data['description'];
        $checklist->is_completed = false;
        $checklist->completed_at = null;
        $checklist->updated_by = null;
        $checklist->due = $data['due'];
        $checklist->urgency = $data['urgency'];
        $checklist->created_at = new \DateTime();
        $checklist->updated_at = null;
        $checklist->save();

        foreach ($data['items'] as $item) {
            $items = new Item;  
            $items->name = $item;
            $items->due = $data['due'];
            $items->urgency = $data['urgency'];
            $items->created_at = new \DateTime();
            $items->updated_at = null;
            $items->save();
        }

        return response()->json(['data' => [
            'attributes' => Checklist::where('id', $checklist->id)->get(),
            'links' => [
                'self' => "http://192.168.33.10:8080/api/checklists/{$checklist->id}"
            ]
        ]]);

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
