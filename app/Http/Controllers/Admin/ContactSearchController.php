<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABP;
use App\Models\Broker;
use App\Models\BrokerStaff;
use App\Models\City;
use App\Models\User;
use App\Models\ClientReferral;
use App\Models\ClientRelation;
use App\Models\ClientRole;
use App\Models\CommissionData;
use App\Models\Commission;
use App\Models\ContactAddress;
use App\Models\StreetType;
use App\Models\Deal;
use App\Models\Industry;
use App\Models\Processor;
use App\Models\ReferrerCommission;
use App\Models\Lenders;
use App\Models\RefferorRelation;
use App\Models\Service;
use App\Models\State;
use App\Models\TaskRelation;
use App\Models\ContactSearch;
use App\Models\RefferorCommissionSchedule;
use App\Models\ReferrerCommissionModelinstitute;
use App\Models\ReferrerCommissionModel;
use App\Models\Relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class ContactSearchController extends Controller
{
    public function contactList()
    {
        $data = [];
        $data['states'] = State::all();
        $data['industries'] = Industry::all();
        return view('admin.contact.list', $data);
    }
    public function contactAdd()
    {
        $relations = Relationship::all();
        $data['relations'] = $relations;
        $data['processors'] = Processor::all();
        $data['clients'] = ContactSearch::select([
            'id',
            DB::raw('CONCAT_WS(" ", trading, trust_name, CONCAT(surname, " ", first_name, " ", middle_name), preferred_name, entity_name) as display_name'),
            DB::raw('CONCAT(first_name, " ", middle_name, " ", surname) as client_name'),
            'trading',
            'trust_name',
            'surname',
            'first_name',
            'middle_name',
            'preferred_name',
            'entity_name',
        ])
            ->where('search_for', 1)
            ->whereNull('deleted_at')
            ->get();
        $data['referrers'] = ContactSearch::select([
            'id',
            DB::raw('CONCAT_WS(" ", trading, trust_name, CONCAT(surname, " ", first_name, " ", middle_name), preferred_name, entity_name) as display_name'),
            'trading',
            'trust_name',
            'surname',
            'first_name',
            'middle_name',
            'preferred_name',
            'entity_name',
        ])
            ->where('search_for', 2)
            ->whereNull('deleted_at')
            ->get();

        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['abps'] = Broker::select([
            'id',
            DB::raw('CONCAT_WS(" ", trading, trust_name, surname, given_name) as display_name'),
            'trading',
            'trust_name',
            'surname',
            'given_name',
        ])
            ->where('is_bdm', 0)
            ->whereNull('deleted_at')
            ->get();

        $data['states'] = State::all();
        return view('admin.contact.add_edit', $data);
    }

    public function ReferrerAdd()
    {
        $relations = Relationship::all();
        $data['relations'] = $relations;
        $data['clients'] = ContactSearch::select(
            DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 1)->whereNull('deleted_at')->get();
        $data['referrers'] = ContactSearch::select(
            DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name, CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 2)->whereNull('deleted_at')->get();
        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['states'] = State::all();
        $data['abps'] =  Broker::select(
            DB::raw('id,CONCAT(given_name," ",surname) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 0)->whereNull('deleted_at')->get();
        $data['states'] = State::all();
        return view('admin.contact.add_edit_referror', $data);
    }

    public function contactPost(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'required|in:Individual,Company',
            'surname' => $request->input('client_type') == 'Individual' ? 'required' : '',
            'preferred_name' => $request->input('client_type') == 'Individual' ? 'required' : '',
            'trading' => $request->input('client_type') == 'Company' ? 'required' : '',
            'principle_contact' => $request->input('client_type') == 'Company' ? 'required' : '',
            'street_number' => 'required',
            'postal_street_number' => 'required',
        ], [], [
            //'search_for' => 'Type',
            'client_type' => 'Type',
            'surname' => 'Surname',
            'preferred_name' => 'Given Name',
            'trading' => 'Trading Name',
            'principle_contact' => 'Principle Contact',
            'street_number' => 'Street Number',
            'postal_street_number' => 'Post Code',
        ]);

        try {
            DB::beginTransaction();
            $contact = new ContactSearch();
            if ($request['dob'] != '') {
                $tempdob = str_replace('/', '-', $request['dob']);
                $request['dob'] = date('Y-m-d', strtotime($tempdob));
            }
            $contact->search_for = $request['search_for'];
            $contact->individual = $request->input('client_type') == 'Individual' ? 1 : 2;
            $contact->trading = $request['trading'];
            $contact->trust_name = $request['trust_name'];
            $contact->surname = $request['surname'];
            $contact->first_name = $request['first_name'];
            $contact->preferred_name = $request['preferred_name'];
            $contact->middle_name = $request['middle_name'];
            $contact->dob = $request['dob'];
            $contact->status = $request->input('status') == '1' ? 1 : 0;
            $contact->has_gst = $request->input('has_gst') == '1' ? 1 : 0;
            $contact->role_title = $request['role_title'];
            $contact->role = $request['role'];
            $contact->entity_name = $request['entity_name'];
            $contact->principle_contact = $request['principle_contact'];
            $contact->work_phone = $request['work_phone'];
            $contact->home_phone = $request['home_phone'];
            $contact->mobile_phone = $request['mobile_phone'];
            $contact->fax = $request['fax'];
            $contact->email = $request['email'];
            $contact->web = $request['web'];
            $contact->client_industry = $request['client_industry'];
            $contact->other_industry = ($request['client_industry'] == 27) ? $request['other_industry'] : '';
            $contact->note = $request['note'];
            $contact->acc_name = $request['acc_name'];
            $contact->acc_no = $request['acc_no'];
            $contact->bank = $request['bank'];
            $contact->bsb = $request['bsb'];
            $contact->abp = $request['abp'];
            $contact->abn = $request['abn'];
            $contact->referrer_type = $request['referrer_type'];
            $contact->referrer_id = ($request['referrer_type'] == 2) ? $request['client_id'] : $request['referrer_id']; //$request['referrer_id'];
            $contact->other_referrer = $request['other_referrer'];
            $contact->social_media_link = $request['social_media_link'];
            $contact->refferor_relation_to_client = $request['refferor_relation_to_client'];
            $contact->refferor_note = $request['refferor_note'];
            $contact->created_by = Auth::user()->id;
            $contact->save();

            // Save user_id and contact_id in user_contacts table
            if ($contact->id > 0) {
                DB::table('user_contacts')->insert([
                    'user_id' => Auth::user()->id,
                    'contact_id' => $contact->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            if ($contact->id > 0) {
                if (!empty($request->referrors)) {
                    $tempArr = [];
                    foreach ($request->referrors as $referrors) {
                        if ($referrors['referrer_id'] > 0 && $referrors['upfront'] != '') {
                            $tempArr[] = [
                                'client_id' => $contact->id,
                                'referrer_id' => $referrors['referrer_id'],
                                'upfront' => $referrors['upfront'],
                                'trail' => $referrors['trail'],
                                'comm_per_deal' => $referrors['comm_per_deal'],
                            ];
                        }
                    }
                    if (!empty($tempArr)) {
                        ReferrerCommission::insert($tempArr);
                    }
                }

                // store contact address
                ContactAddress::create([
                    "contact_id" => $contact->id,
                    "unit" => $request['street_number'],
                    "street_name" => $request['street_name'] ?? NULL,
                    "city" => $request['suburb'] ?? NULL,
                    "state" => $request['state'] ?? NULL,
                    "postal_code" => $request['postal_code'] ?? NULL,
                    "address_type" => 1 ?? NULL,
                ]);
                ContactAddress::create([
                    "contact_id" => $contact->id,
                    "unit" => $request['postal_street_number'],
                    "street_name" => $request['postal_street_name'] ?? NULL,
                    "city" => $request['postal_suburb'] ?? NULL,
                    "state" => $request['mail_state'] ?? NULL,
                    "postal_code" => $request['mail_postal_code'] ?? NULL,
                    "address_type" => 2 ?? NULL,
                ]);
            }
            if ($contact->id > 0) {
                if (!empty($request->relationship)) {
                    $tempArr = [];
                    foreach ($request->relationship as $relation) {
                        if ($relation['linked_to'] > 0 && $relation['relation'] > 0) {
                            $tempArr[] = [
                                'client_id' => $contact->id,
                                'relation_with' => $relation['linked_to'],
                                'relation' => $relation['relation']
                            ];
                        }
                    }
                    if (!empty($tempArr)) {
                        ClientRelation::insert($tempArr);
                    }
                }
                if (!empty($request['client_referrals'])) {
                    $tempArr = [];
                    foreach ($request['client_referrals'] as $client_referral) {
                        if ($client_referral['referred_to'] != '' && $client_referral['service_id'] > 0) {
                            $tempdate = str_replace('/', '-', $client_referral['date']);
                            $tempArr[] = [
                                'client_id' => $contact->id,
                                'referred_to' => $client_referral['referred_to'],
                                'notes' => $client_referral['notes'],
                                'date' => date('Y-m-d', strtotime($tempdate)),
                                'service_id' => $client_referral['service_id']
                            ];
                        }
                    }
                    if (!empty($tempArr)) {
                        ClientReferral::insert($tempArr);
                    }
                }
                if (!empty($request->tasks)) {
                    $taskArr = [];
                    foreach ($request->tasks as $task) {
                        if ($task['followup_date'] != '' && $task['processor'] != '' && $task['details'] != '') {
                            $tempdob = str_replace('/', '-', $task['followup_date']);
                            $task['followup_date'] = date('Y-m-d', strtotime($tempdob));
                            $taskArr[] = [
                                'client_id' => $contact->id,
                                'followup_date' => $task['followup_date'],
                                'processor' => $task['processor'],
                                'details' => $task['details'],
                                'created_by' => Auth::user()->id,
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString()
                            ];
                        }
                    }
                    if (!empty($taskArr)) {
                        TaskRelation::insert($taskArr);
                    }
                }
                DB::commit();
            } else {
                DB::rollback();
                return response()->json(['error' => "Something went wrong while save record!"]);
            }
            return response()->json(['success' => 'Record added successfully!', 'id' => encrypt($contact->id)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function contactEdit($id)
    {
        $contact = ContactSearch::with(['withAddress'])->select(DB::raw('contact_searches.*,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"' . $this->mysql_date_format . '"),"") as dob'))->with(['relations', 'tasks'])->where('id', decrypt($id))->first();
        $data['processors'] = Processor::all();
        $data['relations'] = Relationship::all();

        $data['clients'] = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 1)->whereNull('deleted_at')->get();

        $data['referrers'] = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 2)->whereNull('deleted_at')->get();

        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['abps'] =  Broker::select(
            DB::raw('id,CONCAT(given_name," ",surname) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 0)->whereNull('deleted_at')->get();
        $data['states'] = State::all();

        $data['contact'] = $contact;
        $data['client_referrals'] = ClientReferral::whereClientId(decrypt($id))->get();
        if ($contact) {
            $tempalte = 'add_edit';
            if ($contact->search_for == 2) {
                $tempalte = 'add_edit_referror';
            }
            return view('admin.contact.' . $tempalte, $data);
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function contactView($id)
    {

        $contact = ContactSearch::with(['withAddress', 'withAddress.withState', 'withOtherReferrer', 'withReferrerRelationship', 'withReferrerToExistingClient', 'withStaffReferral'])->select(DB::raw(
            'contact_searches.*, CONCAT_WS(",",reffered_by_client.trading,reffered_by_client.trust_name,CONCAT(reffered_by_client.surname," ",reffered_by_client.first_name," ",reffered_by_client.middle_name),
            reffered_by_client.preferred_name,reffered_by_client.entity_name) as reffered_by_client_display_name, 
            IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"' . $this->mysql_date_format . '"),"") as dob,
            contact_searches.status as status'
        ))
            ->leftJoin('contact_searches as reffered_by_client', 'reffered_by_client.id', '=', 'contact_searches.referrer_id')
            ->where('contact_searches.id', decrypt($id))
            ->first();
        $address = ContactAddress::where("contact_id", $contact?->id)->where("address_type", 1)->orderBy("id", "DESC")->first();
        $postalAddress = ContactAddress::where("contact_id", $contact?->id)->where("address_type", 2)->orderBy("id", "DESC")->first();

        $client_referrals = ClientReferral::where('client_id', decrypt($id))
            ->with(['service'])
            ->latest()
            ->get();

        if ($contact->referrer_type == 1) {

            $contact1 = ContactSearch::find($contact->other_referrer);

            $first_name = $contact1->first_name ?? '';
            $surname = $contact1->surname ?? '';
            $referrer_name = $first_name . ' ' . $surname;

            $title = 'Referrer';
        } elseif ($contact->referrer_type == 2) {

            $contact1 = ContactSearch::find($contact->referrer_id);

            $first_name = $contact1->first_name ?? '';
            $surname = $contact1->surname ?? '';
            $referrer_name = $first_name . ' ' . $surname;


            $title = 'Existing Clients';
        } elseif ($contact->referrer_type == 3) {

            $contact1 = Processor::find($contact->referrer_id);

            $referrer_name = $contact1->name ?? '';


            $title = 'Staff Referral';
        } elseif ($contact->referrer_type == 4) {
            if ($contact->social_media_link == 1) {
                $referrer_name = 'Facebook';
            } elseif ($contact->social_media_link == 2) {
                $referrer_name = 'Twitter';
            } elseif ($contact->social_media_link == 3) {
                $referrer_name = 'Instagram';
            } elseif ($contact->social_media_link == 4) {
                $referrer_name = 'Youtube';
            } else {
                $referrer_name = '';
            }

            $title = 'Social Media';
        } else {
            $referrer_name = '';
            $title = '';
        }

        if ($contact) {
            $processors = Processor::get();
            $task_users = BrokerStaff::where("broker_id", 8)->get();
            $services = Service::get();
            $relations = Relationship::get();
            $clients = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->where('search_for', 1)->whereNull('deleted_at')->get();
            $referrers = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->where('search_for', 2)->whereNull('deleted_at')->get();
            return view('admin.contact.view', compact('contact', 'processors', 'client_referrals', 'referrer_name', 'title', 'services', 'relations', 'clients', 'referrers', 'address', 'postalAddress', 'task_users'));
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function contactViewReferrer($id)
    {
        $contact = ContactSearch::select(DB::raw('contact_searches.*,rcommmodel_tbl.id as rcomid,
        rcommmodel_tbl.commission_model_id,rcommmodel_tbl.upfront_per,rcommmodel_tbl.trail_per,rcommmodel_tbl.flat_fee_chrg,
        abp.id as contact_abp_id,CONCAT_WS(",",abp.trading,abp.trust_name,abp.surname,abp.given_name) abp_name,
        "N/A" as state_name,
        "" as city_name,
        IF(contact_searches.client_industry=27,
        contact_searches.other_industry,
        industries.name) as industry_name,
        "N/A" as service_1_display,
        "N/A" as service_2_display,
        CONCAT_WS(",",reffered_by_client.trading,reffered_by_client.trust_name,
        CONCAT(reffered_by_client.surname," ",reffered_by_client.first_name," ",reffered_by_client.middle_name),
        reffered_by_client.preferred_name,reffered_by_client.entity_name) as reffered_by_client_display_name, 
        "N/A" as refferor_relation_display_client,
        IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"' . $this->mysql_date_format . '"),"") as dob,
        "N/A" as mail_city,"N/A" as mail_state'))
            ->with(['relationDetails', 'tasks'])
            ->leftJoin('brokers as abp', 'abp.id', '=', 'contact_searches.abp')
            ->leftJoin('industries', 'industries.id', '=', 'contact_searches.client_industry')
            ->leftJoin('contact_searches as reffered_by_client', 'reffered_by_client.id', '=', 'contact_searches.referrer_id')
            ->where('contact_searches.id', decrypt($id))
            ->leftJoin('referrer_commission_model as rcommmodel_tbl', 'rcommmodel_tbl.referrer_id', '=', 'contact_searches.id')
            ->first();
        $address = ContactAddress::where("contact_id", $contact?->id)->where("address_type", 1)->orderBy("id", "DESC")->first();
        $postalAddress = ContactAddress::where("contact_id", $contact?->id)->where("address_type", 2)->orderBy("id", "DESC")->first();
        $client_referrals = ClientReferral::where('client_id', decrypt($id))
            ->with(['service'])
            ->get();
        if ($contact) {
            $task_users = BrokerStaff::where("broker_id", 8)->get();
            $institutes = Lenders::all();
            $commission_models = Commission::all();
            return view('admin.contact.view_referrer', compact('contact', 'client_referrals', 'institutes', 'commission_models', "address", "postalAddress", 'task_users'));
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function contactUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'search_for' => 'required|max:255',
            'client_type' => 'required|in:Individual,Company',
            'surname' => $request->input('client_type') == 'Individual' ? 'required' : '',
            'preferred_name' => $request->input('client_type') == 'Individual' ? 'required' : '',
            'trading' => $request->input('client_type') == 'Company' ? 'required' : '',
            'principle_contact' => $request->input('client_type') == 'Company' ? 'required' : '',
            'street_number' => 'required',
            'postal_street_number' => 'required',
        ], [], [
            'search_for' => 'Type',
            'client_type' => 'Client Type',
            'surname' => 'Surname',
            'preferred_name' => 'Given Name',
            'trading' => 'Trading Name',
            'principle_contact' => 'Principle Contact',
        ]);
        try {
            $contact = ContactSearch::find(decrypt($id));

            if ($contact) {
                if ($request['dob'] != '') {
                    $tempdob = str_replace('/', '-', $request['dob']);
                    $request['dob'] = date('Y-m-d', strtotime($tempdob));
                }
                if ($request['date_1'] != '') {
                    $tempdob = str_replace('/', '-', $request['date_1']);
                    $request['date_1'] = date('Y-m-d', strtotime($tempdob));
                }
                if ($request['date_2'] != '') {
                    $tempdob = str_replace('/', '-', $request['date_2']);
                    $request['date_2'] = date('Y-m-d', strtotime($tempdob));
                }

                $client_type = 0;
                if ($request->input('client_type') == 'Individual') {
                    $client_type = 1;
                } else if ($request->input('client_type') == 'Company') {
                    $client_type = 2;
                }
                DB::beginTransaction();
                $contact->search_for = $request['search_for'];
                $contact->individual = $client_type;
                $contact->trading = $request['trading'];
                $contact->trust_name = $request['trust_name'];
                $contact->surname = $request['surname'];
                $contact->first_name = $request['first_name'];
                $contact->preferred_name = $request['preferred_name'];
                $contact->middle_name = $request['middle_name'];
                $contact->dob = $request['dob'];
                $contact->status = $request->input('status') == '1' ? 1 : 0;
                $contact->has_gst = $request->input('has_gst') == '1' ? 1 : 0;
                $contact->role_title = $request['role_title'];
                $contact->role = $request['role'];
                $contact->entity_name = $request['entity_name'];
                $contact->principle_contact = $request['principle_contact'];
                $contact->work_phone = $request['work_phone'];
                $contact->home_phone = $request['home_phone'];
                $contact->mobile_phone = $request['mobile_phone'];
                $contact->fax = $request['fax'];
                $contact->email = $request['email'];
                $contact->web = $request['web'];
                $contact->client_industry = $request['client_industry'];
                $contact->other_industry = ($request['client_industry'] == 27) ? $request['other_industry'] : '';
                $contact->note = $request['note'];
                $contact->acc_name = $request['acc_name'];
                $contact->acc_no = $request['acc_no'];
                $contact->bank = $request['bank'];
                $contact->bsb = $request['bsb'];
                $contact->abp = $request['abp'];
                $contact->abn = $request['abn'];
                $contact->referrer_type = $request['referrer_type'];
                $contact->referrer_id = ($request['referrer_type'] == 2) ? $request['client_id'] : $request['referrer_id']; //$request['referrer_id'];
                $contact->other_referrer = $request['other_referrer'];
                $contact->social_media_link = $request['social_media_link'];
                $contact->refferor_relation_to_client = $request['refferor_relation_to_client'];
                $contact->refferor_note = $request['refferor_note'];
                // $contact->last_updated_by = Auth::user()->id;
                $contact->save();
                if ($contact->id > 0) {
                    if (!empty($request->referrors)) {
                        $tempArr = [];
                        foreach ($request->referrors as $referrors) {
                            //if ($referrors['referrer_id'] > 0 && $referrors['entity'] != '') {
                            if ($referrors['referrer_id'] > 0) {
                                $tempArr[] = [
                                    'client_id' => $contact->id,
                                    'referrer_id' => $referrors['referrer_id'],
                                    'upfront' => $referrors['upfront'],
                                    'trail' => $referrors['trail'],
                                    'comm_per_deal' => $referrors['comm_per_deal'],
                                ];
                            }
                        }
                        if (!empty($tempArr)) {
                            ReferrerCommission::insert($tempArr);
                        }
                    }
                    ContactAddress::updateOrCreate([
                        "address_type" => 1,
                        "contact_id" => $contact->id,
                    ], [
                        "contact_id" => $contact->id,
                        "unit" => $request['street_number'],
                        "street_name" => $request['street_name'] ?? NULL,
                        //"street_type" => $request['street_type'] ?? NULL,
                        "city" => $request['suburb'] ?? NULL,
                        "state" => $request['state'] ?? NULL,
                        "postal_code" => $request['postal_code'] ?? NULL,
                        "address_type" => 1 ?? NULL,
                    ]);
                    ContactAddress::updateOrCreate([
                        "address_type" => 2,
                        "contact_id" => $contact->id,
                    ], [
                        "contact_id" => $contact->id,
                        "unit" => $request['postal_street_number'],
                        "street_name" => $request['postal_street_name'] ?? NULL,
                        //"street_type" => $request['postal_street_type'] ?? NULL,
                        "city" => $request['postal_suburb'] ?? NULL,
                        "state" => $request['mail_state'] ?? NULL,
                        "postal_code" => $request['mail_postal_code'] ?? NULL,
                        "address_type" => 2 ?? NULL,
                    ]);
                }

                DB::commit();
                return response()->json(['success' => 'Record updated successfully!', 'id' => $id]);
            } else {
                return response()->json(['error' => "Record not found!"]);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function contactDelete($id)
    {
        $contact = ContactSearch::find(decrypt($id));
        if ($contact) {
            $contact->delete();
            return redirect()->back()->with('success', 'Contact deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function getRecords(Request $request)
    {
        try {
            $input =  $request->all();
            $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');
            $start = 0;
            $rowperpage = 25;
            if (isset($datatableData['iDisplayStart']) && $datatableData['iDisplayLength'] != '-1') {
                $start =  intval($datatableData['iDisplayStart']);
                $rowperpage = intval($datatableData['iDisplayLength']);
            }
            if ($datatableData['type'] == 1 || $datatableData['type'] == 2) {
                // Ordering
                $sortableColumnsName = [
                    'contact_searches.id',
                    'contact_searches.surname',
                    'contact_searches.preferred_name',
                    'contact_searches.trading',
                    'contact_searches.created_at',
                    'contact_searches.updated_at',
                    '',
                ];
                $columnName              = "contact_searches.created_at";
                $columnSortOrder              = "DESC";
                if (isset($datatableData['iSortCol_0'])) {
                    $sOrder = "ORDER BY  ";
                    for ($i = 0; $i < intval($datatableData['iSortingCols']); $i++) {
                        if ((bool) $datatableData['bSortable_' . intval($datatableData['iSortCol_' . $i])] == TRUE && isset($datatableData['sSortDir_' . $i])) {
                            $columnSortOrder = (strcasecmp($datatableData['sSortDir_' . $i], 'ASC') == 0) ? 'ASC' : 'DESC';
                            $columnName  = $sortableColumnsName[intval($datatableData['iSortCol_' . $i])];
                        }
                    }
                    if ($columnName == "") {
                        $columnName              = "contact_searches.created_at";
                        $columnSortOrder              = "DESC";
                    }
                }
                $searchValue = '';
                if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                    $searchValue = $datatableData['sSearch'];
                }
                // Total records
                $totalRecords = ContactSearch::select(DB::raw('count(*) as allcount'))->whereNull('deleted_at')->count();
                $filterSql = ContactSearch::select(DB::raw('count(*) as allcount'))->whereNull('contact_searches.deleted_at')
                    ->leftJoin('brokers as abp', 'abp.id', '=', 'contact_searches.abp');
                if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                    $filterSql = $filterSql->where('contact_searches.search_for', '=', $datatableData['type']);
                }
                if (isset($datatableData['surname']) && $datatableData['surname'] != '') {
                    $filterSql = $filterSql->where('contact_searches.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] != '') {
                    $filterSql = $filterSql->where('contact_searches.preferred_name', '=', $datatableData['given_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] != '') {
                    $filterSql = $filterSql->where('contact_searches.trading', '=', $datatableData['trading']);
                }
                if ($searchValue != '') {
                    $filterSql = $filterSql->where(function ($query) use ($searchValue) {
                        return $query->where('contact_searches.trading', 'LIKE', $searchValue . '%')
                            ->orWhere('contact_searches.surname', 'like',  $searchValue . '%')
                            ->orWhere('contact_searches.preferred_name', 'like',  $searchValue . '%')
                            ->orWhere('abp.given_name', 'like',  $searchValue . '%')
                            ->orWhere('abp.surname', 'like',  $searchValue . '%');
                    });
                }

                $totalRecordswithFilter = $filterSql->count();
                // Fetch records
                $sql = ContactSearch::select(DB::raw('contact_searches.id,(CASE contact_searches.search_for WHEN 1 THEN "Client" WHEN 2 THEN "Referror" END) as type,contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.preferred_name,contact_searches.entity_name,contact_searches.principle_contact,CONCAT_WS(" ",abp.surname,abp.given_name) as abp_name,contact_searches.dob,contact_searches.email,"" as state_name,"" as city_name,contact_searches.work_phone,contact_searches.home_phone,contact_searches.mobile_phone, DATE_FORMAT(contact_searches.created_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_created_at, DATE_FORMAT(contact_searches.updated_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_updated_at,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"' . $this->mysql_date_format . '"),"") as dob, "-" as address,"" as postal_code'))
                    ->orderBy($columnName, $columnSortOrder)
                    ->whereNull('contact_searches.deleted_at')
                    ->leftJoin('brokers as abp', 'abp.id', '=', 'contact_searches.abp')
                    ->skip($start)
                    ->take($rowperpage);
                if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                    $sql = $sql->where('contact_searches.search_for', '=', $datatableData['type']);
                }
                if (isset($datatableData['surname']) && $datatableData['surname'] != '') {
                    $sql = $sql->where('contact_searches.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] != '') {
                    $sql = $sql->where('contact_searches.preferred_name', '=', $datatableData['given_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] != '') {
                    $sql = $sql->where('contact_searches.trading', '=', $datatableData['trading']);
                }
                if ($searchValue != '') {
                    $sql = $sql->where(function ($query) use ($searchValue) {
                        return $query->where('contact_searches.trading', 'like',  $searchValue . '%')
                            ->orWhere('contact_searches.surname', 'like',  $searchValue . '%')
                            ->orWhere('contact_searches.preferred_name', 'like', $searchValue . '%');
                    });
                }

                $records = $sql->get();
                if (count($records) > 0) {
                    foreach ($records as $rkey => $record) {
                        $records[$rkey]['encrypt_id'] = encrypt($record->id);
                    }
                }
            } else {
                $sortableColumnsName = [
                    'brokers.id',
                    'brokers.surname',
                    'brokers.given_name',
                    'brokers.trading',
                    'brokers.created_at',
                    'brokers.updated_at',
                    '',
                ];
                $columnName              = "brokers.created_at";
                $columnSortOrder              = "DESC";
                if (isset($datatableData['iSortCol_0'])) {
                    $sOrder = "ORDER BY  ";
                    for ($i = 0; $i < intval($datatableData['iSortingCols']); $i++) {
                        if ((bool) $datatableData['bSortable_' . intval($datatableData['iSortCol_' . $i])] == TRUE && isset($datatableData['sSortDir_' . $i])) {
                            $columnSortOrder = (strcasecmp($datatableData['sSortDir_' . $i], 'ASC') == 0) ? 'ASC' : 'DESC';
                            $columnName  = $sortableColumnsName[intval($datatableData['iSortCol_' . $i])];
                        }
                    }
                    if ($columnName == "") {
                        $columnName              = "brokers.created_at";
                        $columnSortOrder              = "DESC";
                    }
                }
                $searchValue = '';
                if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                    $searchValue = $datatableData['sSearch'];
                }
                // Total records
                $totalRecords = Broker::select(DB::raw('count(*) as allcount'))
                    ->whereNull('deleted_at')
                    ->count();
                $filterSql = Broker::select(DB::raw('count(*) as allcount'))
                    ->whereNull('brokers.deleted_at')
                    ->leftJoin('brokers as parent_brokers', 'parent_brokers.id', '=', 'brokers.parent_broker');
                if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                    if ($datatableData['type'] == 3) {
                        $filterSql = $filterSql->where('brokers.parent_broker', '=', 0);
                    } else {
                        $filterSql = $filterSql->where('brokers.parent_broker', '>', 0);
                    }
                }
                if (isset($datatableData['surname']) && $datatableData['surname'] != '') {
                    $filterSql = $filterSql->where('brokers.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] != '') {
                    $filterSql = $filterSql->where('brokers.given_name', '=', $datatableData['given_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] != '') {
                    $filterSql = $filterSql->where('brokers.trading', '=', $datatableData['trading']);
                }

                if ($searchValue != '') {
                    $filterSql = $filterSql->where(function ($query) use ($searchValue) {
                        return $query->where('brokers.trading', 'like',  $searchValue . '%')
                            ->orWhere('brokers.surname', 'like',  $searchValue . '%')
                            ->orWhere('brokers.given_name', 'like',  $searchValue . '%');
                    });
                }

                $totalRecordswithFilter = $filterSql->count();
                // Fetch records
                $sql = Broker::select(DB::raw('brokers.id,brokers.trading,brokers.trust_name,brokers.surname,brokers.given_name as preferred_name,DATE_FORMAT(brokers.dob,"' . $this->mysql_date_format . '") as dob,"" as state_name,"N/A" as city_name,brokers.pincode,brokers.work_phone,brokers.home_phone,brokers.mobile_phone, DATE_FORMAT(brokers.created_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_created_at, DATE_FORMAT(brokers.updated_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_updated_at, brokers.business as address,parent_brokers.entity_name as abp_name'))
                    ->orderBy($columnName, $columnSortOrder)
                    ->whereNull('brokers.deleted_at')
                    ->leftJoin('brokers as parent_brokers', 'parent_brokers.id', '=', 'brokers.parent_broker')
                    ->skip($start)
                    ->take($rowperpage);
                if (isset($datatableData['surname']) && $datatableData['surname'] != '') {
                    $sql = $sql->where('brokers.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] != '') {
                    $sql = $sql->where('brokers.given_name', '=', $datatableData['given_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] != '') {
                    $sql = $sql->where('brokers.trading', '=', $datatableData['trading']);
                }
                if (isset($datatableData['id']) && $datatableData['id'] > 0) {
                    $sql = $sql->where('brokers.id', '=', $datatableData['id']);
                }
                if ($searchValue != '') {
                    $sql = $sql->where(function ($query) use ($searchValue) {
                        return $query->where('brokers.trading', 'like',  $searchValue . '%')
                            ->orWhere('brokers.surname', 'like',  $searchValue . '%')
                            ->orWhere('brokers.given_name', 'like',  $searchValue . '%');
                    });
                }
                $records = $sql->get();
                if (count($records) > 0) {
                    foreach ($records as $rkey => $record) {
                        $records[$rkey]['encrypt_id'] = encrypt($record->id);
                    }
                }
            }
            $response = array(
                "sEcho" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                "draw" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $records
            );
            return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getSgDCommissions(Request $request)
    {
        try {
            $input =  $request->all();
            if ($request->ajax()) {
                $contact = ContactSearch::find(decrypt($request->deal_id));
                if (!$contact) {
                    return response()->json(['error' => 'Invalid Contact!']);
                }
                $data = CommissionData::select(DB::raw("commissions_data.id, date_format(commissions_data.settlement_date,'" . $this->mysql_date_format . "') as settlement_date,commission_types.name as commission_type,commissions_data.period,commissions_data.commission,commissions_data.gst,commissions_data.total_paid,commissions_data.payment_no"))
                    ->leftJoin('commission_types', 'commission_types.id', '=', 'commissions_data.commission_type');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->filterColumn('account_no', function ($query, $keyword) {
                        $sql = "commissions_data.account_number  like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getCommission1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referror_split_referror' => "required|exists:contact_searches,id",
            'product_id' => "required|exists:products,id",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $commissions =   RefferorCommissionSchedule::where('refferor_id', $request->referror_split_referror)->where(
            'product_id',
            $request->product_id
        )
            ->join('commission_types as ct', 'ct.id', '=', 'refferor_product_commission_schedule.commission_type_id')
            ->whereIn('ct.name', ['Upfront', 'Trail', 'Brokerage'])->get();
        $upfront = 0;
        $brokrage = 0;
        $trail = 0;
        if ($commissions) {
            foreach ($commissions as $commission) {
                if ($commission->name == 'Upfront') {
                    $upfront = $commission->per_rate;
                } else if ($commission->name == 'Trail') {
                    $trail = $commission->per_rate;
                } else if ($commission->name == 'Brokerage') {
                    $brokrage = $commission->per_rate;
                }
            }
        }
        return response()->json(['success' => 'success', 'model' => ['upfront' => $upfront, 'trail' => $trail, 'brokrage' => $brokrage]]);
    }
    public function getCommission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referror_split_referror' => "required|exists:contact_searches,id",
            //'product_id' => "required|exists:products,id",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        //$commissionModel = ReferrerCommissionModelinstitute::where('referrer_id',$request->referror_split_referror)->where('lender_id', $request->product_id)->first();
        $commissionModel = ReferrerCommissionModelinstitute::where('referrer_id', $request->referror_split_referror)->where('lender_id', 1)->first();
        if (!$commissionModel) {
            return response()->json(['status' => 'success', 'model' => []]);
        }
        $BrokerCom = ReferrerCommissionModel::find($commissionModel->referrer_com_mo_inst_id);
        $commission_model = 0;
        $fee_per_deal = 0;
        if ($BrokerCom) {
            $commission_model = $BrokerCom->commission_model_id;
            $fee_per_deal = $BrokerCom->flat_fee_chrg;
        }

        return    response()->json(['status' => 'success', 'model' => ['commission_model' => $commission_model, 'fee_per_deal' => $fee_per_deal, 'upfront_per' =>
        $commissionModel->upfront, 'trail' => $commissionModel->trail]]);
    }

    public function addReferredTo(Request $request, $contact_id)
    {
        $contact_id = decrypt($contact_id);
        $contact = ContactSearch::find($contact_id);
        if ($request->method() == 'GET') {
            $referrals = $contact?->withClientReferral;
            return Datatables::of($referrals)->addIndexColumn()
                ->addColumn("name", function ($row) {
                    return $row->referred_to ?? "N/A";
                })
                ->editColumn("service_id", function ($row) {
                    return $row->service?->name ?? "N/A";
                })
                ->addColumn('action', function ($row) use ($contact_id) {
                    $btn = "<a href='javascript:void(0)' data-href='" . route('admin.contact.viewEditReferredTo', ['referred_to_id' => encrypt($row?->id)]) . "' class='btn btn-primary view-referral-to'>View</a> <a href='javascript:void(0)' data-href='" . route('admin.contact.viewEditReferredTo', ['referred_to_id' => encrypt($row?->id)]) . "' data-type='edit' class='btn btn-default edit-referral-to'>Edit</a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $validator = Validator::make($request->all(), [
            "client_referral" => "required",
            "service_id" => "required|exists:services,id",
            "date" => "required",
            "referred_to_note" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if (!$contact) {
            return response()->json(['error' => "Contact does not exist"]);
        }
        $tempdate = str_replace('/', '-', $request->date);
        $data = [
            "referred_to" => $request->client_referral,
            "service_id" => $request->service_id,
            "date" => date("Y-m-d", strtotime($tempdate)),
            "notes" => $request->referred_to_note,
        ];
        if ($request->edit_id) {
            ClientReferral::where("id", decrypt($request->edit_id))->update($data);
            return response()->json(['status' => 'success', "message" => "Referral updated"]);
        }
        $ref = $contact->withClientReferral()->create($data);
        return response()->json(['status' => 'success', "message" => "Referral added"]);
    }
    function viewEditReferredTo(Request $request, $referred_to_id)
    {
        $referred_to_id = decrypt($referred_to_id);
        $data = ClientReferral::find($referred_to_id);
        if ($request->method() == 'GET') {
            if ($request->type == "edit") {
                $services = Service::get();
                $html = view("admin.contact.edit_referred_to_modal", compact('data', 'services'))->render();
            } else {
                $html = view("admin.contact.view_referred_to_modal", compact('data'))->render();
            }
            return response()->json(['status' => 'success', "html" => $html]);
        }
    }
    public function addRelationship(Request $request, $contact_id)
    {
        $contact_id = decrypt($contact_id);
        $contact = ContactSearch::with(['relations', 'relations.relationLabel', 'relations.relationWith'])->find($contact_id);
        if ($request->method() == 'GET') {
            $relationships = $contact?->relations;
            return Datatables::of($relationships)->addIndexColumn()
                ->addColumn("relation_with", function ($row) {
                    // Assuming relationWith model has 'surname' and 'preferred_name' properties
                    $surname = $row->relationWith?->surname ?? "N/A";
                    $preferredName = $row->relationWith?->preferred_name ?? "N/A";

                    // Concatenate surname and preferred_name
                    return $surname . ' ' . $preferredName;
                })
                ->editColumn("relation", function ($row) {
                    return $row->relationLabel?->name ?? "N/A";
                })
                ->addColumn('action', function ($row) use ($contact_id) {
                    $btn = "<a href='javascript:void(0)' data-href='" . route('admin.contact.viewEditRelationship', ['relationship_id' => encrypt($row?->id)]) . "' class='btn btn-primary view-referral-to'>View</a> <a href='javascript:void(0)' data-href='" . route('admin.contact.viewEditRelationship', ['relationship_id' => encrypt($row?->id)]) . "' data-type='edit' class='btn btn-default edit-referral-to'>Edit</a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $validator = Validator::make($request->all(), [
            "relation_with" => "required|exists:contact_searches,id",
            "relation" => "required|exists:relationship,id",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if (!$contact) {
            return response()->json(['error' => "Contact does not exist"]);
        }
        try {
            $data = $validator?->validated();
            if ($request->edit_id) {
                ClientRelation::where("id", decrypt($request->edit_id))->update($data);
                return response()->json(['status' => 'success', "message" => "Relationship updated"]);
            }
            $ref = $contact->relations()->create($data);
            return response()->json(['status' => 'success', "message" => "Relationship added"]);
        } catch (\Exception $e) {
            $code = $e->getCode();
            if ($code == 23000) {
                return response()->json(['error' => "Relation already exists"]);
            }
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function viewEditRelationship(Request $request, $relationship_id)
    {
        $relationship_id = decrypt($relationship_id);
        $data = ClientRelation::find($relationship_id);
        if (!$data) {
            return response()->json(['error' => "Relation does not exist"]);
        }
        if ($request->method() == 'GET') {
            if ($request->type == "edit") {
                $relations = Relationship::get();
                //$clients = ContactSearch::select(
                //    DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
                //)->where('search_for', 1)->whereNull('deleted_at')->get();

                $clients = ContactSearch::select(
                    DB::raw(
                        'id, 
                              CASE 
                                WHEN individual = 1 THEN CONCAT(surname, " ", preferred_name)
                                WHEN individual = 2 THEN trading
                                ELSE CONCAT_WS(",", trading, trust_name, CONCAT(surname, " ", first_name, " ", middle_name), preferred_name, entity_name)
                              END as display_name,
                              CONCAT(surname, " ", first_name, " ", middle_name) as client_name, 
                              trading, 
                              trust_name, 
                              surname, 
                              first_name, 
                              middle_name, 
                              preferred_name, 
                              entity_name'
                    )
                )->where('search_for', 1)->whereNull('deleted_at')->get();

                $html = view("admin.contact.edit_relationship_modal", compact('data', 'clients', 'relations'))->render();
            } else {
                $html = view("admin.contact.view_relationship_modal", compact('data'))->render();
            }
            return response()->json(['status' => 'success', "html" => $html]);
        }
    }
    function editSourceOfClient(Request $request, $contact_id)
    {
        $contact_id = decrypt($contact_id);
        $contact = ContactSearch::with(['relations', 'relations.relationLabel', 'relations.relationWith'])->find($contact_id);

        if (!$contact) {
            return response()->json(['error' => "Contact does not exist"]);
        }
        $referrer_type = $request->referrer_type;
        $data = [
            "referrer_type" => $referrer_type,
            "refferor_note" => $request->refferor_note,
            "refferor_relation_to_client" => $request->refferor_relation_to_client,
        ];
        switch ($referrer_type) {
            case 1:
                $other_referrer = $request->other_referrer;
                if (!$other_referrer) {
                    return response()->json(['error' => "Referrer field is required"]);
                }
                $contact->other_referrer = $other_referrer;
                break;
            case 2:
                $client_id = $request->client_id;
                $clientExists = ContactSearch::where("id", $client_id)->exists();
                if (!$client_id || !$clientExists) {
                    return response()->json(['error' => "Existing Clients field is required or Selected Client does not exist"]);
                }
                $contact->referrer_id = $client_id;
                break;
            case 3:
                $referrer_id = $request->referrer_id;
                $referrerExists = Processor::where("id", $referrer_id)->exists();
                if (!$referrer_id || !$referrerExists) {
                    return response()->json(['error' => "Staff Referral field is required or Selected Referral does not exist"]);
                }
                $contact->referrer_id = $referrer_id;
                break;
            case 4:
                $social_media_link = $request->social_media_link;
                if ($social_media_link < 1 || $social_media_link > 4) {
                    return response()->json(['error' => "Social Media field is required or Selected Social Media does not exist"]);
                }
                $contact->social_media_link = $social_media_link;
                break;
            default:
                return response()->json(['error' => "Invalid Referrer type selected"]);
                break;
        }
        try {
            $contact->referrer_type = $data['referrer_type'];
            $contact->refferor_note = $data['refferor_note'];
            $contact->refferor_relation_to_client = $data['refferor_relation_to_client'];
            $contact->save();
            return response()->json(['status' => 'success', "message" => "Source of client updated"]);
        } catch (\Exception $e) {
            $code = $e->getCode();
            if ($code == 23000) {
                return response()->json(['error' => "Query exception"]);
            }
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function viewEditAddress(Request $request, $contact_id)
    {
        $contact_id = decrypt($contact_id);
        $contact = ContactSearch::with(['relations', 'relations.relationLabel', 'relations.relationWith', 'withAddress'])->find($contact_id);
        if (!$contact) {
            return response()->json(['error' => "Contact does not exist"]);
        }
        if ($request->method() == "GET") {
            $address = ContactAddress::where("contact_id", $contact_id)->where("address_type", 1)->orderBy("id", "DESC")->first();
            $postalAddress = ContactAddress::where("contact_id", $contact_id)->where("address_type", 2)->orderBy("id", "DESC")->first();
            $states = State::get();
            $streetTypes = StreetType::get();
            $html = view("admin.contact.edit_address_modal", compact('contact', 'address', 'postalAddress', 'states', 'streetTypes'))->render();
            return response()->json(['status' => 'success', "html" => $html]);
        }
        $address = $request->address;
        $data_address = [
            "unit" => $address['street_number'],
            "street_name" => $address['street_name'],
            //"street_type" => $address['street_type'],
            "city" => $address['suburb'],
            "state" => $address['state'],
            "postal_code" => $address['postal_code'],
        ];
        $contact->withAddress()->updateOrCreate(["address_type" => 1], $data_address);
        $sameAsAddress = $request->same_as_address;
        if (!$sameAsAddress) {
            $postalAdress = $request->postal_address;
            $data_postalAdress = [
                "unit" => $postalAdress['street_number'],
                "street_name" => $postalAdress['street_name'],
                //"street_type" => $postalAdress['street_type'],
                "city" => $postalAdress['suburb'],
                "state" => $postalAdress['state'],
                "postal_code" => $postalAdress['postal_code'],
            ];
        } else {
            $data_postalAdress = $data_address;
        }
        $contact->withAddress()->updateOrCreate(["address_type" => 2], $data_postalAdress);
        return response()->json(['success' => 'Address updated']);
    }
}
