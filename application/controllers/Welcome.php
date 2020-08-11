<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		echo "Hii";
		//$this->Model->exiting_update('1','stores','zipcode','168144');
		//$this->Model->exiting_replace('1','stores','zipcode','168144');
	}

	public function self_Tbl_data(){
		/*SELECT A.`id` AS store1_catid, B.`id` AS store13_catid, A.`cat_name` FROM category A, category B WHERE A.`id` <> B.`id` AND A.`cat_name` = B.`cat_name` AND A.`store_id` = 1 AND B.`store_id` = 13 ORDER BY `A`.`id` ASC*/
		
		$table = "category"; $oldstore = "1"; $storenew = "13";

		$sql = "SELECT A.id AS oldstore_catid, B.id AS newstore_catid, A.cat_name FROM $table A, $table B WHERE A.id <> B.id AND A.cat_name = B.cat_name AND A.store_id = $oldstore AND B.store_id = $storenew ORDER BY A.id ASC";

		$data = $this->Model->custom_qry($sql);
		//print_r($data);die;
		$result = array(); $catcount = 0;
		foreach ($data as $key => $value) { 
			$done = $this->Model->update('store13_item',array('mother_cat_id' => $value['newstore_catid']),array('mother_cat_id' => $value['oldstore_catid']));
			if($done){
				$catcount++;
				$result['cat_name'] = $catcount;
			}else{
				echo $value['oldstore_catid']."<br>";
			}
		}
		echo "<pre>";
		print_r($result);
	}

	public function self_subTbl_data(){
		/*SELECT A.sub_id AS store1_subcatid, B.sub_id AS store14_subcatid, A.sub_name FROM subcategory A, subcategory B WHERE A.sub_id <> B.sub_id AND A.sub_name = B.sub_name AND A.`cat_id` IN(SELECT id FROM `category` WHERE store_id =1 ) AND B.`cat_id` IN(SELECT id FROM `category` WHERE store_id =14 ) GROUP BY A.sub_id ORDER BY A.sub_id ASC*/
		$oldstore = "1"; $newstore = "13";

		$sql = "SELECT A.sub_id AS old_subcatid, B.sub_id AS new_subcatid, A.sub_name FROM subcategory A, subcategory B WHERE A.sub_id <> B.sub_id AND A.sub_name = B.sub_name AND A.`cat_id` IN(SELECT id FROM `category` WHERE store_id = $oldstore ) AND B.`cat_id` IN(SELECT id FROM `category` WHERE store_id = $newstore) GROUP BY A.sub_id ORDER BY A.sub_id ASC";

		$data = $this->Model->custom_qry($sql);
		echo "<pre>";
		print_r($data);die;
		$result = array(); $catcount = 0;
		foreach ($data as $key => $value) { 
			$done = $this->Model->update('store13_itemlink',array('subcat_id' => $value['new_subcatid']),array('subcat_id' => $value['old_subcatid']));
			if($done){
				$catcount++;
				$result['cat_name'] = $catcount;
			}else{
				echo $value['old_subcatid']."<br>";
			}
		}
		echo "<pre>";
		print_r($result);
	}
	// public function self_subTbl_data(){
	// 	/*SELECT A.sub_id AS store1_subcatid, B.sub_id AS store14_subcatid, A.sub_name FROM subcategory A, subcategory B WHERE A.sub_id <> B.sub_id AND A.sub_name = B.sub_name AND A.`cat_id` IN(SELECT id FROM `category` WHERE store_id =1 ) AND B.`cat_id` IN(SELECT id FROM `category` WHERE store_id =14 ) GROUP BY A.sub_id ORDER BY A.sub_id ASC*/
	// 	$oldstore = "1"; $newstore = "13";

	// 	$sql = "SELECT A.sub_id AS old_subcatid, B.sub_id AS new_subcatid, A.sub_name FROM subcategory A, subcategory B WHERE A.sub_id <> B.sub_id AND A.sub_name = B.sub_name AND A.`cat_id` IN(SELECT id FROM `category` WHERE store_id = $oldstore ) AND B.`cat_id` IN(SELECT id FROM `category` WHERE store_id = $newstore) GROUP BY A.sub_id ORDER BY A.sub_id ASC";

	// 	$data = $this->Model->custom_qry($sql);
	// 	echo "<pre>";
	// 	print_r($data);die;
	// 	$result = array(); $catcount = 0;
	// 	foreach ($data as $key => $value) { 
	// 		$done = $this->Model->update('store13_itemlink',array('subcat_id' => $value['new_subcatid']),array('subcat_id' => $value['old_subcatid']));
	// 		if($done){
	// 			$catcount++;
	// 			$result['cat_name'] = $catcount;
	// 		}else{
	// 			echo $value['old_subcatid']."<br>";
	// 		}
	// 	}
	// 	echo "<pre>";
	// 	print_r($result);
	// }
	public function table(){
		$sql="SELECT `item_price`,`item_id` FROM  store1_item";
		$data = $this->Model->custom_qry($sql);
		
		$catcount = 0;$result = array();
		foreach ($data as $key => $value) { 
			//echo "<pre>";
		//print_r($value);die;
			$done = $this->Model->update('store1_item',array('item_go2gro_price' => $value['item_price']),array('item_id' => $value['item_id']));
			//print$done;die;
			if($done){
				$catcount++;
				$result['cat_name'] = $catcount;
			}else{
				echo $value['ttt']."<br>";
			}
		}
		echo"bye";die;
	}

	/*public function changetbleid(){
		$this->Model->transstart();
		try {
			$prefix = "ITM";
	        $count = 0; $link_count = 0; $imgtbl_count = 0;
	        $data = $this->Model->get_selected_data('item_id','store14_item');
	        $itemcount = count($data);
	        $batches = round($itemcount / 1000); // Number of while-loop calls - around 120.
			for ($i = 0; $i <= $batches; $i++) {
			 	$offset = $i * 1000; // MySQL Limit offset number
			  	$data = $this->Model->get_selected_data('item_id','store14_item',$where='',$order='',$type='',$limit=1000,$start=$offset);
			  	
			  	foreach ($data as $key => $value) { 
		        	$randomstr = rand(1000, 9999);
		        	$id = $prefix . time() .$randomstr;
					$done = $this->Model->update('store14_item',array('item_id' => $id),array('item_id' => $value['item_id']));
					if($done){
						$count++;
						$this->Model->add('test_record',array('name' => $value['item_id']));
					}
				}
			}
			$this->Model->transcommit();
		} catch (Exception $e) {
			$this->Model->transrollback();
			echo $e;
		}
		

  //       foreach ($data as $key => $value) { 
  //       	//$randomstr = generateRandomString();
  //       	$randomstr = rand(1000, 9999);
  //       	$id = $prefix . time() .$randomstr;
		// 	$done = $this->Model->update('store14_item',array('item_id' => $id),array('item_id' => $value['item_id']));
		// 	if($done){
		// 		$count++;
		// 		$linktbl = $this->Model->update('store14_itemlink',array('item_id' => $id),array('item_id' => $value['item_id']));
		// 		if($linktbl){
		// 			$link_count++;
		// 			$imgtbl = $this->Model->update('store14_items_images',array('item_id' => $id),array('item_id' => $value['item_id']));
		// 			if($imgtbl){
		// 				$imgtbl_count++;
		// 			}
		// 		}
		// 	}
		// }
		echo $count . " @ ". $link_count . " @ ". $imgtbl_count ."<br>";
	}*/

}
