<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Entity;
use App\Models\State;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function entityEdit() {
        $data = [];
        $data['states'] = State::all();
        $data['entity'] = Entity::findOrNew(1);
        return view('admin.entity.edit',$data);
    }

    public function entityUpdate(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'contact_person' => 'required|max:255',
            'phone_no' => 'required|max:255',
            'email' => 'required|email|max:255',
            //'web' => 'url',
        ]);

        try {
            $entity = Entity::findOrNew(1);

            if($entity) {
                $entity->name = $request['name'];
                $entity->contact_person = $request['contact_person'];
                $entity->phone_no = $request['phone_no'];
                $entity->email = $request['email'];
                $entity->fax = $request['fax'];
                $entity->web = $request['web'];
                $entity->address = $request['address'];
                $entity->state = $request['state'];
                $entity->city = $request['city'];
                $entity->suburb = $request['suburb'];
                $entity->street_address_level = $request['street_address_level'];
                $entity->street_address_number = $request['street_address_number'];
                $entity->street_address_name = $request['street_address_name'];
                $entity->street_address_type = $request['street_address_type'];
                $entity->pincode = $request['postcode'];
                $entity->abn = $request['abn'];
                $entity->is_active = 1;

                $entity->save();
                return response()->json(['success'=>'Record updated successfully!']);

            } else {
                return response()->json(['error' => 'Record id mismatch!']);
            }

        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }
}
