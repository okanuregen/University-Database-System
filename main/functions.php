<?php
function freeHours($conn, $ssn, $courseCode, $section) : array{
    $sql ="
select T.dayy, T.hourr
from timeslot T
where (T.dayy, T.hourr) not in (SELECT W.dayy, W.hourr
                           		from enrollment E NATURAL JOIN weeklyschedule W
                               	where E.yearr=2020 and  
								E.semester = 2 and 
								E.sssn in (SELECT E2.sssn
											from enrollment E2
											where E2.sssn = E.sssn and E2.issn= '$ssn' and 
											E2.courseCode='$courseCode' and E2.sectionId = '$section' 
											and E2.yearr=2020 and  E2.semester = 2))";
    $result = mysqli_query($conn,$sql);
    if(!$result){
        die("Query Error");
    }
    $resulset[] = null;
    while($row=mysqli_fetch_array($result)){
        $resulset[] = $row;
    }
    return $resulset;
}
?>