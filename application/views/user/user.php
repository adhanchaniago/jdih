    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List <? echo isset($title) ? ucwords(str_replace('_', ' ', $title)) : ''; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php 
            if ($this->session->userdata('user_level_id')!=2){ ?>
		        <button class="btn btn-success btn-sm btn_add" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
		        <button class="btn btn-default btn-sm btn_refresh" onclick="reload_user_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
            
                    <br />
        <br />

        <div class="panel panel-default custom_filter">
            <div class="panel-heading">
                <h3 class="panel-title" >Custom Filter : </h3>
            </div>
            <div class="panel-body">
                <form id="form-filter" class="form-horizontal">
                    
                  <div class="form-group">
                        <label for="instansi" class="col-sm-2 control-label">Instansi</label>
                        <div class="col-sm-4">
                            <?php echo $form_instansi2; ?>
                        </div>
                    </div>
                        
                    <div class="form-group fg-pegawai2">
                        <label for="pegawai" class="col-sm-2 control-label">Pegawai</label>
                        <div class="col-sm-4">
                            <select name="id_pegawai2" id="id_pegawai2" class="form-control"></select>
                        </div>
                    </div>
        
                <div class="form-group">
                        <label for="btn-filter" class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" id="btn-filter" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-filter"></i> Filter</button>
                            <button type="button" id="btn-reset" class="btn btn-default btn-flat btn-sm"><i class="fa fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            <?php } ?>
		        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead>
		                <tr>		
		                  <th>Instansi</th>
                      <th>Pegawai</th>
                      <th>Username</th>
                      <th>Level</th>
                      <th>Aktif</th>
				              <th style="width:125px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            		<tfoot>
            		<tr>
                  <td>Instansi</td>
                  <td>Pegawai</td>
                  <td>Username</td>
                  <td>Level</td>
                  <td>Aktif</td>
					        <td>Action</td>
		           </tr>
		           </tfoot>
		       </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>	
      <!-- /.row -->
    </section>       
	