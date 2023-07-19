<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header">
                    <a href="/pengajuan">&laquo; Kembali ke daftar pengajuan</a>
                </div>
                <div class="card-body">
                    <?= form_open_multipart('/pengajuan/tambah_pengajuan'); ?>
                    <?= csrf_field(); ?>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="judul">Perihal</label>
                                <input type="text" name="judul_pengajuan" id="judul" class="form-control <?= $validation->hasError('judul_pengajuan') ? 'is-invalid' : ''; ?>" value="Surat Keterangan Tanda Lahir<?= old('judul_pengajuan'); ?>" autofocus disabled>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('judul_pengajuan'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="isi">Jelaskan lebih rinci</label>
                                <textarea name="isi_pengajuan" id="isi" cols="30" rows="13" class="form-control <?= $validation->hasError('isi_pengajuan') ? 'is-invalid' : ''; ?>"><?= old('isi_pengajuan'); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('isi_pengajuan'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nama Pengajuan</label>
                                <div class="form-check">
                                    <input class="form-check-input anonym" type="radio" name="nama_pengaju" id="nama_pengaju1" value="anonym" checked>
                                    <label class="form-check-label" for="nama_pengaju">
                                        <span class="text-gray-800">Samarkan (anonym)</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nama_pengaju" id="nama_pengaju2">
                                    <label class="form-check-label" for="nama_pengaju2">
                                        <span class="text-gray-800">Gunakan nama sendiri</span>
                                    </label>
                                </div>
                                <input type="text" class="form-control pengajuan" value="<?= $user['nama']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Upload foto bukti</label>
                                <div class="mb-3">
                                    <p class="mb-0 text-info">Berikut ada beberapa ketentuan yang diperlukan untuk mengajukan Surat Keterangan Tanda Lahir: <br>Surat Tanda Lahir dari Rumah Sakit<br>KTP Orang Tua<br>Kartu Keluarga<br>Surat Nikah Orang Tua.</p>
                                </div>
                                <hr>

                                <input type="file" name="images[]" id="images" class="p-1 form-control <?= $validation->hasError('images') ? 'is-invalid' : ''; ?>" multiple>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('images'); ?>
                                </div>
                                <?= session()->getFlashdata('err-files'); ?>
                            </div>
                        </div>
                        <button class="btn btn-block btn-primary">Tambah Data</button>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $('.pengajuan').hide();
    $('input[type=radio]').click(function() {
        if ($(this).hasClass('anonym')) {
            $('.pengajuan').hide()
        } else {
            $('.pengajuan').show()
        }
    })
</script>
<?= $this->endSection(); ?>
