<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionType;
use App\Models\LenderCommissionSchedule;
use App\Models\Lenders;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LenderCommissionSchedulesController extends Controller
{
    public function index() {
        $lenders = Lenders::all();
        $products = Products::all();
        $commission_types = CommissionType::all();

        return view('admin.lendercommissionschedule.index',compact('lenders','products','commission_types'));
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lender_id' => 'required|exists:lenders,id',
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
        LenderCommissionSchedule::updateOrCreate(['lender_id'=>$request->lender_id,
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
                'lender_product_commission_schedule.id',
                'lenders.name',
                'products.name',
                'commission_types.name',
                'flat_rate',
                'per_rate',
                 '',
            ];
            $columnName              = "lender_product_commission_schedule.created_at";
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
                    $columnName              = "lender_product_commission_schedule.created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if ( isset( $datatableData['sSearch'] ) && $datatableData['sSearch'] != "" ) {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords =LenderCommissionSchedule::select(DB::raw('count(*) as allcount'))->whereNull('deleted_at')->count();
             $filterSql =LenderCommissionSchedule::select(DB::raw('count(*) as allcount'))->whereNull('lender_product_commission_schedule.deleted_at')->leftJoin('lenders','lenders.id','=','lender_product_commission_schedule.lender_id')->leftJoin('products','products.id','=','lender_product_commission_schedule.product_id')->leftJoin('commission_types','commission_types.id','=','lender_product_commission_schedule.commission_type_id');
            if($searchValue!='')
            {
                $filterSql =$filterSql->where(function($query) use($searchValue){
                    $query->where('lenders.name', 'like', '%' .$searchValue . '%')
                          ->orWhere('products.name', 'like','%'.$searchValue . '%')
                          ->orWhere('commission_types.name', 'like','%'.$searchValue . '%');
                });
            }

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql =LenderCommissionSchedule::select(DB::raw('lender_product_commission_schedule.id, lenders.name as lender_name,products.name as product_name,commission_types.name as commission_type_name,flat_rate,per_rate,note, DATE_FORMAT(lender_product_commission_schedule.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(lender_product_commission_schedule.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at'))->leftJoin('lenders','lenders.id','=','lender_product_commission_schedule.lender_id')->leftJoin('products','products.id','=','lender_product_commission_schedule.product_id')->leftJoin('commission_types','commission_types.id','=','lender_product_commission_schedule.commission_type_id')->orderBy($columnName,
                $columnSortOrder)->whereNull('lender_product_commission_schedule.deleted_at')
                                ->skip($start)
                                ->take($rowperpage);
                if($searchValue!='')
                {
                    $sql =$sql->where(function($query) use($searchValue) {
                        $query->where('lenders.name', 'like', '%' . $searchValue . '%')
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
            $record =LenderCommissionSchedule::find($request->id);
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
            'lender_id' => 'required|exists:lenders,id',
            'product_id' => 'required|exists:products,id',
            'commission_type_id' => 'required|exists:commission_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $LendersLenders =LenderCommissionSchedule::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }
        $LendersLenders->delete();
        LenderCommissionSchedule::updateOrCreate(['lender_id'=>$request->lender_id,
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

        $LendersLenders =LenderCommissionSchedule::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }

        $LendersLenders->delete();
        return response()->json(['success'=>'Record deleted successfully!']);
    }

    public function getSgCommission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lender_id' => "required|exists:lenders,id",
            'product_id' => "required|exists:products,id",
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

      $commissions =   LenderCommissionSchedule::where('lender_id',$request->lender_id)->where('product_id',
        $request->product_id)
            ->join('commission_types as ct','ct.id','=','lender_product_commission_schedule.commission_type_id')
            ->whereIn('ct.name',['Upfront','Trail','Brokerage'])->get();

        $upfront = 0;
        $brokrage = 0;
        $trail = 0;
        if($commissions)
        {
            foreach($commissions as $commission)
            {
                if($commission->name == 'Upfront')
                {
                    $upfront = $commission->per_rate;
                }else if($commission->name == 'Trail')
                {
                    $trail = $commission->per_rate;
                }else if($commission->name == 'Brokerage')
                {
                    $brokrage = $commission->per_rate;
                }
            }
        }

        return response()->json(['success'=>'success','model'=>['upfront'=>$upfront,'trail'=>$trail,'brokrage'=>$brokrage]]);
    }
}
