@extends('layout.main')

@section('title')
    Referrer Commission Rating Summary
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Reports
            </h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex"><svg class="svg-icon"
                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page"> Reports
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('body')
    <div class="card">
        <div class="card-header">
            <div class="row w-100">
                <div class="col-md-6">
                    <div class="card-title">Referrer Commission Rating Summary</div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="card-title">Report Period: {{ date('d/m/Y', strtotime($date_from)) }} -
                        {{ date('d/m/Y', strtotime($date_to)) }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive datatble-filter">
                <table id="example4" class="table-hover card-table table-vcenter text-nowrap border-top-0 table p-0">
                    <thead>
                        <tr>
                            <th>Referrer</th>
                            <th>No. of Loans</th>
                            <th>Loan Amount</th>
                            <th>Gross Upfront</th>
                            <th>% Paid To Ref</th>
                            <th>Referrer Upfront</th>
                            <th>FMD Upfront</th>
                            <th>Average FMD Upfront</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalNumberOfLoans = 0;
                            $totalLoanAmount = 0;
                            $totalGrossUpfront = 0;
                            $totalReferrerUpfront = 0;
                            $totalFMDUpfront = 0;
                            $totalAverageFMDUpfront = 0;
                        @endphp
                        @forelse ($deals as $deal)
                            @if ($deal->NumberOfLoansUpfront != 0)
                                <tr class="table-subheader">
                                    <td>{{ $deal->Result }}</td>
                                    <td>{{ number_format($deal->NumberOfLoansUpfront, 0) }}</td>
                                    <td>${{ number_format($deal->SumOfLoansUpfront, 2) }}</td>
                                    <td>${{ number_format($deal->SumOfdea_UpfrontEst_ABP, 2) }}
                                    </td>
                                    <td>
                                        <?php
                                        $d = \App\Models\Deal::where('referror_split_referror', $deal->referror_split_referror)
                                            ->orderBy('id', 'desc')
                                            ->first();
                                        echo $d->referror_split_agg_brk_sp_upfrt . '%';
                                        $referror_upfront = ($d->referror_split_agg_brk_sp_upfrt / 100) * $deal->SumOfdea_UpfrontEst_ABP;
                                        ?>
                                    </td>

                                    <td>${{ number_format($referror_upfront, 2) }}</td>

                                    <td>${{ number_format($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront, 2) }}
                                    </td>
                                    <td><?php
                                    if ($deal->NumberOfLoansUpfront) {
                                        echo '$' . number_format(($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront) / $deal->NumberOfLoansUpfront, 2);
                                    } else {
                                        echo '0';
                                    }
                                    ?></td>
                                </tr>
                                <tr style="display:none">
                                    <td colspan="9">
                                        <div class="table-responsive datatble-filter">
                                            <table class="detail-transaction table">
                                                <thead>
                                                    <tr>
                                                        <th>Deal ID</th>
                                                        <th>Client</th>
                                                        <th>Lender</th>
                                                        <th>Broker</th>
                                                        <th>Product</th>
                                                        <th>Settlement date</th>
                                                        <th>Loan Amount</th>
                                                        <th>Upfront</th>
                                                        <th>Trail</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $dealInstance = new App\Models\Deal();
                                                        //$subDeals = $dealInstance->referrerDeals($date_from, $date_to, $deal->referror_split_referror);
                                                        $subDeals = $dealInstance->referrerDeals($date_from, $date_to, $deal->referror_split_referror, $broker_id);

                                                    @endphp
                                                    @if ($subDeals->isNotEmpty())
                                                        @forelse ($subDeals->where('status', 4)->sortBy('lender.code') as $subDeal)
                                                            <tr>
                                                                <td>{{ $subDeal->id }}</td>

                                                                @if ($subDeal->client?->individual == 1)
                                                                    <td>{{ $subDeal?->client?->surname ?? '' }},
                                                                        {{ $subDeal?->client?->preferred_name ?? '' }}</td>
                                                                @else
                                                                    <td>{{ $subDeal?->client?->trading ?? '' }}</td>
                                                                @endif

                                                                <td>{{ $subDeal?->lender?->code ?? 'N/A' }}</td>

                                                                @if ($subDeal?->broker?->is_indvidual == 1)
                                                                    <td>{{ $subDeal?->broker?->surname ?? '' }}
                                                                        {{ $subDeal?->broker?->given_name ?? '' }}</td>
                                                                @else
                                                                    <td>{{ $subDeal?->broker?->trading ?? 'N/A' }}</td>
                                                                @endif

                                                                <td>{{ $subDeal?->product?->name ?? 'N/A' }}</td>
                                                                <td>{{ date('d/m/Y', strtotime($subDeal->status_date ?? 'N/A')) }}
                                                                </td>
                                                                <td>${{ number_format($subDeal->broker_est_loan_amt ?? 0, 2) }}
                                                                </td>
                                                                <td>${{ $subDeal->broker_est_upfront ?? 'N/A' }}</td>
                                                                <td>${{ $subDeal->broker_est_trail ?? 'N/A' }}</td>

                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                    @else
                                                        <tr>
                                                            <td colspan="9">No Data</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    // Increment totals
                                    $totalNumberOfLoans += $deal->NumberOfLoansUpfront;
                                    $totalLoanAmount += $deal->SumOfLoansUpfront;
                                    $totalGrossUpfront += $deal->SumOfdea_UpfrontEst_ABP;
                                    $totalReferrerUpfront += $referror_upfront;
                                    $totalFMDUpfront += $deal->SumOfdea_UpfrontEst_ABP - $referror_upfront;
                                    $totalAverageFMDUpfront += $deal->NumberOfLoansUpfront ? ($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront) / $deal->NumberOfLoansUpfront : 0;
                                @endphp
                            @endif
                        @empty
                        @endforelse
                        <tr class="table-subheader" style="font-weight: bold;">
                            <td>Total</td>
                            <td>{{ number_format($totalNumberOfLoans, 0) }}</td>
                            <td>${{ number_format($totalLoanAmount, 2) }}</td>
                            <td>${{ number_format($totalGrossUpfront, 2) }}</td>
                            <td></td>
                            <td>${{ number_format($totalReferrerUpfront, 2) }}</td>
                            <td>${{ number_format($totalFMDUpfront, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('layout.datatable')
    <script>
        $("#example4").dataTable();
    </script>
@endsection
