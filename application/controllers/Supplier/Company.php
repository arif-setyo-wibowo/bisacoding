<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Supplier');
        $this->load->model('M_User');
        
        $this->load->library('form_validation');  
    }
    
    public function index()
    {
        if (!$this->session->userdata('iduser')) {
            redirect('logout');
         }else{
            $iduser = $this->session->userdata('iduser');
            $user = $this->M_User->get_where($iduser);
            $supplier = $this->M_Supplier->getWhereIdSupplier($iduser);

            if ($supplier[0]->verify == 2) {
                $data = [
                    'supplier' => $this->M_Supplier->getWhereIdSupplier($iduser),
                    'cekdata' => $this->M_Supplier->getWhereIdCompany($supplier[0]->idsupplier),
                    'header' => 'template/v_header_supplier',
                    'footer' => 'template/v_footer_supplier',
                ];
                
                return $this->load->view('supplier/v_profileCompany',$data);
            }else{
                redirect('profil');
            }
        }
    }

    function store(){

        if (empty($_FILES['gambarCompany']['name']))
        {
            $this->form_validation->set_rules('gambarCompany', 'Image');
        }

        $gambar = $_FILES['gambarCompany']['name'];
        $config['upload_path']          = 'uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']        = $gambar;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambarCompany')) {
            $this->session->set_flashdata('pesan_e', 'Gambar gagal diupload');
            redirect('supplier/company');
        } else {
            $data = array(
                'namaPerusahaan' => $this->input->post('company'),
                'alamat' => $this->input->post('alamat'),
                'kota' => $this->input->post('kota'),
                'gambarCompany' => $this->upload->data('file_name'),
                'idsupplier' => $this->input->post('idsupplier')
            );

            $this->M_Supplier->insertCompany($data);
            $this->session->set_flashdata('pesan', 'Update data berhasil');
            redirect('supplier/company');
        }
    }

    function update(){

        $idsupplier = $this->input->post('idsupplier');
        $gambar_lama = $this->input->post('lastgambar');
        $gambar_baru = $_FILES['upgambar']['name'];

        $config['upload_path']          = 'uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']        = $gambar_baru;
        
        $this->load->library('upload', $config);

        if ($gambar_baru == TRUE) {
            if (!$this->upload->do_upload('upgambar') ) {
                $this->session->set_flashdata('pesan_e', 'Gambar gagal diupload');
                redirect('supplier/company');
            } else {
                if (file_exists('uploads/'.$gambar_lama)) {
                    unlink('uploads/'.$gambar_lama);
                    $gmb = $this->upload->data('file_name');
                }else{
                    $gmb = $this->upload->data('file_name');
                }
            }
        }else{
            $gmb = $gambar_lama;
        }

        $data = array(
            'namaPerusahaan' => $this->input->post('ucompany'),
            'alamat' => $this->input->post('ualamat'),
            'kota' => $this->input->post('ukota'),
            'gambarCompany' => $gmb,
            'idsupplier' => $this->input->post('idsupplier')
        );

        $this->M_Supplier->updateCompany($idsupplier,$data);
        $this->session->set_flashdata('pesan', 'Update data berhasil');
        redirect('supplier/company');
    }

    function store_desk() {

        $data = array(
            'deskripsiPendek' => $this->input->post('despen'),
            'deskripsiPanjang' => $this->input->post('despan'),
            'idsupplier' => $this->input->post('idsupplier')

        );

        $this->M_Supplier->insertCompany($data);
        $this->session->set_flashdata('pesan', 'Update data berhasil');
        
        redirect('supplier/company');
    }

    function update_desk(){
        $idsupplier = $this->input->post('idsupplier');

        $data = array(
            'deskripsiPendek' => $this->input->post('udespen'),
            'deskripsiPanjang' => $this->input->post('udespan'),
            'idsupplier' => $this->input->post('idsupplier')
        );

        $this->M_Supplier->updateCompany($idsupplier,$data);
        $this->session->set_flashdata('pesan', 'Update data berhasil');

        redirect('supplier/company');
    }

    function store_sosmed(){

        $data = array(
            'instagram' => $this->input->post('instagram'),
            'facebook' => $this->input->post('facebook'),
            'linkedin' => $this->input->post('linkedin'),
            'website' => $this->input->post('website'),
            'idsupplier' => $this->input->post('idsupplier')
        );

        $this->M_Supplier->insertCompany($data);
        $this->session->set_flashdata('pesan', 'Update data berhasil');

        redirect('supplier/company');
    }

    function update_sosmed(){
        $idsupplier = $this->input->post('idsupplier');

        $data = array(
            'instagram' => $this->input->post('uinstagram'),
            'facebook' => $this->input->post('ufacebook'),
            'linkedin' => $this->input->post('ulinkedin'),
            'website' => $this->input->post('uwebsite'),
            'idsupplier' => $this->input->post('idsupplier')
        );

        $this->M_Supplier->updateCompany($idsupplier,$data);
        $this->session->set_flashdata('pesan', 'Update data berhasil');

        redirect('supplier/company');
    }




}

/* End of file supplier.php */
