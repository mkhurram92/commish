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
}
