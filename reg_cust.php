<?php
//reg_cust.php

include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
    header('location:login.php');
}

include('header.php');


?>
    <link rel="stylesheet" href="css/datepicker.css">
    <script src="js/bootstrap-datepicker1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <script>
    $(document).ready(function(){
        $('#inventory_order_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });
    </script>
        <span id='alert_action'></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">Customer List</h3>
                            </div>
                        
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
                                <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="customer_data" class="table table-bordered table-striped">
                                <thead><tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Enter By</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr></thead>
                            </table>
                        </div></div>
                    </div>
                </div>
			</div>
		</div>

        <div id="customerModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="customer_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Add Customer</h4>
                        </div>
                        <div class="modal-body">
                        <label>Enter Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required />
                        <label>Enter Customer Address</label>
                        <textarea name="customer_address" id="customer_address" class="form-control" required></textarea>
                        <label>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="customer_id" id="customer_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="customerdetailsModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="customer_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Customer Details</h4>
                        </div>
                        <div class="modal-body">
                            <Div id="customer_details"></Div>
                        </div>
                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="orderModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="order_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Create Order</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Enter Receiver Name</label>
                                    <input type="text" name="inventory_order_name" id="inventory_order_name" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" name="inventory_order_date" id="inventory_order_date" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Enter Receiver Address</label>
                            <textarea name="inventory_order_address" id="inventory_order_address" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Enter Product Details</label>
                            <hr />
                            <span id="span_product_details"></span>
                            <hr />
                        </div>
                        <div class="form-group">
                            <label>Select Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="inventory_order_id" id="inventory_order_id" />
                        <input type="hidden" name="btn_action" id="btn_action" value="Add"/>
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    </div>
                </div>
            </form>
        </div>

    </div>

<script type="text/javascript">
$(document).ready(function(){

    $('#add_button').click(function(){
        $('#customerModal').modal('show');
        $('#customer_form')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Customer");
        $('#action').val('Add');
        $('#btn_action').val("Add");
    });

    $(document).on('submit','#customer_form', function(event){
        event.preventDefault();
        $('#action').attr('disabled','disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url:"customer_action.php",
            method:"POST",
            data:form_data,
            success:function(data)
            {
                $('#customer_form')[0].reset();
                $('#customerModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                $('#action').attr('disabled', false);
                customerdataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.view', function(){
        var customer_id = $(this).attr("id");
        var btn_action = 'customer_details';
        $.ajax({
            url:"customer_action.php",
            method:"POST",
            data:{customer_id:customer_id, btn_action:btn_action},
            success:function(data){
                $('#customerdetailsModal').modal('show');
                $('#customer_details').html(data);
            }
        })
    });

    $(document).on('click', '.update', function(){
        var customer_id = $(this).attr("id");
        var btn_action = 'fetch_single';
        $.ajax({
            url:"customer_action.php",
            method:"POST",
            data:{customer_id:customer_id, btn_action:btn_action},
            dataType:"json",
            success:function(data)
            {
                $('#customerModal').modal('show');
                $('#customer_id').val(data.customer_id);
                $('#customer_name').val(data.customer_name);
                $('#customer_address').val(data.customer_address);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Customer");
                $('#customer_id').val(customer_id);
                $('#action').val('Edit');
                $('#btn_action').val("Edit");
            }
        })
    });

    var customerdataTable = $('#customer_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"customer_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[4,5,6,7],
                "orderable":false,
            },
        ],
        "pageLength": 25
    });
    $(document).on('click', '.delete', function(){
        var customer_id = $(this).attr('id');
        var status = $(this).data("status");
        var btn_action = 'delete';
        if(confirm("Are you sure you want to change status?"))
        {
            $.ajax({
                url:"customer_action.php",
                method:"POST",
                data:{customer_id:customer_id, status:status, btn_action:btn_action},
                success:function(data)
                {
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                    customerdataTable.ajax.reload();
                }
            })
        }
        else
        {
            return false;
        }
    });
    $(document).on('click','.order',function(){
        var customer_id = $(this).attr('id');
        var status = $(this).data("status");
        var btn_action="add_customer";
        $.ajax({
            url:"customer_action.php",
                method:"POST",
                data:{customer_id:customer_id, status:status, btn_action:btn_action},
                dataType:"json",
                success:function(data){
                    $('#orderModal').modal('show');
                    $('#order_form')[0].reset();
                    $('.modal-title').html("<i class='fa fa-plus'></i> Create Order");
                    $('#inventory_order_name').val(data.customer_name);
                    $('#inventory_order_address').val(data.customer_address);
                    $('#span_product_details').html('');
                    add_product_row();
                    $('#action').attr('Add');
                    $('#btn_action').val('Add');
                }
        });

    });
    
    function add_product_row(count = '')
        {
            var html = '';
            html += '<span id="row'+count+'"><div class="row">';
            html += '<div class="col-md-8">';
            html += '<select name="product_id[]" id="product_id'+count+'" class="form-control selectpicker" data-live-search="true" required>';
            html += '<?php echo fill_product_list($connect); ?>';
            html += '</select><input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" />';
            html += '</div>';
            html += '<div class="col-md-3">';
            html += '<input type="text" name="quantity[]" class="form-control" required />';
            html += '</div>';
            html += '<div class="col-md-1">';
            if(count == '')
            {
                html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
            }
            else
            {
                html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
            }
            html += '</div>';
            html += '</div></div><br /></span>';
            $('#span_product_details').append(html);

            $('.selectpicker').selectpicker();
        }

        var count = 0;

        $(document).on('click', '#add_more', function(){
            count = count + 1;
            add_product_row(count);
        });
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            $('#row'+row_no).remove();
        });

       
        $(document).on('submit', '#order_form', function(event){
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"order_action.php",
                method:"POST",
                data:form_data,
                success:function(data){
                    $('#order_form')[0].reset();
                    $('#orderModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    $('#action').attr('disabled', false);
                    customerdataTable.ajax.reload();
                }
            });
        });
});
</script>
<?php 
include('footer.php');
?>
