<?=$this->extend('backend/template')?>

<?=$this->section('content')?>

<div class="container">
    <button class="float-end btn btn-sm btn-primary" id="btn-tambah">Tambah</button>

    <table id='tabel-pelanggan' class="datatable table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Koleksi</th>
                <th>Nomor</th>
                <th>Status tersedia</th>
                <th>Anggota</th>
                <th>Pustakawan</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<?=$this->endSection()?>

<?=$this->Section('script')?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/agoenxz2186/submitAjax@develop/submit_ajax.js">

</script>
<link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


<div id="modalForm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Form Stok Koleksi</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <form id="formStokKoleksi" method="post" action="<?=base_url('stokkoleksi')?>" class="was-validated">
                    <input type="hidden" name="id" />
                    <input type="hidden" name="_method" />
                    <div class="mb-3">
                        <label class="form-label"> Koleksi</label>
                        <select name="koleksi_id" class="form-control" id="selok" required aria-label="select example">
                            <option value=""> Koleksi</option>
                            <?php foreach($koleksi as $kl):?>
                            <option value='<?=$kl['id']?>'><?=$kl['judul']?></option>
                        <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Example invalid select feedback</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor</label>
                        <input type="text" name="nomor" class="form-control" placeholder="Masukan Nomor" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Tersedia</label>
                        <select name="status_tersedia" class="form-control" required aria-label="select example">
                            <option value="">Status Tersedia</option>
                            <option value="A">Ada</option>
                            <option value="P">Pinjam</option>
                            <option value="R">Rusak</option>
                            <option value="H">Hilang</option>
                        </select>
                        <div class="invalid-feedback">Example invalid select feedback</div>
                    </div >
                    <div class="mb-3">
                        <label class="form-label"> Anggota</label>
                        <select name="anggota_id" class="form-control" required aria-label="select example">
                            <option value="">Angota</option>
                            <?php foreach($anggota as $ag):?>
                            <option value='<?=$ag['id']?>'><?=$ag['nama_depan']?></option>
                            <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Example invalid select feedback</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                 <button class="btn btn-success" id="btn-kirim">Kirim</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.selok').select2({width:'100%',
            dropdownParent: $('form#formStokKoleksi')
        });
        
        $('form#formStokKoleksi').submitAjax({
            pre:()=>{
                $('button#btn-kirim').hide();
            },
            pasca:()=>{
                $('button#btn-kirim').show();
            },
            success:(response, status)=>{
                $("modalForm").modal('hide');
                $("table#tabel-pelanggan").DataTable().ajax.reload();
                alert('Data berhasil ditambahkan')
            },
            error: (xhr, status)=>{
                alert('Maaf, data Stok Koleksi gagal direkam');
            }
        });

        $('button#btn-kirim').on('click', function(){
            $('form#formStokKoleksi').submit();
        });

        $('button#btn-tambah').on('click', function(){
            $('#modalForm').modal('show');
            $('form#formStokKoleksi').trigger('reset');
            $('input[name=_method]').val('');
        });

        $('table#tabel-pelanggan').on('click', '.btn-edit', function(){
            let id = $(this).data('id');
            let baseurl = "<?=base_url()?>";
            $.get(`${baseurl}/stokkoleksi/${id}`).done((e)=>{
                $('input[name=id]').val(e.id);
                $('input[name=koleksi]').val(e.koleksi);
                $('input[name=nomor]').val(e.nomor);
                $('input[name=status_tersedia]').val(e.status_tersedia);
                $('input[name=anggota]').val(e.anggota);
                $('input[name=pustakawan]').val(e.pustakawan);
                $('#modalForm').modal('show');
                $('input[name=_method]').val('patch');
            });
        });

        $('table#tabel-pelanggan').on('click', '.btn-hapus', function(){
            let konfirmasi = confirm('Data Stok Koleksi akan dihapus, mau dilanjutkan?');
            if(konfirmasi === true){
                let _id = $(this).data('id');
                let baseurl = "<?=base_url()?>";
                $.post(`${baseurl}/stokkoleksi`, {id:_id, _method:'delete'}).done(function(e){
                    $('table#tabel-pelanggan').DataTable().ajax.reload();
                })
            }
        });

        $('table#tabel-pelanggan').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "<?=base_url('stokkoleksi/all')?>",
                method: 'GET'
            },
            columns:[
                { data: 'id', sortable:false, searchable:false,
                render: (data,type,row,meta)=>{
                return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                { data: 'koleksi'},
                { data: 'nomor'},
                { data: 'status_tersedia',
                    render:(data, type, meta, row)=>{
                    if(data === 'A')
                        return 'Ada';
                    else if( data === 'P'){
                        return 'Pinjam';
                    }
                    else if( data === 'R'){
                        return 'Rusak'
                    }
                    else if( data === 'H'){
                        return 'Hilang'
                    }
                    return data;
                    }
                },
                { data: 'anggota'},
                { data: 'pustakawan'},
                { data: 'id',
                    render: (data,type, meta, row)=>{
                    var btnEdit = `<button class='btn-edit btn-warning' data-id='${data}'>Edit</button>`;
                    var btnHapus = `<button class='btn-hapus btn-danger' data-id='${data}'>Hapus</button>`;
                    return btnEdit + btnHapus;
                    }
                }
            ]
        });
    });
</script>
<?=$this->endSection()?>