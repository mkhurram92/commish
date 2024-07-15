<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\Deals;
use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function getCounts()
    {
        $deals = Deal::latest()->value("id");
        $customers = ContactSearch::where("search_for", 1)->count();
        $referrers  = ContactSearch::where("search_for", 2)->count();
        $brokers  = Broker::count();

        return response()->json(compact("referrers", "brokers", "deals", "customers"));
    }

    public function profileSetting()
    {
        return view('admin.setting.profile');
    }

    public function editProfileSetting(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if ($request['email'] != $user->email) {
            $request->validate([
                'email' => 'required | unique:users',
            ]);
            $user->email = $request['email'];
        }

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

        if ($request['username'] != $user->username) {
            $request->validate([
                'username' => 'required | unique:users',
            ]);
            $user->username = $request['username'];
        }
        if ($request['password']) {
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'same:retype_password'
                ],
                'retype_password' => 'required',
            ]);
            $user->password = Hash::make($request['password']);
        }
        $user->fname = $request['first_name'];
        //$user->image = $imageName;
        $user->lname = $request['last_name'];
        $user->save();
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}