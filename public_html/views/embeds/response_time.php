<?php 

    $output_str = 'Responds within ';

    if ($response_time == null)
        $output_str = "No responses";
    else if ($response_time < 60)
        $output_str .= 'an hour';
    else if ($response_time >= 60 && $response_time < 60*24)
        $output_str .= 'a day';
    else if ($response_time >= 60*24 && $response_time < 60*24*7)
        $output_str .= 'a week';
    else
        $output_str .= 'a month';

    echo $output_str;
?>