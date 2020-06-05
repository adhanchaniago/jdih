<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "scrollX": true,
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('user_akses/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });


    $(".btn-toggle").click(function() {
        $(this).find('.btn').toggleClass('active');  
        if ($(this).find('.btn-primary').size()>0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        if ($(this).find('.btn-danger').size()>0) {
            $(this).find('.btn').toggleClass('btn-danger');
        }
        if ($(this).find('.btn-success').size()>0) {
            $(this).find('.btn').toggleClass('btn-success');
        }
        if ($(this).find('.btn-info').size()>0) {
            $(this).find('.btn').toggleClass('btn-info');
        }
        $(this).find('.btn').toggleClass('btn-default');  
    });

    $("form").submit(function(){
        alert($(this["user_akses_active"]).val());
        return false;
    });

});

function add_user_akses()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('input[name=user_akses_aktif][value=0]').prop('checked', 'checked');
    $('input[name=insert_data][value="N"]').prop('checked', 'checked');
    $('input[name=update_data][value="N"]').prop('checked', 'checked');
    $('input[name=delete_data][value="N"]').prop('checked', 'checked');
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add User Akses'); // Set Title to Bootstrap modal title
}

function edit_user_akses(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('user_akses/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.user_akses_id);
            $('[name="menu_sub_id"]').val(data.menu_sub_id);
            $('[name="user_level_id"]').val(data.user_level_id);
            $('input[name=user_akses_aktif][value='+data.user_akses_aktif+']').prop('checked', 'checked');
            $('input[name=insert_data][value='+data.insert_data+']').prop('checked', 'checked');
            $('input[name=update_data][value='+data.update_data+']').prop('checked', 'checked');
            $('input[name=delete_data][value='+data.delete_data+']').prop('checked', 'checked');        
            
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User Akses'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_user_akses_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('user_akses/ajax_add')?>";
    } else {
        url = "<?php echo site_url('user_akses/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                reload_user_akses_table();
                if (save_method== 'add')
                {
				    //$('#form')[0].reset(); // reset form on modals
                    $('[name="menu_sub_id"]').val("");
				    $('.form-group').removeClass('has-error'); // clear error class
                    $('input[name=user_akses_aktif][value=0]').prop('checked', 'checked');
                    $('input[name=insert_data][value="N"]').prop('checked', 'checked');
                    $('input[name=update_data][value="N"]').prop('checked', 'checked');
                    $('input[name=delete_data][value="N"]').prop('checked', 'checked');
				    $('.help-block').empty(); // clear error string
				    $('.modal-title').text('Add User Akses'); // Set Title to Bootstrap modal title 
				}
				else
				{
					$('#modal_form').modal('hide');
				}                
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_user_akses(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('user_akses/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_user_akses_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Akses Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
		  
                <div class="form-group">
                    <label class="control-label col-md-3" for="user_level_id">Level</label>
                    <div class="col-md-9">              
                        <?php echo $form_user_level; ?>
                        <span class="help-block"></span>
                    </div>
                </div>
                
				<div class="form-group">
					<label class="control-label col-md-3" for="menu_sub_id">Menu</label>
					<div class="col-md-9">				
                    <?php echo $form_menu_sub; ?>
					<span class="help-block"></span>
					</div>
				</div>

    	       <div class="form-group" id="aktif">
                    <label class="control-label col-md-3" for="user_akses_aktif">Aktif</label>
                    <div class="col-md-9">
                        <label class="radio-inline">
                            <input type="radio" name="user_akses_aktif" id="user_akses_aktif" value="1"> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user_akses_aktif" id="user_akses_aktif" value="0"> No
                        </label>
                        <span class="help-block"></span>
                    </div>
                </div>
    
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
		