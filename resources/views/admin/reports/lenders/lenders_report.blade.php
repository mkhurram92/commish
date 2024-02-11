<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Deals Settled</title>
    <style>
        thead{display: table-header-group;}
        tfoot {display: table-row-group;}
        tr {page-break-inside: avoid;}
        th {
            text-align: left !important;
        }
        table {
            border-collapse: collapse;
        }

        .border-tr {
            border-bottom: 0.01em solid #666666;
        }
        thead{display: table-header-group;}
        tfoot {display: table-row-group;}
        tr {page-break-inside: avoid;}
    </style>
</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">

<table style="margin-top: 5px;margin-bottom:5px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Lenders </span></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px" >
    <thead class="thead_style">
    <tr>
        <th width="15%">Id</th>
        <th width="85%">Name</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @foreach($lenders as $k=>$lender)
        <tr class="border-tr">
            <td width="15%">{{ $lender->id }}</td>
            <td width="85%">{{ $lender->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
