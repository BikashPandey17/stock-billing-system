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
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:4px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:4px 6px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-cayh{text-align:center;vertical-align:bottom}
.tg .tg-yw4l{vertical-align:top;}
.tg-yw42{vertical-align:top;text-align:center}
.tg .page_break { page-break-before: always; }
</style>
<table class="tg" style="undefined;table-layout: fixed; width: 810px;">

  <tr>
    <td class="tg-yw4l" colspan="20"  style="font-size:18px"><pre>GSTIN:19AANFM9893J1Z           <b><u>TAX INVOICE</u></pre></b></br><div class="tg-yw42" ><h1 style="text-align:center;">MAA BHAWANI BHANDER</h1><br><p  align="center"><i>All Types of Cosmetics Brush, Playing Cards</i></p><br><h3 align="center">20 & 21, RAM MOHAN MULLICK LANE, KOLKATA - 700 007</h3><br><h3 align="center">PHONE : (S) 2268 1596, MOB. : 9831240264</h3></div></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">Reverse Charge :</td>
    <td class="tg-yw4l" colspan="10">Transportation Mode:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">Invoice No. :  '.$row["inventory_order_id"].'</td>
    <td class="tg-yw4l" colspan="10">Vehicle no. :</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">Invoice Date :  '.$row["inventory_order_date"].'</td>
    <td class="tg-yw4l" colspan="10">Date of Supply</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">State : West Bengal State code : </td>
    <td class="tg-yw4l" colspan="10">Place of Supply</td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="10">Details of reciever (Billed to )</td>
    <td class="tg-baqh" colspan="10">Details of Consignee (Shipped to)</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">Name :  '.$row["inventory_order_name"].'</td>
    <td class="tg-yw4l" colspan="10">Name:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">Address :  '.$row["inventory_order_address"].'</td>
    <td class="tg-yw4l" colspan="10">Address :</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">State:  State code:</td>
    <td class="tg-yw4l" colspan="10">State:  State code:</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="10">GSTIN:</td>
    <td class="tg-yw4l" colspan="10">GSTIN:</td>
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
    <th class="tg-yw4l" rowspan="2">Sr.<br>no.</th>
    <th class="tg-baqh" colspan="2" rowspan="2">Name of Product</th>
    <th class="tg-baqh" rowspan="2">HSN</th>
    <th class="tg-baqh" rowspan="2">Qty.</th>
    <th class="tg-yw4l" rowspan="2" colspan="2">Rate</th>
    <th class="tg-yw4l" colspan="2" rowspan="2">Amount</th>
    <th class="tg-yw4l" rowspan="2">Less <br>Disc.</th>
    <th class="tg-yw4l" rowspan="2" colspan="2">Taxable Value</th>
    <th class="tg-baqh" colspan="2">CGST</th>
    <th class="tg-baqh" colspan="2">SGST</th>
    <th class="tg-baqh" colspan="2">IGST</th>
    <th class="tg-yw4l" colspan="2" rowspan="2">TOTAL </th>
  </tr>
  <tr>
    <th class="tg-yw4l">Rate %</th>
    <th class="tg-yw4l">Amt.</th>
    <th class="tg-yw4l">Rate %</th>
    <th class="tg-yw4l">Amt.</th>
    <th class="tg-yw4l">Rate %</th>
    <th class="tg-yw4l">Amt.</th>
  </tr>
  ';
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
    <td class="tg-yw4l">'.$count.'</td>
    <td class="tg-yw4l" colspan="2">'.$product_data['product_name'].'</td>
    <td class="tg-yw4l" ></td>
    <td class="tg-yw4l">'.$sub_row["quantity"].'</td>
    <td class="tg-yw4l" colspan="2">'.$sub_row["price"].'</td>
    <td class="tg-yw4l" colspan="2">'.number_format($actual_amount, 2).'</td>
    <td class="tg-yw4l" ></td>
    <td class="tg-yw4l" colspan="2">'.number_format($tax_amount, 2).'</td>
    <td class="tg-yw4l" style="font-size:13px;">'.$sub_row["tax"].'</td>
    <td class="tg-yw4l" style="font-size:13px;">'.number_format($tax_amount, 2).'</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l" colspan="2">'.number_format($total_product_amount, 2).'</td>
  </tr>';
  };
  $output.='
  <tr>
    <td class="tg-baqh" colspan="4">TOTAL</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2">'.number_format($total_actual_amount, 2).'</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l" colspan="2">'.number_format($total_tax_amount, 2).'</td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2"></td>';
    if($count > 3 ){
    $output.='<td class="tg-yw4l" colspan="2" >'.number_format($total, 2).'<div class="page_break"></div></td>';
  }else{
    $output.='<td class="tg-yw4l" colspan="2" >'.number_format($total, 2).'</td>';
  }
    $output.='</tr>
    <tr>
<td class="tg-yw4l" colspan="9" rowspan="3">Total Invoice Amount in Words_______________________________________<br/><br/>________________________________</td>
    <td class="tg-yw4l" colspan="9">Total Amount Befofre Tax:</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="9">Add : CGST</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="9">Add : SGST</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="9" rowspan="4">Bank Details :<br/><br/><p>INDIAN OVERSEAS BANK<br/>ACC NO : 049702000003084<br/>IFSC CODE : IOBA0000497</p></td>
    <td class="tg-yw4l" colspan="9">Add : IGST</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="9">Tax Amount : GST</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="9">Total amount after tax</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="9">GST Payable on Reverse Charge</td>
    <td class="tg-yw4l" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="9" rowspan="2"><p>Terms And Condition : </p></td>
    <td class="tg-baqh" colspan="11">Certified that the particulars given above are true and correct</td>
  </tr>
  <tr>
    <td class="tg-cayh" colspan="5" rowspan="1">Common Seal</td>
    <td class="tg-baqh" colspan="6" rowspan="1">For <p>MAA BHAWANI BHANDER<br/><br/><br/><br/><br/><br/>Proprietor/Authorised Signatory</p></td>
  </tr>
  
</table>';
}
	$pdf = new Pdf();
	$file_name = 'Order-'.$row["inventory_order_id"].'.pdf';
	$pdf->loadHtml($output);
	$pdf->render();
	$pdf->stream($file_name, array("Attachment" => false));
}

?>