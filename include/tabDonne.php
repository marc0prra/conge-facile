<?php 

$holidays = [
    "2025-01-01", "2025-04-21", "2025-05-01", "2025-05-08", "2025-05-29",
    "2025-06-09", "2025-07-14", "2025-08-15", "2025-11-01", "2025-11-11", "2025-12-25"
];

function getWorkingDays($start, $end, $holidays = []) {
    $begin = new DateTime($start);
    $end = new DateTime($end);
    $end->modify('+1 day');

    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($begin, $interval, $end);

    $workingDays = 0;
    foreach ($dateRange as $date) {
        $day = $date->format('N');
        $formatted = $date->format('Y-m-d');
        if ($day < 6 && !in_array($formatted, $holidays)) {
            $workingDays++;
        }
    }
    return $workingDays;
}

function getStatus($codes) {
    $numbers = explode(',', $codes);
    if (in_array('0', $numbers)) {
        return 'En cours';
    } elseif (in_array('1', $numbers)) {
        return 'Acceptée';
    } elseif (in_array('2', $numbers)) {
        return 'Refusée';
    } else {
        return 'Statut inconnu';
    }
}

?>