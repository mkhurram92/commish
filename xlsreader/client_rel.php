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
$mysqli = new mysqli("localhost","root","pwd","mkhurram92_commishlara");

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

$sql = "SELECT * FROM relationship";
$result = $mysqli -> query($sql);


// Fetch all
$allCities = $result -> fetch_all(MYSQLI_ASSOC);
$cityKey = [];
if($allCities  && count($allCities) > 0)
{
    foreach ($allCities as $allCity)
    {
        $cityKey[strtolower($allCity['name'])] = $allCity;
    }
}

$result -> free_result();


require_once __DIR__.'/src/SimpleXLSX.php';
if (!function_exists('array_pluck')) {
    function array_pluck($array,$key)
    {
        if(is_array($array) && count(array_filter($array)) > 0)
        {
            return array_map(function ($v) use ($key) {
                if(is_object($v))
                {
                    return  isset($v->$key) ? $v->$key : '';
                }else{
                    return  isset($v[$key]) ? $v[$key] : '';
                }
            },$array);
        }
        return $array;
    }
}
echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Client_link.xlsx') ) {
    $sql = "";

    /* $reltions = array_unique(array_pluck($xlsx->rows(),3));

    $relsql = '';
    foreach($reltions as $k => $rel)
    {
        if($k == 0 || $rel == '')
                            continue;

        if(!array_key_exists(strtolower($rel),$cityKey))
        {
            echo $rel."<br/>";
        } */
        /* if(!in_array($rel,array('Father','Mother','Brother','Son','Daughter')))
        {
            $relsql .= ' INSERT INTO relationship (`name`,`created_at`) VALUES ("'.$rel.'",now()); ';
        } */

    /* } */
    //echo $relsql;

   // die;

    foreach( $xlsx->rows() as $rk => $r ) {
        if($rk == 0)
                continue;
            if(empty($r[1]) || empty($r[2])  || empty($r[3]))
                    continue;

        if($r[3] == 'Frined')
        {
            $r[3] = 'Friend';
        }

        if($r[3] == 'Brother-in-law')
        {
            $r[3] = 'Brother in law';
        }

        if($r[3] == 'Mother-in-Law')
        {
            $r[3] = 'Mother in law';
        }

        if($r[3] == 'Father-in-Law')
        {
            $r[3] = 'Father in law';
        }

        if($r[3] == 'Sister-in-law')
        {
            $r[3] = 'Sister in law';
        }

        if(!array_key_exists(trim(strtolower($r[3])),$cityKey))
        {
            echo $r[3]."<br/>";

        }else{

            $mail_city = $cityKey[trim(strtolower($r[3]))]['id'];


            $sql .="INSERT IGNORE INTO `client_relations`( `client_id`, `relation_with`, `relation`, `mailout`, `created_at`, `updated_at`) VALUES (".$r[1].",".$r[2].",".$mail_city.",0,now(),now());";
        }



    }

} else {
	echo SimpleXLSX::parseError();
}

echo $sql = str_replace(['_x000d_','\\'],['',''],$sql);

file_put_contents('client_relations.sql',$sql);
