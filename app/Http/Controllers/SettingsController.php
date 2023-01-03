<?php

namespace App\Http\Controllers;

use App\Status;
use App\Achievement;
use Illuminate\Http\Request;
use Response;
use Validator;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $statuses = Status::all();
        $achievements = Achievement::all();

        return view('settings/index',['statuses' => $statuses, 'achievements' => $achievements]);
    }

    public function getJsonStatus($id)
    {
        $status = Status::find($id);
        
        return Response::json(array('success' => true, 'data' => $status), 200);
    }

    
    public function updateStatus(Request $request, $id)
    {

       
        $status = Status::find($id);
        if (!$status) {
            return Response::json(404);
        }

        $validator = Validator::make($request->all(), [
            'points' => ['required'],
            
        ]);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'data' => $request->all(),
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $status->points = $request['points'];
        $status->save();
        
        return Response::json(array('success' => true, 'data' => $request->all()), 200);

    }

    
}
