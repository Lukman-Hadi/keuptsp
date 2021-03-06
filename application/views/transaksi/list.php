<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0"><?= $title ?></h6>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<!-- Table -->
	<div class="row">
		<div class="col">
			<div class="card">
				<!-- Card header -->
				<div class="card-header">
					<h3 class="mb-0">Daftar Pengajuan</h3>
					<p class="text-sm mb-0">
						This is an exmaple of datatable using the well known datatables.net plugin. This is a minimal setup in order to get started fast.
					</p>
				</div>

				<div class="table-responsive py-2 px-4">
					<table id="table"
						   data-toggle="table"
						   data-url="showAll"
						   data-pagination="true" 
						   data-search="true"
						   data-click-to-select="true"
						   class="table table-sm" 
						   data-side-pagination="server">
						<thead class="thead-light">
							<tr>
								<!-- <th data-checkbox="true" data-width="1" data-width-unit="%"></th> -->
								<th data-field="no" data-formatter="nomerFormatter" data-width="1" data-width-unit="%">No</th>
								<th data-field="kode_pengajuan" data-sortable="true" data-width="15" data-width-unit="%" >Nomor Permohonan</th>
								<th data-field="nama_bidang" data-sortable="true" data-width="10" data-width-unit="%" >Bidang</th>
								<th data-field="nama_user" data-sortable="true" data-width="10" data-width-unit="%" >Pemohon</th>
								<th data-field="total" data-width="15" data-width-unit="%" data-formatter="formatRupiah">Total Permintaan</th>
								<th data-field="status" data-width="5" data-width-unit="%">Status</th>
								<th data-field="status" data-width="5" data-width-unit="%" data-formatter="statusFormatter">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    const uang = new Intl.NumberFormat('ID-id', {
        style: 'currency',
        currency: 'IDR'
    });
    function nomerFormatter(value, row, i) {
		return i+1;
	}
    function formatRupiah(val, row){
        console.log('row', row)
        // let num = row.jumlah
        // let numFor = 'Rp ' + num.replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1.");
	    return uang.format(row.total);
    };
    function statusFormatter(val, row){
        return `
        <div class="col-12 p-0 text-center">
        <div class="row d-flex justify-content-around">
            <button class="btn btn-outline-primary btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Track Pengajuan"><span class="btn-inner--icon"><i class="fa fa-poll"></i></span></button>
            <button class="btn btn-success btn-sm py-0 m-0" title="Lihat Pengajuan"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>
        </div>
        </div>`
    }
</script>