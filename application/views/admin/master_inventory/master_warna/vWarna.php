<div>
	<div class="card">
        <div class="card-header">
        	<div class="row">
        		<div class="col-6">
           			<h5 id="textHeader" class="mt-2"></h5>
        		</div>
        		<div class="col-6">
        			<div class="float-right">
            			<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="tambahWarna()"><i class="fa fa-plus text-white"></i> Tambah Warna</a>
            			<a href="javascript:void(0)" onclick="reloadData()" class="btn btn-primary btn-sm"><i class="fa fa-refresh text-white"></i> Reload</a>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="card-block">
        	<form id="formWarna" style="display: none;">
        		<? $this->load->view('admin/master_inventory/master_warna/vWarnaForm'); ?>
        	</form>
            <div class="table-responsive">
                <table class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="30%">Kode Warna</th>
                            <th width="50%">Warna</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tb_warna"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?=base_url('assets/pages/notification/notification.css')?>">
<script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap-growl.min.js"></script>
<script src="<?=base_url('assets/custom_js/notification.custom.js')?>"></script>    
<!--  -->
<script type="text/javascript" src="<?=base_url('assets/custom_js/warna.custom.index.js')?>"></script>
