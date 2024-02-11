<?php


ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
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
/*
$cities = ["Victor Harbor","Parafield Gardens","Rosslyn Park","Mile End","Flinders Park","Surrey Downs","Brunswick",
    "Newton","Fulham Gardens","Crafers West","Port Adelaide","West Lakes","Fulham","Salisbury","Enfield","Modbury Heights","McLaren Vale","Hazelwood Park","Rostrevor","Vale Park","Cumberland Park","Seaton","Henley Beach","Findon","Dulwich","Campbelltown","Elizabeth","Athelstone","Torrens Park","","Basket Range","Woodville","Onkaparinga Hills","Flagstaff Hill","Cullen Bay","Stirling","Rose Park","Morphett Vale","Blakeview","North Adelaide","Tranmere","Tennyson","west beach","Beaumont","Renown Park","Brahma Lodge","Blair Athol","Grange","Propsect","Paradise","Modbury","Osborne","East Snowtown","Hillcrest","Valley View","Marino","Seacliff","Adelaide","Vale park","Hawthorndene","Exeter","Algate","Para Vista","Nailsworth","Erindale","Hallett Cove","Evanston Gardens","Modbury North","Winkie","Manningham","Gilles Plains","Mount Barker","Highbury","Magill","Port Noarlunga South","Prospect","Surery Downs","Marble Hill","North Bungama","Port Pirie","Swan Reach","Elizabeth Vale","Hackham West","Pooraka","Rosewater","Golden Grove","Kurrulta Park","Hope Valley","Christies Beach","Montacute","Belgrave","Highgate","Kidman Park","Brompton","Burton","Caulfield South","St Georges","St Morris","St Peters","Myrtle Park","Salisbury South","Pasadena","Virginia","Underdale","North Haven","West Hindmarsh","Toorak Gardens","Salisbury Park","Lockleys","Gawler","Banksia Park","Blakehurst","Greenwith","Angle Vale","Clapham","Woodville South","Woodcroft","Fullham Gardens","Hampstead Gardens","Dernancourt","Keswick","Marleston","Largs Bay","Fairview Park","Dover Gardens","Happy Valley","Salisbury North","Warradale","Glanville","Red Cliffs","Henely Beach","Camden Park","Klemzig","Para Hills","Maitland","Crafers","Myrtle Bank","Waterloo Corner","Brooklyn Park","Wynn Vale","Belair","South Plympton","Royston Park","Two Wells","Seacliff Park","Vista","Felixstow","Paralowie","Salisbury East","Aberfoyle Park","Torrensville Plaza","Kent Town","Salisbury Heights","Ingle Farm","Clarence Park","Croydon","Parkside","Oakden","Gilberton","Edwardstown","Glenside","Arthurton","Roseworthy","Linden Park","Craigmore","Henley Beach South","Unley","Malvern","Balhannah","Largs North","Westbourne Park","Waikerie","Morphettville","Avondale Heights","Trinity Gardens","Frenches Forest","Summertown","Royal Park","Wulagi","Stonyfell","Williamstoen","Semaphore","Millswood","Wingfield","Glen Osmond","Bleair","Norwood","Payneham South","Kilburn","Kadina","Windsor Gardens","Plympton","Kew","West Croydon","Hahndorf","Garran","Auldana","Coromandel Valley","Blackforest","174 Pitt St, Sydney","West Richmond","Elizabeth East","Burnside","Mildura","Seaford Rise","Salisbury Downs","Glynde","Torrensville","Hyde Park","Frewville","Mt George","Clovelly Park","Tea Tree Gully","Sydney","Glenelg","Semaphore Park","Aldgate","Glandore","Mt Gambier","Hectorville","Mt Barker","Hackney","Thebarton","Rundle Mall Adelaide","Lynton","Forrestulle","Hendon","Raglan Pde, Warnambool","Lewiston","Richmond","Lower Mitcham","Semaphore South","Murray Bridge","Allenby Gardens","Brighton"," Brighton","Firle","Sturt","New Port","Greenfields","Flemington","Croydon Park","Reynella","Albert Park","Welland","Woodville West","Direk","Trott Park","Wesy Croydon","Para Hills West","Walkerville","Somerton Park","Diagonal Rd, Pooraka","Newtown North","Netherby","Birdwood","Aldinga Beach","Colonel Light Gardens","Beverley","Ottoway","KENT TOWN","Andamooka","Noarlunga Downs","Surry Hills","Peterhead","Cowandilla","Alberton","Hilton","Ridgehaven","Balmain","Gulfview Heights","Taperoo","Greenacres"," Greenacres","Ferryden Park","Scarborough","Holden Hill","Casuarina","Mt Pleasant","Glenelg North","Tusmore","Wayville","Bridgewater","westlakes","Nuriootpa","Kilkenny","Goldstream","Goodwood","North  Adelaide","Marden","Bellevue Hill","Golden Grove Village","Unley Park","Mawson Lakes","Royla Park","Ceduna","Kensington","kensington","Caufield South","Mansfield Park","McDonald Park","Northfield","Whyalla","Westmead","Broadview","Kingston Park","Andrews Farm","Beulah Park","Murrya Bridge","Gillies Plains","Woodforde","Collins St East","Stepney","Davoren Park","St Marys","Willaston","Melton","Redwood Park","Castle Hill","Redfern East","Kensington Park","Brigderwater","Huntfield Heights","Devon Park","Bangkok, Thailand","Craomandel Valley","Royston Park SA","Regency Park","Holdfast Shores","Salisbury Plain","Elizabeth North","Port Augusta"," Port Augusta","Munno Parra","Leabrook","Mitcham","Burra","Kurralta Park","Paravista","Gulfview Hights","Woodville North","West Lakes Shore","Maylands","Truro","Renmark","Unley BC","Marryatville","Hindmarsh","Artamanon","Dudley Park","Greenhill","Felixtow","North Brighton","Munno Para West","St Agnes","Keith","Williamstown","Willunga","Seacliffe","Springfield","Gumeracha","Redlynch","Riverton","Ridleyton","Evandale","East Brighton","Noble Park","Dandenong","davoren park","adelaide","Ridgehave","Walkley Heights","Fullarton","Outer Harbor","Netley","West Beach","Potts Point","Hallet Cove","Seaview Downs","Blackwood","Ethelton","Daw Park","Bellavista","Wangary","Cavan","Paracombe","Pt Clinton","Hove","Brahama Lodge","Delamere","Goolwa","Goowla","Sheidow Park","Joslin","Risdon Park","Tatton","Pennignton","Northgate","Hawthorn","BRAHMA LODGE","Clearview","Five Dock","Pennington","Merrylands","Sefton Park","Kingswood","Sellicks Beach","O'Hallaran Hill","Kingston","Teringie","Darlington","St Kilda","Mylor","Higbury","Roxby Downs","Smithfield","Woodville Park","Hackham","Glenalta","Payneham","Lewsiton","Charleston","WINGFIELD","Magil","Kersbrook","Koo Wee Rup","Lonsdale","Athestone","Tea Tree Plaza, Modbury","Moranbah","Denmark","Modbury Hights","Birkenhead","East Redfern","Adelaide Airport","Glenelg East","Salisbury Hights","Munno Para","Beverly","Inglewood","Park Holme","Henley South","Panorama","Hillbank","Angaston","Holen Hill","Macclesfield","South Narre Warren","Mary St BC Unley","Glengowrie","Golden Heights","Encounter Bay","Woodside","Nairne","Black Forest","Victor Harbour","Carrickalinga","Port Elliot","Shepparton","Ashburton","Port Noarlunga","St Albans","Middleton","Blackwwod","Korringal","Strathalbyn","Roxburgh Park","Tatura","Plympton Park","Port Hughes","Medindie","St peters","Edwardstn","Coburg North","lynton","Lobethal","Clarence","Oaklands Park","Novar Gardens","BridgeWater","Glenelg South","Greensborough","rostrevor"," Moonta bay","Coober Pedy","Colonel Light gardens","Henly Beach","Reynella East","Fullham","Woodville Gardens","Para Hils","St Mary's","Marion","Port Willunga","Port Pirie South","Pt Pirie","Pt Augusta","Balaklava","Somerton park","Stirling North","McLaren Flat","Seacombe Gardens","Little Hampton","Broken Hill","Eden Hills","Gould Creek","Maslin Beach","Craigburn Farm","Queenstown","Seven Hills","Streaky Bay Heights","Streaky Bay","Gawler East","Goolwa Beach","Longwood","Wirrina Cove","Balahannah","Mt Compass","Penfield","Dovar  Gardens","MOONTA BAY","Parafield gardens","Littlehampton","Coromandel valley","Myponga Beach","Alice Springs","Whyalla Norrie","Eastwood","COLONEL LIGHT GARDENS","Deakin","Propect","Mitchell Park","Ascot Park","Durack","La Trobe","Herron","Olympic Dam","Port Germein","Bedford Park","Bentleigh East","Glenunga","Butler Tanks","Mindarie","Kanmantoo","Goolwa North","Athol Park","Box Hill","CHERRYVILLE","CLEARVIEW","STEPNEY","ROSSLYN PARK","GULFVIEW HEIGHTS","PAYNEHAM","NEWTON","TORRENSVILLE","NORTH ADEALAIDE","BROOKLYN PARK","GLENGOWRIE","GRANGE","PARA HILLS WEST","COROMANDEL VALLEY","MacDonald Park","Melrose Park","Blackwood Park","Pewesy Vale","Pewsey Vale","North Plympton","Manoora","Black Forrest","Torrens park","Pt Julia","Maroubra","Warnertown","Moonta Bay","Wellington East","Campbells Creek","Sawtell","Pimbee","Ashford","St Clair","Dry Creek","Moana","frewville","Clovelly","Blandford","Northagte","St Lucia","Port Augusta West","Milang","Woodfdorde","Greenock","Moonta","Ponde","Lindfield","Oakbank","Urrbrae","Mudgeeraba","Yorketown","Royal park","Hewitt","Kingston prk","Salisbury Plains","Meadows","Christie Downs","orth Brighton","Yorktown","Clarence Gardens","Wattle Park","Midvale","Elizabeth Grove","Reedy Creek","Whyalla Jenkins","Spalding","Mollymook Beach","Krondorf","Houghton","Cannons Creek","Heathpool","Adeliade","Burwood","Kensington Gardens","Downer","Evanston South","Evanston Park","Tanunda","Barmera","Wantirna South","Heathfield","Sommerton Park","Coonalpyn","Pacific Pines","Seaford","Wanniassa","Drysdale","Everard Park","Naracoorte","Yerriyong","Townsville","Collinswood","Warriewood","Wariewood","Forrestfield","West End","Darlinghurst","Curramulka","Moorooka","Camberwell","One Treehill","KANWAL","MYPONGA","Parafiled Gardens","Bonegilla","Currumbin","Narre Warren South","Hewett","Blakiston","Elizabeth Downs","BANGHOLME","Wirrabarra","Broadbeach","Goulburn","One Tree Hill","Hocking","Sawyers Valley","West Lake Shore","Port Melbourne","Docklands","Fletcher","Gulfview Height","Moss Vale","Mosman","ROSTREVOR","Hindmarsh Island","Lalor","Toorak","Echunga","Bulleen","Taylors Lakes","Preston","Doreen","Deer Park","Epping","Truganina","Hammondville","Pakenham","Darakabin","Charlestown","Centennial Park","Noosaville","Berwick","Poorgaka","Landsdale","Wollert","Endeavour Hills","Blacktown","Boronia","Mill Park","South Kingsville","Jamisontown","Carrackalinga","Hillside","Piccadilly","Woodleigh","Loxton","Coromandel East","Benbournie","Mt Barker Springs","Bamerra","Loganholme"];
$citi_state = [
    'Deakin' => 'ACT',
    'Wanniassa' => 'ACT',
    'Newtown North' => 'NSW',
    'Surry Hills' => 'NSW',
    'Balmain' => 'NSW',
    'Westmead' => 'NSW',
    'Castle Hill' => 'NSW',
    'Artamanon' => 'NSW',
    'Potts Point' => 'NSW',
    'Bellavista' => 'NSW',
    'Kooringal' => 'NSW',
    'Five Dock' => 'NSW',
    'Merrylands' => 'NSW',
    'East Redfern' => 'NSW',
    'Redfern East' => 'NSW',
    'Broken Hill' => 'NSW',
    'Sawtell' => 'NSW',
    'Pimbee' => 'NSW',
    'KANWAL' => 'NSW',
    'Drummoyne' => 'NSW',
    'Cullen Bay' => 'NT',
    'Wulagi' => 'NT',
    'Casuarina' => 'NT',
    'Alice Springs' => 'NT',
    'Durack' => 'NT',
    'Redlynch' => 'QLD',
    'Moranbah' => 'QLD',
    'Norman Park' => 'QLD',
    'Manoora' => 'QLD',
    'Mudgeeraba' => 'QLD',
    'Reedy Creek' => 'QLD',
    'Pacific Pines' => 'QLD',
    'Loganholme' => 'QLD',
    'La Trobe' => 'TAS',
    'Fitzroy' => 'VIC',
    'Belgrave' => 'VIC',
    'Caulfield South' => 'VIC',
    'Red Cliffs' => 'VIC',
    'Avondale Heights' => 'VIC',
    'Kew' => 'VIC',
    'Mildura' => 'VIC',
    'Goldstream' => 'VIC',
    'Collins St East' => 'VIC',
    'Melton' => 'VIC',
    'East brighton' => 'VIC',
    'Noble Park' => 'VIC',
    'Elwood' => 'VIC',
    'St Kilda' => 'VIC',
    'Koo Wee Rup' => 'VIC',
    'South Narre Warren' => 'VIC',
    'Ashburton' => 'VIC',
    'St Albans' => 'VIC',
    'Roxburgh Park' => 'VIC',
    'Tatura' => 'VIC',
    'Coburg North' => 'VIC',
    'Box Hill' => 'VIC',
    'Brighton' => 'VIC',
    'Croydon' => 'VIC',
    'Campbells Creek' => 'VIC',
    'Burwood' => 'VIC',
    'Scarborough' => 'WA',
    'Herron' => 'WA',
    'Midvale' => 'WA',
    'Forrestfield' => 'WA',
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

           if($ne == '174 Pitt St, Sydney')
           {
               continue;
           }



        if(array_key_exists($ne,$citi_state))
        {
            $state_name = $citi_state[$ne];
            $state_id = $stateIds[$state_name];
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

$sql = "SELECT * FROM cities";
$result = $mysqli -> query($sql);


// Fetch all
$allCities = $result -> fetch_all(MYSQLI_ASSOC);
$cityKey = [];
if($allCities  && count($allCities) > 0)
{
    foreach ($allCities as $allCity)
    {
        $cityKey[trim(strtolower($allCity['name']))] = $allCity;
    }
}


$result -> free_result();


$indSql = 'SELECT * FROM `industries`';
$result = $mysqli->query($indSql);
$indKey = [];
$allInd = $result->fetch_all(MYSQLI_ASSOC);
if($allInd  && count($allInd) > 0)
{
    foreach ($allInd as $allCity)
    {
        $indKey[trim(strtolower($allCity['name']))] = $allCity;
    }
}

$relationServices = [
    'Accountancy' => 1,
    'Conveyancing' => 2,
    'Financial Planning' => 3,
    'Insurance' => 4,
    'Legal Advice' => 5,
    'SGIC' => 6,

];
require_once __DIR__.'/src/SimpleXLSX.php';
$needToAdd = [];
echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Client.xlsx') ) {
    $sql = "";
    $types = [
        'Broker' => 1,
        'Inactive Broker' => 2,
        'Potential Broker' => 3
    ];
    //print_r(implode('","',array_unique(array_pluck($xlsx->rows(),14))));
    //die;
    foreach( $xlsx->rows() as $rk => $r ) {

        if ($rk == 0)
        {
            continue;
        }

        $id = $r[0];
        $search_for = ($r[50] == 'Referror') ? 2 : 1;
        $is_individual = 0;
        $general_mail_out = $r[27];
        $trading = ($r[3] != '') ? $r[3] : $r[5];
        $trust_name = $r[4];
        $surname = $r[1];
        $first_name = $r[2];
        $preferred_name = $r[39];
        $middle_name = $r[40];
        $dob = $r[6];
        $role_title = $r[24];
        $role = ($r[46]!='') ? 1 : 0;
        $abp = $r[28];
        $abn = $r[38];
        $entity_name = $r[53];
        $principle_contact = $r[49];
        $work_phone = $r[7];
        $home_phone = $r[8];
        $mobile_phone = $r[9];
        $fax = $r[10];
        $email = $r[11];
        $web = $r[12];
        $city = $r[14];
        $postal_code = $r[16];

        $city = str_replace('_x000d_','',$city);
        $city = trim($city);
        if ($city == '174 Pitt St, Sydney')
        {
            $city = 'Sydney';
        } if ($city == 'Halifax Street Adelaide')
        {
            $city = 'Adelaide';
        } if ($city == 'Seaview Downts')
        {
            $city = 'Seaview Downs';
        }if ($city == 'Adealdei')
        {
            $city = 'Adelaide';
        }if ($city == 'Norwood South')
        {
            $city = 'Norwood';
        }if ($city == '52 Gaylard Cresent')
        {
            $city = 'Redwood Park';
        }if ($city == 'Prospect East')
        {
            $city = 'Prospect';
        }

        $city_id = 0;
        $state_id = 0;
        if (!empty($city)) {
            if(!array_key_exists(trim(strtolower($city)),$cityKey))
            {
                if(!array_key_exists($city,$needToAdd))
                {
                    $needToAdd [$city] = [
                        'city' => $city,
                        'state' => $r[15]
                    ];
                }

            }
            $city_id = $cityKey[trim(strtolower($city))]['id'];
            $state_id = $cityKey[trim(strtolower($city))]['state_id'];
        }
        $mail_c = $r[18];
        $mail_c = str_replace('_x000d_','',$mail_c);
        $mail_c = trim($mail_c);
        if ($mail_c == '174 Pitt St, Sydney')
        {
            $mail_c = 'Sydney';
        } if ($mail_c == 'Halifax Street Adelaide')
        {
            $mail_c = 'Adelaide';
        } if ($mail_c == 'Seaview Downts')
        {
            $mail_c = 'Seaview Downs';
        }if ($mail_c == 'Adealdei')
        {
            $mail_c = 'Adelaide';
        }if ($mail_c == 'Norwood South')
        {
            $mail_c = 'Norwood';
        }if ($mail_c == '52 Gaylard Cresent')
        {
            $mail_c = 'Redwood Park';
        }if ($mail_c == 'Prospect East')
        {
            $mail_c = 'Prospect';
        }
        $mail_city = 0;
        $mail_state = 0;
        if (!empty($mail_c)) {
            if(trim(strtolower($mail_c)) == 'meadows ')
            {
                $mail_city = 826;
                $mail_state = 1;
            }else{

                if(!array_key_exists(trim(strtolower($mail_c)),$cityKey))
                {
                    $mail_city = 826;
                    $mail_state = 1;
                }else{
                    $mail_city = $cityKey[trim(strtolower($mail_c))]['id'];
                    $mail_state = $cityKey[trim(strtolower($mail_c))]['state_id'];
                }

            }

        }
        $mail_postal_code = $r[20];

        $indName = $r[31];
        $othindName = $r[32];
        $client_ind = 0;
        $otherInd = '';
        if ($indName != '')
        {
            if(array_key_exists($indName,$indKey))
            {
                $client_ind = $indKey[$indName]['id'];
            }else{
                $client_ind = 27;
                $otherInd = $indName;
            }
        }else if($othindName != '')
        {
            $client_ind = 27;
            $otherInd = $indName;
        }
        $note = $r[22];
        $referred_to_1 = $r[33];
        $services_1_name = $r[34];
        $services_1 = 0;
        if(!empty($services_1_name))
        {
            $services_1 = $relationServices[$services_1_name];
        }
        $date_1 = $r[35];
        $note_1 = $r[44];
        $referred_to_2 = $r[41];
        $services_2_name = $r[42];
        $services_2 = 0;
        if(!empty($services_2_name))
        {
            $services_2 = $relationServices[$services_2_name];
        }
        $date_2 = $r[43];
        $note_2 = $r[45];
        $acc_name = $r[54];
        $acc_no = $r[55];
        $bank = $r[56];
        $bsb = $r[57];
        $reffered_by_existing_client = $r[47];
        $refferor = '';
        $refferor_relation_to_client = '';
        $refferor_note = $r[48];
        $created_at = $r[23];
        $updated_at = $r[36];

        $sql .='INSERT INTO `contact_searches`(`id`, `search_for`, `individual`, `general_mail_out`, `trading`, `trust_name`, `surname`, `first_name`, `preferred_name`, `middle_name`, `dob`, `role_title`, `role`, `abp`, `abn`, `entity_name`, `principle_contact`, `work_phone`, `home_phone`, `mobile_phone`, `fax`, `email`, `web`, `city`, `state`, `postal_code`, `mail_city`, `mail_state`,`mail_postal_code`,`client_industry`,`other_industry`,`note`,`referred_to_1`,`services_1`,`date_1`,`note_1`,`referred_to_2`,`services_2`,`date_2`,`note_2`,`acc_name`,`acc_no`,`bank`,`bsb`,`reffered_by_existing_client`,`refferor`,`refferor_relation_to_client`,`refferor_note`, `created_at`, `updated_at`, `created_by`) VALUES ("'.$id.'","'.$search_for.'","'.$is_individual.'","'.$general_mail_out.'","'.$trading.'","'.$trust_name.'","'.$surname.'","'.$first_name.'","'.$preferred_name.'","'.$middle_name.'","'.$dob.'","'.$role_title.'","'.$role.'","'.$abp.'","'.$abn.'","'.$entity_name.'","'.$principle_contact.'","'.$work_phone.'","'.$home_phone.'","'.$mobile_phone.'","'.$fax.'","'.$email.'","'.$web.'","'.$city_id.'","'.$state_id.'","'.$postal_code.'","'.$mail_city.'","'.$mail_state.'","'.$mail_postal_code.'","'.$client_ind.'","'.$otherInd.'","'.$note.'","'.$referred_to_1.'","'.$services_1.'","'.$date_1.'","'.$note_1.'","'.$referred_to_2.'","'.$services_2.'","'.$date_2.'","'.$note_2.'","'.$acc_name.'","'.$acc_no.'","'.$bank.'","'.$bsb.'","'.$reffered_by_existing_client.'","'.$refferor.'","'.$refferor_relation_to_client.'","'.$refferor_note.'","'.$created_at.'","'.$updated_at.'",0);';
    }

} else {
	echo SimpleXLSX::parseError();
}

echo json_encode($needToAdd);
echo $sql = str_replace('_x000d_','',$sql);

file_put_contents('clients.sql',$sql);

