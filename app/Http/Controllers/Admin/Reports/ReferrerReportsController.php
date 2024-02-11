<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferrerReportsController extends Controller
{
    //
    public function index(){
        return view('admin.reports.brokers.index',['Commish| Referrer Report']);
    }
    public function referrerCommissionSummary(){

    }
}
