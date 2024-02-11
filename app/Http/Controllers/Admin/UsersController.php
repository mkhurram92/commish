<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABP;
use App\Models\ContactSearch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index() {

        return view('admin.abp.index');
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:abp"
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $abp = new ABP();
        $abp->name = $request['name'];
        $abp->created_at = Carbon::now('utc')->toDateTimeString();
        $abp->updated_at = Carbon::now('utc')->toDateTimeString();
        $abp->save();

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
                'id',
                'name',
                'created_at',
                'updated_at',
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
            $totalRecords = ABP::select('count(*) as allcount')->whereNull('deleted_at')->count();
             $filterSql = ABP::select('count(*) as allcount')->whereNull('deleted_at');
            if($searchValue!='')
                $sql =$filterSql->where('name', 'like', '%' .$searchValue . '%');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = ABP::select(DB::raw('id,name, DATE_FORMAT(created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->orderBy($columnName,
                $columnSortOrder)->whereNull('deleted_at')
                                ->skip($start)
                                ->take($rowperpage);
                if($searchValue!='')
                    $sql =$sql->where('name', 'like', '%' .$searchValue . '%');

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
            $record = ABP::find($request->id);
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
            'id' => "required|integer",
            'name' => "required|alpha_dash|unique:abp,id,".$request->id
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $abp = ABP::find($request->id);
        if(!$abp)
        {
            return response()->json(['error'=>'Invalid record!']);
        }
        $abp->name = $request['name'];
        $abp->save();

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

        $abp = ABP::find($request->id);
        if(!$abp)
        {
            return response()->json(['error'=>'Invalid record!']);
        }

        $existcount = ContactSearch::where('abp',$abp->id)->count();
        if($existcount>0)
        {
            return response()->json(['error'=>'Record already used in contacts!']);
        }

        $abp->delete();
        return response()->json(['success'=>'Record deleted successfully!']);
    }

}
