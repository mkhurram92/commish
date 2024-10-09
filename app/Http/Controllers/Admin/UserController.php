<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        //print_R('ff');die;
        return view('admin.users.index');
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        /*$imageName = '';
        if($request->file('profile_img')!=null){
            $image = $request->file('profile_img');
            $image_path = $image->store('public/profile/images/');
            $imageName = $request->profile_img->hashName();
        }*/

        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {

                $extension = $request->image->extension();
                $image_name = time() . "." . $extension;
                $request->image->storeAs('/public/profile/images/', $image_name);
            } else {

                $image_name = "default.png";
            }
        } else {

            $image_name = "default.png";
        }

        $user = new User();
        $user->fname = $request['fname'];
        $user->lname = $request['lname'];
        $user->email = $request['email'];
        $user->role = 'subadmin';
        $user->status = $request['status']; //($request['status'] == 1)?true:false;
        $user->username = $request['username'];
        $user->image = $image_name;
        $user->password = Hash::make($request['password']);
        $user->created_at = Carbon::now('utc')->toDateTimeString();
        $user->updated_at = Carbon::now('utc')->toDateTimeString();
        $user->save();
        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function getRecords(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = User::select(DB::raw('id,concat(fname," ",lname) name,role,username,email,if(status=1,"Enable","Disable") as status,DATE_FORMAT(created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->orderBy('created_at', 'desc')->where('role', '!=', 'admin')->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" onclick="return editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';
                        if ($row->role != 'admin') {
                            //$actionBtn .= '<a onclick="javascript:deleteRecord(this);" data-url="'.route('admin.user.delete',$row->id).'" href="javascript:void(0);" data-id="'.$row->id.'" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"></i></a>';
                            $actionBtn .= '<a onclick="javascript:return setPermission(this);" href="javascript:void(0);" data-id="' . $row->id . '" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-success" title="Module Permissions"><i class="pe-7s-users btn-icon-wrapper"></i></a>';
                            $actionBtn .= '<a href="' . route('admin.user.permissions', ['user_id' => $row->id]) . '" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-info" title="Org Permissions"><i class="pe-7s-settings btn-icon-wrapper"></i></a>';

                        }
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getRecord(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => "required|integer"
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }
            $record = User::find($request->id);
            if (!$record) {
                return response()->json(['error' => 'Record not found']);
            }
            return response()->json(['success' => true, 'payload' => $record]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users,id,' . $request->edit_id,
            'username' => 'required|unique:users,id,' . $request->edit_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $user = User::find($request->edit_id);

        /*      if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {

                $extension = $request->image->extension();
                $image_name = time().".".$extension;
                $request->image->storeAs('/public/profile/images/', $image_name);
                $user->image = $image_name;
            }
        }
*/

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                // Define the path to store the image in the public directory
                $image_path = 'profile/images/';
                $image_name = $request->image->hashName();
                // Store the image in the public/profile/images directory
                $request->image->move(public_path($image_path), $image_name);
                $user->image = $image_name;
            }
        }

        if (!$user) {
            return response()->json(['error' => 'Invalid record!']);
        }
        if ($user->role == 'admin') {
            return response()->json(['error' => 'Invalid record!']);
        }

        $user->fname = $request['fname'];
        $user->lname = $request['lname'];
        $user->email = $request['email'];

        $user->role = 'subadmin';
        $user->status = $request['estatus']; //($request['status'] == 1)?true:false;
        $user->username = $request['username'];
        if (!empty($request['password']))
            $user->password = Hash::make($request['password']);
        $user->save();
        return redirect()->back()->with('success', 'Record updated successfully!');
    }

    public function deleteData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|integer"
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['error' => 'Invalid record!']);
        }
        $user->delete();
        return response()->json(['success' => 'Record deleted successfully!']);
    }

    public function getModules(Request $request)
    {
        $record = [];
        $rel_data = User::with('modules')->where('id', $request->id)->first();
        $record['selrec'] = $rel_data->modules->pluck('id');
        $record['modules'] = Modules::all();
        if (!$record) {
            return response()->json(['error' => 'Record not found']);
        }
        return response()->json(['success' => true, 'payload' => $record]);
    }

    public function saveModules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_user_id' => "required|integer",
            'module' => 'array'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = User::find($request->per_user_id);
        if (!$user) {
            return response()->json(['error' => 'Invalid record!']);
        }
        $user->modules()->sync($request->module);
        $user->save();
        return response()->json(['success' => 'Record updated successfully!']);
    }

    
}
