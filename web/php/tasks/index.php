<?php
include_once('./php/bbddCon.php');

function where_or_and($q, $s){
    $buffer = $s ? " AND" : " WHERE";
    $q .= $buffer;
    return $q;
}

parse_str(rawurldecode($_SERVER['QUERY_STRING']), $constraints);
$start = False;
$query = "SELECT * FROM tasks WHERE id_student in (SELECT id_student FROM students WHERE name LIKE '%" . $_SESSION['nombreuser'] . "%')";



if(isset($constraints['subject'])){
    $query = where_or_and($query, $start);
    $query .= " subject=" . "'" . $constraints['subject'] . "'";
    $start = True;
}

if(isset($constraints['date']['gte'])){
    $query = where_or_and($query, $start);
    if($constraints['date']['gte'] === "now"){
        $constraints['date']['gte'] = date("Y-m-d");
    }
    $query .= "  date>=" . "'" . $constraints['date']['gte'] . "'";
    $start = True;
}

if(isset($constraints['date']['eq'])){
    $query = where_or_and($query, $start);
    $query .= " date=" . "'" . $constraints['date']['eq'] . "'";
    $start = True;
}

if(isset($constraints['date']['lt'])){
    $query = where_or_and($query, $start);
    $query .= " date<" . "'" . $constraints['date']['lt'] . "'";
    $start = True;
}

if(isset($constraints['limit'])){
    $query .= " LIMIT " . $constraints['limit'];
}


$query .= " ORDER BY date";
$result = mysqli_query($conn, $query);
if(!$result){
    die("query failed");
}

while ($row = mysqli_fetch_assoc($result)){
    $data[]=$row;
}



?>