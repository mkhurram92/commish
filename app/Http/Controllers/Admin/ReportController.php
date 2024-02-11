<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PipelineExport;
use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    //
    public function pipeline(){
        return view('admin.reports.pipeline_report',['header'=>'Commish| Pipeline Report']);
    }
    public function getPipelineRecords(Request $request){
        $deals=Deal::select('deals.*')->with(['lender','deal_status','client','product']);
        if($request['group_by']!=''){
            if($request['group_by']=='lender'){
                $deals->groupBy('lender_id');
            }if($request['group_by']=='broker_staff'){
                $deals->groupBy('broker_staff_id');
            }
            if($request['group_by']=='client'){
                $deals->groupBy('contact_id');
            }

        }
        if(!empty($request['from_date'])){
            $deals->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $deals->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }
        return DataTables::of($deals)
            ->make(true);
    }
    public function exportPipelineRecords(Request $request){
        $validator=Validator::make($request->all(),[
            'group_by'=>"",
            'from_date'=>"",
            'to_date'=>""
        ]);
        if($validator->fails()){
            return response()->json([
                'error'=>true,
                'message'=>$validator->errors()->first()
            ]);
        }else{
            $export=new PipelineExport();
            if(!empty($request['from_date'])){
                $export->date_from($request['from_date']);
            }
            if(!empty($input['to_date'])){
                $export->date_to($request['to_date']);
            }
            if(!empty($input['group_by'])){
                $export->group_by($request['group_by']);
            }
            return Excel::download($export, 'pipeline.'.$request['export_type']);
        }
    }
}
