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
                            Nomor DPA-/DPAL-/DPPA-SKPD 
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= $permohonan->nama_user ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Tahun Anggaran 
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : <?= date('Y',strtotime($permohonan->created_at)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            Jumlah yang diminta 
                        </div>
                        <div class="col-sm-9 col-md-9">
                            : Rp. <?= number_format($permohonan->total,2,',','.') ?> (<?= terbilang($permohonan->total)?> Rupiah)
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
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
                                $jpagu=0;$jtotal=0;$jjumlah=0;$jsisa=0;
                                foreach ($detail as $d){
                                    $sisa = $d->pagu - ($d->jumlah+$d->total);
                                    $pagu = number_format($d->pagu,2,',','.');
                                    $total = number_format($d->total,2,',','.');
                                    $jumlah = number_format($d->jumlah,2,',','.');
                                    $sisaF = number_format($sisa,2,',','.');
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
                                        $jpagu+=$d->pagu;
                                        $jtotal+=$d->total;
                                        $jjumlah+=$d->jumlah;
                                        $jsisa+=$sisa;
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>Rp. <?= number_format($jpagu,2,',','.'); ?></b></td>
                                        <td class="text-right"><b>Rp. <?= number_format($jtotal,2,',','.'); ?></b></td>
                                        <td class="text-right"><b>Rp. <?= number_format($jjumlah,2,',','.'); ?></b></td>
                                        <td class="text-right"><b>Rp. <?= number_format($jsisa,2,',','.'); ?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <button class="btn btn-block btn-primary">
                                <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                                <span class="btn-inner--text">Approve</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>