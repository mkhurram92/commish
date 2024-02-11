<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    public function index() {
        return view('admin.commission');
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:commission_model'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }


        $comm = new Commission();
        $comm->name = $request['name']; 
        $comm->created_at = Carbon::now('utc')->toDateTimeString();
        $comm->updated_at = Carbon::now('utc')->toDateTimeString();
        $comm->save();

        return response()->json(['success'=>'Record added successfully!']);
    }


}
