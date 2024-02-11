<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABP;
use App\Models\BrokerStaff;
use App\Models\Broker;
use App\Models\BrokerCommissionModel;
use App\Models\BrokerCommissionModelinstitute;
use App\Models\BrokerReferror;
use App\Models\BrokerType;
use App\Models\Certification;
use App\Models\City;
use App\Models\ClientRelation;
use App\Models\ClientRole;
use App\Models\Commission;
use App\Models\Industry;
use App\Models\RefferorRelation;
use App\Models\Service;
use App\Models\State;
use App\Models\TaskRelation;
use App\Models\ContactSearch;
use App\Models\FeeType;
use App\Models\Lenders;
use App\Models\Relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class BrokersController extends Controller
{
    public function brokerList()
    {
        $data = [];
        $data['states'] = State::all();
        //$data['cities'] = City::all();
        $data['types'] = BrokerType::all();
        return view('admin.broker.list', $data);
    }

    public function brokerAdd()
    {

        $data['types'] =  BrokerType::where('status',1)->get();
        $data['refferors'] = ContactSearch::select(
            DB::raw('id,CONCAT(surname," ",first_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 2)->whereNull('deleted_at')->get();

        $data['bdms'] = Broker::select(
            DB::raw('id,CONCAT(given_name," ",surname) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 1)->whereNull('deleted_at')->get();

        $data['parents'] = Broker::select(
            DB::raw('id,CONCAT_WS(" ",trading,trust_name,surname,given_name) as display_name')
        )->where('parent_broker','=',0)->whereNull('deleted_at')->get();


        $data['states'] = State::all();
        //$data['cities'] = City::all();
        return view('admin.broker.add_edit', $data);
    }

    public function brokerPost(Request $request)
    {
        $validated = $request->validate([
            'individual' => 'required|in:Individual,Company',
            'trading' => $request->input('individual') == 'Company' ? 'required' : '',
            'preferred_name' => $request->input('individual') == 'Individual' ? 'required' : '',
            'surname' => $request->input('individual') == 'Individual' ? 'required' : '',
        ], [], [
            'preferred_name' => 'Given name',
            'surname' => 'Surname',
            'trading' => 'Trading Name',
            'individual' => 'Type',
        ]);

        /**$validated = $request->validate([
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
        ]);**/

        try {
            DB::beginTransaction();
            $contact = new Broker();
            if($request['dob'] != '')
            {
                $tempdob = str_replace('/','-',$request['dob']);
                $request['dob'] = date('Y-m-d',strtotime($tempdob));
            }

            if($request['start_date'] != '')
            {
                $tempdob = str_replace('/','-',$request['start_date']);
                $request['start_date'] = date('Y-m-d',strtotime($tempdob));
            }
            if($request['end_date'] != '')
            {
                $tempdob = str_replace('/','-',$request['end_date']);
                $request['end_date'] = date('Y-m-d',strtotime($tempdob));
            }

            $is_individual = 0;
            if ($request->input('individual') == 'Individual') {
                $is_individual = 1;
            } else if ($request->input('individual') == 'Company') {
                $is_individual = 2;
            }
            $contact->is_individual = $is_individual;
            $contact->is_bdm = isset($request['is_bdm']) && $request['is_bdm'] ?  1 : 0;
            $contact->subject_to_gst = isset($request['subject_to_gst']) && $request['subject_to_gst'] ?  1 : 0;
            $contact->parent_broker = isset($request['parent_broker']) && $request['parent_broker'] > 0 ? $request['parent_broker'] : 0;
            if(isset($request['parent_broker']) && $request['parent_broker'] > 0){
                $contact->type = 4 ;
            }
            $contact->trading = $request['trading'];
            $contact->trust_name = $request['trust_name'];
            $contact->surname = $request['surname'];
            $contact->given_name = $request['preferred_name'];
            $contact->dob = $request['dob'];
            $contact->is_active = $request->input('is_active') == '1' ? 1 : 0;
            $contact->salutation = $request['role_title'];
            $contact->entity_name = $request['entity_name'];
            $contact->work_phone = $request['work_phone'];
            $contact->home_phone = $request['home_phone'];
            $contact->mobile_phone = $request['mobile_phone'];
            $contact->fax = $request['fax'];
            $contact->email = $request['email'];
            $contact->web = $request['web'];
            $contact->city = $request['city'];
            $contact->state = $request['state'];
            $contact->pincode = $request['postal_code'];
            $contact->business = $request['business'];
            $contact->bdm = $request['bdm'];

            $contact->note = $request['note'];
            $contact->account_name = $request['acc_name'];
            $contact->account_number = $request['acc_number'];
            $contact->bank = $request['bank'];
            $contact->bsb = $request['bsb'];
            $contact->start_date = $request['start_date'];
            $contact->end_date = $request['end_date'];
            $contact->abn = $request['abn'];

            $contact->created_by = Auth::user()->id;

            //dd($contact);

            $contact->save();

            if ($contact->id > 0) {
                if (!empty($request->referrors)) {
                    $tempArr = [];
                    foreach ($request->referrors as $referrors) {
                        if ($referrors['referror'] > 0) {
                            $tempArr[] = [
                                'broker_id' => $contact->id,
                                'referror' => $referrors['referror'],
                                'upfront' => $referrors['upfront'],
                                'trail' => $referrors['trail'],
                                'comm_per_deal' => $referrors['comm_per_deal'],
                            ];
                        }
                    }

                    if (!empty($tempArr)) {
                        BrokerReferror::insert($tempArr);
                    }
                }

                DB::commit();
            } else {
                DB::rollback();
                return response()->json(['error' => "Something went wrong while save record!"]);
            }
           return response()->json(['success'=>'Record added successfully!','id'=>encrypt($contact->id)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function brokerEdit($id)
    {

        $broker = Broker::select(DB::raw('brokers.*,DATE_FORMAT(brokers.dob,"'.$this->mysql_date_format.'") as dob,DATE_FORMAT(brokers.start_date,"'.$this->mysql_date_format.'") as start_date,DATE_FORMAT(brokers.end_date,"'.$this->mysql_date_format.'") as end_date'))
                ->with(['referrors'])
                ->where('id', decrypt($id))
                ->first();

        $data['parents'] = Broker::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,surname,given_name) as display_name')
        )->where('id','!=',decrypt($id))->where('parent_broker','=',0)->whereNull('deleted_at')->get();

        $data['refferors'] = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for', 2)->whereNull('deleted_at')->get();
        $data['bdms'] = Broker::select(
            DB::raw('id,CONCAT(surname," ",given_name) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 1)->where('id', '!=', $broker->id)->whereNull('deleted_at')->get();
        $data['states'] = State::all();
        // $data['cities'] = City::all();
        $data['broker'] = $broker;
        // $data['types'] = BrokerType::all();
        $data['types'] =  BrokerType::where('status',1)->get();
        if ($broker) {
            return view('admin.broker.add_edit', $data);
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function brokerView($id)
    {

        $broker = Broker::select(DB::raw('brokers.*,bcommmodel_tbl.id as bcomid,bcommmodel_tbl.commission_model_id,bcommmodel_tbl.upfront_per,bcommmodel_tbl.trail_per,bcommmodel_tbl.flat_fee_chrg,DATE_FORMAT(brokers.dob,"'.$this->mysql_date_format.'") as dob,DATE_FORMAT(brokers.start_date,"'.$this->mysql_date_format.'") as start_date,DATE_FORMAT(brokers.end_date,"'.$this->mysql_date_format.'") as end_date,states.name as state_name, brokers.city as city_name,CONCAT_WS(",",bdm_brokers.trading,bdm_brokers.trust_name,bdm_brokers.surname,bdm_brokers.given_name) as bdm_display_name,CONCAT_WS(",",parent_broker_tbl.trading,parent_broker_tbl.trust_name,parent_broker_tbl.surname,parent_broker_tbl.given_name) as parent_broker_display_name'))
        ->with(['referrorClients', 'broker_staff'])
        ->where('brokers.id', decrypt($id))
        ->leftJoin('states', 'states.id','=', 'brokers.state')
        ->leftJoin('brokers as bdm_brokers','bdm_brokers.id','=','brokers.bdm')
        ->leftJoin('brokers as parent_broker_tbl','parent_broker_tbl.id','=','brokers.parent_broker')
        ->leftJoin('broker_commission_model as bcommmodel_tbl','bcommmodel_tbl.broker_id','=','brokers.id')
        ->first();       
        if ($broker) {
            $frequencies = [
                '1' => 'Day',
                '2' => 'Month',
                '3' => 'Year'
            ];
            // $statuses= ABPStatus::all();
            $institutes= Lenders::all(); 
            //print_R($institutes);die;
            $commission_models = Commission::all();
            return view('admin.broker.view', compact('broker','frequencies','institutes','commission_models'));
        } else {
            return redirect()->back()->with('error', 'Broker not found.');
        }
    }

    public function brokerUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'individual' => 'required|max:255',
            'trading' => $request->input('individual') == 2 ? 'required|max:255' : '',
            'preferred_name' => $request->input('individual') == 1 ? 'required|max:255' : '',
            'surname' => $request->input('individual') == 1 ? 'required|max:255' : '',

            'acc_number' => 'exclude_if:acc_name,null|required',
            'bank' => 'exclude_if:acc_name,null|required',
            'bsb' => 'exclude_if:acc_name,null|required',
        ], [], [
            'preferred_name' => 'Given name',
            'surname' => 'Surname',
            'trading' => 'Trading',
            'individual' => 'Type'
        ]);

        try {

            $contact = Broker::find(decrypt($id));

            if ($contact) {
                $new_bdm = isset($request['is_bdm']) && $request['is_bdm'] ?  1 : 0;
                if ($contact->is_bdm == 1 && $new_bdm) {
                    $allDepCon = Broker::where('bdm', $contact->id)->count();
                    if ($allDepCon > 0) {
                        return response()->json(['error' => "If you want to cancel from BDM please unassigned This broker as BDM from Other Brokers!"]);
                    }
                }
                if($request['dob'] != '')
                {
                    $tempdob = str_replace('/','-',$request['dob']);
                    $request['dob'] = date('Y-m-d',strtotime($tempdob));
                }

                if($request['start_date'] != '')
                {
                    $tempdob = str_replace('/','-',$request['start_date']);
                    $request['start_date'] = date('Y-m-d',strtotime($tempdob));
                }
                if($request['end_date'] != '')
                {
                    $tempdob = str_replace('/','-',$request['end_date']);
                    $request['end_date'] = date('Y-m-d',strtotime($tempdob));
                }
                DB::beginTransaction();
                //$contact->type = isset($request['search_for'])? $request['search_for']:0;
                //$contact->is_individual = isset($request['broker_type']) && $request['broker_type'] ?  1 : 2;

                $broker_type = 0;
                if ($request->input('individual') == 'Individual') {
                    $is_individual = 1;
                } else if ($request->input('individual') == 'Company') {
                    $is_individual = 2;
                }
                $contact->is_individual = $is_individual;

                $contact->is_bdm = isset($request['is_bdm']) && $request['is_bdm'] ?  1 : 0;
                $contact->subject_to_gst = isset($request['subject_to_gst']) && $request['subject_to_gst'] ?  1 : 0;

                if($request['isParent'] > 0)
                    $contact->parent_broker = isset($request['parent_broker']) && $request['parent_broker'] > 0 ? $request['parent_broker'] : 0;
                else
                    $contact->parent_broker = 0;

                $contact->trading = $request['trading'];
                $contact->trust_name = $request['trust_name'];
                $contact->surname = $request['surname'];
                $contact->given_name = $request['preferred_name'];
                $contact->dob = $request['dob'];
                $contact->salutation = $request['role_title'];
                $contact->entity_name = $request['entity_name'];
                $contact->work_phone = $request['work_phone'];
                $contact->home_phone = $request['home_phone'];
                $contact->mobile_phone = $request['mobile_phone'];
                $contact->fax = $request['fax'];
                $contact->email = $request['email'];
                $contact->web = $request['web'];
                $contact->city = $request['city'];
                $contact->state = $request['state'];
                $contact->pincode = $request['postal_code'];
                $contact->business = $request['business'];
                $contact->bdm = $request['bdm'];
                $contact->is_active = $request->input('is_active') == '1' ? 1 : 0;
                $contact->note = $request['note'];

                $contact->account_name = $request['acc_name'];
                $contact->account_number = $request['acc_number'];
                $contact->bank = $request['bank'];
                $contact->bsb = $request['bsb'];
                $contact->start_date = $request['start_date'];
                $contact->end_date = $request['end_date'];
                $contact->abn = $request['abn'];

                $contact->last_updated_by = Auth::user()->id;
                $contact->save();

                if ($contact->id > 0) {
                    if (!empty($request->referrors)) {
                        $tempArr = [];
                        $existingAr = [];
                        foreach ($request->referrors as $referrors) {
                            if ($referrors['referror'] > 0 && $referrors['entity'] != '') {
                                if (isset($referrors['old_id']) && $referrors['old_id'] > 0) {
                                    $existingAr[] = $referrors['old_id'];
                                    BrokerReferror::where('id', $referrors['old_id'])->where('client_id', $contact->id)
                                        ->update([
                                            'referror' => $referrors['referror'],
                                            'entity' => $referrors['entity'],
                                            'upfront' => $referrors['upfront'],
                                            'trail' => $referrors['trail'],
                                            'comm_per_deal' => $referrors['comm_per_deal'],
                                        ]);
                                } else {
                                    $tempArr[] = [
                                        'broker_id' => $contact->id,
                                        'referror' => $referrors['referror'],
                                        'entity' => $referrors['entity'],
                                        'upfront' => $referrors['upfront'],
                                        'trail' => $referrors['trail'],
                                        'comm_per_deal' => $referrors['comm_per_deal'],
                                    ];
                                }
                            }
                        }
                        $delSql = BrokerReferror::where('broker_id', $contact->id);
                        if (count($existingAr) > 0) {
                            $delSql = $delSql->whereNotIn('id', $existingAr);
                        }
                        $delSql->delete();
                        if (!empty($tempArr)) {
                            BrokerReferror::insert($tempArr);
                        }
                    } else {
                        BrokerReferror::where('broker_id', $contact->id)->delete();
                    }

                    DB::commit();
                } else {
                    DB::rollback();
                    return response()->json(['error' => "Something went wrong while save record!"]);
                }
            } else {
                return response()->json(['error' => "Record not found!"]);
            }



            //return redirect()->route('admin.contact.list')->with('success', 'Contact detail added successfully.');
            // Session::flash('message', 'Record added successfully!');
            //response()->json(['success' => 'Record added successfully!']);
            return response()->json(['success'=>'Record updated successfully!','id'=>$id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function brokerDelete($id)
    {
        $contact = Broker::find(decrypt($id));
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

            // Ordering
            $sortableColumnsName = [
                'brokers.id',
                'brokers.type',
                'brokers.trading',
                'brokers.surname',
                'brokers.dob',
                'states.name',
                'cities.name',
                'brokers.mobile_phone',
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
            $totalRecords = Broker::select('count(*) as allcount')->whereNull('deleted_at')->count();
            $filterSql = Broker::select('count(*) as allcount')->whereNull('brokers.deleted_at')
                ->leftJoin('states', 'states.id', '=', 'brokers.state')->leftJoin('cities', 'cities.id', '=', 'brokers.city');
            if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                $filterSql = $filterSql->where('brokers.type', '=', $datatableData['type']);
            }
            if (isset($datatableData['surname']) && $datatableData['surname'] > 0) {
                $filterSql = $filterSql->where('brokers.surname', '=', $datatableData['surname']);
            }
            if (isset($datatableData['given_name']) && $datatableData['given_name'] > 0) {
                $filterSql = $filterSql->where('brokers.given_name', '=', $datatableData['given_name']);
            }
            if (isset($datatableData['trading']) && $datatableData['trading'] > 0) {
                $filterSql = $filterSql->where('brokers.trading', '=', $datatableData['trading']);
            }
            if (isset($datatableData['state']) && $datatableData['state'] > 0) {
                $filterSql = $filterSql->where('brokers.state', '=', $datatableData['state']);
            }
            if (isset($datatableData['city']) && $datatableData['city'] > 0) {
                $filterSql = $filterSql->where('brokers.city', '=', $datatableData['city']);
            }
            if (isset($datatableData['postal_code']) && $datatableData['postal_code'] > 0) {
                $filterSql = $filterSql->where('brokers.pincode', '=', $datatableData['postal_code']);
            }
            if (isset($datatableData['id']) && $datatableData['id'] > 0) {
                $filterSql = $filterSql->where('brokers.id', '=', $datatableData['id']);
            }
            if ($searchValue != '') {
                $filterSql = $filterSql->where(function ($query) use ($searchValue) {
                    return $query->where('brokers.trading', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.trust_name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.surname', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.given_name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('states.name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('cities.name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.work_phone', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.home_phone', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.mobile_phone', 'like',  '%' . $searchValue . '%');
                });
            }


            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = Broker::select(DB::raw('brokers.id,broker_types.name as type,brokers.trading,brokers.trust_name,brokers.surname,brokers.given_name,DATE_FORMAT(brokers.dob,"'.$this->mysql_date_format.'") as dob,states.name as state_name,cities.name as city_name,brokers.pincode,brokers.work_phone,brokers.home_phone,brokers.mobile_phone, DATE_FORMAT(brokers.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(brokers.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at'))->orderBy(
                $columnName,
                $columnSortOrder
            )->whereNull('brokers.deleted_at')->leftJoin('broker_types', 'broker_types.id', '=', 'brokers.type')->leftJoin('states', 'states.id', '=', 'brokers.state')->leftJoin('cities', 'cities.id', '=', 'brokers.city')

                ->skip($start)
                ->take($rowperpage);
            if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                $sql = $sql->where('brokers.type', '=', $datatableData['type']);
            }
            if (isset($datatableData['surname']) && $datatableData['surname'] > 0) {
                $sql = $sql->where('brokers.surname', '=', $datatableData['surname']);
            }
            if (isset($datatableData['given_name']) && $datatableData['given_name'] > 0) {
                $sql = $sql->where('brokers.given_name', '=', $datatableData['given_name']);
            }
            if (isset($datatableData['trading']) && $datatableData['trading'] > 0) {
                $sql = $sql->where('brokers.trading', '=', $datatableData['trading']);
            }
            if (isset($datatableData['state']) && $datatableData['state'] > 0) {
                $sql = $sql->where('brokers.state', '=', $datatableData['state']);
            }
            if (isset($datatableData['city']) && $datatableData['city'] > 0) {
                $sql = $sql->where('brokers.city', '=', $datatableData['city']);
            }
            if (isset($datatableData['postal_code']) && $datatableData['postal_code'] > 0) {
                $sql = $sql->where('brokers.pincode', '=', $datatableData['postal_code']);
            }
            if (isset($datatableData['id']) && $datatableData['id'] > 0) {
                $sql = $filterSql->where('brokers.id', '=', $datatableData['id']);
            }
            if ($searchValue != '') {
                $sql = $sql->where(function ($query) use ($searchValue) {
                    return $query->where('brokers.trading', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.trust_name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.surname', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.given_name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('states.name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('cities.name', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.work_phone', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.home_phone', 'like',  '%' . $searchValue . '%')
                        ->orWhere('brokers.mobile_phone', 'like',  '%' . $searchValue . '%');
                });
            }

            $records = $sql->get();

            if (count($records) > 0) {
                foreach ($records as $rkey => $record) {
                    $records[$rkey]['encrypt_id'] = encrypt($record->id);
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

    public function getCommission(Request $request)
    {
        $validated = $request->validate([
            'broker_id' => 'required|exists:brokers,id',
            'lender_id' => 'required|exists:lenders,id',

        ], [], [
            'broker_id' => 'Broker',
            'lender_id' => 'Lender'
        ]);

        $commissionModel = BrokerCommissionModelinstitute::where('broker_id',$request->broker_id)->where('lender_id',
            $request->lender_id)->first();

        if(!$commissionModel)
        {
            return response()->json(['status' => 'success','model'=> []]);
        }

        $BrokerCom = BrokerCommissionModel::find($commissionModel->broker_com_mo_inst_id);

        $commission_model = 0;
        $fee_per_deal = 0;

        if($BrokerCom)
        {
            $commission_model = $BrokerCom->commission_model_id;
            $fee_per_deal = $BrokerCom->flat_fee_chrg;
        }

        return response()->json(['status' => 'success','model'=> ['commission_model' => $commission_model,'fee_per_deal' => $fee_per_deal,'upfront_per' => $commissionModel->upfront,'trail' => $commissionModel->trail]]);
    }

    function addBrokerStaff(Request $request, $broker_id){
        $id = decrypt($broker_id);
        $validated = $request->validate([
            'given_name' => 'required',
            'surname' =>'required',
        ]);
        $broker = Broker::select(DB::raw('brokers.*,bcommmodel_tbl.id as bcomid,bcommmodel_tbl.commission_model_id,bcommmodel_tbl.upfront_per,bcommmodel_tbl.trail_per,bcommmodel_tbl.flat_fee_chrg,DATE_FORMAT(brokers.dob,"' . $this->mysql_date_format . '") as dob,DATE_FORMAT(brokers.start_date,"' . $this->mysql_date_format . '") as start_date,DATE_FORMAT(brokers.end_date,"' . $this->mysql_date_format . '") as end_date,states.name as state_name,CONCAT_WS(",",bdm_brokers.trading,bdm_brokers.trust_name,bdm_brokers.surname,bdm_brokers.given_name) as bdm_display_name,CONCAT_WS(",",parent_broker_tbl.trading,parent_broker_tbl.trust_name,parent_broker_tbl.surname,parent_broker_tbl.given_name) as parent_broker_display_name'))
            ->with(['referrorClients'])
            ->where('brokers.id', $id)
            ->leftJoin('states', 'states.id', '=', 'brokers.state')
            ->leftJoin('brokers as bdm_brokers', 'bdm_brokers.id', '=', 'brokers.bdm')
            // ->leftJoin('cities', 'cities.id', '=','brokers.city')
            ->leftJoin('brokers as parent_broker_tbl', 'parent_broker_tbl.id', '=', 'brokers.parent_broker')
            ->leftJoin('broker_commission_model as bcommmodel_tbl', 'bcommmodel_tbl.broker_id', '=', 'brokers.id')
            ->first();
        $broker->broker_staff()->create([
            "given_name"=>$request->given_name,
            "surname"=>$request->surname,
            "email" => $request->email ?? 0,
            "mobile" => $request->phone ?? 0,
        ]);
        return response()->json(['success' => "Broker staff added"]);
    }
    function editBrokerStaff(Request $request,$broker_staff_id){
        $id = decrypt($broker_staff_id);
        $broker_staff = BrokerStaff::find($id);
        if(!$broker_staff){
            return response()->json(['error' => "Broker Staff does not exist"]);
        }
        if($request->method() == "GET"){
            $html = view("admin.broker.edit_broker_staff_modal",['broker_staff'=>$broker_staff])->render();
            return response()->json(["html" => $html]);
        }
        $broker_staff->update([
            "given_name" => $request->given_name,
            "surname"=>$request->surname,
            "mobile" => $request->mobile,
            "email" => $request->email
        ]);
        return response()->json(['success' => "Broker staff updated"]);
    }
    function deleteBrokerStaff(Request $request,$broker_staff_id){
        $id = decrypt($broker_staff_id);
        $broker_staff = BrokerStaff::find($id);
        if(!$broker_staff){
            return response()->json(['error' => "Broker Staff does not exist"]);
        }
        $broker_staff->delete();
        return response()->json(['success' => "Broker staff deleted"]);
    }
}
