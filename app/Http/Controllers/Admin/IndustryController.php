<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndustryController extends Controller
{
    public function index() {
        return view('admin.industry.index');
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:industries'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $contRole = new Industry();
        $contRole->name = $request['name'];
        $contRole->created_at = Carbon::now('utc')->toDateTimeString();
        $contRole->updated_at = Carbon::now('utc')->toDateTimeString();
        $contRole->save();

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
            $totalRecords = Industry::select('count(*) as allcount')->count();
             $filterSql = Industry::select('count(*) as allcount');
            if($searchValue!='')
                $sql =$filterSql->where('name', 'like', '%' .$searchValue . '%');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = Industry::select(DB::raw('id,name, DATE_FORMAT(created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at'))->orderBy($columnName,
                $columnSortOrder)
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
            $record = Industry::find($request->id);
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
            'name' => "required|unique:industries,id,".$request->id
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $Industry = Industry::find($request->id);
        if(!$Industry)
        {
            return response()->json(['error'=>'Invalid record!']);
        }
        $Industry->name = $request['name'];
        $Industry->save();

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

        $Industry = Industry::find($request->id);
        if(!$Industry)
        {
            return response()->json(['error'=>'Invalid record!']);
        }

        $Industry->delete();
        return response()->json(['success'=>'Record deleted successfully!']);
    }
}
