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

$mysqli = new mysqli("localhost","root","pwd","mkhurram92_commishlara");

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
$lenderCh = ["Liberty","St George","GE","Bluestone","Adel Bank","ING","CBA","Suncorp","Resimac","CBFC Ltd","Esanda","ANZ","Homeloans","Westpac","Bank SA","NAB","Lifeplan","Challenger","Bank West","Pepper","AXA","Murray Mort","","Service Finance Corp","Banksia","Sav & Loans","ACCU","La Trobe","Garvan","Assist","Macquarie","Nab Broker","Interstar","Citibank","Connect","RAMS","Finance Now","Tonto","Grosvenor","HLP","Firstmac","EFS","Pioneer","AMP","ALI","Allsurity","HSBC","AFM","Widebay","Paramount","OFG","Capital","MKM Captial","Maxis","Legacy","Homestart","AFG","Leasechoice","Sundry Commission","Vero","Adv Bus Finance","ABN Amro","TBA","Novasure","KWS","Cardiff","RedZed","Tower","EastWood Securities","MedFin","Murphy FS","Sintex","Mango Credit","IDEN","Interim Finance","Prospa","Police Credit Union","Selfco","Beyond Bank","FMA Capital","Moula Money Pty Ltd","Brian Beames","Thorn Equipment Fin","GoGetta Equipment Fu","Kingsley Finance","Liberation Loans","Metro Finance","Bank of Sydney","BOQ","Bank of Melbourne"];

