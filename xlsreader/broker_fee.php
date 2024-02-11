<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/src/SimpleXLSX.php';

echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('t_Consultant_Fee.xlsx') ) {
    $sql = "";
    $types = [
        'Web' => 1,
        'Software' => 2,
        'I-Lend' => 3
    ];
    $frequences = [
        'Day' => 1,
        'Month' => 2,
        'Year' => 3,
        'Annual' => 3,
    ];

    foreach( $xlsx->rows() as $rk => $r ) {
            if($rk == 0)
                continue;

            if($r[2] == '')
                continue;

            $type =($types[$r[2]]);
            $frequency =($frequences[$r[4]]);

        $sql .="INSERT INTO `broker_fees`(`id`, `type`, `frequency`, `due_date`, `amount`, `created_by`,  `created_at`, `updated_at`, `broker_id`) VALUES (".$r[0].",".$type.",".$frequency.",'".$r[3]."','".$r[5]."',1,now(),now(),".$r[1].");";
    }

} else {
	echo SimpleXLSX::parseError();
}

echo $sql = str_replace('_x000d_','',$sql);

file_put_contents('broker_fee.sql',$sql);
