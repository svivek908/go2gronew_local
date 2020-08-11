<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    function __construct() {
        parent::__construct();
        if(!$this->session->has_userdata('go2groadmin_session')){
            redirect('Go2gro_adminlogin');
        }
        $this->load->model('Datatables_model',"tablemodels");
        $logged_user = logged_admin_record();
        /*$this->loggeduserId = $logged_user['id'];
        $this->loggeduserName = $logged_user['name'];*/
    }

    public function index()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $data = array('store_logo' =>'', 'store_name'=>'','store_id'=> '');
        if($this->session->has_userdata('store')) {
          $data['store_id'] =   $this->session->userdata['store'][0]['id'];
          $data['store_logo'] = $this->session->userdata['store'][0]['logo'];
          $data['store_name'] = $this->session->userdata['store'][0]['name'];
        } 
        $this->load->view('admin/product/viewproduct',$data);
        $this->load->view('admin/include/footer');
    }

    //-------------Product list --------------------

    public function getalliteam()
    {
        //-------------------------
        $store_id = $this->session->userdata['store'][0]['id'];
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $column_select = array('it.id', 'it.item_id',"CONCAT(it.item_name,'".' -'."', it.item_size) as item_name",
        'it.item_sdesc', 'it.item_fdesc','it.item_price','it.item_status','it.Sales_tax',
        'IFNULL( ROUND( AVG( irt.rating ) ) , 0 ) AS rating_average','itimg.imageurl AS item_image');
        
        $relation1 = 'irt.item_id = it.item_id';
        $relation2 = 'it.item_image = itimg.image_id';
        $condition = array('it.item_status !=' =>  '2');

        $join_type1 = "RIGHT";
        $join_type2 = "LEFT";
        if($_POST['length'] != -1){
            $limit= $_POST['length'];
            $start = $_POST['start'];
        }

        $column_order = array('it.item_id',null,'it.item_name',null,null,null,'it.item_status'); //set column field database for datatable orderable
        $column_search = array('it.item_id','it.item_name'); //set column field database for datatable searchable 
        $order = array('it.id' => 'desc'); // default order

        $list = $this->tablemodels->get_data_threetables(''.$itemrating_table.' as irt',''.$item_table.' as it',''.$itemimages_table.' as itimg',$column_select,$relation1,$relation2,$join_type1,$join_type2,
	    $condition,$column_search,$column_order,$order,$limit,$start,$group_by = 'it.item_id');
        
        $data = array();
        $no = $_POST['start'];
        //--------search highlight-----
        $pattern=[];
        if($_POST['search']['value']){
            $array_of_words = array($_POST['search']['value']);
            $pattern = '#(?<=^|\C)(' . implode('|', array_map('preg_quote', $array_of_words))
         . ')(?=$|\C)#i';
        }
        foreach ($list as $product) {
            $no++;
            $row = array();
            $row[] = preg_replace($pattern,"<span style='background-color:yellow;'>$1</span>",$product['item_id']);
            $row[] = '<div class="cell-image-list"  data-target=".product-image" data-toggle="modal">
            <div class="cell-img" style="background-image: url('.IMAGE_SHOWURL.$product['item_image']. ')"></div>
            </div>';
            $row[] = preg_replace($pattern,"<span style='background-color:yellow;'>$1</span>",$product["item_name"]);
            $row[] = $product['item_sdesc'];
            $row[] = $product['item_fdesc'];
            $row[] = $product['item_price'];
            $row[] = '<div class="status-pill ' .($product['item_status'] == 0 ? 'green' : 'red') . '" data-title="Complete" id="data3" data-toggle="tooltip"><input type="hidden" id="status" value="' . $product['item_status'] . '"/></div>';
            $row[] = '<a href="'.base_url('Admin/editproduct/'.$product['id']). '" class="edit-item-btn green-bg"> Edit Item </\a><a href="'.base_url('Admin/linking/' .$product['item_id']). '"  class="edit-item-btn blue-bg"> Edit linking </a>';
            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->tablemodels->count_all(''.$item_table.' as it',$condition),
                "recordsFiltered" => $this->tablemodels->count_threetablefiltered(''.$itemrating_table.' as irt',''.$item_table.' as it',''.$itemimages_table.' as itimg',$column_select,$relation1,$relation2,$join_type1,$join_type2,$condition,$column_search,$column_order,$order),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function addproduct()
    {
        $data = array('store_logo' =>'', 'store_name'=>'','store_id'=> '');
        if($this->session->has_userdata('store')) {
            $data['store_id'] =   $this->session->userdata['store'][0]['id'];
            $data['store_logo'] = $this->session->userdata['store'][0]['logo'];
            $data['store_name'] = $this->session->userdata['store'][0]['name'];
        } 
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/product/addproduct',$data);
        $this->load->view('admin/include/footer');
    }

    public function addnewitem(){
        $this->form_validation->set_rules('item_name','Item Name','trim|required');
        $this->form_validation->set_rules('item_sdesc','Item short description','trim|required');
        $this->form_validation->set_rules('item_fdesc','Item full description','trim|required');
        $this->form_validation->set_rules('item_price','Item price','trim|required');
        $this->form_validation->set_rules('Sales_tax','Item tax','trim|required');
        $this->form_validation->set_rules('rackno','rackno','trim|required');
        $this->form_validation->set_rules('item_size','Item size','trim|required');
        $response = array('error' => true);
        if($this->form_validation->run()==true){
            $store_id = $this->input->post('store_id');
            $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
            $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
            $item_status = 0;

            $itemname = $this->input->post('item_name');
            $itemshortdep = $this->input->post('item_sdesc');
            $itemfulldep = $this->input->post('item_fdesc');
            $itemprice = $this->input->post('item_price');
            $saletax = $this->input->post('Sales_tax');
            $rackno = $this->input->post('rackno');
            $item_size = $this->input->post('item_size');
            $fluid_ounce = $this->input->post('fluid_ounce');
            $image = $_FILES['image'];
            $item_id = "ITM" . time() . rand(1000, 9999);
            $data = array('item_id' => $item_id,
            'item_name' => $this->input->post('item_name'),
            'item_sdesc' => $this->input->post('item_sdesc'),
            'item_fdesc' => $this->input->post('item_fdesc'),
            'item_price' => $this->input->post('item_price'),
            'Sales_tax' => $this->input->post('Sales_tax'),
            'mother_cat_id' => $this->input->post('rackno'),
            'item_size' => $this->input->post('item_size'),
            'unitime' => time()
            );
            
            if ($fluid_ounce != -1) {   // If there is value for fluid_ounce then only insert, -1 stands for unlimited
                $data['fluid_ounce'] = $this->input->post('fluid_ounce');
            }
            $chk = $this->Model->get_selected_data('item_name',$item_table,array('item_name' => $this->input->post('item_name')));
            if(!$chk){
                if($this->Model->add($item_table,$data)){
                    $response['error'] = false;
                    $response['message'] = "Product added successfully";

                    $img_count = 0;
                    $allowed_type = "png|jpeg|PNG|JPEG|jpg|JPG";
                    $uploaddir = "./public/upload/item/";
                    for($z = 0; $z < count($image['name']); $z++){
                        $iimage_id = "IMG" . time() . rand(1000, 9999);
                        
                        $_FILES['file']['name']     = $image['name'][$z];
                        $_FILES['file']['type']     = $image['type'][$z];
                        $_FILES['file']['tmp_name'] = $image['tmp_name'][$z];
                        $_FILES['file']['error']     = $image['error'][$z];
                        $_FILES['file']['size']     = $image['size'][$z];
                        
                        $uploaded_img = do_file_upload('file',$uploaddir,$allowed_type);
                        if($uploaded_img['success'] == true){
                            $img_count++;
                            if($this->Model->add($itemimages_table,array('image_id' => $iimage_id,'item_id' => $item_id, 'imageurl' => '/public/upload/item/'.$uploaded_img['done']['file_name'])))
                            {
                                if($img_count==1){
                                    $this->Model->update($item_table,array('item_image' => $iimage_id),array('item_id' => $item_id));
                                }
                            }
                            else{
                                $response['error'] = true;
                                $response['message'] = "Something wrong into image add try again";
                            }
                        }else{
                            $response['error'] = true;
                            $response['message'] = strip_tags($uploaded_img['error']);
                        }
                    }
                }else{
                    $response['message'] = "Something wrong try again";
                }
            }else{
                $response['message'] = "This item  already Exist if any change please update it";
            }
        }else{
            $response['message'] = "Mendetory fields are required.";
        }
        responseJSON($response);
    }

    //----------------Edit product--------------
    public function editproduct($id)
    {
        $data = array('store_logo' =>'', 'store_name'=>'','store_id'=> '');
        if($this->session->has_userdata('store')) {
            $data['store_id'] =   $this->session->userdata['store'][0]['id'];
            $data['store_logo'] = $this->session->userdata['store'][0]['logo'];
            $data['store_name'] = $this->session->userdata['store'][0]['name'];
        }
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/product/editproduct',$data);
        $this->load->view('admin/include/footer');
    }
    
    public function edititem($id)
    {
        $response = array("error" => true,"message" => "No Record Found");
        $store_id = $this->session->userdata['store'][0]['id'];
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $rec = $this->Model->gettwodata(array('p.id','p.item_id','p.item_name', 'p.item_size','p.item_image as primary_imageid',
        'p.item_sdesc','p.item_fdesc','p.item_price','p.item_status','p.Sales_tax',' p.mother_cat_id', 'p.fluid_ounce', 
        'itmg.image_id','itmg.imageurl as item_image'),''.$item_table.' as p',''.$itemimages_table.' as itmg','p.item_image=itmg.image_id',$where=array('p.id' => $id),$order="",$type="",$limit="1");
    
        if(count($rec) > 0){
            $all_imgs = $this->Model->get_selected_data(array('image_id','imageurl'),$itemimages_table,array('item_id' => $rec[0]['item_id']));
            $response["images"] =  $all_imgs;
            $response["item"] = $rec;
            $response["error"] = false;
            $response["message"] = "item list avaliable";
        }
        responseJSON($response);
    }

    public function updateitem(){
        $this->form_validation->set_rules('item_name','Item Name','trim|required');
        $this->form_validation->set_rules('item_sdesc','Item short description','trim|required');
        $this->form_validation->set_rules('item_fdesc','Item full description','trim|required');
        $this->form_validation->set_rules('item_price','Item price','trim|required');
        $this->form_validation->set_rules('Sales_tax','Item tax','trim|required');
        $this->form_validation->set_rules('rackno','rackno','trim|required');
        $this->form_validation->set_rules('item_size','Item size','trim|required');
        $response = array('error' => true);
        if($this->form_validation->run()==true){
            $store_id = $this->session->userdata['store'][0]['id'];
            $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
            $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
            $item_status = 0;
            $item_id = $this->input->post('item_id');
            $itemname = $this->input->post('item_name');
            $itemshortdep = $this->input->post('item_sdesc');
            $itemfulldep = $this->input->post('item_fdesc');
            $itemprice = $this->input->post('item_price');
            $saletax = $this->input->post('Sales_tax');
            $rackno = $this->input->post('rackno');
            $item_size = $this->input->post('item_size');
            $fluid_ounce = $this->input->post('fluid_ounce');
            $image = $_FILES['image'];
            $data = array('item_name' => $this->input->post('item_name'),
            'item_sdesc' => $this->input->post('item_sdesc'),
            'item_fdesc' => $this->input->post('item_fdesc'),
            'item_price' => $this->input->post('item_price'),
            'Sales_tax' => $this->input->post('Sales_tax'),
            'mother_cat_id' => $this->input->post('rackno'),
            'item_size' => $this->input->post('item_size'),
            'unitime' => time()
            );
            
            if ($fluid_ounce != -1) {   // If there is value for fluid_ounce then only insert, -1 stands for unlimited
                $data['fluid_ounce'] = $this->input->post('fluid_ounce');
            }
            $chk = $this->Model->get_selected_data(array('item_name','item_id'),$item_table,array('id' => $item_id));
            
            if(count($chk) > 0){
                $chk_itemname = $this->Model->get_selected_data('item_name',$item_table,array('item_name' =>  $itemname,'id!=' => $item_id));
                if(count($chk_itemname) > 0){
                    $response['message'] = "Item Name used by other item";
                }else{
                    if($this->Model->update($item_table,$data,array('id' => $item_id))){
                        $response['error'] = false;
                        $response['message'] = "Product added successfully";
    
                        $img_count = 0;
                        $allowed_type = "png|jpeg|PNG|JPEG|jpg|JPG";
                        $uploaddir = "./public/upload/item/";
                        for($z = 0; $z < count($image['name']); $z++){
                            $iimage_id = "IMG" . time() . rand(1000, 9999);
                            
                            $_FILES['file']['name']     = $image['name'][$z];
                            $_FILES['file']['type']     = $image['type'][$z];
                            $_FILES['file']['tmp_name'] = $image['tmp_name'][$z];
                            $_FILES['file']['error']     = $image['error'][$z];
                            $_FILES['file']['size']     = $image['size'][$z];
                            
                            $uploaded_img = do_file_upload('file',$uploaddir,$allowed_type);
                            if($uploaded_img['success'] == true){
                                $img_count++;
                                if($this->Model->add($itemimages_table,array('image_id' => $iimage_id,'item_id' => $chk[0]['item_id'], 'imageurl' => '/public/upload/item/'.$uploaded_img['done']['file_name'])))
                                {
                                    /* if($img_count==1){
                                        $this->Model->update($item_table,array('item_image' => $iimage_id),array('item_id' => $item_id));
                                    } */
                                }
                                else{
                                    $response['error'] = true;
                                    $response['message'] = "Something wrong into image add try again";
                                }
                            }else{
                                $response['error'] = true;
                                $response['message'] = strip_tags($uploaded_img['error']);
                            }
                        }
                    }else{
                        $response['message'] = "Something wrong try again";
                    }
                }
            }else{
                $response['message'] = "This item  not Exist.";
            }
        }else{
            $response['message'] = "Mendetory fields are required.";
        }
        responseJSON($response);
    }

    public function delete_image(){
        $item_tbl_id         =      $this->input->post('item_id');
        $image_id        =      $this->input->post('image_id');
        $store_id = $this->session->userdata['store'][0]['id'];
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $response = array("error" => true,"message" => "error");
        $item_id = $this->Model->get_selected_data('item_id',$item_table,array('id' => $item_tbl_id));
        if(count($item_id) > 0){
            $item_id = $item_id[0]['item_id'];
            $exit_img_chk = $this->Model->get_selected_data('*',$itemimages_table,array('image_id' =>$image_id ,'item_id' => $item_id));
            if($exit_img_chk){
                if($this->Model->delete($itemimages_table,array('image_id' =>$image_id ,'item_id' => $item_id)))
                {
                    $response["error"] = false;
                    $response["message"] = "Image Delete Sucessfully";
                }else{
                    $response['error'] = true;
                    $response['message'] = 'Image Not delete Some Error Occured';
                }
            }else{
                $response['error'] = true;
                $response['message'] = 'You Cant Delete Primary Image';
            }
        }
        responseJSON($response);
    }

    public function make_primary(){
        $item_tbl_id         =      $this->input->post('item_id');
        $image_id        =      $this->input->post('image_id');
        $store_id = $this->session->userdata['store'][0]['id'];
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $response = array("error" => true,"message" => "error");

        $item_id = $this->Model->get_selected_data('item_id',$item_table,array('id' => $item_tbl_id));

        if(count($item_id) > 0){
            $item_id = $item_id[0]['item_id'];
            $exit_img_chk = $this->Model->get_selected_data('*',$itemimages_table,array('image_id' =>$image_id ,'item_id' => $item_id));
            if($exit_img_chk){
                if($this->Model->update($item_table,array('item_image' => $image_id),array('item_id' => $item_id)))
                {
                    $response["error"] = false;
                    $response["Imageid"] = $image_id;
                    $response["message"] = "order status update sucessfully";
                }else{
                    $response['error'] = true;
                    $response['message'] = 'image status not update';
                }
            }else{
                $response['error'] = true;
                $response['message'] = 'Image Not Exist For This Item';
            }
        }
        responseJSON($response);
    }

    public function getMothersCategory(){
        $store_id = $this->session->userdata['store'][0]['id'];
        $cat_id = $this->input->post('cat_id');
        $category = $this->Model->get_selected_data(array('id','cat_name as category_name','store_id'),'category',array('store_id' => $store_id));
        $option = '<option value="">Select Category</option>';
        if(count($category) > 0 ){
            foreach($category as $key => $value){
                $slt = "";
                if(isset($cat_id)){
                    if($cat_id == $value['id']){
                        $slt = "selected";
                    }
                    $option.= '<option value="'.$value['id'].'" '.$slt.'>'.$value['category_name'].'</option>';
                }else{
                    $option.= '<option value="'.$value['id'].'">'.$value['category_name'].'</option>';
                }
            }
        }
        echo $option;
    }
    
    public function additemexcel(){
        $response = array();
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes))
        {
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                
                //open uploaded csv file with read only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                $myarr = array();
                //skip first line
                fgetcsv($csvFile);
                //print_r(fgetcsv($csvFile)); die();
                //parse data from csv file line by line
                $count = 0;
                while(($line = fgetcsv($csvFile)) !== FALSE){
                    $store_id = $line[0];
                    $itemname = $line[1];
                    $itemshortdep = $line[2];
                    $itemfulldep = $line[3];
                    $itemprice = $line[4];
                    $saletax = $line[5];
                    $rackno = $line[6]; //category
                    $image = $line[7];
                    $item_size = "";
                    $fluid_ounce = "";
                    //$item_id = "ITM" . time() . rand(1000, 9999);
                    $randomstr = generateRandomString();
                    $item_id = "ITM" . time() .$randomstr;
                    $db->adm_insertitemexcel($item_id, $itemname, $itemshortdep, $itemfulldep, $itemprice, $saletax, $rackno, $image,$item_size, $fluid_ounce,$store_id);
                    $count++;
                }
                echoRespnse(200, array('insert' => $count));
            }
        }
    }
}

?>