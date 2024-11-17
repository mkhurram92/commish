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

        // Process referrors
        $selected_referror_ids = $request->input('referror_ids', []);
        DB::table('user_referrors')->where('user_id', $user_id)->delete(); // Remove old referror permissions
        foreach ($selected_referror_ids as $referror_id) {
            DB::table('user_referrors')->insert([
                'user_id' => $user_id,
                'referror_id' => $referror_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    public function permissionsData(Request $request)
    {
        $user_id = $request->user_id;

        // Fetch user information (name)
        $user = DB::table('users')
            ->select('fname', 'lname') // Assuming your table has fname and lname fields
            ->where('id', $user_id)
            ->first();

        // Fetch brokers
        $brokers = DB::table('brokers')
            ->select('id', 'surname', 'given_name', 'trading', 'is_individual')
            ->get();

        // Fetch existing broker permissions for the user
        $existingPermissions = DB::table('user_brokers')
            ->where('user_id', $user_id)
            ->pluck('broker_id')->toArray();

        // Fetch contacts (search_for = 1)
        $contacts = DB::table('contact_searches')
            ->select('id', 'surname', 'first_name', 'trading', 'individual')
            ->where('search_for', 1) // Only contacts (search_for = 1)
            ->get();

        // Fetch referrors (search_for = 2)
        $referrors = DB::table('contact_searches')
            ->select('id', 'surname', 'first_name', 'trading', 'individual')
            ->where('search_for', 2) // Only referrors (search_for = 2)
            ->get();


        // Fetch existing permissions for contacts (from user_contacts table)
        $existingContactPermissions = DB::table('user_contacts')
            ->where('user_id', $user_id)
            ->pluck('contact_id')->toArray();

        // Fetch existing permissions for referrors (from user_referrors table)
        $existingReferrorPermissions = DB::table('user_referrors')
            ->where('user_id', $user_id)
            ->pluck('referror_id')->toArray();

        // Pass data to the view
        return view('admin.users.permissions', [
            'user_id' => $user_id,
            'user' => $user,
            'brokers' => $brokers,
            'existingPermissions' => $existingPermissions,
            'contacts' => $contacts,
            'referrors' => $referrors,  // Added referrors
            'existingContactPermissions' => $existingContactPermissions,
            'existingReferrorPermissions' => $existingReferrorPermissions  // Added referror permissions
        ]);
    }
}
