<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('menu/ajax_list')?>",
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
        alert($(this["menu_active"]).val());
        return false;
    });

});

function add_menu()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('input[name=menu_active][value=0]').prop('checked', 'checked');
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Menu'); // Set Title to Bootstrap modal title
}

function edit_menu(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('menu/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.menu_id);
            $('[name="menu_name"]').val(data.menu_name);
            $('[name="menu_link"]').val(data.menu_link);
            $('[name="menu_class"]').val(data.menu_class);
            $('input[name=menu_active][value='+data.menu_active+']').prop('checked', 'checked');
            $('[name="menu_position"]').val(data.menu_position);
        
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Menu'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_menu_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').html('<span class="glyphicon glyphicon-ok"></span> Saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('menu/ajax_add')?>";
    } else {
        url = "<?php echo site_url('menu/ajax_update')?>";
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
                $('#modal_form').modal('hide');
                reload_menu_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').html('<span class="glyphicon glyphicon-ok"></span> Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').html('<span class="glyphicon glyphicon-ok"></span> Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_menu(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('menu/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_menu_table();
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
                <h3 class="modal-title">Menu Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
		
					<div class="form-group">
						<label class="control-label col-md-3" for="menu_name">Menu</label>
						<div class="col-md-9">
	    				<input placeholder="Menu Name" type="text" name="menu_name" class="form-control span4" size="50" value="<?php echo set_value('menu_name', isset($default['menu_name']) ? $default['menu_name'] : ''); ?>" />
						<span class="help-block"></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3" for="menu_link">Link</label>
						<div class="col-md-9">
	    				<input placeholder="Menu Link" type="text" name="menu_link" class="form-control span4" size="50" value="<?php echo set_value('menu_link', isset($default['menu_link']) ? $default['menu_link'] : ''); ?>" />
						<span class="help-block"></span>
						</div>
					</div>

                    <div class="form-group">
                        <label class="control-label col-md-3" for="menu_class">Class</label>
                        <div class="col-md-9">
                        <input placeholder="fa fa-circle-o" type="text" name="menu_class" class="form-control span4" size="50" value="<?php echo set_value('menu_class', isset($default['menu_class']) ? $default['menu_class'] : ''); ?>" />
                        <span class="help-block"></span>
                        </div>
                    </div>

					    
                <div class="form-group">
                    <label class="control-label col-md-3" for="menu_active">Aktif</label>
                    <div class="col-md-9">
                        <label class="radio-inline">
                        <input type="radio" name="menu_active" id="menu_active" value="1"> Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="menu_active" id="menu_active" value="0"> No
                        </label>
                        <span class="help-block"></span>
                    </div>  
                </div>
	
				<div class="form-group">
					<label class="control-label col-md-3" for="menu_position">Position</label>
					<div class="col-md-9">	
    					<input placeholder="Menu Position" type="number" name="menu_position" class="form-control" size="11" value="<?php echo set_value('menu_position', isset($default['menu_position']) ? $default['menu_position'] : ''); ?>" />
					<span class="help-block"></span>
					</div>
				</div>
				
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-ok"></span> Simpan</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
		