$indSql = 'SELECT * FROM `lenders`';
$result = $mysqli->query($indSql);
$indKey = [];
$allInd = $result->fetch_all(MYSQLI_ASSOC);
if($allInd  && count($allInd) > 0)
{
    foreach ($allInd as $allCity)
    {
        $indKey[trim(strtolower($allCity['name']))] = $allCity;
        $indKey[trim(strtolower($allCity['code']))] = $allCity;
    }
}
require_once __DIR__.'/src/SimpleXLSX.php';
$needToAdd = [];
echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Deal.xlsx') ) {
    $sql = "";

    //print_r(implode('","',array_unique(array_pluck($xlsx->rows(),3))));
    //die;
    foreach( $xlsx->rows() as $rk => $r ) {


        if ($rk == 0)
        {
            continue;
        }

        $dea_id = $r[0];
        $dea_con_id = $r[1];
        $dea_cli_id = $r[2];
        $dea_ins_id = $r[3];
        $dea_pro_id = $r[4];
        $dea_InstitutionRef = $r[5];
        $dea_LoanAmount = $r[6];
        $dea_DateAdvised = $r[7];
        $dea_DateSettled = $r[8];
        $dea_ConsultantPercent = $r[9];
        $dea_Comm1Type = $r[10];
        $dea_Comm1Amount = $r[11];
        $dea_Date1Received = $r[12];
        $dea_Comm1MasterAmount = $r[13];
        $dea_Comm1ConsultAmount = $r[14];
        $dea_Comm1DatePaid = $r[15];
        $dea_Comm2Type = $r[16];
        $dea_Comm2Amount = $r[17];
        $dea_Date2Received = $r[18];
        $dea_Comm2MasterAmount = $r[19];
        $dea_Comm2ConsultAmount = $r[20];
        $dea_Comm2DatePaid = $r[21];
        $dea_Comm3Type = $r[22];
        $dea_Comm3Amount = $r[23];
        $dea_Date3Received = $r[24];
        $dea_Comm3MasterAmount = $r[25];
        $dea_Comm3ConsultAmount = $r[26];
        $dea_Comm3DatePaid = $r[27];
        $dea_DateExpiry = $r[28];
        $dea_DateExpired = $r[29];
        $dea_Note = $r[30];
        $dea_Comm1ReferrorAmount = $r[31];
        $dea_Comm1ReferrorDatePaid = $r[32];
        $dea_Comm2ReferrorAmount = $r[33];
        $dea_Comm2ReferrorDatePaid = $r[34];
        $dea_Comm3ReferrorAmount = $r[35];
        $dea_Comm3ReferrorDatePaid = $r[36];
        $dea_ReferrorPercent = $r[37];
        $dea_ref_id = $r[38];
        $dea_hasTrail = $r[39];
        $dea_DateSettleProposed = $r[40];
        $dea_IncludeInBonus = $r[41];
        $dea_cm_id = $r[42];
        $dea_GSTApplies = $r[43];
        $dea_Processor = $r[44];
        $dea_cs_id = $r[45];
        $dea_ConsultantFlatFee = $r[46];
        $dea_ReferrorFlatFee = $r[47];
        $dea_ReferrorPercentTrail = $r[48];
        $dea_LoanAmountEst = $r[49];
        $dea_UpfrontEst = $r[50];
        $dea_TrailEst = $r[51];
        $dea_ConsultantPercentTrail = $r[52];
        $dea_LoanAmountEst_ABP = $r[53];
        $dea_UpfrontEst_ABP = $r[54];
        $dea_TrailEst_ABP = $r[55];
        $dea_LinkedDeal = $r[56];
        $dea_Aggregator = $r[57];
        $dea_DateCreated = $r[58];
        $dea_ABPStaffFlatFee = $r[59];
        $dea_ABPStaffPercent = $r[60];
        $dea_ABPStaffPercentTrail = $r[61];
        $dea_Comm1ABPStaffAmount = $r[62];
        $dea_Comm2ABPStaffAmount = $r[63];
        $dea_BrokerageEst_ABP = $r[64];
        $dea_BrokerageEst = $r[65];
        $dea_Status = $r[66];
        $dea_DateSubmitted_OLD = $r[67];
        $dea_DateStatusEN = $r[68];
        $dea_DateStatus00 = $r[69];
        $dea_DateStatus01 = $r[70];
        $dea_DateStatus02 = $r[71];
        $dea_DateStatus03 = $r[72];
        $dea_DateStatus04 = $r[73];
        $dea_DateStatus05 = $r[74];
        $dea_DateStatus06 = $r[75];
        $dea_DateStatus07 = $r[76];
        $dea_DateStatus08 = $r[77];
        $dea_LineCredit = $r[78];
        $dea_ProposedSettlementDate = $r[79];
        $dea_DateStatusLEAD = $r[80];
        $dea_Status_YesNo1 = $r[81];
        $dea_Status_YesNo2 = $r[82];
        $dea_Date1 = $r[83];
        $dea_Date2 = $r[84];
        $dea_date3 = $r[85];
        $dea_YesNo1 = $r[86];
        $dea_YesNo2 = $r[87];
        $dea_YesNo3 = $r[88];
        $dea_Value1 = $r[89];
        $dea_Value2 = $r[90];
        $dea_Value3 = $r[91];
        $dea_Comments1 = $r[92];
        $dea_Comments2 = $r[93];
        $dea_Text1 = $r[94];
        $dea_Text2 = $r[95];
        $dea_Int1 = $r[96];
        $dea_Int2 = $r[97];
        $dea_DateStatusAIP = $r[98];
        $dea_DateStatus09 = $r[99];
        $dea_DateStatus10 = $r[100];
        $dea_ExcludefromTracking = $r[101];
        $dea_StatusString1 = $r[102];

        $dea_ins_pk = 0;
        if(array_key_exists(strtolower($dea_ins_id),$indKey))
        {
            $dea_ins_pk = $indKey[strtolower($dea_ins_id)]['id'];
        }

        $sql .='INSERT INTO `deals`(`id`, `broker_id`, `broker_staff_id`, `contact_id`, `product_id`, `lender_id`, `line_of_credit`, `loan_ref`, `linked_to`, `loan_repaid`, `status`, `proposed_settlement`, `actual_loan`, `exclude_from_tracking`, `gst_applies`, `broker_est_upfront`, `broker_est_trail`, `broker_est_brokerage`, `broker_est_loan_amt`, `agg_est_upfront`, `agg_est_trail`, `agg_est_brokerage`, `has_trail`, `note`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ("'.$dea_id.'","'.$dea_con_id.'",0,"'.$dea_cli_id.'","'.$dea_pro_id.'","'.$dea_ins_pk.'","'.$dea_LineCredit.'","'.$dea_InstitutionRef.'","'.$dea_LinkedDeal.'","'.$middle_name.'","'.$dob.'","'.$role_title.'","'.$role.'","'.$abp.'","'.$abn.'","'.$entity_name.'","'.$principle_contact.'","'.$work_phone.'","'.$home_phone.'","'.$mobile_phone.'","'.$fax.'","'.$email.'","'.$web.'","'.$city_id.'","'.$state_id.'","'.$postal_code.'","'.$mail_city.'","'.$mail_state.'","'.$mail_postal_code.'","'.$client_ind.'","'.$otherInd.'","'.$note.'","'.$referred_to_1.'","'.$services_1.'","'.$date_1.'","'.$note_1.'","'.$referred_to_2.'","'.$services_2.'","'.$date_2.'","'.$note_2.'","'.$acc_name.'","'.$acc_no.'","'.$bank.'","'.$bsb.'","'.$reffered_by_existing_client.'","'.$refferor.'","'.$refferor_relation_to_client.'","'.$refferor_note.'","'.$created_at.'","'.$updated_at.'",0);';
    }

} else {
	echo SimpleXLSX::parseError();
}

echo json_encode($needToAdd);
echo $sql = str_replace('_x000d_','',$sql);

file_put_contents('deals.sql',$sql);

