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
$types = [
    'COSL' => 1,
    'FBAA' => 2,
    'FMA Review' => 3,
    'MFAA' => 4,
    'PI' => 5
];

require_once __DIR__.'/src/SimpleXLSX.php';

echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Consultant_Certification.xlsx') ) {
    $sql = "";


    foreach( $xlsx->rows() as $rk => $r ) {
        if($rk == 0)
                continue;
            if(empty($r[2]) || empty($r[3]))
                    continue;


        $sql .="INSERT INTO `broker_certifications`(`id`, `type`, `required`, `held`, `expiry_date`, `created_by`, `created_at`, `updated_at`, `broker_id`) VALUES (".$r[0].",".$types[$r[2]].",".((empty($r[3])) ? 0 : $r[3]).",".(empty($r[4]) ? 0 : $r[4]).",'".DATE('Y-m-d',strtotime($r[5]))."',1,now(),now(),".$r[1].");";
    }

} else {
	echo SimpleXLSX::parseError();
}

echo $sql = str_replace(['_x000d_','\\'],['',''],$sql);

file_put_contents('broker_cert.sql',$sql);
