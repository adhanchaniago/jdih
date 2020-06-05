
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
        <button class="btn btn-success btn-sm" onclick="add_menu_sub()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
        <button class="btn btn-default btn-sm" onclick="reload_menu_sub_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>		
		<th>Nama </th><th>Link </th><th>Posisi </th><th>Menu</th>
	<th style="width:125px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
	<th>Nama </th><th>Link </th><th>Posisi </th><th>Menu</th>
	<th>Aksi</th>
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