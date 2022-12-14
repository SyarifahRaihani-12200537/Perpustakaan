<?php

namespace App\Controllers;

use Agoenxz21\Datatables\Datatable;
use App\Controllers\BaseController;
use App\Models\BahasaModel;
use App\Models\KategoriModel;
use App\Models\KlasifikasiModel;
use App\Models\KoleksiModel;
use App\Models\PenerbitModel;
use App\Models\PustakawanModel;
use CodeIgniter\Email\Email;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Message;

class KoleksiController extends BaseController
{
    public function index()
    {
        return view('backend/Koleksi/table', [
            'penerbit'      => (new PenerbitModel())->findAll(),
            'klasifikasi'   => (new KlasifikasiModel())->findAll(),
            'bahasa'        => (new BahasaModel())->findAll(),
            'kategori'      => (new KategoriModel())->findAll(),
            'pustakawan'    => (new PustakawanModel())->findAll()

        ]);
    }

    public function all()
    {

        return (new Datatable( KoleksiModel::view() ))
            ->setFieldFilter(['judul', 'penerbit','penulis','isbn'])
            ->draw();
    }

    public function show($id){
        $r = (new KoleksiModel())->where('id', $id)->first();
        if($r == null)throw PageNotFoundException::forPageNotFound();
        return $this->response->setJSON($r);
    }

    public function store(){
        $pm = new KoleksiModel();

        $pustakawan = session('pustakawan');
        $id = $pm->insert([
                'judul'             => $this->request->getVar('judul'),
                'jilid'             => $this->request->getVar('jilid'),
                'edisi'             => $this->request->getVar('edisi'),
                'penerbit_id'       => $this->request->getVar('penerbit_id'),
                'penulis'           => $this->request->getVar('penulis'),
                'thn_terbit'        => $this->request->getVar('thn_terbit'),
                'klasifikasi_id'    => $this->request->getVar('klasifikasi_id'),
                'juml_halaman'      => $this->request->getVar('juml_halaman'),
                'isbn'              => $this->request->getVar('isbn'),
                'bahasa_id'         => $this->request->getVar('bahasa_id'),
                'stok'              => $this->request->getVar('stok'),
                'eksemplar'         => $this->request->getVar('eksemplar'),
                'kategori_id'       => $this->request->getVar('kategori_id'),
                'pustakawan_id '    => $pustakawan['id']
        ]);
        return $this->response->setJSON(['id' => $id])
                    ->setStatusCode( intval($id) > 0 ? 200 : 406 );
    }

    public function update(){
        $pm     = new KoleksiModel();
        $id     =(int)$this->request->getVar('id');
        if( $pm->find($id) == null)
            throw PageNotFoundException::forPageNotFound();
            
        $pustakawan = session('pustakawan');

         $hasil = $pm->update($id, [
            'judul'             => $this->request->getVar('judul'),
            'jilid'             => $this->request->getVar('jilid'),
            'edisi'             => $this->request->getVar('edisi'),
            'penerbit_id'       => $this->request->getVar('penerbit_id'),
            'penulis'           => $this->request->getVar('penulis'),
            'thn_terbit'        => $this->request->getVar('thn_terbit'),
            'klasifikasi_id'    => $this->request->getVar('klasifikasi_id'),
            'juml_halaman'      => $this->request->getVar('jUml_halaman'),
            'isbn'              => $this->request->getVar('isbn'),
            'bahasa_id'         => $this->request->getVar('bahasa_id'),
            'stok'              => $this->request->getVar('stok'),
            'eksemplar'         => $this->request->getVar('eksemplar'),
            'kategori_id'       => $this->request->getVar('kategori_id'),
            'pustakawan_id '    => $pustakawan['id']
         ]);
         return $this->response->setJSON(['result'=>$hasil]);   
    }

    public function delete(){
        $pm     = new KoleksiModel();
        $id     = $this->request->getVar('id');
        $hasil  = $pm->delete($id);
        return $this->response->setJSON(['result' => $hasil ]);
    }
}
