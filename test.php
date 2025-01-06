<?php
    session_start();
    // $now = new DateTime();
    // $future_date = new DateTime('2023-01-9 12:00:00');
    // $interval = $future_date->diff($now);
    // $out = '';
    // if($interval->format("%R") == "+")
    //     $out .= 'Assignment is overdue by: ';
    // echo $out.= $interval->format("%a days - %h hours - %i minutes - %s seconds");
    include_once("validate.php");
    echo insertTest();
?>
