<?php

require_once 'dbconnect.php';
require_once 'Film.class.php';

$conn = Database::getConnection();

$ROWS_PER_PAGE = 50;

$sql = "SELECT COUNT(*) FROM filmlist";
$result = $conn->query($sql);
$row = $result->fetch_row();
$total_rows = $row[0];
$amount_of_pages = ceil($total_rows / $ROWS_PER_PAGE);

$pagination = "";
for($i = 1; $i <= $amount_of_pages; ++$i) {
    $pagination .= "<li><a href='#' data-page='$i'>$i</a></li>";
}

$sql = "SELECT * FROM filmlist WHERE needs_approval=0 ORDER BY name LIMIT 0,$ROWS_PER_PAGE";
$result = $conn->query($sql);
$tbody = "";

while($row = $result->fetch_assoc()) {
  // TODO
  $tbody .= "";
}

echo json_encode(array("amount_of_pages" => $amount_of_pages, "pagination" => $pagination, "tbody" => $tbody));
