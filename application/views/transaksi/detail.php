<?php if (isset($approve) && $approve) {
    $cek = canApproveCheck();
    if(!(in_array($permohonan->status,$cek))){
        redirect(base_url());
    };
} ?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Permohonan NPD No : <?= $permohonan->kode_pengajuan ?></h6><br>
                    <h6 class="h2 text-white d-inline-block mb-0">Status Pengajuan : <?= $permohonan->nama_progress ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Diajukan Oleh
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $permohonan->nama_user ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Bidang
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $permohonan->nama_bidang ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Program
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $detail[0]->nama_program ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Kegiatan
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $detail[0]->nama_kegiatan ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Sub Kegiatan
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $detail[0]->nama_sub ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Tahun Anggaran
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= date('Y', strtotime($permohonan->created_at)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Jumlah yang diminta
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : Rp. <?= number_format($permohonan->total, 2, ',', '.') ?> (<?= terbilang($permohonan->total) ?> Rupiah)
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center table-hover table-sm">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th width="1%">No</th>
                                    <th width="10%">Kode Rekening</th>
                                    <th>Uraian</th>
                                    <th width="10%">Anggaran</th>
                                    <th width="10%">Akumulasi Pencairan</th>
                                    <th width="10%">Pencairan Saat Ini</th>
                                    <th width="10%">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $jpagu = 0;
                                $jtotal = 0;
                                $jjumlah = 0;
                                $jsisa = 0;
                                foreach ($detail as $d) {
                                    $sisa = $d->pagu - ($d->jumlah + $d->total);
                                    $pagu = number_format($d->pagu, 2, ',', '.');
                                    $total = number_format($d->total, 2, ',', '.');
                                    $jumlah = number_format($d->jumlah, 2, ',', '.');
                                    $sisaF = number_format($sisa, 2, ',', '.');
                                    echo "<tr>
                                    <td>
                                        $no
                                    </td>
                                    <td>
                                        $d->kode_rekening
                                    </td>
                                    <td>
                                        $d->nama_rekening
                                    </td>
                                    <td class='text-right'>
                                        Rp. $pagu
                                    </td>
                                    <td class='text-right'>
                                        Rp. $total
                                    </td>
                                    <td class='text-right'>
                                        Rp. $jumlah
                                    </td>
                                    <td class='text-right'>
                                        Rp. $sisaF
                                    </td>
                                </tr>";
                                    $no++;
                                    $jpagu += $d->pagu;
                                    $jtotal += $d->total;
                                    $jjumlah += $d->jumlah;
                                    $jsisa += $sisa;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>Rp. <?= number_format($jpagu, 2, ',', '.'); ?></b></td>
                                    <td class="text-right"><b>Rp. <?= number_format($jtotal, 2, ',', '.'); ?></b></td>
                                    <td class="text-right"><b>Rp. <?= number_format($jjumlah, 2, ',', '.'); ?></b></td>
                                    <td class="text-right"><b>Rp. <?= number_format($jsisa, 2, ',', '.'); ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <div class="text-center">
                        <p>Detail Pengajuan</p>
                    </div>
                    <div class="table-responsive">
                        <table id="table" data-toolbar="#toolbar" data-toggle="table" data-url="/npd/approve/getDetail/<?= $permohonan->kode_pengajuan ?>" data-pagination="false" data-search="false" data-click-to-select="false" class="table table-flush" data-show-footer="true" data-group-by="true" data-group-by-field="nama_rekening" data-side-pagination="client">
                            <thead class="thead-light text-center" style="white-space: normal;word-wrap:break-word;">
                                <tr>
                                    <!-- <th data-field="no" data-formatter="nomerFormatter" data-width="5" data-width-unit="%">No</th> -->
                                    <th data-field="kode_program" data-width="5" data-width-unit="%" rowspan="2" data-valign="middle">Kode Program</th>
                                    <th data-field="kode_kegiatan" data-width="5" data-width-unit="%" rowspan="2" data-valign="middle">Kode Kegiatan</th>
                                    <th data-field="kode_sub" data-width="5" data-width-unit="%" rowspan="2" data-valign="middle">Kode Sub Kegiatan</th>
                                    <th data-field="kode_rekening" data-width="5" data-width-unit="%" rowspan="2" data-valign="middle">Kode Rekening Kegiatan</th>
                                    <th data-field="nama_rekening" data-width="10" data-width-unit="%" rowspan="2" data-valign="middle">Nama Rekening Kegiatan</th>
                                    <th colspan="4">Item Detail</th>
                                    <th data-field="jumlah" data-width="10" data-width-unit="%" data-formatter="formatRupiah" data-footer-formatter="footerJumlah" rowspan="2" data-valign="middle">Jumlah Pengajuan</th>
                                </tr>
                                <tr>
                                    <th data-field="keterangan" data-width="10" data-width-unit="%">Keterangan</th>
                                    <th data-field="satuan" data-width="10" data-width-unit="%">Satuan</th>
                                    <th data-field="harga" data-width="10" data-formatter="formatRupiah" data-width-unit="%">Harga</th>
                                    <th data-field="total" data-width="10" data-width-unit="%">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                    <?php if (isset($approve) && $approve) {
                        echo '<div class="row">';
                        echo '<div class="col-12 col-sm-12 col-md-6"><button class="btn btn-primary btn-block" onclick="approve('.$permohonan->_id.')">Approve</button></div>';
                        echo '<div class="col-12 col-sm-12 col-md-6"><button class="btn btn-danger btn-block"onclick="reject('.$permohonan->_id.')">Reject</button></div>';
                        echo '</div>';
                    } ?>
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
								<button type="submit" class="btn btn-info my-4"><span id="inrbtn"></span></button>
							</div>
						</form>
					</div>
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
    function approve(id){
        console.log('id', id)
        $('#modal-form').modal('toggle');
        $('#ff').trigger("reset");
        $('#id_pengajuan').val(id);
        $('#inrbtn').text('Approve');
        url = '../approve/approve';
    }
    function reject(id) {
        console.log('id', id)
        $('#modal-form').modal('toggle');
        $('#ff').trigger("reset");
        $('#id_pengajuan').val(id);
        $('#inrbtn').text('Reject');
        url = '../approve/reject';
    }
    $('#ff').on('submit', function(e) {
        e.preventDefault();
        const string = $('#ff').serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: string,
            success: (result) => {
                var result = eval('(' + result + ')');
                console.log('result', result)
                if (result.errorMsg) {
                    Toast.fire({
                        type: 'error',
                        title: '' + result.errorMsg + '.'
                    })
                } else if (result.openMsg) {
                    console.log('testttttt');
                    $('#modal-form').modal('toggle');
                    $('#modal-form-acc').modal('toggle');
                } else {
                    Toast.fire({
                        type: 'success',
                        title: '' + result.message + '.'
                    })
                    $('#modal-form').modal('toggle'); // close the dialog
                    $('#table').bootstrapTable('refresh');
                    window.location.replace('../../');
                }
            },
        })
    })

    function formatRupiah(val, row) {
        console.log('row', val)
        return uang.format(val);
    };

    function footerJumlah(data, footerValue) {
        console.log('data', data)
        console.log('footerValue', footerValue)
        let sum = 0;
        data.map((e) => {
            sum += parseInt(e.jumlah, 10);
            console.log('e.jumlah', e.jumlah)
        })
        return uang.format(sum);
    };
</script>