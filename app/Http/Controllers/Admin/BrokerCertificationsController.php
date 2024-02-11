<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABPStatus;
use App\Models\Broker;
use App\Models\BrokerCeritifcation;
use App\Models\BrokerExpense;
use App\Models\BrokerFee;
use App\Models\BrokerTasks;
use App\Models\Certification;
use App\Models\FeeType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrokerCertificationsController extends Controller
{
    public function brokerExpList($id) {

        $broker  = Broker::find(decrypt($id));

        if(!$broker)
        {
            return redirect()->back()->with('error', 'Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;
        $data['statuses'] = ABPStatus::all();
        return view('admin.brokercert.list', $data);
    }

    public function brokerAdd($id)
    {
        $broker  = Broker::find(decrypt($id));

        if(!$broker)
        {
            return redirect()->back()->with('error', 'Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;
        $data['certifications'] = Certification::all();

        return view('admin.brokercert.add_edit', $data);
    }

    public function getRecords($id,Request $request)
    {
        try {
            $broker  = Broker::find(decrypt($id));
            $input =  $request->all();
            $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');
            if(!$broker)
            {
                $response = array(
                    "sEcho" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                    "draw" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                );

                return response()->json(['success' => false, 'payload' => $response]);
            }


            $start = 0;
            $rowperpage = 25;
            if (isset($datatableData['iDisplayStart']) && $datatableData['iDisplayLength'] != '-1') {
                $start =  intval($datatableData['iDisplayStart']);
                $rowperpage = intval($datatableData['iDisplayLength']);
            }

            // Ordering
            $sortableColumnsName = [
                'broker_certifications.id',
                'broker_certifications.required',
                'broker_certifications.held',
                'broker_certifications.expiry_date',
                'broker_certifications.created_at',
                'broker_certifications.updated_at',
                ''

            ];
            $columnName              = "broker_certifications.created_at";
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
                    $columnName              = "broker_certifications.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = BrokerCeritifcation::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('deleted_at')
                                                                                      ->count();
            $filterSql = BrokerCeritifcation::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('broker_certifications.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = BrokerCeritifcation::select(DB::raw('broker_certifications.id,IF(broker_certifications.required=1,"Yes","No") as required_display,IF(broker_certifications.held=1,"Yes","No") as held_display,broker_certifications.expiry_date,certifications.name as certificate_name,CONCAT(users.fname," ",users.lname) as created_by,CONCAT(user_mod.fname," ",user_mod.lname) as modified_by,DATE_FORMAT(broker_certifications.created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(broker_certifications.updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->leftJoin('certifications','certifications.id','=','broker_certifications.type')->leftJoin('users','users.id','=','broker_certifications.created_by')->leftJoin('users as user_mod','user_mod.id','=','broker_certifications.updated_by')->where('broker_certifications.broker_id',$broker->id)->orderBy(
                $columnName,
                $columnSortOrder
            )->whereNull('broker_certifications.deleted_at')
                ->skip($start)
                ->take($rowperpage);

            if($searchValue!='')
                $sql =$sql->where('certifications.name', 'like', '%' .$searchValue . '%');

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

    public function brokerPost( $id, Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'expiry_date' => 'required|date',

        ]);
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Broker!']);
        }
        $brokerExp = new BrokerCeritifcation();

        $brokerExp->broker_id = decrypt($id);
        $brokerExp->type = $request['type'];
        $brokerExp->required = isset($request['required']) ? $request['required'] : 0;
        $brokerExp->held = isset($request['held']) ? $request['held'] : 0;
        $brokerExp->expiry_date = $request['expiry_date'];
        $brokerExp->created_by = Auth::user()->id;
        $brokerExp->created_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->save();

        response()->json(['success' => 'Record added successfully!']);
    }
    public function brokerEdit($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return redirect()->back()->with('error','Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;

        $data['taskdata'] = BrokerCeritifcation::where('broker_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        $data['certifications'] = Certification::all();
        return view('admin.brokercert.add_edit', $data);
    }

    public function brokerEditGet($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return response()->json(['error'=>'Broker not found!']);
        }

        $data = [];
        $data['broker'] = $broker;

        $data['taskdata'] = BrokerCeritifcation::where('broker_id',$broker->id)->where('id','=',decrypt($id))->first();

        $data['taskdata']->enc_id = encrypt($data['taskdata']->id);
        $data['certifications'] = Certification::all();
        return response()->json(['success' => true,'data' => $data]);
    }

    public function brokertskUpdate( $id,$bid, Request $request)
    {
        try{
            $validated = $request->validate([
                'type' => 'required',
                'expiry_date' => 'required|date',

            ]);
            $broker = Broker::find(decrypt($bid));

            if(!$broker) {
                response()->json(['error' => 'Invalid Broker!']);
            }

            $brokerExp = BrokerCeritifcation::find(decrypt($id));
            if(!$brokerExp || (isset($brokerExp) && $brokerExp->broker_id != $broker->id))
            {
                return redirect()->back()->with('error','Invalid Certification Record!');
            }

            $brokerExp->type = $request['type'];
            $brokerExp->required = isset($request['required']) ? $request['required'] : 0;
            $brokerExp->held = isset($request['held']) ? $request['held'] : 0;
            $brokerExp->expiry_date = $request['expiry_date'];
            $brokerExp->updated_by = Auth::user()->id;
            $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
            $brokerExp->save();

            response()->json(['success' => 'Record updated successfully!']);
        }catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
}
