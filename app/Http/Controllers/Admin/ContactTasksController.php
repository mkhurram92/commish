<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABPStatus;
use App\Models\Broker;
use App\Models\TaskRelation;
use App\Models\ContactSearch;
use App\Models\Processor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactTasksController extends Controller
{
    public function brokerExpList($id)
    {

        $broker  = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->find(decrypt($id));

        if (!$broker) {
            return redirect()->back()->with('error', 'Contact not found!');
        }

        $data = [];
        $data['contact'] = $broker;


        return view('admin.contacttsk.list', $data);
    }

    public function brokerAdd($id)
    {
        $broker  = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->find(decrypt($id));

        if (!$broker) {
            return redirect()->back()->with('error', 'Contact not found!');
        }

        $data = [];
        $data['contact'] = $broker;
        $data['processors'] = Processor::all();
        return view('admin.contacttsk.add_edit', $data);
    }

    public function getRecords($id, Request $request)
    {
        $input =  $request->all();
        $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');

        try {
            $broker  = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->find(decrypt($id));

            if (!$broker) {
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
                'client_tasks.id',
                'client_tasks.followup_date',
                'client_tasks.end_date',
                //'processors.name',
                'client_tasks.user',
                'client_tasks.details',
                'client_tasks.created_at',
                ''

            ];
            $columnName              = "client_tasks.created_at";
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
                    $columnName              = "client_tasks.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = TaskRelation::select('count(*) as allcount')->where('client_id', $broker->id)->whereNull('deleted_at')
                ->count();
            $filterSql = TaskRelation::select('count(*) as allcount')->where('client_id', $broker->id)->whereNull('client_tasks.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = TaskRelation::select(DB::raw('client_tasks.id,client_tasks.client_id,IF(client_tasks.followup_date IS NOT NULL,
            DATE_FORMAT(client_tasks.followup_date,"' . $this->mysql_date_format . '"),"") as followup_date,
            date_format(client_tasks.end_date,"' . $this->mysql_date_format . '") as end_date,
            CONCAT(brokers_staffs.surname," ", brokers_staffs.given_name) as processor,
            client_tasks.details,CONCAT(users.fname," ",users.lname) as created_by,CONCAT(users_u.fname," ",users_u.lname) as user,
            CONCAT(user_mod.fname," ",user_mod.lname) as modified_by,
            DATE_FORMAT(client_tasks.created_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_created_at, 
            DATE_FORMAT(client_tasks.updated_at,"' . $this->mysql_date_format . ' %H:%i:%s") as formated_updated_at'))
            ->leftJoin('brokers_staffs', 'brokers_staffs.id', '=', 'client_tasks.user')
            ->leftJoin('users as users_u', 'users_u.id', '=', 'client_tasks.user')->leftJoin('users', 'users.id', '=', 'client_tasks.created_by')->leftJoin('users as user_mod', 'user_mod.id', '=', 'client_tasks.updated_by')->orderBy(
                $columnName,
                $columnSortOrder
            )->where('client_tasks.client_id', $broker->id)->whereNull('client_tasks.deleted_at')
                ->skip($start)
                ->take($rowperpage);

            if ($searchValue != '')
                $sql = $sql->where('processors.name', 'like', '%' . $searchValue . '%');

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

    public function brokerPost($id, Request $request)
    {
        $validated = $request->validate([

            'followup_date' => 'required|date_format:d/m/Y', //'required|date',
            //'end_date' => 'required|date_format:d/m/Y',
            //'processor' => 'required',
            'user' => 'required',
            //'detail' => 'required',
        ]);
        $broker = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->find(decrypt($id));

        if (!$broker) {
            response()->json(['error' => 'Invalid Contact!']);
        }
        $brokerExp = new TaskRelation();
        if ($request['followup_date'] != '') {
            $tempdob = str_replace('/', '-', $request['followup_date']);
            $request['followup_date'] = date('Y-m-d', strtotime($tempdob));
        }
        if ($request['end_date'] != '') {
            $tempdob = str_replace('/', '-', $request['end_date']);
            $request['end_date'] = date('Y-m-d', strtotime($tempdob));
        }
        $brokerExp->client_id = decrypt($id);
        $brokerExp->followup_date = $request['followup_date'];
        $brokerExp->end_date = $request['end_date'];
        //$brokerExp->processor = $request['processor'];
        $brokerExp->details = $request['detail'];
        $brokerExp->user = $request['user'];
        $brokerExp->created_by = Auth::user()->id;
        $brokerExp->created_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->save();

        return response()->json(['success' => 'Record added successfully!']);
    }
    public function brokerEdit($id, $bid)
    {

        $broker = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->find(decrypt($bid));

        if (!$broker || !$id) {
            return redirect()->back()->with('error', 'Contact not found!');
        }

        $data = [];
        $data['contact'] = $broker;
        $data['processors'] = Processor::all();
        $data['taskdata'] = TaskRelation::select(DB::raw('client_tasks.*,IF(client_tasks.followup_date IS NOT NULL,DATE_FORMAT(client_tasks.followup_date,"' . $this->mysql_date_format . '"),"") as followup_date,date_format(end_date,"' . $this->mysql_date_format . '") as  end_date'))->where('client_id', $broker->id)->where(
            'id',
            '=',
            decrypt($id)
        )
            ->first();

        return view('admin.contacttsk.add_edit', $data);
    }

    public function brokerGetRecord($id, $bid)
    {

        $broker = ContactSearch::select(
            DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->find(decrypt($bid));

        if (!$broker || !$id) {
            return response()->json(['error' => 'Contact not found!']);
        }

        $data = [];
        $data['contact'] = $broker;
        $data['processors'] = Processor::all();
        $data['taskdata'] = TaskRelation::select(DB::raw('client_tasks.*,IF(client_tasks.followup_date IS NOT NULL,DATE_FORMAT(client_tasks.followup_date,"' . $this->mysql_date_format . '"),"") as followup_date'))->where('client_id', $broker->id)->where('id', '=', decrypt($id))->first();
        $data['taskdata']['enc_id'] = encrypt($data['taskdata']->id);
        return response()->json(['success' => true, 'task' => $data]);
    }

    public function brokertskUpdate($id, $bid, Request $request)
    {
        try {
            $validated = $request->validate([
                //'processor' => 'required',
                //'followup_date' => 'required|date',
                'followup_date' => 'required|date_format:d/m/Y', //'required|date',
                'user' => 'required',
                //'detail' => 'required',
            ]);
            $broker = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->find(decrypt($bid));

            if (!$broker) {
                response()->json(['error' => 'Invalid Contact!']);
            }

            $brokerExp = TaskRelation::find(decrypt($id));
            if (!$brokerExp || (isset($brokerExp) && $brokerExp->client_id != $broker->id)) {
                return redirect()->back()->with('error', 'Invalid Task Record!');
            }
            if ($request['followup_date'] != '') {
                $tempdob = str_replace('/', '-', $request['followup_date']);
                $request['followup_date'] = date('Y-m-d', strtotime($tempdob));
            }
            if ($request['end_date'] != '') {
                $tempdob = str_replace('/', '-', $request['end_date']);
                $request['end_date'] = date('Y-m-d', strtotime($tempdob));
            }

            $brokerExp->followup_date = $request['followup_date'];
            $brokerExp->end_date = $request['end_date'];
            //$brokerExp->processor = $request['processor'];
            $brokerExp->details = $request['detail'];
            $brokerExp->user = $request['user'];
            $brokerExp->updated_by = Auth::user()->id;
            $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
            $brokerExp->save();

            return response()->json(['success' => 'Record updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
