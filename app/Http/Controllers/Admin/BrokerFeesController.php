<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABPStatus;
use App\Models\Broker;
use App\Models\BrokerExpense;
use App\Models\BrokerFee;
use App\Models\BrokerTasks;
use App\Models\FeeType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrokerFeesController extends Controller
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
        return view('admin.brokerfee.list', $data);
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
        $data['fee_types'] = FeeType::all();
        $data['frequencies'] = [
            '1' => 'Day',
            '2' => 'Month',
             '3' => 'Year'
        ];
        return view('admin.brokerfee.add_edit', $data);
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
                'broker_fees.id',
                'broker_fees.type',
                'broker_fees.frequency',
                'broker_fees.due_date',
                'broker_fees.amount',
                /*'broker_tasks.created_by',
                'broker_tasks.updated_by',*/
                'broker_fees.created_at',
                'broker_fees.updated_at',
                ''

            ];
            $columnName              = "broker_fees.created_at";
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
                    $columnName              = "broker_fees.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = BrokerFee::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('deleted_at')
                                                                                      ->count();
            $filterSql = BrokerFee::select('count(*) as allcount')->where('broker_id',$broker->id)->whereNull('broker_fees.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = BrokerFee::select(DB::raw('broker_fees.id,broker_fees.amount,broker_fees.broker_id,broker_fees.due_date,fee_types.name as fee_type_display,(CASE WHEN broker_fees.frequency =1 THEN "Day" WHEN broker_fees.frequency =2 THEN "Month" WHEN broker_fees.frequency =3 THEN "Year" END )as frequency_display,CONCAT(users.fname," ",users.lname) as created_by,CONCAT(user_mod.fname," ",user_mod.lname) as modified_by,DATE_FORMAT(broker_fees.created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(broker_fees.updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->leftJoin('fee_types','fee_types.id','=','broker_fees.type')->leftJoin('users','users.id','=','broker_fees.created_by')->leftJoin('users as user_mod','user_mod.id','=','broker_fees.updated_by')->orderBy(
                $columnName,
                $columnSortOrder
            )->whereNull('broker_fees.deleted_at')
                ->skip($start)
                ->take($rowperpage);

            if($searchValue!='')
                $sql =$sql->where('fee_types.name', 'like', '%' .$searchValue . '%');

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
            'frequency' => 'required',
            'amount' => 'required',
            'due_date' => 'required|date',

        ]);
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Broker!']);
        }
        $brokerExp = new BrokerFee();

        $brokerExp->broker_id = decrypt($id);
        $brokerExp->type = $request['type'];
        $brokerExp->frequency = $request['frequency'];
        $brokerExp->due_date = $request['due_date'];
        $brokerExp->amount = $request['amount'];
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

        $data['taskdata'] = BrokerFee::where('broker_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        $data['fee_types'] = FeeType::all();
        $data['frequencies'] = [
            '1' => 'Day',
            '2' => 'Month',
            '3' => 'Year'
        ];
        return view('admin.brokerfee.add_edit', $data);
    }

    public function brokerEditGet($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return response()->json(['error'=>'Invalid argument!']);
        }

        $data = [];
        $data['broker'] = $broker;

        $data['taskdata'] = BrokerFee::where('broker_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        $data['fee_types'] = FeeType::all();
        $data['frequencies'] = [
            '1' => 'Day',
            '2' => 'Month',
            '3' => 'Year'
        ];
        $data['taskdata']->enc_id = encrypt($data['taskdata']->id);
        return response()->json(['data'=>$data,'success'=>true]);
    }

    public function brokertskUpdate( $id,$bid, Request $request)
    {
        try{
            $validated = $request->validate([
                'type' => 'required',
                'frequency' => 'required',
                'amount' => 'required',
                'due_date' => 'required|date',

            ]);
            $broker = Broker::find(decrypt($bid));

            if(!$broker) {
                response()->json(['error' => 'Invalid Broker!']);
            }



            $brokerExp = BrokerFee::find(decrypt($id));
            if(!$brokerExp || (isset($brokerExp) && $brokerExp->broker_id != $broker->id))
            {
                return redirect()->back()->with('error','Invalid Fee Record!');
            }


            $brokerExp->type = $request['type'];
            $brokerExp->frequency = $request['frequency'];
            $brokerExp->due_date = $request['due_date'];
            $brokerExp->amount = $request['amount'];
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
