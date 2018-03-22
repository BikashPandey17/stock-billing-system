<?php

//view_order.php

if(isset($_GET["pdf"]) && isset($_GET['order_id']))
{
	require_once 'pdf.php';
	include('database_connection.php');
	include('function.php');
	if(!isset($_SESSION['type']))
	{
		header('location:login.php');
	}
	$output = '';
	$statement = $connect->prepare("
		SELECT * FROM inventory_order 
		WHERE inventory_order_id = :inventory_order_id
		LIMIT 1
	");
	$statement->execute(
		array(
			':inventory_order_id'       =>  $_GET["order_id"]
		)
	);
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output .= '
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:4px 6px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:4px 6px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-yw4l{vertical-align:top;}
.tg-yw42{vertical-align:top;text-align:center}
</style>
<table class="tg" style="undefined;table-layout: fixed; width: 800px">
<colgroup>
<col style="width: 42px">
<col style="width: 140px">
<col style="width: 76px">
<col style="width: 60px">
<col style="width: 64px">
<col style="width: 53px">
<col style="width: 66px">
<col style="width: 68px">
<col style="width: 82px">
<col style="width: 42px">
<col style="width: 46px">
<col style="width: 42px">
<col style="width: 44px">
<col style="width: 52px">
<col style="width: 44px">
<col style="width: 79px">
</colgroup>
  <tr>
    <td class="tg-yw4l" colspan="16"  style="font-size:18px"><pre>GSTIN:19AANFM9893J1Z           <b><u>INVOICE</u></pre></b></br><div class="tg-yw42" ><h1 style="text-align:center;">MAA BHAWANI BHANDER</h1><br><p  align="center"><i>All Types of Cosmetics Brush, Playing Cards</i></p><br><h3 align="center">20 & 21, RAM MOHAN MULLICK LANE, KOLKATA - 700 007</h3><br><h3 align="center">PHONE : (S) 2268 1596, MOB. : 9831240264</h3></div></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">Reverse Charge :</td>
    <td class="tg-yw4l" colspan="9">Transportation Mode:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">Invoice No. :  '.$row["inventory_order_id"].'</td>
    <td class="tg-yw4l" colspan="9">Vehicle no. :</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">Invoice Date :  '.$row["inventory_order_date"].'</td>
    <td class="tg-yw4l" colspan="9">Date of Supply</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">State : West Bengal                                            State code : </td>
    <td class="tg-yw4l" colspan="9">Place of Supply</td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="7">Details of reciever (Billed to )</td>
    <td class="tg-baqh" colspan="9">Details of Consignee (Shipped to)</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">Name :  '.$row["inventory_order_name"].'</td>
    <td class="tg-yw4l" colspan="9">Name:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">Address :  '.$row["inventory_order_address"].'</td>
    <td class="tg-yw4l" colspan="9">Address :</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">State:                                                                   State code:</td>
    <td class="tg-yw4l" colspan="9">State:                                                               State code:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="7">GSTIN:</td>
    <td class="tg-yw4l" colspan="9">GSTIN:</td>
  </tr>';
  $statement = $connect->prepare("
     SELECT * FROM inventory_order_product 
     WHERE inventory_order_id = :inventory_order_id
    ");
    $statement->execute(
     array(
       ':inventory_order_id'       =>  $_GET["order_id"]
     )
    );
    $product_result = $statement->fetchAll();
    $count = 0;
    $total = 0;
    $total_actual_amount = 0;
    $total_tax_amount = 0;
      $output .= '<tr>
    <td class="tg-yw4l" rowspan="2">Sr.<br>no.</td>
    <td class="tg-baqh" colspan="2" rowspan="2">Name of Product</td>
    <td class="tg-baqh" rowspan="2">UOM</td>
    <td class="tg-baqh" rowspan="2">Qty.</td>
    <td class="tg-yw4l" rowspan="2">Rate</td>
    <td class="tg-yw4l" rowspan="2" style="font-size:12px">Amount</td>
    <td class="tg-yw4l" rowspan="2" style="font-size:11px">Less <br>Discount</td>
    <td class="tg-yw4l" rowspan="2" style="font-size:12px">Taxable Value</td>
    <td class="tg-baqh" colspan="2">CGST</td>
    <td class="tg-baqh" colspan="2">SGST</td>
    <td class="tg-baqh" colspan="2">IGST</td>
    <td class="tg-yw4l" rowspan="2" style="font-size:12px">TOTAL </td>
  </tr>
  <tr>
    <td class="tg-yw4l">Rate</td>
    <td class="tg-yw4l">Amt.</td>
    <td class="tg-yw4l">Rate</td>
    <td class="tg-yw4l">Amt.</td>
    <td class="tg-yw4l">Rate</td>
    <td class="tg-yw4l">Amt.</td>
  </tr>';
   foreach($product_result as $sub_row)
    {
     $count = $count + 1;
     $product_data = fetch_product_details($sub_row['product_id'], $connect);
     $actual_amount = $sub_row["quantity"] * $sub_row["price"];
     $tax_amount = ($actual_amount * $sub_row["tax"])/100;
     $total_product_amount = $actual_amount + $tax_amount;
     $total_actual_amount = $total_actual_amount + $actual_amount;
     $total_tax_amount = $total_tax_amount + $tax_amount;
     $total = $total + $total_product_amount;
     $output.='
  <tr>
    <td class="tg-yw4l" rowspan="7">'.$count.'</td>
    <td class="tg-yw4l" colspan="2" rowspan="7">'.$product_data['product_name'].'</td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7">'.$sub_row["quantity"].'</td>
    <td class="tg-yw4l" rowspan="7">'.$sub_row["price"].'</td>
    <td class="tg-yw4l" rowspan="7">'.number_format($actual_amount, 2).'</td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7">'.number_format($tax_amount, 2).'</td>
    <td class="tg-yw4l" rowspan="7">'.$sub_row["tax"].'</td>
    <td class="tg-yw4l" rowspan="7">'.number_format($tax_amount, 2).'</td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7"></td>
    <td class="tg-yw4l" rowspan="7">'.number_format($total_product_amount, 2).'</td>
    </tr>
  ';
  };
  $output.='
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>

    <td class="tg-baqh" colspan="4">TOTAL</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
<td class="tg-yw4l" colspan="7" rowspan="3">Total Invoice Amount in Words__________________________________________________</td>
    <td class="tg-yw4l" colspan="8">Total Amount Befofre Tax:</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">Add : CGST</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">Add : SGST</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="7" rowspan="4">Bank Details :</td>
    <td class="tg-yw4l" colspan="8">Add : IGST</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">Tax Amount : GST</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">Total amount after tax</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">GST Payable on REverse Charge</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="7" rowspan="3">Terms And Condition : </td>
    <td class="tg-yw4l" colspan="9">Certified that the particulars given above are true and correct</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="4" rowspan="2">common seal</td>
    <td class="tg-yw4l" colspan="5" rowspan="2">for MAA BHAWANI BHANDER</td>
  </tr>
  <tr>
  </tr>
</table>
</table>';
}
	$pdf = new Pdf();
	$file_name = 'Order-'.$row["inventory_order_id"].'.pdf';
	$pdf->loadHtml($output);
	$pdf->render();
	$pdf->stream($file_name, array("Attachment" => false));
}

?>