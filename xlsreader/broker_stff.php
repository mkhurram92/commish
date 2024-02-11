<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
/*$cities = ['Marleston','Greenacres','Adelaide','Athelstone','Fulham Gardens','Glenelg','Campbelltown','Seacliff',
'Parafield','Gulfview Heights','Paradise','Parafield Gardens','Woodcroft','Glenelg','Thorngate','Hectorville','Torrens Park','Unley','Felixstow','Gulfview Heights','Findon','Christies Beach','Ottoway','Brunswick','Croydon','Salisbury East','Adelaide','Newton','Glenelg','Norwood','Beverley','Wayville','Kent town','Fulham Gardens','Adelaide','Glandore','Edwardstown','Greenacres','Adelaide','Vale Park','Albury','Salisbury','Norwood','Seacliff Park','Glenelg','Adelaide','Perkside','Adelaide','Adelaide Mail Centre','Adelaide','17A Edgeworth st','Adelaide','Hillbank','Longwood','Kensington Gardens','Magill North','Adelaide','Adelaide','Richmond','Blair Athol','Ottoway','Gawler','Stirling','Blackwood','Adelaide','Adelaide','Welland','Modbury','Smithfield','Seacliff'];
$citi_state = [
    'North Coburg' => 'VIC',
    'Lavington' => 'NSW'
];
$mysqli = new mysqli("localhost","root","pwd","mkhurram92_commishlara");

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

$sql = "SELECT * FROM cities";
 $result = $mysqli -> query($sql);


// Fetch all
$allCities = $result -> fetch_all(MYSQLI_ASSOC);
$result -> free_result();
$citinames = [];
if(count($allCities) > 0)
{
    foreach($allCities as $allCity)
    {
        $citinames[] = $allCity['name'];
    }
}
$uniqueCities = array_unique($cities);
$needToInsert = [];
foreach($uniqueCities as $uniqueCity)
{
    if(!in_array($uniqueCity,$citinames))
    {
        $needToInsert[] = $uniqueCity;
    }
}
$stateIds = [
    'SA' => 1,
    'QLD' => 2,
    'NSW' => 3,
    'VIC' => 4,
    'WA' => 5,
    'TAS' => 6,
    'NT' => 7,
    'ACT' => 8
];

$state_names = array_flip($stateIds);
// Free result set
$citiSql = '';
if(count($needToInsert) > 0)
{
    foreach($needToInsert as $ne)
    {
        $state_id = 1;

        if(array_key_exists($ne,$citi_state))
        {
            $state_id = $citi_state[$ne];
        }
        $citiSql .= "INSERT INTO `cities`( `name`, `state_id`, `state_name` ,`created_at`) VALUES ('".$ne."',"
            .$state_id.",'".$state_names[$state_id]."',now());";
    }
}
echo $citiSql;
die;*/
/*$mysqli = new mysqli("localhost","root","pwd","mkhurram92_commishlara");

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

$sql = "SELECT * FROM cities";
$result = $mysqli -> query($sql);


// Fetch all
$allCities = $result -> fetch_all(MYSQLI_ASSOC);
$cityKey = [];
if($allCities  && count($allCities) > 0)
{
    foreach ($allCities as $allCity)
    {
        $cityKey[$allCity['name']] = $allCity;
    }
}

$result -> free_result();*/
$stateIds = [
    'SA' => 1,
    'QLD' => 2,
    'NSW' => 3,
    'VIC' => 4,
    'WA' => 5,
    'TAS' => 6,
    'NT' => 7,
    'ACT' => 8
];

$state_names = array_flip($stateIds);
$allCities = [
    'Parafield Gardens' => [
        'id' => 1045,
        'state_id' => 1
    ],
    'Vale Park' => [
        'id' => 1064,
        'state_id' => 1
    ],
    'Albury' => [
        'id' => 29,
        'state_id' => 3
    ],
    'Adelaide' => [
        'id' => 5,
        'state_id' => 1
    ],
    'Parkside' => [
        'id' => 1079,
        'state_id' => 1
    ],
    'Seacliff' => [
        'id' => 1041,
        'state_id' => 1
    ]
];
require_once __DIR__.'/src/SimpleXLSX.php';

echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Consultant_Staff.xlsx') ) {
    $sql = "";
    $types = [
        'Broker' => 1,
        'Inactive Broker' => 2,
        'Potential Broker' => 3
    ];

    foreach( $xlsx->rows() as $rk => $r ) {
        if($rk == 0)
                continue;
            if(empty($r[2]) || empty($r[3]))
                    continue;

        $city_id = 0;
        $state_id = 0;
           if(!empty($r[12]))
           {
               $city_id = $allCities[$r[12]]['id'];
               $state_id = $allCities[$r[12]]['state_id'];
           }
        $sql .="INSERT INTO `brokers`( `type`, `is_individual`,trading,trust_name, `surname`, `given_name`, `dob`, `entity_name`, `work_phone`, `home_phone`, `mobile_phone`, `fax`, `email`, `web`, `business`, `state`, `city`, `pincode`, `created_at`, `updated_at`, `created_by`, `start_date`, `end_date`, `parent_broker`) VALUES (1,0,'".$r[3]."','".$r[3]."','".$r[2]."','".$r[3]."','".$r[9]."','".$r['3']."','".$r[5]."','".$r[6]."','".$r[6]."','".$r[7]."','".$r[8]."','".$r[10]."','".$r[11]."','".$state_id."','".$city_id."','".$r[13]."',now(),now(),1,'".$r[15]."','".$r[16]."','".$r[1]."');";
    }

} else {
	echo SimpleXLSX::parseError();
}

echo $sql = str_replace(['_x000d_','\\'],['',''],$sql);

file_put_contents('broker_staff.sql',$sql);
