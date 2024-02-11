<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Deals Settled</title>
    <style>
        thead{display: table-header-group;}
        tfoot {display: table-row-group;}
        tr {page-break-inside: avoid;}
    </style>
</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">

            <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
                <tbody>
                <tr>
                    <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">FMA Clients List</span></td>
                </tr>
                </tbody>
            </table>
                <table style="width: 100%;margin-top: 5px" >
                    <thead class="thead_style">
                    <tr>
                        <th>Client</th>
                        <th>Entity Name</th>
                        <th>Surname</th>
                        <th>Given name</th>
                        <th>Middle name</th>
                        <th>Mail</th>
                        <th>Work Phone</th>
                        <th>Home Phone</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Mailing Address</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="body_class">
                    @foreach($clients as $k=>$client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->entity_name }}</td>
                            <td>{{ $client->surname }}</td>
                            <td>{{ $client->preferred_name }}</td>
                            <td>{{ $client->middle_name }}</td>
                            <td>{{ $client->general_mail_out==1?'Yes':'no' }}</td>
                            <td>{{ $client->work_phone }}</td>
                            <td>{{ $client->home_phonee }}</td>
                            <td>{{ $client->mobile_phone }}</td>
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
