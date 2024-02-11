<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReconciliationReportsController extends Controller
{
    //
    public function index(){
        return view('admin.reports.brokers.index',['Commish| Broker Report']);
    }
    public function referrerCommissionSummary(){

    }
}
