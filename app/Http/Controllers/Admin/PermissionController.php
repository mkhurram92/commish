<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $user_id = $request->input('user_id');

        // Process brokers
        $selected_broker_ids = $request->input('broker_ids', []);
        DB::table('user_brokers')->where('user_id', $user_id)->delete(); // Remove old permissions
        foreach ($selected_broker_ids as $broker_id) {
            DB::table('user_brokers')->insert([
                'user_id' => $user_id,
                'broker_id' => $broker_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Process contacts
        $selected_contact_ids = $request->input('contact_ids', []);
        DB::table('user_contacts')->where('user_id', $user_id)->delete(); // Remove old permissions
        foreach ($selected_contact_ids as $contact_id) {
            DB::table('user_contacts')->insert([
                'user_id' => $user_id,
                'contact_id' => $contact_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    public function permissionsData(Request $request)
    {
        $user_id = $request->user_id;

        // Fetch brokers
        $brokers = DB::table('brokers')
            ->select('id', 'surname', 'given_name', 'trading', 'is_individual')
            ->get();

        // Fetch existing broker permissions for the user
        $existingPermissions = DB::table('user_brokers')
            ->where('user_id', $user_id)
            ->pluck('broker_id')->toArray();

        // Fetch all contacts from contact_searches table
        $contacts = DB::table('contact_searches')
            ->select('id', 'surname', 'first_name', 'trading', 'individual') // Include 'individual' field
            ->get();

        // Fetch existing permissions for contacts (from user_contacts table)
        $existingContactPermissions = DB::table('user_contacts')
            ->where('user_id', $user_id)
            ->pluck('contact_id')->toArray();

        // Pass user_id, brokers, existing broker permissions, contacts, and existing contact permissions to the view
        return view('admin.users.permissions', compact('user_id', 'brokers', 'existingPermissions', 'contacts', 'existingContactPermissions'));
    }
}
