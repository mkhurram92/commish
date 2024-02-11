<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Broker List</title>
    <style>
        thead{display: table-header-group;}
        tfoot {display: table-row-group;}
        tr {page-break-inside: avoid;}
    </style>
</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">

<table style="margin-top: 5px;margin-bottom:10px;width: 100%">
    <tbody>
        <tr>
            <td style="width: 100%; text-align: center;"> <span style="font-size: 24px;font-weight: bold;">Broker List</span></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 10px; text-align: left;" >
    <thead class="thead_style">
    <tr>
        <!--<th>Id</th>-->
        <th style="width: 30%">Name</th>
        <th style="width: 20%">Work Phone</th>
        <!--<th style="width: 10%">Home Phone</th>-->
        <th style="width: 20%">Mobile</th>
        <!--<th>Fax</th>-->
        <th style="width: 20%">Email</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @foreach($brokers as $k=>$broker)
        <tr>
            <!--<td>{{ $broker->id }}</td> -->
            <td>{{ $broker->entity_name }}</td>
            <td>{{ $broker->work_phone }}</td>
            <!--<td>{{ $broker->home_phone }}</td>-->
            <td>{{ $broker->mobile_phone }}</td>
            <!--<td>{{ $broker->fax }}</td> -->
            <td>{{ $broker->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
