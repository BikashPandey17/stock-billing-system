<?php

//customer_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query .= "
	SELECT * FROM customer 
	INNER JOIN user_details ON user_details.user_id = customer.customer_enter_by
	";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE customer.customer_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR customer.customer_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_details.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY customer_id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$status = '';
	if($row['customer_status'] == 'active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['customer_id'];
	$sub_array[] = $row['customer_name'];
	$sub_array[] = $row['user_name'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="view" id="'.$row["customer_id"].'" class="btn btn-info btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["customer_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["customer_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["customer_status"].'">Delete</button>';
	$sub_array[] = '<button type="button" name="order" id="'.$row["customer_id"].'" class="btn btn-warning btn-xs order">Create Order</button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"			=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"				=>	$data
);

function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM customer");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>