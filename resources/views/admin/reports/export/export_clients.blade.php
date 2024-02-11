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
</style>
        <table style="width: 1024px;margin-left: auto;margin-right: auto">
            <tbody>
            <tr>
                <td>
                    <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
                        <tbody>
                        <tr>
                            <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">FMA Clients List</span></td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="width: 100%;margin-top: 20px" >
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
                                <th>State</th>
                                <th>Postal Code</th>
                            </tr>
                            </thead>
                            <tbody class="body_class">
                            @foreach($clients as $client)
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
                                    <td>{{ $client->mail_state_table->name??'' }}</td>
                                    <td>{{ $client->mail_postal_code }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
