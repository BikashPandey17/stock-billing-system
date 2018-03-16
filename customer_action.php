<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO customer (customer_name,customer_address,customer_enter_by) 
		VALUES (:customer_name,:customer_address,:customer_enter_by)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':customer_name'	=>	$_POST["customer_name"],
				':customer_address' =>  $_POST["customer_address"],
				':customer_enter_by'=>	$_SESSION["user_id"],
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Customer Name Added';
		}
	}
		if($_POST['btn_action'] == 'customer_details')
	{
		$query = "
		SELECT * FROM customer 
		INNER JOIN user_details ON user_details.user_id = customer.customer_enter_by 
		WHERE customer.customer_id = '".$_POST["customer_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';
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
			$output .= '
			<tr>
				<td>Customer Name</td>
				<td>'.$row["customer_name"].'</td>
			</tr>
			<tr>
				<td>Customer Address</td>
				<td>'.$row["customer_address"].'</td>
			</tr>
			<tr>
				<td>Enter By</td>
				<td>'.$row["user_name"].'</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM customer WHERE customer_id = :customer_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':customer_id'	=>	$_POST["customer_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['customer_name'] = $row['customer_name'];
			$output['customer_address']=$row['customer_address'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE customer set customer_name = :customer_name,
		customer_address = :customer_address  
		WHERE customer_id = :customer_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':customer_name'	=>	$_POST["customer_name"],
				':customer_address' =>  $_POST["customer_address"],
				':customer_id'		=>	$_POST["customer_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Customer Data Edited';
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';	
		}
		$query = "
		UPDATE customer 
		SET customer_status = :customer_status 
		WHERE customer_id = :customer_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':customer_status'	=>	$status,
				':customer_id'		=>	$_POST["customer_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Customer status change to ' . $status;
		}
	}
}

?>