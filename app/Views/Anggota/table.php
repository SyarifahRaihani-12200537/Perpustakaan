<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
            rel="stylesheet" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/agoenxz2186/submitAjax@develop/submit_ajax.js"></script>
<link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<div class="container">
    <button class="float-end btn btn-sm btn-primary" id="btn-tambah">Tambah</button>

    <table id='table-pelanggan' class="datatable table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Depan</th>
                <th>nama Belakang</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Gender</th>
                <th>Foto</th>
                <th>Tanggal Daftar</th>
                <th>Status Aktif</th>
                <th>Berlaku Awal</th>
                <th>Berlaku Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<div id="modalForm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Anggota</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAnggota" method="post" action="<?=base_url('anggota')?>">
                    <input type="hidden" name="id" />
                    <div class="mb-3">
                        <label class="form-label">Nama Depan</label>
                        <input type="text" name="nama_depan" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Belakang</label>
                        <input type="text" name="nama_belakang" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text" name="nohp" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option>Pilih Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Daftar</label>
                        <input type="date" name="tgl_daftar" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Aktif</label>
                        <select name="status_aktif" class="form-control">
                            <option value="A">Aktif</option>
                            <option value="N">Non Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku Awal</label>
                        <input type="date" name="berlaku_awal" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku Akhir</label>
                        <input type="date" name="berlaku_akhir" class="form-control" />
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
        $('form#formAngota').submitAjax({
            pre:()=>{
                $('button#btn-kirim').hide();
            },
            pasca:()=>{
                $('button#btn-kirim').show();
            },
            success:(response, status)=>{
                $("#modalForm").modal('hide');
                $("table#table-pelanggan").DataTable().ajax.reload();
            },
            error:(xhr, status)=>{
                alert('Maaf, data pengguna gagal direkam');
            }
        });

        $('button#btn-kirim').on('click', function(){
            $('form#formAnggota').submit();
        });

        $('button#btn-tambah').on('click', function(){
            $('#modalForm').modal('show');
            $('form#formAnggota').trigger('reset');
        });

        $('table#table-pelanggan').on('click', '.btn-edit',  function(){
            let id = $(this).data('id');
            let baseurl = "<?=base_url()?>";
            $.get(`${baseurl}/anggota/${id}`).done((e)=>{
                $('input[name=id]').val(e.id);
                $('input[name=nama_depan]').val(e.nama_depan);
                $('input[name=nama_belakang]').val(e.namaA-A)
            });
        });
        
        $('table#table-pelanggan').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "<?=base_url('anggota/all')?>",
                method: 'GET'
            },
            columns:[
                { data: 'id', sortable:false, searchable:false,
                  render: (data,type,row,meta)=>{
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                { data: 'nama_depan'},
                { data: 'nama_belakang'},
                { data: 'email'},
                { data: 'nohp'},
                { data: 'alamat'},
                { data: 'kota'},
                { data: 'gender',
                    render: (data, type, meta, row)=>{
                    if(data === 'L'){
                        return 'Laki-Laki';
                    }else if( data === 'P'){
                        return 'Perempuan';
                    }
                    return data;
                  }
                },
                { data: 'foto'},
                { data: 'tgl_daftar'},
                { data: 'status_aktif',
                    render: (data, type, meta, row)=>{
                        if(data === 'A'){
                            return 'Aktif';
                        }else if( data === 'N'){
                            return 'Non Aktif';
                        }
                    }
                },
                { data: 'berlaku_awal'},
                { data: 'berlaku_akhir'},
                { data: 'id',
                    render: (data,type, meta, row)=>{
                    var btnEdit = `<button class='btn-edit' data-id='$(data)'>Edit</button>`;
                    var btnHapus = `<button class='btn-Hapus' data-id='$(data)'>Hapus</button>`;
                    return btnEdit + btnHapus;
                  }
                }
            ]
        });
    });
</script>