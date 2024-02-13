<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\CommissionData;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\Lenders;
use App\Models\Products;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DB;

class LenderReportsController extends Controller
{
    //
    public function index()
    {
        return view('admin.reports.lenders.index', [
            'Commish| Lender Report',
        ]);
    }
    public function getLenderRecords(Request $request)
    {
        $lenders = Lenders::select('id', 'name');
        $pdf = PDF::loadView('admin.reports.lenders.lenders_report', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('lenders_list.pdf');
    }
    public function exportLenderRecords(Request $request)
    {
        $lenders = Lenders::select('id', 'name', 'code');
        $pdf = PDF::loadView('admin.reports.lenders.lenders_report', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('lenders_list.pdf');
    }
    public function getLenderReconciliationRecords(Request $request)
    {
        $lenders = Lenders::select('id', 'name', 'code')->with('deals');
        $lender_ids = $lenders->pluck('id')->toArray();

        $pdf = PDF::loadView('admin.reports.lenders.lender_reconciliation_report', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])
            ->setPaper('a4', 'landscape')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('lenders_commission_reconciliation.pdf');
    }
    public function exportLenderReconciliationRecords(Request $request)
    {
        $lenders = Lenders::select('id', 'name', 'code')->with('deals');
        $pdf = PDF::loadView('admin.reports.lenders.lender_reconciliation_report', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('lenders_commission_reconciliation.pdf');
    }
    public function getTrailCommissionNotReceivedRecords(Request $request)
    {
        // $lenders=Lenders::select('id','name','code')->with('deals');
        $lenders = Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        // dd($lenders->get());
        $pdf = PDF::loadView('admin.reports.lenders.trail_commission_not_received', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('trail_commission_not_received.pdf');
    }
    public function exportTrailCommissionNotReceivedRecords(Request $request)
    {
        $lenders = Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.trail_commission_not_received', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('trail_commission_not_received.pdf');
    }
    public function getUpfrontCommissionNotReceivedRecords(Request $request)
    {
        $lenders = Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.upfront_commission_not_received', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('upfront_commission_not_received.pdf');
    }
    public function exportGetUpfrontCommissionNotReceivedRecords(Request $request)
    {
        $lenders = Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.upfront_commission_not_received', [
            'lenders' => $lenders->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('upfront_commission_not_received.pdf');
    }
    public function getCommissionOtstanding()
    {
        $outstandings = [];
        $dealIds =  Deal::query()->pluck('id')->toArray();
        foreach ($dealIds as $dealId) {
            $commissionTrail = CommissionData::query()->where(['deal_id' => $dealId, 'commission_type' => 12])->first();

            if ($commissionTrail == null) {
                $outstandings[] = [$dealId, 'Trail'];
            }
            $commissionUpfront = CommissionData::query()->where(['deal_id' => $dealId, 'commission_type' => 13])->first();

            if ($commissionUpfront == null) {
                $outstandings[] = [$dealId, 'Upfront'];
            }
        }
        return view('admin.reports.commission.index', compact('outstandings'));
    }

    public function getLenderCommissionStatement(Request $request)
    {
        try {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
        } catch (\Exception $e) {
            // Handle date parsing error
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $selectedLender = $request->lenders;

        $dealsforFIMU = $this->buildCommissionQuery($selectedLender, 8, $to_date, 'FIMU');
        $dealsforOthers = $this->buildCommissionQuery($selectedLender, [8], $to_date, 'Others');
        $dealsforReferror = $this->buildReferror($selectedLender, $to_date);

        //dd($dealsforReferror);

        $pdf = PDF::loadView(
            'admin.reports.lenders.lender_commission_statement',
            [
                'dealsforFIMU' => $dealsforFIMU,
                'dealsforOthers' => $dealsforOthers,
                'dealsforReferror' => $dealsforReferror,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'lenders' => $request->lenders
            ]
        )
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);

        try {
            return $pdf->stream('lender_commission_statement.pdf');
        } catch (\Exception $e) {
            // Handle PDF generation error
            return response()->json(['error' => 'PDF generation error'], 500);
        }
    }

    private function buildCommissionQuery($selectedLender, $brokerId, $to_date, $brokerType)
    {
        // Ensure $brokerId is always an array
        $brokerId = is_array($brokerId) ? $brokerId : [$brokerId];

        $query = Deal::select(
            'products.name as productName',
            DB::raw("CASE WHEN contact_searches.individual = 1 THEN CONCAT(contact_searches.surname, ', ', contact_searches.preferred_name) WHEN contact_searches.individual = 2 THEN contact_searches.trading ELSE 'No Record' END AS client_name"),
            'deal_commissions.agg_amount',
            'deal_commissions.broker_amount',
            'deal_commissions.referror_amount',
            'deal_commissions.total_amount',
            'commission_types.name as dealType',
        )
            ->join('products', 'deals.product_id', '=', 'products.id')
            ->join('contact_searches', 'deals.contact_id', '=', 'contact_searches.id')
            ->leftJoin('deal_commissions', 'deals.id', '=', 'deal_commissions.deal_id')
            ->join('commission_types', 'deal_commissions.type', '=', 'commission_types.id')
            ->where('deal_commissions.date_statement', $to_date);

        if ($brokerType == 'FIMU') {
            $query->whereIn('deals.broker_id', $brokerId);
        } else {
            $query->where(function ($query) {
                $query->where('deals.broker_id', '!=', 8)
                    ->orWhereNull('deals.broker_id');
            });
        }

        if (!empty($selectedLender)) {
            $query->where('deals.lender_id', $selectedLender);
        }

        $query->orderBy('productName')->orderBy('client_name', 'asc');

        return $query->get()->groupBy('dealType');
    }
    private function buildReferror($selectedLender, $to_date)
    {
        $query = Deal::select(
            'products.name as productName',
            DB::raw("CASE WHEN contact_searches.individual = 1 THEN CONCAT(contact_searches.surname, ', ', contact_searches.preferred_name) WHEN contact_searches.individual = 2 THEN contact_searches.trading ELSE 'No Record' END AS client_name"),
            'deal_commissions.agg_amount',
            'deal_commissions.broker_amount',
            'deal_commissions.referror_amount',
            'deal_commissions.total_amount',
            'commission_types.name as dealType'
        )
            ->join('products', 'deals.product_id', '=', 'products.id')
            ->join('contact_searches', 'deals.referror_split_referror', '=', 'contact_searches.id')
            ->leftJoin('deal_commissions', 'deals.id', '=', 'deal_commissions.deal_id')
            ->join('commission_types', 'deal_commissions.type', '=', 'commission_types.id')
            ->where('deal_commissions.referror_amount', '<>', '0')
            ->where('deal_commissions.date_statement', $to_date)
            ->whereNotNull('deals.referror_split_referror')
            ->orderBy('productName');

        if (!empty($selectedLender)) {
            $query->where('deals.lender_id', $selectedLender);
        }

        return $query->get()->groupBy('dealType');
    }
}
function getCurrentDateTimeFormatted()
{
    return 'Printed on : ' . (new DateTime())->format('d/m/Y h:i A');
}
