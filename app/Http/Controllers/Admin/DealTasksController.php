<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealTask;
use App\Models\TaskRelation;
use App\Models\Processor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DealTasksController extends Controller
{
    public function brokerExpList($id) {

        $broker  = Deal::find(decrypt($id));

        if(!$broker)
        {
            return redirect()->back()->with('error', 'Deal not found!');
        }

        $data = [];
        $data['deal'] = $broker;

        return view('admin.dealtsk.list', $data);
    }

    public function brokerAdd($id)
    {
        $broker  = Deal::find(decrypt($id));

        if(!$broker)
        {
            return redirect()->back()->with('error', 'Deal not found!');
        }

        $data = [];
        $data['deal'] = $broker;
        $data['processors'] = Processor::all();
        $data['dealDetails'] = [
            '1' => '12 month follow up',
            '2' => '24 month follow up'
        ];
        return view('admin.dealtsk.add_edit', $data);
    }

    public function getRecords($id,Request $request)
    {
        $input =  $request->all();
            $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');

        try {
            $broker  = Deal::find(decrypt($id));

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
                'deal_tasks.id',
                'deal_tasks.followup_date',
                'deal_tasks.end_date',
                //'processors.name',
                'deal_tasks.user',
                'deal_tasks.followup_detail',
                'deal_tasks.details',
                'deal_tasks.created_at',
                ''

            ];
            $columnName              = "deal_tasks.created_at";
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
                    $columnName              = "deal_tasks.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = DealTask::select('count(*) as allcount')->where('deal_id',$broker->id)->whereNull('deleted_at')->count();
            $filterSql = DealTask::select('count(*) as allcount')->where('deal_id',$broker->id)->whereNull('deal_tasks.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = DealTask::select(DB::raw('deal_tasks.id,deal_tasks.deal_id,processors.name as processor,deal_tasks.details,CONCAT(users_u.given_name," ",users_u.surname) as user,CONCAT(users.fname," ",users.lname) as created_by,CONCAT(user_mod.fname," ",user_mod.lname) as modified_by,DATE_FORMAT(deal_tasks.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(deal_tasks.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at,
            date_format(deal_tasks.followup_date,"'.$this->mysql_date_format.'") as  followup_date,date_format(deal_tasks.end_date,"'.$this->mysql_date_format.'") as  end_date, ( CASE deal_tasks.followup_detail WHEN 1 THEN "12 month follow up" WHEN 2 THEN "24 month follow up" END ) as followup_detail'))
            ->leftJoin('processors','processors.id','=','deal_tasks.processor')
            ->leftJoin('brokers_staffs as users_u','users_u.id','=','deal_tasks.user')
            ->leftJoin('users','users.id','=','deal_tasks.created_by')
            ->leftJoin('users as user_mod','user_mod.id','=','deal_tasks.updated_by')
            ->orderBy($columnName,$columnSortOrder)
            ->where('deal_tasks.deal_id',$broker->id)
            ->whereNull('deal_tasks.deleted_at')
            ->skip($start)
            ->take($rowperpage);

            if($searchValue!='')
                $sql =$sql->where('processors.name', 'like', '%' .$searchValue . '%');

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
      //  print_R($request);die;
        $validated = $request->validate([
           
            'followup_date' => 'required|date_format:d/m/Y',
            //'end_date' => 'required|date_format:d/m/Y',
            //'processor' => 'required',
            'user' => 'required',
            //'detail' => 'required',
        ]);
        
        $broker = Deal::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Deal!']);
        }
        $brokerExp = new DealTask();
        if($request['followup_date'] != '')
        {
            $tempdob = str_replace('/','-',$request['followup_date']);
            $request['followup_date'] = date('Y-m-d',strtotime($tempdob));
        }

        if($request['end_date'] != '')
        {
            $tempdob = str_replace('/','-',$request['end_date']);
            $request['end_date'] = date('Y-m-d',strtotime($tempdob));
        }
        $brokerExp->deal_id = decrypt($id);
        $brokerExp->followup_date = $request['followup_date'];
        $brokerExp->end_date = $request['end_date'];
        //$brokerExp->processor = $request['processor'];
        $brokerExp->followup_detail = $request['followup_detail'];
        $brokerExp->details = $request['detail'];
        $brokerExp->user = $request['user'];
        $brokerExp->created_by = Auth::user()->id;
        $brokerExp->created_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->save();

       return response()->json(['success' => 'Record added successfully!']);
    }
    public function brokerEdit($id, $bid) {

        $broker = Deal::find(decrypt($bid));

        if(!$broker || !$id) {
            return redirect()->back()->with('error','Contact not found!');
        }

        $data = [];
        $data['deal'] = $broker;
        $data['processors'] = Processor::all();
        $data['dealDetails'] = [
            '1' => '12 month follow up',
            '2' => '24 month follow up'
        ];
        $data['taskdata'] = DealTask::where('deal_id',$broker->id)->where('id','=',decrypt($id))
                                                                                 ->first();

        return view('admin.dealtsk.add_edit', $data);
    }

    public function brokergetRecord($id, $bid) {

        $broker = Deal::find(decrypt($bid));

        if(!$broker || !$id) {
            return response()->json(['error'=> 'Task not found']);
        }

        $data = [];
        $data['deal'] = $broker;
        $data['processors'] = Processor::all();
        $data['dealDetails'] = [
            '1' => '12 month follow up',
            '2' => '24 month follow up'
        ];
        //$data['taskdata'] = DealTask::where('deal_id',$broker->id)->where('id','=',decrypt($id))->first();

        //$data['taskdata'] = DealTask::select(DB::raw('id,details,processor,followup_detail,date_format(followup_date,"'.$this->mysql_date_format.'") as  followup_date,date_format(end_date,"'.$this->mysql_date_format.'") as  end_date'))->where('deal_id',$broker->id)->where('id','=',decrypt($id))->first();
         $data['taskdata'] = DealTask::select(DB::raw('deal_tasks.*,IF(deal_tasks.followup_date IS NOT NULL,DATE_FORMAT(deal_tasks.followup_date,"'.$this->mysql_date_format.'"),"") as followup_date'))->where('deal_id',$broker->id)->where('id','=',decrypt($id))->first();

        $data['taskdata']['enc_id'] = encrypt($data['taskdata']->id);
        return response()->json(['success'=>true,'data' =>$data]);
    }

    public function brokertskUpdate( $id,$bid, Request $request)
    {
        try{
            $validated = $request->validate([
                //'processor' => 'required',
                'followup_date' => 'required|date_format:d/m/Y', //'required|date',
                'user' => 'required',
                //'detail' => 'required',
            ]);
            $broker = Deal::find(decrypt($bid));

            if(!$broker) {
                response()->json(['error' => 'Invalid Deal!']);
            }

            $brokerExp = DealTask::find(decrypt($id));
            if(!$brokerExp || (isset($brokerExp) && $brokerExp->deal_id != $broker->id))
            {
                return redirect()->back()->with('error','Invalid Task Record!');
            }

            if($request['followup_date'] != '')
            {
                $tempdob = str_replace('/','-',$request['followup_date']);
                $request['followup_date'] = date('Y-m-d',strtotime($tempdob));
            }
            if($request['end_date'] != '')
            {
                $tempdob = str_replace('/','-',$request['end_date']);
                $request['end_date'] = date('Y-m-d',strtotime($tempdob));
            }
            $brokerExp->followup_date = $request['followup_date'];
            $brokerExp->end_date = $request['end_date'];
            $brokerExp->processor = $request['processor'];
            $brokerExp->followup_detail = $request['followup_detail'];
            $brokerExp->details = $request['detail'];
            $brokerExp->user = $request['user'];
            $brokerExp->updated_by = Auth::user()->id;
            $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
            $brokerExp->save();

            return response()->json(['success' => 'Record updated successfully!']);
        }catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
}
