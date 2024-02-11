<html lang="en">
<head>
    <title>Deal Tasks</title>

</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">
<style>
    table {
        border: 0;
        border-collapse: separate;
        border-spacing: 0 5px;
    }

    .thead_style tr th {
        border-bottom: 1px solid grey;
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-weight: 800;
        font-size: 12px;
    }
    .subtotal tr th {
        border-top: 1px solid grey;
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-size: 12px;
    }.grand_total tr th {
         border-top: 2px solid grey;
         font-family: system-ui, system-ui, sans-serif;
         border-collapse: separate;
         border-spacing: 5px 5px;
         text-align: left;
         font-size: 14px;
     }
    .body_class tr td {
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-size: 12px;
    }
    .body_class tbody{
        border-collapse: separate;
        border-spacing: 5px 5px;
        border-bottom: 1px solid;
    }
</style>
<table style="width: 1024px;margin-left: auto;margin-right: auto">
    <tbody>
    <tr>
        <td>
            <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
                <tbody>
                <tr>
                    <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Deal Tasks</span></td>
                    <td> <span style="width: 60%;font-size:13px;font-weight: bold;">For period from {{ $date_from.' to '.$date_to }}</span></td>
                </tr>
                </tbody>
            </table>
            <?php
            $total=0;
            ?>
            @if(count($processors)>0)
                <table style="width: 100%;margin-top: 5px" >
                    <thead class="thead_style">
                    <tr>
                        <th style="width: 3%">Followup</th>
                        <th style="width: 15%">Client</th>
                        <th style="width: 10%">Deal Id</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 15%">Details</th>
                    </tr>
                    </thead>
                        @foreach($processors as $processor)
                            <tbody class="body_class">
                                <tr>
                                    <td style="font-size: 12px;padding-left: 5px;font-family: system-ui, system-ui, sans-serif;font-weight: bold">Processor</td>
                                    <td style="font-size: 15px;padding-left: 5px;font-family: system-ui, system-ui, sans-serif;font-weight: bold">{{$processor->name}}</td></tr>
                                    <?php
                                    $total+=count($processor->deal_tasks);

                                    ?>
                                    @foreach($processor->deal_tasks as $deal_task)
                                        <?php
                                        $deals_list=\App\Models\Deal::select('deals.*')->with('deal_status') ->where('id',$deal_task->deal_id)->first();

                                        ?>
                                        <style>tr.border_bottom td {
                                                border-bottom: 1px solid black;
                                            }</style>
                                        <tr class="border_bottom">
                                            <td>{{ date("d/m/Y",strtotime($deal_task->followup_date)) }}</td>
                                            <td>{{ $deal_task->deal->client->surname.','.$deal_task->deal->client->preferred_name }}</td>
                                            <td>{{ $deal_task->deal_id }}</td>
                                            <td>{{ $deal_task->status.' '.$deals_list->deal_status->name??'' }}</td>
                                            <td>{{ $deal_task->details }}</td>
                                        </tr>
                                    @endforeach
                                    @if(count($processor->deal_tasks)>0)
                                        <tr>
                                            <th style="font-size: 12px;font-family: system-ui, system-ui, sans-serif;font-weight: bold" colspan="1"><?php echo 'Total' ?></th>
                                            <th style="font-size: 12px;font-family: system-ui, system-ui, sans-serif;font-weight: bold;float: left">{{ count($processor->deal_tasks)}}</th>
                                        </tr>
                                    @endif
                            </tbody>
                    @endforeach
                </table>

            @else
                <table style="width: 100%;margin-top: 5px" >
                    <thead class="thead_style">
                    <tr>
                        <th style="width: 3%">Followup</th>
                        <th style="width: 15%">Client</th>
                        <th style="width: 10%">Deal Id</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 15%">Details</th>
                    </tr>
                    </thead>
                    <tbody class="body_class">
                    </tbody>
                </table>
            @endif
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
