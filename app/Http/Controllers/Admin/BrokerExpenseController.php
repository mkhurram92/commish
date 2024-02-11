<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\BrokerExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrokerExpenseController extends Controller
{
    public function brokerExpList($id) {

        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            return redirect()->back()->with('error','Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;
        $data['exptypes'] = DB::table('broker_expenses')->whereNull('deleted_at')->get();
        return view('admin.brokerexp.list', $data);
    }

    public function brokerexpAdd($id)
    {
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            return redirect()->back()->with('error','Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;

        $data['exptypes'] = DB::table('expense_types')->whereNull('deleted_at')->get();
        return view('admin.brokerexp.add_edit', $data);
    }

    public function brokerexpPost( $id, Request $request)
    {
        $validated = $request->validate([
            'expense_type_id' => 'required',
            'ordered_date' => 'required|date',
            'broker_charged' => 'required|date',
            'base_cost' => 'required|regex:/^\d+(\.\d{1,2})?/',
            'markup' => 'required|regex:/^\d+(\.\d{1,2})?/',
            'broker_charge' => 'required|regex:/^\d+(\.\d{1,2})?/',
        ]);
        $broker = Broker::find(decrypt($id));

        if(!$broker) {
            response()->json(['error' => 'Invalid Broker!']);
        }
        $brokerExp = new BrokerExpense();

        $brokerExp->broker_id = decrypt($id);
        $brokerExp->expense_type_id = $request['expense_type_id'];
        $brokerExp->ordered_date = $request['ordered_date'];
        $brokerExp->broker_charged = $request['broker_charged'];
        $brokerExp->broker_paid = $request['broker_paid'];
        $brokerExp->base_cost = $request['base_cost'];
        $brokerExp->markup = $request['markup'];
        $brokerExp->broker_charge = $request['broker_charge'];
        $brokerExp->detail = $request['detail'];
        $brokerExp->created_by = Auth::user()->id;
        $brokerExp->created_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->updated_at = Carbon::now('utc')->toDateTimeString();
        $brokerExp->save();

        response()->json(['success' => 'Record added successfully!']);
    }

    public function brokerexpEdit($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return redirect()->back()->with('error','Broker not found!');
        }

        $data = [];
        $data['broker'] = $broker;

        $data['expdata'] = DB::table('broker_expenses')->where('broker_id',$broker->id)->where('id','=',decrypt($id))->first();

        $data['exptypes'] = DB::table('expense_types')->whereNull('deleted_at')->get();
        return view('admin.brokerexp.add_edit', $data);
    }

    public function brokerexpEditGet($id, $bid) {

        $broker = Broker::find(decrypt($bid));

        if(!$broker || !$id) {
            return redirect()->json(['error'=>'Broker not found!']);
        }

        $data = [];
        $data['broker'] = $broker;

        $data['expdata'] = DB::table('broker_expenses')->where('broker_id',$broker->id)->where('id','=',decrypt($id))->first();
        $data['expdata']->enc_id = encrypt($data['expdata']->id);
        $data['exptypes'] = DB::table('expense_types')->whereNull('deleted_at')->get();
        return response()->json(['success' => 'true','data'=>$data]);
    }

    public function getRecords($id, Request $request)
    {
        try {
            $input =  $request->all();
            $bid = decrypt($id);
            $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');

            $start = 0;
            $rowperpage = 25;
            if (isset($datatableData['iDisplayStart']) && $datatableData['iDisplayLength'] != '-1') {
                $start =  intval($datatableData['iDisplayStart']);
                $rowperpage = intval($datatableData['iDisplayLength']);
            }

            // Ordering
            $sortableColumnsName = [
                'broker_expenses.id',
                'expense_types.name',
                'broker_expenses.ordered_date',
                'broker_expenses.broker_charged',
                'broker_expenses.broker_paid',
                'broker_expenses.base_cost',
                'broker_expenses.broker_charge',
                '',
            ];
            $columnName              = "broker_expenses.created_at";
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
                    $columnName              = "broker_expenses.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = BrokerExpense::select('count(*) as allcount')->whereNull('deleted_at')->where('broker_id','=',$bid)->count();
            $filterSql = BrokerExpense::select('count(*) as allcount')->where('broker_id','=',$bid)->whereNull('broker_expenses.deleted_at');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = BrokerExpense::select(DB::raw('broker_expenses.id,broker_expenses.broker_id,expense_types.name as expense_type_id,broker_expenses.ordered_date,broker_expenses.broker_charged,broker_expenses.broker_paid,broker_expenses.base_cost,broker_expenses.broker_charge'))->leftJoin('expense_types','expense_types.id','=','broker_expenses.expense_type_id')->orderBy(
                $columnName,
                $columnSortOrder
                )->whereNull('broker_expenses.deleted_at')
                ->where('broker_id','=',$bid)
                ->skip($start)
                ->take($rowperpage);

            if($searchValue!='')
                $sql =$sql->where('expense_types.name', 'like', '%' .$searchValue . '%');

            $records = $sql->get();

            if (count($records) > 0) {
                foreach ($records as $rkey => $record) {
                    $records[$rkey]['encrypt_id'] = encrypt($record->id);
                    $records[$rkey]['broker_encrypt_id'] = encrypt($record->broker_id);
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

    public function brokerexpUpdate( $id,$bid, Request $request)
    {
        try{
            $validated = $request->validate([
                'expense_type_id' => 'required',
                'ordered_date' => 'required|date',
                'broker_charged' => 'required|date',
                'base_cost' => 'required|regex:/^\d+(\.\d{1,2})?/',
                'markup' => 'required|regex:/^\d+(\.\d{1,2})?/',
                'broker_charge' => 'required|regex:/^\d+(\.\d{1,2})?/',
            ]);

            $broker = Broker::find(decrypt($bid));

            if(!$broker) {
                return redirect()->back()->with('error','Broker not found!');
            }

            $brokerExp = BrokerExpense::find(decrypt($id));
            if(!$brokerExp || (isset($brokerExp) && $brokerExp->broker_id != $broker->id))
            {
                return redirect()->back()->with('error','Invalid Expense Record!');
            }

            $brokerExp->expense_type_id = $request['expense_type_id'];
            $brokerExp->ordered_date = $request['ordered_date'];
            $brokerExp->broker_charged = $request['broker_charged'];
            $brokerExp->broker_paid = $request['broker_paid'];
            $brokerExp->base_cost = $request['base_cost'];
            $brokerExp->markup = $request['markup'];
            $brokerExp->broker_charge = $request['broker_charge'];
            $brokerExp->detail = $request['detail'];
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
