<html lang="en">
<head>
    <title>Deals Settled</title>

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
    thead{display: table-header-group;}
    tfoot {display: table-row-group;}
    tr {page-break-inside: avoid;}
</style>

            <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
                <tbody>
                <tr>
                    <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Birthday List</span></td>
                    <td> <span style="width: 50%;font-weight: bold;">For period from {{ date('m/d/Y',strtotime($date_from)).' to '.date('m/d/Y',strtotime($date_to)) }}</span></td>
                </tr>
                </tbody>
            </table>
            <table style="width: 100%;margin-top: 5px" >
                <thead class="thead_style">
                <tr>
                    <th>Day</th>
                    <th>Surname</th>
                    <th>Given name</th>
                    <th>Middle name</th>
                    <th>Work Phone</th>
                    <th>Home Phone</th>
                    <th>Mobile</th>
                    <th>BirthDate</th>
                    <th>Email</th>
                    <th>Mailing Address</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="body_class">
                <?php
                $day='';
                ?>
                @foreach($clients as $k=>$client)
                    <tr>
                        <td>
                            <?php
                            if($day=='' || $day!=date('d',strtotime($client->dob))){
                                $day=date('d',strtotime($client->dob));
                                echo $day;
                            }
                            ?>
                        </td>
                        <td>{{ $client->surname }}</td>
                        <td>{{ $client->preferred_name }}</td>
                        <td>{{ $client->middle_name }}</td>
                        <td>{{ $client->work_phone }}</td>
                        <td>{{ $client->home_phonee }}</td>
                        <td>{{ $client->mobile_phone }}</td>
                        <td>{{ date('d/m/Y',strtotime($client->dob)) }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->street_number.' '.$client->street_name??'' }}</td>
                        <td>{{ $client->mail_state_table->name??'' }}</td>
                        <td>{{ $client->mail_postal_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

</body>
</html>
