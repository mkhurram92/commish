<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\Commission;
use App\Models\CommissionData;
use App\Models\CommissionType;
use App\Models\Deal;
use App\Models\DealNote;
use App\Models\DealMissing;
use App\Models\User;
use App\Models\DealCommission;
use App\Models\BrokerCommissionModel;
use App\Models\BrokerStaff;
use App\Models\DealStatus;
use App\Models\DealStatusUpdate;
use App\Models\Lenders;
use App\Models\Processor;
use App\Models\Products;
use App\Models\ContactSearch;
use App\Models\DealClient;
use App\Models\DealClientType;
use App\Models\DealsWithMissingRefsStatus;
use App\Models\DealTask;
use App\Models\Relationship;
use App\Models\DealLoanTypes;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class DealsController extends Controller
{
    public $comm_types = [
        'Trail Commissions' => 2,
        'Adjustment' => 3,
        'Upfront' => 1

    ];
    public function dealList()
    {
        $data = [];
        $data['lenders'] = Lenders::whereNull('deleted_at')->get();
        $data['products'] = Products::whereNull('deleted_at')->get();
        $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))->whereNull('deleted_at')->orderBy('status_code', 'ASC')->get();

        return view('admin.deal.list', $data);
    }

    public function dealAdd()
    {
        $data['relations'] = Relationship::all();;
        $data['brokers'] = Broker::select(DB::raw('id,trading,trust_name,surname,given_name,entity_name,parent_broker,is_individual'))->where('parent_broker', 0)->where('is_active', 1)->whereNull('deleted_at')->get();
        $data['broker_staffs'] = BrokerStaff::select(DB::raw('id,CONCAT_WS(" ",given_name, surname) as display_name,surname,given_name,broker_id'))->orderBy('display_name')->get();

        $data['products'] = Products::whereNull('deleted_at')->get();
        $data['lenders'] = Lenders::whereNull('deleted_at')->get();
        $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))->whereNull('deleted_at')->orderBy('status_code', 'ASC')->get();
        $data['refferors'] = ContactSearch::select(DB::raw(
            'id, individual, 
            CASE 
                WHEN individual = 1 THEN CONCAT_WS(" ", surname, preferred_name)
                WHEN individual = 2 THEN trading
            END as display_name,
            trading, trust_name, surname, first_name, middle_name, preferred_name, entity_name'
        ))->where('search_for', 2)->whereNull('deleted_at')->where('status', 1)->get()->sortBy('display_name');

        $data['comm_types'] = CommissionType::all()->pluck('name', 'id')->toArray();
        $data['commission_models'] = Commission::all();
        $data['deal_client_types'] = DealClientType::whereNull('deleted_at')->get();

        $data['existing_deals'] = Deal::select(DB::raw('deals.*, 
            CASE 
                WHEN contact_searches.individual = 1 THEN CONCAT_WS(" ", contact_searches.surname, contact_searches.preferred_name)
                WHEN contact_searches.individual = 2 THEN contact_searches.trading
            END as display_name'))
            ->join('contact_searches', 'contact_searches.id', '=', 'deals.contact_id')
            ->orderBy('id', 'desc')
            ->get();

        $data['LoanTypes'] = DealLoanTypes::select('*')->whereNull('deleted_at')
            ->orderBy('name', 'ASC')->get();
        return view('admin.deal.add_edit', $data);
    }
    public function getClients(Request $request)
    {
        $data['clients'] = ContactSearch::select(DB::raw('id, trading, 
            CASE WHEN individual = 1 THEN CONCAT_WS(" ", surname, preferred_name) 
                 WHEN individual = 2 THEN trading 
            END as display_name, surname, preferred_name, individual'))
            ->whereNull('deleted_at')->where('status', 1)->get();

        $search = $request->search;
        $clients = ContactSearch::select(DB::raw('id, trading, 
                CASE WHEN individual = 1 THEN CONCAT_WS(" ", surname, preferred_name) 
                     WHEN individual = 2 THEN trading 
                END as display_name, surname, preferred_name, search_for'))
            ->when(!empty($search), function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->orWhereRaw("CONCAT_WS(' ', surname, preferred_name) LIKE ?", ["$search%"]);
                    $query->orWhere('trading', 'like', "$search%");
                });
                $q->where('status', 1);
            })
            ->whereNull('deleted_at')
            ->paginate(100, ['*'], 'page', $request->page)
            ->toArray();

        $response = array();
        foreach ($clients['data'] as $client) {
            $response["results"][] = array(
                "id" => $client['id'],
                "text" => $client['display_name']
            );
        }

        if ($clients['next_page_url']) {
            $response['pagination'] = ["more" => true];
        }

        $response['count_filtered'] = $clients['total'];

        return response()->json($response);
    }

    public function dealPost(Request $request)
    {
        $validated = $request->validate([
            'broker_id' => 'required',
            'contact_id' => 'required',
            'product_id' => 'required',
            'lender_id' => 'required',
            'status' => 'required'

        ], [], [
            'broker_id' => 'Broker',
            'contact_id' => 'Client',
            'product_id' => 'Product',
            'lender_id' => 'Lender',
        ]);

        try {
            DB::beginTransaction();
            $contact = new Deal();
            $proposed_settlement = $request['proposed_settlement'];
            $status_date = $request['status_date'];
            if ($request['proposed_settlement'] != '') {
                $proposed_settlement = \DateTime::createFromFormat('d/m/Y', $request['proposed_settlement']);
                $proposed_settlement = $proposed_settlement->format('Y-m-d');
            }
            if ($request['status_date'] != '') {
                $status_date = \DateTime::createFromFormat('d/m/Y', $request['status_date']);
                $status_date = $status_date->format('Y-m-d');
            }
            $contact->gst_applies = isset($request['gst_applies']) && $request['gst_applies'] > 0 ?  1 : 0;

            $contact->broker_id = $request['broker_id'];
            $contact->broker_staff_id = $request['broker_staff_id'];
            $contact->contact_id = $request['contact_id'];
            $contact->product_id = $request['product_id'];
            $contact->lender_id = $request['lender_id'];
            $contact->loan_type_id = $request['loan_type_id'];
            $contact->loan_ref = $request['loan_ref'];
            $contact->application_no = $request['application_no'];
            $contact->linked_to = ($request['linked_to'] > 0) ? $request['linked_to'] : 0;
            $contact->status = $request['status'];
            $contact->proposed_settlement = $proposed_settlement;
            $contact->actual_loan = $request['actual_loan'];
            $contact->broker_est_upfront = $request['broker_est_upfront'];
            $contact->broker_est_trail = $request['broker_est_trail'];
            $contact->broker_est_brokerage = $request['broker_est_brokerage'];
            $contact->broker_est_loan_amt = $request['broker_est_loan_amt'];
            $contact->agg_est_upfront = $request['agg_est_upfront'];
            $contact->agg_est_trail = $request['agg_est_trail'];
            $contact->agg_est_brokerage = $request['agg_est_brokerage'];

            $contact->status_date = $status_date;
            $contact->commission_model = $request['commission_model'];
            $contact->broker_split_commis_model = ($request['commission_model'] > 0) ? $request['commission_model'] : 0;
            $contact->broker_split_fee_per_deal = ($request['broker_split_fee_per_deal'] > 0) ? $request['broker_split_fee_per_deal'] : 0;
            $contact->broker_split_agg_brk_sp_upfrt = ($request['broker_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_split_agg_brk_sp_upfrt'] : 0;
            $contact->broker_split_agg_brk_sp_trail = ($request['broker_split_agg_brk_sp_trail'] > 0) ? $request['broker_split_agg_brk_sp_trail'] : 0;
            $contact->referror_split_referror = ($request['referror_split_referror'] > 0) ? $request['referror_split_referror'] : 0;
            $contact->referror_split_comm_per_deal = ($request['referror_split_comm_per_deal'] > 0) ? $request['referror_split_comm_per_deal'] : 0;
            $contact->referror_split_agg_brk_sp_upfrt = ($request['referror_split_agg_brk_sp_upfrt'] > 0) ? $request['referror_split_agg_brk_sp_upfrt'] : 0;
            $contact->referror_split_agg_brk_sp_trail = ($request['referror_split_agg_brk_sp_trail'] > 0) ? $request['referror_split_agg_brk_sp_trail'] : 0;
            $contact->note = $request['note'];
            $contact->gst_applies = $request['gst_applies'];

            $contact->created_by = Auth::user()->id;

            if ($contact->save()) {
                if (isset($request->actuals_comm) && count($request->actuals_comm) > 0) {
                    $com_data = [];
                    foreach ($request->actuals_comm as $actual_com) {
                        $com_data[] = [
                            'deal_id' => $contact->id,
                            'order' => 1,
                            'type' => isset($actual_com['commission_type']) && $actual_com['commission_type'] > 0 ?
                                $actual_com['commission_type'] : 0,
                            'total_amount' => $actual_com['total_amount'],
                            'date_statement' => date('Y-m-d', strtotime($actual_com['date_statement'])),
                            'agg_amount' => $actual_com['agg_amount'],
                            'broker_amount' => $actual_com['broker_amount'],
                            'broker_staff_amount' => $actual_com['broker_staff_amount'],
                            'bro_staff_amt_date_paid' => date('Y-m-d', strtotime($actual_com['bro_staff_amt_date_paid'])),
                            'bro_amt_date_paid' => date('Y-m-d', strtotime($actual_com['bro_amt_date_paid'])),
                            'referror_amount' => $actual_com['referror_amount'],
                            'ref_amt_date_paid' => date('Y-m-d', strtotime($actual_com['ref_amt_date_paid'])),
                            'type_data' => 0,
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now('utc+9')->toDateTimeString(),
                            'updated_at' => Carbon::now('utc+9')->toDateTimeString(),
                        ];
                    }

                    if (count($com_data) > 0) {
                        DealCommission::insert($com_data);
                    }
                }

                DealStatusUpdate::insert([
                    'deal_id' => $contact->id,
                    'from_status' => 0,
                    'status_id' => $request['status'],
                    'created_by' => Auth::user()->id,
                    'created_at'    => now()

                ]);
                DB::commit();

                return  response()->json(['success' => 'Record added successfully!', 'link' => route('admin.deals.view', encrypt($contact->id))]);
            } else {
                DB::rollback();
                return response()->json(['error' => "Something went wrong while save record!"]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /*    public function dealEdit($id)
    {
        //print_R($id);die;
        $deal = Deal::with(['relations', 'tasks', 'withLoanType'])->find(decrypt($id));
        //echo '</pre>';
        //print_R($deal);die;
        //echo '</pre>';
        $data['relations'] = Relationship::all();;
        if ($deal) {
            $data['brokers'] = Broker::select(DB::raw('id,is_individual, trading as display_name,trading,trust_name,surname,given_name,entity_name,parent_broker'))->where('parent_broker', 0)->where('is_active', 1)->whereNull('deleted_at')->get();
            $data['broker_staffs'] = BrokerStaff::select(DB::raw('id,CONCAT_WS(" ",given_name, surname) as display_name,surname,given_name,broker_id'))->orderBy('display_name')->get();

            //$data['client'] = ContactSearch::select(
            //    DB::raw('id,CONCAT_WS(" ", COALESCE(NULLIF(surname, ""), first_name, "N/A"), COALESCE(NULLIF(preferred_name, ""), "")) AS fullname,CONCAT(first_name," ",middle_name," ",surname) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            //)->where('id', $deal->contact_id)->whereNull('deleted_at')->first();

            $data['client'] = ContactSearch::select(
                DB::raw('id, 
                    CASE 
                        WHEN individual = 1 THEN CONCAT_WS(" ", COALESCE(NULLIF(surname, ""), ""), COALESCE(NULLIF(preferred_name, ""), ""))
                        WHEN individual = 2 THEN trading
                    END AS fullname,
                trading, trust_name, surname, search_for, preferred_name, entity_name')
            )->where('id', $deal->contact_id)->whereNull('deleted_at')->first();

            $data['refferors'] = ContactSearch::select(
                DB::raw('id,CONCAT_WS(" ",surname, preferred_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->where('search_for', 2)->whereNull('deleted_at')->where('status', 1)->get();
            $data['products'] = Products::whereNull('deleted_at')->get();
            $data['lenders'] = Lenders::whereNull('deleted_at')->get();
            $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))
                ->whereNull('deleted_at')->orderBy('status_code', 'ASC')->get();
            $data['deal'] = $deal;
            $data['commission_models'] = Commission::all();
            $data['deal_commissions'] = DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"' . $this->mysql_date_format . '") as date_statement,DATE_FORMAT(bro_amt_date_paid,"' . $this->mysql_date_format . '") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"' . $this->mysql_date_format . '") as ref_amt_date_paid'))->where('deal_id', $deal->id)->whereNull('deal_commissions.deleted_at')->where('type_data', 0)->orderBy('order', 'ASC')->join('commission_types as ct', 'ct.id', '=', 'deal_commissions.type')->get();
            $data['comm_types'] = CommissionType::all()->pluck('name', 'id')->toArray();

            $data['deal_client_types'] = DealClientType::whereNull('deleted_at')->get();
            $data['existing_deals'] = Deal::select(DB::raw('deals.*,CONCAT_WS(",",contact_searches.surname,contact_searches.preferred_name) as display_name'))->join('contact_searches', 'contact_searches.id', '=', 'deals.contact_id')->where('deals.id', '!=', $deal->id)->get();
            $data['LoanTypes'] = DealLoanTypes::select('*')->whereNull('deleted_at')->orderBy('name', 'ASC')->get();
            return view('admin.deal.add_edit', $data);
        } else {
            return redirect()->back()->with('error', 'Deal not found.');
        }
    }
*/
    public function dealEdit($id)
    {
        $deal = Deal::with(['relations', 'tasks', 'withLoanType'])->find(decrypt($id));
        $data['relations'] = Relationship::all();

        if ($deal) {
            $user = auth()->user(); // Get the logged-in user
            $userId = $user->id;

            // Query to get brokers
            $brokersQuery = Broker::select(DB::raw('brokers.id, brokers.is_individual, brokers.trading as display_name, brokers.trading, brokers.trust_name, brokers.surname, brokers.given_name, brokers.entity_name, brokers.parent_broker'))
                ->where('brokers.parent_broker', 0)
                ->where('brokers.is_active', 1)
                ->whereNull('brokers.deleted_at');

            // Apply the user_brokers join and filter only if the user is not an admin
            if ($user->role != 'admin') {
                $brokersQuery->join('user_brokers', 'brokers.id', '=', 'user_brokers.broker_id')
                    ->where('user_brokers.user_id', $userId);
            }

            $data['brokers'] = $brokersQuery->get();

            // Query to get contacts
            $contactsQuery = ContactSearch::select(DB::raw('contact_searches.id, contact_searches.surname, contact_searches.first_name, contact_searches.preferred_name, contact_searches.trading, contact_searches.entity_name'))
                ->whereNull('contact_searches.deleted_at');

            // Apply the user_contacts join and filter only if the user is not an admin
            if ($user->role != 'admin') {
                $contactsQuery->join('user_contacts', 'contact_searches.id', '=', 'user_contacts.contact_id')
                    ->where('user_contacts.user_id', $userId);
            }

            $data['contacts'] = $contactsQuery->get();

            $data['broker_staffs'] = BrokerStaff::select(DB::raw('id, CONCAT_WS(" ", given_name, surname) as display_name, surname, given_name, broker_id'))
                ->orderBy('display_name')
                ->get();

            $data['client'] = $data['contacts']->firstWhere('id', $deal->contact_id);

            $data['refferors'] = ContactSearch::select(
                DB::raw('id, CONCAT_WS(" ", surname, preferred_name) as display_name, trading, trust_name, surname, first_name, middle_name, preferred_name, entity_name')
            )->where('search_for', 2)
                ->whereNull('deleted_at')
                ->where('status', 1)
                ->get();

            $data['products'] = Products::whereNull('deleted_at')->get();
            $data['lenders'] = Lenders::whereNull('deleted_at')->get();
            $data['statuses'] = DealStatus::select(DB::raw('id, status_code, CONCAT(LPAD(status_code, 2, 0), " ", name) as name'))
                ->whereNull('deleted_at')
                ->orderBy('status_code', 'ASC')
                ->get();

            $data['deal'] = $deal;
            $data['commission_models'] = Commission::all();
            $data['deal_commissions'] = DealCommission::select(DB::raw('deal_commissions.*, ct.name as commission_type_name, DATE_FORMAT(date_statement, "' . $this->mysql_date_format . '") as date_statement, DATE_FORMAT(bro_amt_date_paid, "' . $this->mysql_date_format . '") as bro_amt_date_paid, DATE_FORMAT(ref_amt_date_paid, "' . $this->mysql_date_format . '") as ref_amt_date_paid'))
                ->where('deal_id', $deal->id)
                ->whereNull('deal_commissions.deleted_at')
                ->where('type_data', 0)
                ->orderBy('order', 'ASC')
                ->join('commission_types as ct', 'ct.id', '=', 'deal_commissions.type')
                ->get();

            $data['comm_types'] = CommissionType::all()->pluck('name', 'id')->toArray();
            $data['deal_client_types'] = DealClientType::whereNull('deleted_at')->get();
            $data['existing_deals'] = Deal::select(DB::raw('deals.*, CONCAT_WS(",", contact_searches.surname, contact_searches.preferred_name) as display_name'))
                ->join('contact_searches', 'contact_searches.id', '=', 'deals.contact_id')
                ->where('deals.id', '!=', $deal->id)
                ->get();

            $data['LoanTypes'] = DealLoanTypes::select('*')->whereNull('deleted_at')->orderBy('name', 'ASC')->get();

            return view('admin.deal.add_edit', $data);
        } else {
            return redirect()->back()->with('error', 'Deal not found.');
        }
    }

    public function dealView($id)
    {
        $deal = Deal::with(['deal_clients', 'tasks', 'withNotes', 'withLoanType'])->select(DB::raw("deals.*,CONCAT_WS(' ', 
        CASE WHEN cs.individual = 1 THEN COALESCE(NULLIF(cs.surname, ''), '') ELSE COALESCE(NULLIF(cs.trading, ''), '') END,
        CASE WHEN cs.individual = 1 THEN COALESCE(NULLIF(cs.preferred_name, ''), '') ELSE '' END ) AS fullname,brokers.given_name,cs.preferred_name,lenders.code as lendername,CONCAT(cs.first_name,' ',  cs.surname) AS preferred_name, st.name status, p.name productname, cs.email, cs.search_for, date_format(deals.created_at,'" . $this->mysql_date_format . "') date_sattled,
        CASE WHEN brokers.is_individual = 1 THEN CONCAT_WS(',', COALESCE(NULLIF(brokers.surname, ''), ''), COALESCE(NULLIF(brokers.given_name, ''), '')) ELSE brokers.trading END AS broker_display_name,
        CONCAT_WS(' ',broker_staff.given_name,broker_staff.surname) as broker_staff_display_name,CASE 
        WHEN refer.individual = 1 THEN CONCAT_WS(' ', refer.surname, refer.preferred_name)
        WHEN refer.individual = 2 THEN refer.trading
      END AS refer_display_name,commission_model.name as commission_model_display_name,deals.loan_ref as linked_to_loan_ref,deals.application_no, CONCAT_WS(' ',linktocont.surname, linktocont.preferred_name) as linked_to_display_name ,linktocont.surname , linkto.id as linkto_pk_id"))
            ->with(['relationDetails'])
            ->leftJoin('brokers', 'deals.broker_id', '=', 'brokers.id')
            ->leftJoin('contact_searches as cs', 'deals.contact_id', '=', 'cs.id')
            ->leftJoin('brokers_staffs as broker_staff', 'deals.broker_staff_id', '=', 'broker_staff.id')
            ->leftJoin('lenders', 'deals.lender_id', '=', 'lenders.id')
            ->leftJoin('deal_statuses as st', 'deals.status', '=', 'st.id')->leftJoin('deals as linkto', 'deals.linked_to', '=', 'linkto.id')->leftJoin('contact_searches as linktocont', 'linktocont.id', '=', 'linkto.contact_id')->leftJoin('contact_searches as refer', 'deals.referror_split_referror', '=', 'refer.id')->leftJoin('commission_model', 'commission_model.id', '=', 'deals.commission_model')
            ->leftJoin('products as p', 'deals.product_id', '=', 'p.id')->find(decrypt($id));

        $processors = User::where('role', '!=', 'admin')->get();
        $task_users = BrokerStaff::where("broker_id", 8)->get();
        $dealDetails = [
            '1' => '12 month follow up',
            '2' => '24 month follow up'
        ];
        if ($deal) {
            $status_history = $deal->deal_status_history;

            $deal_commissions = DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"' . $this->mysql_date_format . '") as date_statement,DATE_FORMAT(bro_amt_date_paid,"' . $this->mysql_date_format . '") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"' . $this->mysql_date_format . '") as ref_amt_date_paid'))->where('deal_id', $deal->id)->whereNull('deal_commissions.deleted_at')->where('deal_commissions.type', 13)->orderBy('order', 'ASC')->join('commission_types as ct', 'ct.id', '=', 'deal_commissions.type')->get();

            $deal_commissions_total = DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"' . $this->mysql_date_format . '") as date_statement,DATE_FORMAT(bro_amt_date_paid,"' . $this->mysql_date_format . '") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"' . $this->mysql_date_format . '") as ref_amt_date_paid'))->where('deal_id', $deal->id)->whereNull('deal_commissions.deleted_at')->where('deal_commissions.type', 13)->orderBy('order', 'ASC')->join('commission_types as ct', 'ct.id', '=', 'deal_commissions.type')->sum("total_amount");

            $comm_types = CommissionType::all()->pluck('name', 'id')->toArray();
            return view('admin.deal.view', compact('deal', 'deal_commissions', 'deal_commissions_total', 'processors', 'task_users', 'dealDetails', 'comm_types', 'status_history'));
        } else {
            return redirect()->back()->with('error', 'Deal not found.');
        }
    }

    public function getDealData($dealId)
    {
        $dealData = Deal::where('id', $dealId)->first();
        return $dealData;
    }

    public function dealUpdate(Request $request, $id)
    {

        $validated = $request->validate([
            'broker_id' => 'required',
            'contact_id' => 'required',
            'product_id' => 'required',
            'lender_id' => 'required',
            'status' => 'required'
        ], [], [
            'broker_id' => 'Broker',
            'contact_id' => 'Client',
            'product_id' => 'Product',
            'lender_id' => 'Lender',
        ]);

        try {
            $contact = Deal::find(decrypt($id));
            if ($contact) {
                $currentStatus = $contact->status;
                DB::beginTransaction();
                if (isset($request['proposed_settlement']) && !empty($request['proposed_settlement'])) {
                    $tempdob = str_replace('/', '-', $request['proposed_settlement']);
                    $request['proposed_settlement'] = date('Y-m-d', strtotime($tempdob));
                }
                if (isset($request['status_date']) && !empty($request['status_date'])) {
                    $tempdob = str_replace('/', '-', $request['status_date']);
                    $request['status_date'] = date('Y-m-d', strtotime($tempdob));
                }

                $contact->gst_applies = isset($request['gst_applies']) && $request['gst_applies'] > 0 ?  1 : 0;

                $contact->broker_id = $request['broker_id'];
                $contact->broker_staff_id = $request['broker_staff_id'];
                $contact->contact_id = $request['contact_id'];
                $contact->product_id = $request['product_id'];
                $contact->lender_id = $request['lender_id'];
                $contact->loan_type_id = $request['loan_type_id'];
                $contact->loan_ref = $request['loan_ref'];
                $contact->application_no = $request['application_no'];
                $contact->linked_to = ($request['linked_to'] > 0) ? $request['linked_to'] : 0;
                $contact->status = $request['status'];
                $contact->proposed_settlement = $request['proposed_settlement'];
                $contact->actual_loan = $request['actual_loan'];
                $contact->broker_est_upfront = $request['broker_est_upfront'];
                $contact->broker_est_trail = $request['broker_est_trail'];
                $contact->broker_est_brokerage = $request['broker_est_brokerage'];
                $contact->broker_est_loan_amt = $request['broker_est_loan_amt'];
                $contact->agg_est_upfront = $request['agg_est_upfront'];
                $contact->agg_est_trail = $request['agg_est_trail'];
                $contact->agg_est_brokerage = $request['agg_est_brokerage'];
                $contact->status_date = $request['status_date'];
                $contact->commission_model = $request['commission_model'];
                $contact->broker_split_commis_model = ($request['commission_model'] > 0) ? $request['commission_model'] : 0;
                $contact->broker_split_fee_per_deal = ($request['broker_split_fee_per_deal'] > 0) ? $request['broker_split_fee_per_deal'] : 0;
                $contact->broker_split_agg_brk_sp_upfrt = ($request['broker_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_split_agg_brk_sp_upfrt'] : 0;
                $contact->broker_split_agg_brk_sp_trail = ($request['broker_split_agg_brk_sp_trail'] > 0) ? $request['broker_split_agg_brk_sp_trail'] : 0;
                $contact->referror_split_referror = ($request['referror_split_referror'] > 0) ? $request['referror_split_referror'] : 0;
                $contact->referror_split_comm_per_deal = ($request['referror_split_comm_per_deal'] > 0) ? $request['referror_split_comm_per_deal'] : 0;
                $contact->referror_split_agg_brk_sp_upfrt = ($request['referror_split_agg_brk_sp_upfrt'] > 0) ? $request['referror_split_agg_brk_sp_upfrt'] : 0;
                $contact->referror_split_agg_brk_sp_trail = ($request['referror_split_agg_brk_sp_trail'] > 0) ? $request['referror_split_agg_brk_sp_trail'] : 0;
                $contact->note = $request['note'];
                $contact->gst_applies = $request['gst_applies'];
                $contact->updated_by = Auth::user()->id;
                $contact->save();

                if ($contact->id > 0) {
                    if ($currentStatus != $request['status']) {
                        DealStatusUpdate::insert([
                            'deal_id' => $contact->id,
                            'from_status' => $currentStatus,
                            'status_id' => $request['status'],
                            'created_by' => Auth::user()->id,
                            'created_at' => now()
                        ]);
                    }
                    if (isset($request->actuals_comm) && count($request->actuals_comm) > 0) {
                        $com_data = [];
                        foreach ($request->actuals_comm as $actual_com) {
                            $com_data[] = [
                                'deal_id' => $contact->id,
                                'order' => 1,
                                'type' => isset($actual_com['commission_type']) && $actual_com['commission_type'] > 0 ?
                                    $actual_com['commission_type'] : 0,
                                'total_amount' => $actual_com['total_amount'],
                                'date_statement' => date('Y-m-d', strtotime($actual_com['date_statement'])),
                                'agg_amount' => $actual_com['agg_amount'],
                                'broker_amount' => $actual_com['broker_amount'],
                                'broker_staff_amount' => $actual_com['broker_staff_amount'],
                                'bro_staff_amt_date_paid' => date('Y-m-d', strtotime($actual_com['bro_staff_amt_date_paid'])),
                                'bro_amt_date_paid' => date('Y-m-d', strtotime($actual_com['bro_amt_date_paid'])),
                                'referror_amount' => $actual_com['referror_amount'],
                                'ref_amt_date_paid' => date('Y-m-d', strtotime($actual_com['ref_amt_date_paid'])),
                                'created_by' => Auth::user()->id,
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString(),
                            ];
                        }

                        if (count($com_data) > 0) {
                            DealCommission::insert($com_data);
                        }
                    }
                    DB::commit();
                } else {
                    DB::rollback();
                    return response()->json(['error' => "Something went wrong while save record!"]);
                }
            } else {
                return response()->json(['error' => "Record not found!"]);
            }

            return response()->json(['success' => 'Record updated successfully!', 'link' => route('admin.deals.view', encrypt($contact->id))]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function dealDelete($id)
    {
        $contact = ContactSearch::find(decrypt($id));
        if ($contact) {
            $contact->delete();
            return redirect()->back()->with('success', 'Contact deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function getRecords(Request $request)
    {
        try {
            $input = $request->all();
            $search = $request->search['value'];
            $user = auth()->user(); // Get the currently logged-in user

            if ($request->ajax()) {
                $data = Deal::select(DB::raw("
                deals.*,
                CASE WHEN brokers.is_individual = 1 
                    THEN CONCAT_WS(' ', COALESCE(NULLIF(brokers.surname, ''), ''), COALESCE(NULLIF(brokers.given_name, ''), '')) 
                    ELSE brokers.trading 
                END AS broker_trading,
                lenders.code AS lendername,
                CONCAT_WS(' ', 
                    CASE WHEN cs.individual = 1 THEN COALESCE(NULLIF(cs.surname, ''), '') ELSE COALESCE(NULLIF(cs.trading, ''), '') END,
                    CASE WHEN cs.individual = 1 THEN COALESCE(NULLIF(cs.preferred_name, ''), '') ELSE '' END
                ) AS fullname, 
                CONCAT(LPAD(st.status_code, 2, 0), ':', st.name) AS status, 
                p.name AS productname, 
                cs.preferred_name, 
                brokers.given_name, 
                cs.email, 
                DATE_FORMAT(deals.status_date, '%d-%m-%Y') AS proposed_settlement
            "))
                    ->leftJoin('brokers', 'deals.broker_id', '=', 'brokers.id')
                    ->leftJoin('contact_searches as cs', 'deals.contact_id', '=', 'cs.id')
                    ->leftJoin('lenders', 'deals.lender_id', '=', 'lenders.id')
                    ->leftJoin('deal_statuses as st', 'deals.status', '=', 'st.id')
                    ->leftJoin('products as p', 'deals.product_id', '=', 'p.id')

                    // Apply broker-based filtering only for non-admin users
                    ->when($user->role != 'admin', function ($query) use ($user) {
                        $query->join('user_brokers', 'deals.broker_id', '=', 'user_brokers.broker_id')
                            ->where('user_brokers.user_id', $user->id);
                    })

                    ->when($search != "", function ($q) use ($search) {
                        $split = explode(' ', $search);
                        if (is_countable($split) && count($split) > 0) {
                            foreach ($split as $word) {
                                $q->orWhere("deals.id", "LIKE", $word . "%")
                                    ->orWhere("cs.preferred_name", "LIKE", $word . "%")
                                    ->orWhere("cs.surname", "LIKE", $word . "%")
                                    ->orWhere("cs.trading", "LIKE", $word . "%")
                                    ->orWhere("brokers.surname", "LIKE", $word . "%")
                                    ->orWhere("brokers.trading", "LIKE", $word . "%")
                                    ->orWhere("lenders.code", "LIKE", $word . "%")
                                    ->orWhere("deals.actual_loan", "LIKE", $word . "%")
                                    ->orWhere('p.name', "LIKE", $word . "%");
                            }
                        }
                    });

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('id', function ($row) {
                        return $row->id;
                    })
                    ->addColumn('action', function ($row) {
                        $html = ' &nbsp;' .
                            '<div class="dropdown">' .
                            '          <button id="dLabel" type="button" data-toggle="dropdown"' .
                            '                  aria-haspopup="true" aria-expanded="false" class="btn btn-primary"><i class="pe-7s-helm"></i>  <span class="caret"></span>' .
                            '          </button>' .
                            '          <ul class="dropdown-menu broker_menu " role="menu" ' .
                            'aria-labelledby="dLabel">' .
                            '<li class="nav-item"><a href="' . route('admin.deals.edit', encrypt($row->id)) . '" class="edit "><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i> Edit</a></li>' .
                            '            <li class="nav-item"><a  href="' . route('admin.deals.view', encrypt($row->id))
                            . '"><i class="fa fa-eye"></i> View</a></li>' .
                            '          </ul>' .
                            '        </div>';

                        return $html;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('deal_id'))) {
                            $instance->where('deals.id', $request->get('deal_id'));
                        }
                        if (!empty($request->get('surname'))) {
                            $instance->where(function ($w) use ($request) {
                                $w->where('cs.surname', 'LIKE', $request->get('surname') . '%')
                                    ->orWhere('brokers.surname', 'LIKE', $request->get('surname') . '%');
                            });
                        }
                        if (!empty($request->get('preferred_name'))) {
                            $instance->where(function ($w) use ($request) {
                                $w->where('cs.preferred_name', 'LIKE', $request->get('preferred_name') . '%');
                            });
                        }
                        if (!empty($request->get('trading'))) {
                            $instance->where('brokers.trading', 'LIKE', $request->get('trading') . '%')
                                ->orWhere('cs.trading', 'LIKE', $request->get('trading') . '%');
                        }
                        if (!empty($request->get('lender_id'))) {
                            $instance->where('lender_id', $request->get('lender_id'));
                        }
                        if (!empty($request->get('product_id'))) {
                            $instance->where('product_id', $request->get('product_id'));
                        }
                        if (!empty($request->get('loan_op')) && !empty($request->get('loan_amt'))) {
                            $op = $request->get('loan_op');
                            if ($op == "eq") $op = '=';
                            if ($op == "gt") $op = '>';
                            if ($op == "gte") $op = '>=';
                            if ($op == "lt") $op = '<';
                            if ($op == "lte") $op = '<=';

                            $instance->where('actual_loan', $op, $request->get('loan_amt'));
                        }

                        if (!empty($request->get('status'))) {
                            $instance->where('deals.status', '=', $request->get('status'));
                        }
                    })
                    ->rawColumns(['action', 'id'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }



    public function commissions(Request $request)
    {
        $data = [];

        return view('admin.deal.commissions', $data);
    }

    public function commissionPost(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:csv,txt'

        ], [], [
            'file' => 'File'
        ]);

        try {

            $extension = $request->file('file')->getClientOriginalExtension(); //
            $mimeType = $request->file('file')->getClientMimeType(); //

            $csv_mimetypes = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt',
            );

            if (!in_array($mimeType, $csv_mimetypes)) {
                return response()->json(['error' => "Invalid file!"]);
            }

            $header = null;
            $data = array();
            $delimiter = ',';
            $this->comm_types = CommissionType::all()->pluck('id', 'name')->toArray();

            if (($handle = fopen($request->file('file')->getRealPath(), 'r')) !== false) {

                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {


                    if (!$header) {
                        $row[] = 'deal_id';
                        // $row[] = 'broker_id';
                        // $row[] = 'broker_staff_id';
                        // $row[] = 'contact_id';
                        // $row[] = 'product_id';
                        $header = $row;
                        $header = array_map('trim', $header);
                        $header = array_map('cleanString', $header);
                    } else {

                        $row = array_map('trim', $row);
                        $row[] = 0;
                        // $row[] = 0;
                        // $row[] = 0;
                        // $row[] = 0;
                        // $row[] = 0;
                        $tempRw = array_combine($header, $row);
                        if ($tempRw['account_number'] != '') {
                            $tempRw['account_number'] = trim(str_replace('#', '', $tempRw['account_number']));
                        }

                        if ($tempRw['loan_amount'] != '') {
                            $tempRw['loan_amount'] = trim(number_format(str_replace(['$', ','], '', $tempRw['loan_amount']), 2, '.', ''));
                        }
                        if ($tempRw['rate'] != '') {
                            $tempRw['rate'] = trim(number_format(str_replace(['$', '%', ','], '', $tempRw['rate']), 2, '.', ''));
                        }
                        if ($tempRw['commission'] != '') {
                            $tempRw['commission'] = trim(number_format(str_replace(['$', ','], '', $tempRw['commission']), 2, '.', ''));
                        }
                        if ($tempRw['gst'] != '') {
                            $tempRw['gst'] = trim(number_format(str_replace(['$', ','], '', $tempRw['gst']), 2, '.', ''));
                        }
                        if ($tempRw['total_paid'] != '') {
                            $tempRw['total_paid'] = trim(number_format(str_replace(['$', ','], '', $tempRw['total_paid']), 2, '.', ''));
                        }

                        if ($tempRw['period'] != '') {
                            $tempRw['period'] = date('Y-m', strtotime($tempRw['period']));
                        }
                        if ($tempRw['settlement_date'] != '') {
                            $tempRw['settlement_date'] = date('Y-m-d', strtotime($tempRw['settlement_date']));
                        }
                        if ($tempRw['commission_type'] != '') {
                            $type = explode(' ', $tempRw['commission_type']);
                            $comm_type = CommissionType::where('name', 'like', $type[0] . '%')->pluck('name');
                            if (isset($comm_type[0])) {
                                $tempRw['commission_type'] = $this->comm_types[$comm_type[0]];
                            } else {
                                $tempRw['commission_type'] = 1;
                            }
                        } else {
                            $tempRw['commission_type'] = 1;
                        }

                        $data[] = $tempRw;
                    }
                }
                fclose($handle);
            }

            if (!empty($data)) {

                //$message = 'Record uploaded successfully!';
                $message = '1';


                $account_numbers = Arr::pluck($data, 'account_number');

                $deals = Deal::whereIn('loan_ref', array_unique($account_numbers))->get()->keyBy('loan_ref')->toArray();
                $dealMissing = DealMissing::whereIn('loan_ref', array_unique($account_numbers))->get()->keyBy('loan_ref')->toArray();


                $sql = array();

                $upfront = 0;
                $actual_trail = 0;
                $actual_upfront = 0;
                $trail = 0;

                foreach ($data as $key => $row) {
                    // print_R($row);

                    /*if($row['account_number'] == '')
                    {
                       // print_R($row);
                        //$message = 'Some missing ref. No found - Please visit missing import records page for more information';
                        $message ='2';
                    }*/

                    if ($deals &&  array_key_exists($row['account_number'], $deals)) {
                        $row['deal_id'] = $deals[$row['account_number']]['id'];
                        // $row['broker_id'] = isset($deals[$row['account_number']]['broker_id']) && $deals[$row['account_number']]['broker_id'] > 0 ? $deals[$row['account_number']]['broker_id'] : 0 ;
                        // $row['broker_staff_id'] = isset($deals[$row['account_number']]['broker_staff_id']) &&
                        //     $deals[$row['account_number']]['broker_staff_id'] > 0 ?$deals[$row['account_number']]['broker_staff_id'] : 0;
                        // $row['contact_id'] = isset($deals[$row['account_number']]['contact_id']) && $deals[$row['account_number']]['contact_id'] > 0 ? $deals[$row['account_number']]['contact_id'] : 0;
                        // $row['product_id'] = isset($deals[$row['account_number']]['product_id']) && $deals[$row['account_number']]['product_id'] > 0 ? $deals[$row['account_number']]['product_id'] : 0;
                        $upfront =  $deals[$row['account_number']]['broker_split_agg_brk_sp_upfrt'];
                        $trail =  $deals[$row['account_number']]['broker_split_agg_brk_sp_trail'];

                        $actual_trail = $deals[$row['account_number']]['broker_est_trail'];
                        $actual_upfront = $deals[$row['account_number']]['broker_est_upfront'];
                    } else {

                        $missing_data =
                            [
                                'loan_ref' => $row['account_number'],
                                'funder' => $row['funder'],
                                'client' => $row['client'],
                                'created_by' => Auth::user()->id,
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString(),
                            ];

                        if ($dealMissing &&  array_key_exists($row['account_number'], $dealMissing)) {
                        } else {
                            if (!empty($row['account_number'])) {
                                DealMissing::insert($missing_data);
                            }
                        }


                        $message = '2';
                    }
                    unset($row['account_number']);
                    unset($row['line_of_business']);
                    unset($row['loan_writer']);
                    unset($row['loan_amount']);
                    unset($row['client']);
                    $funder = DB::table('lenders')->where('code', $row['funder'])
                        ->first();

                    $totalPaid = $row['total_paid'];
                    $brokerAmount = 0;
                    $masterAmount = 0;
                    $variance = 0;

                    if ($row['commission_type'] == 12) {
                        $brokerAmount = ($trail * $row['total_paid']) / 100;
                        $masterAmount = $totalPaid - $brokerAmount;
                        if ($actual_trail != 0) $variance = round(($totalPaid / $actual_trail) * 100, 2);
                    } else if ($row['commission_type'] == 13) {
                        $brokerAmount = ($upfront * $row['total_paid']) / 100;
                        $masterAmount = $totalPaid - $brokerAmount;
                        if ($actual_upfront != 0) $variance = round(($totalPaid / $actual_upfront) * 100, 2);
                    }
                    $com_data[] =
                        [
                            'deal_id' => $row['deal_id'],
                            'order' => 1,
                            'type' => isset($row['commission_type']) && $row['commission_type'] > 0 ?
                                $row['commission_type'] : 0,
                            'total_amount' =>  $totalPaid,
                            'agg_amount' => $masterAmount,
                            'type_data' => 1,
                            'broker_amount' => $brokerAmount,
                            'variance' => $variance,
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now('utc')->toDateTimeString(),
                            'updated_at' => Carbon::now('utc')->toDateTimeString(),
                        ];



                    if ($funder) {
                        $row['funder'] = $funder->id;
                    }
                    $sql[] = '("' . implode('","', $row) . '")';
                }
                $chunckedArray = array_chunk($sql, 50);
                $query = [];
                if (count($chunckedArray) > 0) {
                    if (($key = array_search('account_number', $header)) !== false) {
                        unset($header[$key]);
                    }
                    if (($key = array_search('line_of_business', $header)) !== false) {
                        unset($header[$key]);
                    }
                    if (($key = array_search('loan_writer', $header)) !== false) {
                        unset($header[$key]);
                    }
                    if (($key = array_search('loan_amount', $header)) !== false) {
                        unset($header[$key]);
                    }
                    if (($key = array_search('client', $header)) !== false) {
                        unset($header[$key]);
                    }

                    foreach ($chunckedArray as $chkAr) {

                        $query[] = "  INSERT INTO commissions_data  (`" . implode('`,`', $header) . "`) VALUES " . implode(",", $chkAr);
                    }
                }
                if (count($com_data) > 0) {
                    DealCommission::insert($com_data);
                }

                if (!empty($query)) {
                    $pdo = DB::connection()->getPdo();

                    foreach ($query as $q) {
                        $sth = $pdo->prepare($q);
                        $sth->execute();
                    }
                }
                return response()->json(['success' => $message]);
            } else {
                return response()->json(['error' => "File is empty!"]);
            }

            return response()->json(['success' => 'Record uploaded successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getCommissions(Request $request)
    {
        try {
            $input =  $request->all();

            if ($request->ajax()) {
                $data = CommissionData::select(DB::raw("commissions_data.*, date_format(commissions_data.updated_at,'%d-%m-%Y')
                date_sattled, date_format(commissions_data.settlement_date,'%d-%m-%Y')
                settlement_date,commission_types.name as commission_type_name, lenders.name as bank"))->leftJoin('commission_types', 'commission_types.id', '=', 'commissions_data.commission_type')->leftJoin('lenders', 'lenders.id', 'commissions_data.funder');

                return DataTables::of($data)
                    ->addIndexColumn()->editColumn('commission_type', function ($row) {
                        return $row->commission_type_name;
                    })
                    ->make(true);
            }

            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getSgDCommissions(Request $request)
    {
        try {

            $input =  $request->all();

            if ($request->ajax()) {

                $deal = Deal::find(decrypt($request->deal_id));
                if (!$deal) {
                    return response()->json(['error' => 'Invalid Deal!']);
                }
                // $data = CommissionData::select(DB::raw("commissions_data.id, date_format(commissions_data.settlement_date,'%Y-%m-%d') as settlement_date,commission_types.name as commission_type_name,commissions_data.period,commissions_data.commission,commissions_data.gst,commissions_data.total_paid,commissions_data.payment_no") )->leftJoin('commission_types','commission_types.id','=','commissions_data.commission_type')->where('deal_id',$deal->id);

                $data = DealCommission::select(DB::raw("deal_commissions.id, date_format(deal_commissions.date_statement,'%d-%m-%Y') as date_statement,commission_types.name as commission_type_name,deal_commissions.agg_amount,deal_commissions.broker_amount,deal_commissions.total_amount, deal_commissions.referror_amount"))
                    ->leftJoin('commission_types', 'commission_types.id', '=', 'deal_commissions.type')
                    ->where('type', '!=', 13)
                    ->where('deal_id', $deal->id)
                    ->orderBy('deal_commissions.date_statement', 'desc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn("action", function ($row) {
                        return '<a href="javascript:void(0)" data-url="' . route('admin.deals.getcomdataedit', encrypt($row->id)) . '" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary editTrail"                                title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"
                                style="color:#fff"></i></a>
                        ';
                    })
                    ->filterColumn('account_no', function ($query, $keyword) {
                        $sql = "deal_commissions.deal_id  like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })->editColumn('commission_type', function ($row) {
                        return $row->commission_type_name;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }


            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getSgDCommissionsEdit(Request $request, $id)
    {
        try {
            $data = DealCommission::select(DB::raw("deal_commissions.id, date_format(deal_commissions.date_statement,'%d-%m-%Y') as date_statement,commission_types.name as commission_type_name,commission_types.id as commission_type_id,deal_commissions.agg_amount,deal_commissions.broker_amount,deal_commissions.total_amount, deal_commissions.referror_amount,deal_commissions.bro_amt_date_paid,deal_commissions.ref_amt_date_paid"))
                ->leftJoin('commission_types', 'commission_types.id', '=', 'deal_commissions.type')
                ->where('type', '!=', 13)
                ->where('deal_commissions.id', decrypt($id))
                ->first();
            return response()->json(['success' => 'Data Found', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateSgDCommissionsEdit(Request $request)
    {
        $DealCommission = DealCommission::find($request->edit_id);
        if (empty($DealCommission)) {
            return response()->json(['error' => "Record not found"]);
        }
        $DealCommission->update([
            "type" => $request->commission_type,
            "total_amount" => $request->total_amount,
            "date_statement" => $request->date_statement,
            "agg_amount" => $request->agg_amount,
            "broker_amount" => $request->broker_amount,
            "referror_amount" => $request->referror_amount,
            "bro_amt_date_paid" => $request->bro_amt_date_paid_1,
            "ref_amt_date_paid" => $request->ref_amt_date_paid_1,
        ]);
        return response()->json(['success' => "Record Updated Successfully"]);
    }

    public function addActual(Request $request, $id)
    {
        $validated = $request->validate([
            'commission_type_1' => 'required',
            'total_amount_1' => 'required'

        ], [], [
            'commission_type_1' => 'Commission Type',
            'total_amount_1' => 'Total Amount'
        ]);
        $actual_trail = 0;
        $actual_upfront = 0;
        $variance = 0;
        $id = decrypt($id);
        $deal = Deal::find($id);

        if (!$deal) {
            return response()->json(['error' => 'Invalid Deal!']);
        }
        $actual_trail = $deal->broker_est_trail;
        $actual_upfront = $deal->broker_est_upfront;
        $totalPaid = $request->total_amount_1;

        if ($actual_trail != 0) $variance = round(($totalPaid / $actual_trail) * 100, 2);
        if ($actual_upfront != 0) $variance = round(($totalPaid / $actual_upfront) * 100, 2);

        $data = [
            'deal_id' => $deal->id,
            'order' => 1,
            'type' => isset($request->commission_type_1) && $request->commission_type_1 > 0 ?
                $request->commission_type_1 : 0,
            'total_amount' => $request->total_amount_1,
            'date_statement' => $request->date_statement_1 != '' ? date(
                'Y-m-d',
                strtotime($request->date_statement_1)
            ) : NULL,
            'agg_amount' => $request->agg_amount_1,
            'broker_amount' => $request->broker_amount_1,
            'broker_staff_amount' => $request->broker_staff_amount_1,
            'bro_staff_amt_date_paid' => $request->bro_staff_amt_date_paid_1,
            'bro_amt_date_paid' => $request->bro_amt_date_paid_1 != '' ? date('Y-m-d', strtotime($request->bro_amt_date_paid_1)) : NULL,
            'referror_amount' => $request->referror_amount_1,
            'ref_amt_date_paid' => $request->ref_amt_date_paid_1 != '' ?  date('Y-m-d', strtotime($request->ref_amt_date_paid_1)) : NULL,
            'variance' => $variance,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now('utc')->toDateTimeString(),
            'updated_at' => Carbon::now('utc')->toDateTimeString(),
        ];

        DealCommission::create($data);

        response()->json(['success' => 'Actual Added!']);
    }

    public function getDealCommission(Request $request)
    {


        $dealCommissions = DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"' . $this->mysql_date_format . '") as date_statement,DATE_FORMAT(bro_amt_date_paid,"' . $this->mysql_date_format . '") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"' . $this->mysql_date_format . '") as ref_amt_date_paid'))->join('commission_types as ct', 'ct.id', '=', 'deal_commissions.type')->where('deal_commissions.id', $request->dealId)->first();

        return $dealCommissions;
    }

    public function updateDealCommission(Request $request)
    {

        $update = DealCommission::where('id', $request->id)->update([

            'deal_id' => $request->deal_id,
            'order' => 1,
            'type' => isset($request->commission_type) && $request->commission_type > 0 ?
                $request->commission_type : 0,
            'total_amount' => $request->total_amount,
            'date_statement' => $request->date_statement != '' ? date(
                'Y-m-d',
                strtotime($request->date_statement)
            ) : NULL,
            'agg_amount' => $request->agg_amount,
            'broker_amount' => $request->broker_amount,
            'broker_staff_amount' => $request->broker_staff_amount,
            'bro_staff_amt_date_paid' => $request->bro_staff_amt_date_paid != '' ? date('Y-m-d', strtotime($request->bro_staff_amt_date_paid)) : NULL,
            'bro_amt_date_paid' => $request->bro_amt_date_paid != '' ? date('Y-m-d', strtotime($request->bro_amt_date_paid)) : NULL,
            'referror_amount' => $request->referror_amount,
            'ref_amt_date_paid' => $request->ref_amt_date_paid != '' ?  date('Y-m-d', strtotime($request->ref_amt_date_paid)) : NULL,

        ]);

        if ($update) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getBrokerComRecords(Request $request)
    {
        $records = BrokerCommissionModel::where(['broker_id' => $request->broker_id, 'commission_model_id' => $request->commission_model])->first();
        return $records;
    }

    public function updateProgress($id)
    {
        $deal = DealMissing::find($id);
        $next_status = $deal->status;
        $deal->status = $next_status + 1;
        $deal->status_date = now();
        $deal->save();
        return redirect()->back();
    }
    public function dealMissingList()
    {
        $data = [];
        $data['missing'] = DealMissing::whereNull('deleted_at')->get();
        //print_R($data);die;
        return view('admin.deal.deal_missing_list', $data);
    }

    public function getdealMissingRecords(Request $request)
    {
        try {
            $input =  $request->all();

            $datatableData = arrangeArrayPair(obj2Arr($input), 'name', 'value');

            $start = 0;
            $rowperpage = 10;
            if (isset($datatableData['iDisplayStart']) && $datatableData['iDisplayLength'] != '-1') {
                $start =  intval($datatableData['iDisplayStart']);
                $rowperpage = intval($datatableData['iDisplayLength']);
            }

            // Ordering
            $sortableColumnsName = [
                'id',
                'loan_ref',
                'created_at',
                'updated_at',
                '',
            ];
            $columnName              = "created_at";
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
                    $columnName              = "created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords = DealMissing::select('count(*) as allcount')->count();
            $filterSql = DealMissing::select('count(*) as allcount');
            if ($searchValue != '')
                $sql = $filterSql->where('loan_ref', 'like', '%' . $searchValue . '%');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql = DealMissing::select(DB::raw('id,loan_ref,funder,client,status, DATE_FORMAT(created_at,"%Y-%m-%d %H:%i:%s") as formated_created_at, DATE_FORMAT(updated_at,"%Y-%m-%d %H:%i:%s") as formated_updated_at'))->orderBy(
                $columnName,
                $columnSortOrder
            )->with(['deal_status'])
                ->skip($start)
                ->take($rowperpage);
            if ($searchValue != '')
                $sql = $sql->where('loan_ref', 'like', '%' . $searchValue . '%');

            $records = $sql->get();

            $result = $records->map(function ($record) {
                if (!$record->funder) {
                    $record->funder = "N/A";
                }
                if (!$record->client) {
                    $record->client = "N/A";
                }
                if ($record->status == DealsWithMissingRefsStatus::$COMPLETED) {

                    $record_status_date = DealMissing::find($record->id)->status_date;
                    $is_return = false;

                    if ($record_status_date) {
                        $date_now = new DateTime("now");
                        $staus_completed_date = new DateTime(date("Y-m-d", strtotime($record_status_date)));
                        if ($date_now->diff($staus_completed_date)->i < 10) {

                            $is_return = true;
                        }
                    } else {

                        $is_return = true;
                    }
                    if ($is_return) {
                        $record->formated_updated_at = '<a href="#" class="btn btn-sm ' . $record->deal_status->color . '">' . $record->deal_status->name . '</a>';
                        return $record;
                    }
                } else {
                    $route = route("admin.deals.dealMissingList.progress", $record->id);
                    $record->formated_updated_at = '<a href="' . $route . '" class="btn btn-sm ' . $record->deal_status->color . '">' . $record->deal_status->name . '</a>';
                    return $record;
                }
            });
            $filteredCollection = $result->filter(function ($value) {
                return !is_null($value);
            })->flatten();


            $response = array(
                "sEcho" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                "draw" => intval((isset($datatableData['sEcho']) ? $datatableData['sEcho'] : 0)),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $filteredCollection
            );

            return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function getdealMissingRecords1(Request $request)
    {
        try {
            $input =  $request->all();

            if ($request->ajax()) {
                /*$data = Deal::select(DB::raw("deals.*,brokers.trading,preferred_name,lenders.code as lendername,CONCAT(cs.preferred_name,' ',cs.surname) AS preferred_name, CONCAT(LPAD(st.status_code,2,0),':',st.name) status, p.name productname, cs.email, date_format(deals.proposed_settlement,'%d-%m-%Y') proposed_settlement") )
                    ->leftJoin('brokers','deals.broker_id','=','brokers.id')
                    ->leftJoin('contact_searches as cs','deals.contact_id','=','cs.id')
                    ->leftJoin('lenders','deals.lender_id','=','lenders.id')
                    ->leftJoin('deal_statuses as st','deals.status','=','st.id')
                    ->leftJoin('products as p','deals.product_id','=','p.id')
                    ;*/
                $data = DealMissing::whereNull('deleted_at');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('id', function ($row) {


                        return $row->id;
                    })
                    ->addColumn('action', function ($row) {
                        $html = ' &nbsp;' .
                            '<div class="dropdown">' .
                            '          <button id="dLabel" type="button" data-toggle="dropdown"' .
                            '                  aria-haspopup="true" aria-expanded="false" class="btn btn-primary"><i class="pe-7s-helm"></i>  <span class="caret"></span>' .
                            '          </button>' .
                            '          <ul class="dropdown-menu broker_menu " role="menu" ' .
                            'aria-labelledby="dLabel">' .
                            '<li class="nav-item"><a href="' . route('admin.deals.edit', encrypt($row->id)) . '" class="edit "><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i> Edit</a></li>' .
                            '            <li class="nav-item"><a  href="' . route('admin.deals.view', encrypt($row->id))
                            . '"><i class="fa fa-eye"></i> View</a></li>' .

                            /* '            <li class="nav-item"><a  href="'.route('admin.dealtsk.list',encrypt($row->id)).'"><i class="fa fa-tasks"></i> Tasks</a></li>' .
                                '            <li class="nav-item"><a  href="javascript:void(0)" data-id="'.encrypt($row->id)
                                .'" onclick="return showCommissions(this)"><i class="fa fa-tasks"></i> Commissions</a></li>' .*/
                            '          </ul>' .
                            '        </div>';

                        return $html;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('type'))) {
                            $instance->where('cs.search_for', $request->get('type'));
                        }
                        if (!empty($request->get('deal_id'))) {
                            $instance->where('deals.id', $request->get('deal_id'));
                        }
                        if (!empty($request->get('surname'))) {
                            $instance->where(function ($w) use ($request) {
                                $w->where('cs.surname', $request->get('surname'))
                                    ->orWhere('brokers.surname', $request->get('surname'));
                            });
                        }
                        if (!empty($request->get('first_name'))) {

                            $instance->where(function ($w) use ($request) {
                                $w->where('cs.preferred_name', $request->get('first_name'))
                                    ->orWhere('brokers.trading', $request->get('first_name'));
                            });
                        }
                        if (!empty($request->get('trading'))) {
                            $instance->where('cs.trading', $request->get('trading'))
                                ->orWhere('brokers.trading', $request->get('trading'));
                        }
                        if (!empty($request->get('entity_name'))) {
                            $instance->where(function ($w) use ($request) {
                                $w->where('cs.entity_name', $request->get('entity_name'))
                                    ->orWhere('brokers.entity_name', $request->get('entity_name'));
                            });
                        }
                        if (!empty($request->get('lender_id'))) {
                            $instance->where('lender_id', $request->get('lender_id'));
                        }
                        if (!empty($request->get('product_id'))) {
                            $instance->where('product_id', $request->get('product_id'));
                        }
                        if (!empty($request->get('loan_op')) && !empty($request->get('loan_amt'))) {
                            $op = $request->get('loan_op');
                            if ($op == "eq") $op = '=';
                            if ($op == "gt") $op = '>';
                            if ($op == "gte") $op = '>=';
                            if ($op == "lt") $op = '<';
                            if ($op == "lte") $op = '<=';

                            $instance->where('actual_loan', $op, $request->get('loan_amt'));
                        }
                        if (!empty($request->get('status'))) {
                            $instance->where('status', '=', $request->get('status'));
                        }
                        if (!empty($request->get('from_date'))) {
                            $instance->where('proposed_settlement', '>=', $request->get('from_date'));
                        }
                        if (!empty($request->get('to_date'))) {
                            $instance->where('proposed_settlement', '<=', $request->get('to_date'));
                        }
                    })
                    ->rawColumns(['action', 'id'])
                    ->make(true);
            }

            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function addNote(Request $request, $deal_id)
    {
        $deal = decrypt($deal_id);
        $deal = Deal::find($deal);
        if (!$deal) {
            return response()->json(['error' => "Deal does not exist"]);
        }
        $note = $request->note;
        if (empty($note)) {
            return response()->json(['error' => "Note field is required"]);
        }
        $data = [
            "note" => $note,
        ];
        if ($request->edit_id) {
            $note = DealNote::where("id", $request->edit_id)->update($data);
            return response()->json(['success' => "note updated"]);
        } else {
            $deal->withNotes()->create($data);
            return response()->json(['success' => "note added"]);
        }
    }
    function editNote(Request $request, $note_id)
    {
        $note = decrypt($note_id);
        $note = DealNote::find($note);
        if (!$note) {
            return response()->json(['error' => "Note does not exist"]);
        }
        $html = view("admin.deal.edit_note", compact("note"))->render();
        return response()->json(["html" => $html]);
    }
    function deleteNote(Request $request, $note_id)
    {
        $note = decrypt($note_id);
        DealNote::where("id", $note)->delete();
        return response()->json(['success' => "note deleted"]);
    }
    function addRelation(Request $request, $deal_id)
    {
        $deal = decrypt($deal_id);
        $deal = Deal::find($deal);
        if (!$deal) {
            return response()->json(['error' => "Deal does not exist"]);
        }
        if ($request->method() == "GET") {
            // $address = DealClient::where("deal_id", $deal->id)->first();
            // $postalAddress = ContactAddress::where("contact_id", $contact_id)->where("address_type", 2)->orderBy("id", "DESC")->first();
            $clients = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->whereNull('deleted_at')->get();
            $relations = Relationship::get();
            $html = view("admin.deal.add_edit_relationship", compact('clients', 'relations', 'deal'))->render();
            return response()->json(['status' => 'success', "html" => $html]);
        }
        $validator = Validator::make($request->all(), [
            "client_id" => "required|exists:contact_searches,id",
            "type" => "required|exists:relationship,id",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $data = $validator->validated();
        $exists = DealClient::where("client_id", $data['client_id'])->where("type", $data['type'])->where("deal_id", $deal->id)->exists();
        if ($exists) {
            return response()->json(['error' => "Relationship exist"]);
        }
        $deal->withClientRel()->create($data);
        return response()->json(['success' => "Relationship added successfully"]);
    }
    function editRelation(Request $request, $relationship)
    {
        $relationship = decrypt($relationship);
        $relationship = DealClient::find($relationship);
        if (!$relationship) {
            return response()->json(['error' => "Relationship does not exist"]);
        }
        if ($request->method() == "GET") {
            $clients = ContactSearch::select(
                DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->whereNull('deleted_at')->get();

            $relations = Relationship::get();
            $html = view("admin.deal.add_edit_relationship", compact('clients', 'relations', 'relationship'))->render();
            return response()->json(['status' => 'success', "html" => $html]);
        }
        $validator = Validator::make($request->all(), [
            "client_id" => "required|exists:contact_searches,id",
            "type" => "required|exists:relationship,id",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $data = $validator->validated();
        $exists = DealClient::where("client_id", $data['client_id'])->where("type", $data['type'])->where("deal_id", $relationship?->withDeal?->id)->where("id", "!=", $relationship->id)->exists();
        if ($exists) {
            return response()->json(['error' => "Relationship exist"]);
        }
        $relationship->update($data);
        return response()->json(['success' => "Relationship added successfully"]);
    }
}
