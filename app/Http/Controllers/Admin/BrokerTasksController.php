<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABPStatus;
use App\Models\Broker;
use App\Models\BrokerExpense;
use App\Models\BrokerTasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrokerTasksController extends Controller
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
        return view('admin.brokertsk.list', $data);
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
        $data['statuses'] = ABPStatus::all();
        return view('admin.brokertsk.add_edit', $data);
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
                'broker_tasks.id',
                'broker_tasks.followup_date',
                'broker_tasks.person_to_followup',
                'broker_tasks.detail',
                'broker_tasks.completed_date',
                'broker_tasks.status',
                /*'broker_tasks.created_by',
                'broker_tasks.updated_by',*/
                'broker_tasks.created_at',
                ''
                /*'broker_tasks.updated_at',*/
            ];
            $columnName              = "broker_tasks.created_at";
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
                    $columnName              = "broker_tasks.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = BrokerTasks::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('deleted_at')
                                                                                      ->count();
            $filterSql = BrokerTasks::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('broker_tasks.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = BrokerTasks::select(DB::raw('broker_tasks.id,broker_tasks.broker_id,broker_tasks.followup_date,broker_tasks.person_to_followup,broker_tasks.detail,broker_tasks.completed_date,abp_statuses.name as status_display,CONCAT(users.fname," ",users.lname) as created_by,CONCAT(user_mod.fname," ",user_mod.lname) as modified_by,DATE_FORMAT(broker_tasks.created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(broker_tasks.updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->leftJoin('abp_statuses','abp_statuses.id','=','broker_tasks.status')->leftJoin('users','users.id','=','broker_tasks.created_by')->leftJoin('users as user_mod','user_mod.id','=','broker_tasks.updated_by')->orderBy(
                $columnName,
                $columnSortOrder
            )->where('broker_tasks.broker_id',$broker->id)->whereNull('broker_tasks.deleted_at')
                ->skip($start)
                ->take($rowperpage);

            if($searchValue!='')
                $sql =$sql->where('person_to_followup', 'like', '%' .$searchValue . '%');

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
            'person_to_followup' => 'required',
            'followup_date' => 'required|date',
            //'detail' => 'required',
        ]);
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Broker!']);
        }
        $brokerExp = new BrokerTasks();

        $brokerExp->broker_id = decrypt($id);
        $brokerExp->followup_date = $request['followup_date'];
        $brokerExp->person_to_followup = $request['person_to_followup'];
        $brokerExp->detail = $request['detail'];
        $brokerExp->completed_date = $request['completed_date'];
        $brokerExp->status = $request['status'];
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

        $data['taskdata'] = BrokerTasks::where('broker_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        $data['statuses'] = ABPStatus::all();
        return view('admin.brokertsk.add_edit', $data);
    }

    public function brokerEditGet($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return redirect()->back()->with('error','Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;

        $data['taskdata'] = BrokerTasks::where('broker_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        $data['statuses'] = ABPStatus::all();
        $data['taskdata']->enc_id = encrypt($data['taskdata']->id);

        return response()->json(['success' => 'true','data'=>$data]);
    }

    public function brokertskUpdate( $id,$bid, Request $request)
    {
        try{
            $validated = $request->validate([
                'person_to_followup' => 'required',
                'followup_date' => 'required|date',
                //'detail' => 'required',
            ]);
            $broker = Broker::find(decrypt($bid));

            if(!$broker) {
                response()->json(['error' => 'Invalid Broker!']);
            }

            $brokerExp = BrokerTasks::find(decrypt($id));
            if(!$brokerExp || (isset($brokerExp) && $brokerExp->broker_id != $broker->id))
            {
                return redirect()->back()->with('error','Invalid Task Record!');
            }


            $brokerExp->followup_date = $request['followup_date'];
            $brokerExp->person_to_followup = $request['person_to_followup'];
            $brokerExp->detail = $request['detail'];
            $brokerExp->completed_date = $request['completed_date'];
            $brokerExp->status = $request['status'];
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
