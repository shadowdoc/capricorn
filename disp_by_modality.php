<?php
/*
    Capricorn - Open-source analytics tool for radiology residents.
    Copyright (C) 2014  (Howard) Po-Hao Chen

    This file is part of Capricorn.

    Capricorn is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$dispArray = array();       // Holds all the series information, keyed by Type (modality).
$arrangement = array();    // contains all the Table Titles (modality) 

$smn = getExamCodeData('Section, Type', NULL, "ORDER BY TYPE");
foreach ($smn as $codeData) {
    $RVU_unknown=0; //reset counter for unset RVU values
    $array = array();
    if ($cumulative) {
        $array = getCumulativeCountArray($codeData[0], $codeData[1], "", $sd, $ed, $intvl);
    } else {
        $array = getCountArray($codeData[0], $codeData[1], "", $sd, $ed, $intvl);
    }

    // Handle Nuclear Medicine differently
    if ($codeData[0] == 'NM') {
        $arrangement[] = $codeData[0];
        $dispArray[$codeData[0]][$codeData[1]] = $array;
    } 
    else if ($codeData[0] == 'MISC' || $codeData[1] == 'MISC') continue;
    else {
        $arrangement[] = $codeData[1];
        $dispArray[$codeData[1]][$codeData[0]] = $array;
        if($RVU_unknown>0){
            echo "<br>There are <strong>".$RVU_unknown." " . $codeData[0] . " " . $codeData[1] ." </strong> studies without set RVU values that would be included in these graphs.";
        }
    }
}

?>

<script>
<!--
var startDate = '<?php echo $sd ?>'
var endDate = '<?php echo $ed ?>'
var pointInt = <?php echo $dayInt?> * 24 * 3600 * 1000;
//-->
</script>

<?php 
$arrangement = array_unique($arrangement);

// Move babygrams to the end
if ($arrangement[0] == "BABY") {
    unset($arrangement[0]);
    $arrangement[] = "BABY";    
}

foreach ($arrangement as $mod) {
    if ($cumulative) assembleGraph($mod, 'area', $dispArray[$mod]);
    else assembleGraph($mod, 'column', $dispArray[$mod]);
}

// Empty ones after the non-empty ones
$emptyArray = array();
$first = True;
foreach ($arrangement as $mod) {
    $isEmpty = True;
    foreach ($dispArray[$mod] as $ar) if (array_sum($ar) > 0) $isEmpty = False;
    if ($isEmpty) {
        $emptyArray[] = $mod;
        continue;
    }
    tableStartSection($mod);
    if ($first)  {
        makeDIV($mod, '750px', '400px', $isEmpty, "Click on the section legends on the right side to hide the series. <P> Mouse-over individual data points for details.<P>Click on a bar to show the studies.");
        $first = False;
    } else {
        makeDIV($mod, '750px', '400px', $isEmpty);
    }
    tableEndSection();
}

foreach ($emptyArray as $mod) {
    tableStartSection($mod);
    makeDIV($mod, '750px', '400px', True);
    tableEndSection();
}



?>


