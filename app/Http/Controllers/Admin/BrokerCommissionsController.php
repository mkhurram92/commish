<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABPStatus;
use App\Models\Broker;
use App\Models\BrokerCommissionModel;
use App\Models\BrokerCommissionModelinstitute;
use App\Models\ContactSearch;
use App\Models\ReferrerCommissionModel;
use App\Models\ReferrerCommissionModelinstitute;
use App\Models\BrokerExpense;
use App\Models\BrokerFee;
use App\Models\BrokerTasks;
use App\Models\Commission;
use App\Models\FeeType;
use App\Models\Lenders;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrokerCommissionsController extends Controller
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
        return view('admin.brokercom.list', $data);
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
        $data['institutes'] = Lenders::all();
        $data['commission_models'] = Commission::all();
        return view('admin.brokercom.add_edit', $data);
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
            'commission_model' => 'required',
            'upfront_per' => 'required',
            'trail_per' => 'required'

        ]);
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Broker!']);
        }
        try{
            DB::beginTransaction();
            $brokerComModel = BrokerCommissionModel::firstOrNew(['broker_id'=>$broker->id, 'commission_model_id'=>$request->commission_model]); //,'commission_model_id'=>$request->commission_model
            $brokerComModel->upfront_per = $request->upfront_per;
            $brokerComModel->commission_model_id = $request->commission_model;
            $brokerComModel->trail_per = $request->trail_per;
            $brokerComModel->flat_fee_chrg = isset($request->flat_fee_chrg) && $request->flat_fee_chrg > 0 ? $request->flat_fee_chrg : 0;
            $brokerComModel->bdm_flat_fee_per = isset($request->bdm_flat_fee_per) && $request->bdm_flat_fee_per > 0 ? $request->bdm_flat_fee_per : 0;
            $brokerComModel->bdm_upfront_per = isset($request->bdm_upfront_per) && $request->bdm_upfront_per > 0 ? $request->bdm_upfront_per : 0;
            $brokerComModel->created_by = Auth::user()->id;
            $brokerComModel->updated_by = Auth::user()->id;
            $brokerComModel->save();

            if(isset($request->institutes_model) && count($request->institutes_model) > 0)
            {
                $dbSql = '';
                $user_id = Auth::user()->id;
                foreach($request->institutes_model as $inst)
                {
                    $dbSql = BrokerCommissionModelinstitute::firstOrNew(['lender_id'=>$inst['id'] , 'broker_com_mo_inst_id'=>$brokerComModel->id]);
                    $dbSql->broker_id = $broker->id;
                    $dbSql->broker_com_mo_inst_id = $brokerComModel->id;
                    $dbSql->lender_id = $inst['id'];
                    $dbSql->upfront = $inst['upfront'];
                    $dbSql->trail = $inst['trail'];
                    $dbSql->created_by = $user_id;
                    $dbSql->created_at = Carbon::now('utc')->toDateTimeString();
                    $dbSql->updated_by = $user_id;
                    $dbSql->updated_at = Carbon::now('utc')->toDateTimeString();

                    // $dbSql = " INSERT INTO  broker_commission_model_institution (`broker_id`,`broker_com_mo_inst_id`,`lender_id`,`upfront`,`trail`,`created_by`,`created_at`) VALUES ('".$broker->id."','".$brokerComModel->id."','".$inst['id']."','".$inst['upfront']."','".$inst['trail']."','".$user_id."','".Carbon::now('utc')->toDateTimeString()."') ON DUPLICATE KEY UPDATE upfront = '".$inst['upfront']."', trail = '".$inst['trail']."', updated_by = '".$user_id."', updated_at = '".Carbon::now('utc')->toDateTimeString()."'; ";
                    // DB::statement(DB::raw($dbSql));

                    $dbSql->save();
                }

                if($dbSql != '')
                {
                    //DB::statement(DB::raw($dbSql));
                }
            }

            DB::commit();
            return response()->json(['success' => 'Record added successfully!']);
        }catch(Exception $e)
        {
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()]);
        }
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
        return view('admin.brokercom.add_edit', $data);
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
            $broker = Broker::find(decrypt($id));

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

    public function getCommissionModel($id, Request $request)
    {
        try{
            $validated = $request->validate([
                'com_model' => 'required'
            ]);
            $broker = Broker::find(decrypt($id));

            if(!$broker) {
                response()->json(['error' => 'Invalid Broker!']);
            }

            $getCommData = BrokerCommissionModel::where('broker_id',$broker->id)->where('commission_model_id',$request->com_model)->whereNull('deleted_at')->first();
            if($getCommData)
            {
                $getCommInstis = BrokerCommissionModelinstitute::where('broker_id',$broker->id)->where('broker_com_mo_inst_id',$getCommData->id)->whereNull('deleted_at')->get();
                return response()->json(['success'=>true,'comm_model'=>$getCommData,'comm_insti'=>$getCommInstis]);
            }
            return response()->json(['success'=>true,"message"=>'No records.']);
        }catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
    
    
    public function getReferrerCommissionModel($id, Request $request)
    {
        try{
            $validated = $request->validate([
                'com_model' => 'required'
            ]);
            $referrer = ContactSearch::find(decrypt($id));

            if(!$referrer) {
                response()->json(['error' => 'Invalid Referrer!']);
            }

            $getCommData = ReferrerCommissionModel::where('referrer_id',$referrer->id)->where('commission_model_id',$request->com_model)->whereNull('deleted_at')->first();
            if($getCommData)
            {
                $getCommInstis = ReferrerCommissionModelinstitute::where('referrer_id',$referrer->id)->where('referrer_com_mo_inst_id',$getCommData->id)->whereNull('deleted_at')->get();
                return response()->json(['success'=>true,'comm_model'=>$getCommData,'comm_insti'=>$getCommInstis]);
            }
            return response()->json(['success'=>true,"message"=>'No records.']);
        }catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
    
    
        public function referrerPost( $id, Request $request)
    {
        $validated = $request->validate([
            'commission_model' => 'required',
            'upfront_per' => 'required',
            'trail_per' => 'required'

        ]);
        $referrer = ContactSearch::find(decrypt($id));
        //print_R($referrer->id);
        if(!$referrer) {
            response()->json(['error' => 'Invalid Referrer!']);
        }
        try{
            
            
            DB::beginTransaction();
            $referrerComModel = ReferrerCommissionModel::firstOrNew(['referrer_id'=>$referrer->id, 'commission_model_id'=>$request->commission_model]); //,'commission_model_id'=>$request->commission_model
            $referrerComModel->upfront_per = $request->upfront_per;
            $referrerComModel->commission_model_id = $request->commission_model;
            $referrerComModel->trail_per = $request->trail_per;
            $referrerComModel->flat_fee_chrg = isset($request->flat_fee_chrg) && $request->flat_fee_chrg > 0 ? $request->flat_fee_chrg : 0;
            $referrerComModel->bdm_flat_fee_per = isset($request->bdm_flat_fee_per) && $request->bdm_flat_fee_per > 0 ? $request->bdm_flat_fee_per : 0;
            $referrerComModel->bdm_upfront_per = isset($request->bdm_upfront_per) && $request->bdm_upfront_per > 0 ? $request->bdm_upfront_per : 0;
            $referrerComModel->created_by = Auth::user()->id;
            $referrerComModel->updated_by = Auth::user()->id;
            $referrerComModel->save();

            if(isset($request->institutes_model) && count($request->institutes_model) > 0)
            {
                $dbSql = '';
                $user_id = Auth::user()->id;
                foreach($request->institutes_model as $inst)
                {
                    $dbSql = ReferrerCommissionModelinstitute::firstOrNew(['lender_id'=>$inst['id'] , 'referrer_com_mo_inst_id'=>$referrerComModel->id]);
                    $dbSql->referrer_id = $referrer->id;
                    $dbSql->referrer_com_mo_inst_id = $referrerComModel->id;
                    $dbSql->lender_id = $inst['id'];
                    $dbSql->upfront = $inst['upfront'];
                    $dbSql->trail = $inst['trail'];
                    $dbSql->created_by = $user_id;
                    $dbSql->created_at = Carbon::now('utc')->toDateTimeString();
                    $dbSql->updated_by = $user_id;
                    $dbSql->updated_at = Carbon::now('utc')->toDateTimeString();


                    $dbSql->save();
                }

                if($dbSql != '')
                {
                    //DB::statement(DB::raw($dbSql));
                }
            }

            DB::commit();
            return response()->json(['success' => 'Record added successfully!']);
        }catch(Exception $e)
        {
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
}
