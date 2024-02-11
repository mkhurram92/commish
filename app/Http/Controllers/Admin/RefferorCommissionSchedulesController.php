<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionType;
use App\Models\RefferorCommissionSchedule;
use App\Models\ContactSearch;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RefferorCommissionSchedulesController extends Controller
{
    public function index() {
        $lenders = ContactSearch::select(DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name, CONCAT(first_name," ",middle_name," ",surname) as lender_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',2)->whereNull('deleted_at')->get();
        $products = Products::all();
        $commission_types = CommissionType::all();

        return view('admin.refferorcommissionschedule.index',compact('lenders','products','commission_types'));
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lender_id' => 'required|exists:contact_searches,id',
            'product_id' => 'required|exists:products,id',
            'commission_type_id' => 'required|exists:commission_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        if($request->per_rate > 100)
        {
            return response()->json(['error'=>'Percentage should be less then 100!']);
        }
        if(empty($request->flat_rate))
        {
            $request->flat_rate = 0;
        }
        if(empty($request->per_rate))
        {
            $request->per_rate = 0;
        }
        RefferorCommissionSchedule::updateOrCreate(['refferor_id'=>$request->lender_id,
            'product_id'=>$request->product_id,'commission_type_id'=>$request->commission_type_id],
            ['flat_rate'=>$request->flat_rate,'per_rate'=>$request->per_rate,'created_at'=>Carbon::now(),
                'created_by'=>auth()->user()->id,'note'=>$request->note,'deleted_at'=>NULL]);
        return response()->json(['success'=>'Record added successfully!']);
    }

    public function getRecords(Request $request)
    {
        try{
            $input =  $request->all();

            $datatableData = arrangeArrayPair( obj2Arr( $input ), 'name', 'value' );

            $start = 0;
            $rowperpage = 25;
            if ( isset( $datatableData['iDisplayStart'] ) && $datatableData['iDisplayLength'] != '-1' ) {
                $start =  intval( $datatableData['iDisplayStart'] );
                $rowperpage = intval( $datatableData['iDisplayLength'] );
            }

            // Ordering
            $sortableColumnsName = [
                'refferor_product_commission_schedule.id',
                'contact_searches.trading',
                'products.name',
                'commission_types.name',
                'flat_rate',
                'per_rate',
                'note',
                'refferor_product_commission_schedule.created_at',
                '',
            ];
            $columnName              = "created_at";
            $columnSortOrder              = "DESC";
            if ( isset( $datatableData['iSortCol_0'] ) ) {
                $sOrder = "ORDER BY  ";
                for ( $i = 0; $i < intval( $datatableData['iSortingCols'] ); $i ++ ) {
                    if ( (bool) $datatableData[ 'bSortable_' . intval( $datatableData[ 'iSortCol_' . $i ] ) ] == TRUE && isset( $datatableData[ 'sSortDir_' . $i ] ) ) {
                        $columnSortOrder = ( strcasecmp( $datatableData[ 'sSortDir_' . $i ], 'ASC' ) == 0 ) ? 'ASC' : 'DESC';
                        $columnName  = $sortableColumnsName[ intval( $datatableData[ 'iSortCol_' . $i ] ) ];

                    }
                }

                if ( $columnName == "" ) {
                    $columnName              = "created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if ( isset( $datatableData['sSearch'] ) && $datatableData['sSearch'] != "" ) {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords =RefferorCommissionSchedule::select(DB::raw('count(*) as allcount'))->whereNull('deleted_at')->count();
             $filterSql =RefferorCommissionSchedule::select(DB::raw('count(*) as allcount'))->whereNull('refferor_product_commission_schedule.deleted_at')->leftJoin('contact_searches','contact_searches.id','=','refferor_product_commission_schedule.refferor_id')->leftJoin('products','products.id','=','refferor_product_commission_schedule.product_id')->leftJoin('commission_types','commission_types.id','=','refferor_product_commission_schedule.commission_type_id');
            if($searchValue!='')
            {
                $filterSql =$filterSql->where(function($query) use($searchValue){
                    $query->where('contact_searches.name', 'like', '%' .$searchValue . '%')
                          ->orWhere('products.name', 'like','%'.$searchValue . '%')
                          ->orWhere('commission_types.name', 'like','%'.$searchValue . '%');
                });
            }

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql =RefferorCommissionSchedule::select(DB::raw('refferor_product_commission_schedule.id,CONCAT(first_name," ",middle_name," ",surname) as lender_name,products.name as product_name,commission_types.name as commission_type_name,flat_rate,per_rate,refferor_product_commission_schedule.note, DATE_FORMAT(refferor_product_commission_schedule.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(refferor_product_commission_schedule.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at'))->leftJoin('contact_searches','contact_searches.id','=','refferor_product_commission_schedule.refferor_id')->leftJoin('products','products.id','=','refferor_product_commission_schedule.product_id')->leftJoin('commission_types','commission_types.id','=','refferor_product_commission_schedule.commission_type_id')->orderBy($columnName,
                $columnSortOrder)->whereNull('refferor_product_commission_schedule.deleted_at')
                                ->skip($start)
                                ->take($rowperpage);
                if($searchValue!='')
                {
                    $sql =$sql->where(function($query) use($searchValue) {
                        $query->where('contact_searches.name', 'like', '%' . $searchValue . '%')
                              ->orWhere('products.name', 'like', '%' . $searchValue . '%')
                              ->orWhere('commission_types.name', 'like', '%' . $searchValue . '%');
                    });
                }

            $records = $sql->get();

            $response = array(
                "sEcho" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "draw" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $records
            );

            return response()->json(['success'=>true,'payload'=>$response]);
        } catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function getRecord(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => "required|integer"
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            $record =RefferorCommissionSchedule::find($request->id);
            if(!$record)
            {
                return response()->json(['error'=>'Record not found']);
            }
            return response()->json(['success'=>true,'payload'=>$record]);
        } catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function updateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lender_id' => 'required|exists:contact_searches,id',
            'product_id' => 'required|exists:products,id',
            'commission_type_id' => 'required|exists:commission_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $LendersLenders =RefferorCommissionSchedule::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }
        $LendersLenders->delete();
        RefferorCommissionSchedule::updateOrCreate(['lender_id'=>$request->lender_id,
            'product_id'=>$request->product_id,'commission_type_id'=>$request->commission_type_id],
            ['flat_rate'=>$request->flat_rate,'per_rate'=>$request->per_rate,'created_at'=>Carbon::now(),
                'created_by'=>auth()->user()->id,'note'=>$request->note,'deleted_at'=>NULL]);


        return response()->json(['success'=>'Record updated successfully!']);
    }

    public function deleteData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $LendersLenders =RefferorCommissionSchedule::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }

        $LendersLenders->delete();
        return response()->json(['success'=>'Record deleted successfully!']);
    }
}
