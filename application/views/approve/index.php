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
					<h3 class="mb-0"><?= $subtitle ?></h3>
					<p class="text-sm mb-0">
                    <?= $description; ?>, 
					</p>
				</div>

				<div class="table-responsive py-2 px-4">
					<table id="table"
						   data-toggle="table"
						   data-url="approve/getApprove"
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
								<th data-field="nama_progress" data-width="5" data-width-unit="%">Status</th>
								<th data-field="status" data-width="10" data-width-unit="%" data-formatter="statusFormatter">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-header bg-transparent p-0 m-0">
						<div class="text-muted text-center mt-2 mb-3">Berikan Catatan</div>
					</div>
					<div class="card-body px-lg-5 py-lg-2">
						<form id="ff" method="post" enctype="multipart/form-data" class="needs-validation">
							<div class="form-group">
								<input type="text" name="catatan" class="form-control" placeholder="Catatan">
							</div>
							<input id="id_pengajuan" type="text" name="id" class="form-control" placeholder="Catatan" hidden>
							<div class="text-center">
								<button type="submit" class="btn btn-primary my-4">Approve</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-form-acc" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-header bg-transparent p-0 m-0">
						<div class="text-muted text-center mt-2 mb-3">Data Pencairan</div>
					</div>
					<div class="card-body px-lg-5 py-lg-2">
						<form id="ff" method="post" enctype="multipart/form-data" class="needs-validation">
							<div class="form-group">
								<input type="text" name="cair" class="form-control" placeholder="NOMOR PENCAIRAN">
							</div>
							<input id="id_pengajuan" type="text" name="id" class="form-control" placeholder="Catatan" hidden>
							<div class="text-center">
								<button type="submit" class="btn btn-primary my-4">Approve</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function approve(id){
		$('#modal-form').modal('toggle');
		$('#ff').trigger("reset");
		$('#id_pengajuan').val(id);
		url='approve/approve';
	}
	$('#ff').on('submit', function (e) {
	e.preventDefault();
	const string = $('#ff').serialize();
		$.ajax({
			type: "POST",
			url: url,
			data: string,
			success: (result)=>{
				var result = eval('('+result+')');
				console.log('result', result)
				if (result.errorMsg){
					Toast.fire({
					type: 'error',
					title: ''+result.errorMsg+'.'
					})
				}else if(result.openMsg){
					console.log('testttttt');
					$('#modal-form').modal('toggle');	
					$('#modal-form-acc').modal('toggle');	
				}else {
					Toast.fire({
					type: 'success',
					title: ''+result.message+'.'
					})
					$('#modal-form').modal('toggle');		// close the dialog
					$('#table').bootstrapTable('refresh');
				}
			},
		})
	})
    const uang = new Intl.NumberFormat('ID-id', {
        style: 'currency',
        currency: 'IDR'
    });
    function nomerFormatter(value, row, i) {
		return i+1;
	}
    function formatRupiah(val, row){
        // let num = row.jumlah
        // let numFor = 'Rp ' + num.replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1.");
	    return uang.format(row.total);
    };
    function statusFormatter(val, row){
		console.log('row', row)
		if(row.kunci ==1){
			return `
			<div class="col-12 p-0 text-center">
			<div class="row d-flex justify-content-around">
				<button class="btn btn-primary btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Input BKU" onclick="bku('${row.kode_pengajuan}')"><span class="btn-inner--icon"><i class="fa fa-edit"></i></span></button>
				<button class="btn btn-outline-primary btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Track Pengajuan"><span class="btn-inner--icon"><i class="fa fa-poll"></i></span></button>
			</div>
			</div>`
		}
        return `
        <div class="col-12 p-0 text-center">
        <div class="row d-flex justify-content-around">
            <button class="btn btn-primary btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Approve" onclick="approve('${row._id}')"><span class="btn-inner--icon"><i class="fa fa-check"></i></span></button>
            <button class="btn btn-danger btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Tolak" onclick="tolak('${row._id}')"><span class="btn-inner--icon"><i class="fa fa-window-close"></i></span></button>
            <button class="btn btn-outline-primary btn-sm py-0 m-0" data-toggle="tooltip" data-placement="top" title="Track Pengajuan"><span class="btn-inner--icon"><i class="fa fa-poll"></i></span></button>
            <button class="btn btn-success btn-sm py-0 m-0 pengajuan" title="Lihat Pengajuan" onclick="detail('${row.kode_pengajuan}')"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>
        </div>
        </div>`
	}
	function detail(n){
		window.location.replace("approve/detail/"+n);
	}
	function bku(id){
		window.location.replace("approve/bku?id="+id);
	}
</script>