<?php

//namespace App\Http\Controllers\Admin\Reports\Referror;
namespace App\Http\Controllers\Admin\Reports\Referror;


use App\Http\Controllers\Controller;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\DealCommission;
use App\Models\Lenders;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;

class ReferrorReportsController extends Controller
{
    public function index(Request $request)
    {
        $distinctDisplayNames = DB::table('deals')
            ->join('contact_searches as cs', 'deals.referror_split_referror', '=', 'cs.id')
            ->where('cs.search_for', 2)
            ->where('cs.status', 1)
            ->selectRaw("CASE WHEN cs.individual = 1 THEN CONCAT(cs.surname, ' ', cs.preferred_name) WHEN cs.individual = 2 THEN cs.trading END AS display_name, cs.id as cs_id")
            ->distinct('display_name')
            ->orderBy('display_name', 'asc')
            ->get();


        if ($distinctDisplayNames->isNotEmpty()) {
            $criteriaForm = [
                'start_date' => $request->input('start_date', ''),
                'end_date' => $request->input('end_date', ''),
                'display_name' => $request->input('display_name', ''),
                // Add more criteria fields as needed
            ];

            // Pass $criteriaForm and $distinctDisplayNames to the view
            return view('admin.reports.referrors.index', compact('criteriaForm', 'distinctDisplayNames'));
        }
    }
    public function getReferrerInvoice(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'referror_list' => $request->input('referror_list') == null ? 'required' : '',
        // ], [], [
        //     'referror_list' => 'Referrror Required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()->all()]);
        // }

        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
        $end_date = $end_date->format('Y-m-d');

        $referror_list = $request->referror_list;

        $distinctDisplayNames = DB::table('deals')
            ->join('contact_searches as cs', 'deals.referror_split_referror', '=', 'cs.id')
            ->join('contact_address as cd', 'cs.id', '=', 'cd.contact_id')
            ->join('states', 'cd.state', '=', 'states.id')
            ->where('cd.address_type', 1)
            ->where('cs.search_for', 2)
            ->where('cs.status', 1)
            ->selectRaw("CASE 
                        WHEN cs.individual = 1 THEN CONCAT(cs.surname, ' ', cs.preferred_name) 
                        WHEN cs.individual = 2 THEN cs.trading 
                    END AS display_name, 
                    cd.unit as cd_unit, 
                    cd.street_name as cd_street_name, 
                    cd.city as cd_city, 
                    states.name as cd_state, 
                    cs.id as cs_id, cd.postal_code as cd_pincode")
            ->distinct('display_name')
            ->orderBy('display_name', 'asc')
            ->get();

        //Commission Type List
        $distinctCommissionTypeNames = DB::table('commission_types')
            ->leftJoin('deal_commissions as dc', 'commission_types.id', '=', 'dc.type')
            ->selectRaw('name')
            ->distinct('name')
            ->orderBy('commission_types.id')
            ->get();

        $query = DB::table('deals')
            ->join('deal_commissions', 'deals.id', '=', 'deal_commissions.deal_id')
            ->join('contact_searches as cs_all', 'deals.contact_id', '=', 'cs_all.id')
            ->join('contact_searches as cs', 'deals.referror_split_referror', '=', 'cs.id')
            ->join('lenders', 'deals.lender_id', '=', 'lenders.id')
            ->join('commission_types', 'deal_commissions.type', '=', 'commission_types.id')
            ->where('cs.search_for', 2)
            ->whereBetween('deal_commissions.date_statement', [$start_date, $end_date]);

        if (!empty($request['referror_list'])) {
            $query->where('cs.id', '=', $request['referror_list']);
        }

        $referrorInvoices = $query->select(
            DB::raw("CASE WHEN cs.individual = 1 THEN CONCAT(cs.surname, ' ', cs.preferred_name) WHEN cs.individual = 2 THEN cs.trading END AS display_name"),
            DB::raw("CASE WHEN cs_all.individual = 1 THEN CONCAT(cs_all.surname, ' ', cs_all.preferred_name) WHEN cs_all.individual = 2 THEN cs_all.trading END AS cs_all_contact_name"),
            'deals.actual_loan',
            'lenders.code as lender_code',
            'deal_commissions.deal_id',
            'deal_commissions.type',
            'deal_commissions.total_amount as gross_commission',
            'deal_commissions.agg_amount',
            'deal_commissions.broker_amount',
            'deal_commissions.referror_amount',
            'deal_commissions.date_statement',
            'commission_types.name'
        )
            ->groupBy('display_name', 'cs_all_contact_name', 'deals.actual_loan', 'lender_code', 'deal_commissions.deal_id', 'deals.contact_id', 'commission_types.name', 'gross_commission', 'deal_commissions.referror_amount', 'deal_commissions.agg_amount', 'deal_commissions.broker_amount')
            ->having('gross_commission', '>', 0)
            ->having('deal_commissions.referror_amount', '>', 0)
            ->orderBy('lender_code')
            ->orderBy('deals.id')
            ->get();

        //return view('admin.reports.referrors.referror_invoice', compact('referrorInvoices', 'distinctDisplayNames'));

        $pdf = PDF::loadView('admin.reports.referrors.referror_invoice', [
            'referrorInvoices' => $referrorInvoices,
            'distinctDisplayNames' => $distinctDisplayNames,
            'distinctCommissionTypeNames' => $distinctCommissionTypeNames,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('referror_invoice.pdf');
    }
}

function getCurrentDateTimeFormatted()
{
    $adelaideTimeZone = new DateTimeZone('Australia/Adelaide');
    $adelaideTime = new DateTime('now', $adelaideTimeZone);

    return 'Printed on: ' . $adelaideTime->format('d/m/Y h:i A');
}
