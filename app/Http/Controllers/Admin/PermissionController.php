<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'broker_ids' => 'required|array',
            'broker_ids.*' => 'exists:brokers,id',
        ]);

        $user_id = $request->input('user_id');
        $broker_ids = $request->input('broker_ids');

        // Remove existing permissions for the user to avoid duplicates
        DB::table('user_brokers')->where('user_id', $user_id)->delete();

        // Insert new permissions for the selected brokers
        foreach ($broker_ids as $broker_id) {
            DB::table('user_brokers')->insert([
                'user_id' => $user_id,
                'broker_id' => $broker_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    //Broker Permissions Data
    public function permissionsData(Request $request)
    {
        $user_id = $request->user_id;
        // Log the incoming user_id
        //Log::info('User ID:', ['user_id' => $user_id]);

        // Fetch brokers
        $brokers = DB::table('brokers')->select('id', 'surname', 'given_name', 'trading', 'is_individual')->get();

        // Log the brokers fetched from the database
        //Log::info('Brokers:', ['brokers' => $brokers]);

        // Fetch existing permissions for the user
        $existingPermissions = DB::table('user_brokers')->where('user_id', $user_id)->pluck('broker_id')->toArray();

        // Log the existing permissions for the user
       // Log::info('Existing Permissions:', ['permissions' => $existingPermissions]);

        // Pass user_id, brokers, and existing permissions to the view
        return view('admin.users.permissions', compact('user_id', 'brokers', 'existingPermissions'));
    }
}
