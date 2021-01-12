<?php
	require_once 'config/config.php';
	$process =  $input->post('process');
	switch( $process ){
		case  "SUBCATS_BY_CAT" :
		$category_id = (int)$input->post('category_id');
		$res =  $db->get('item_subcategories',array('category_id' => $category_id));
		header('Content-Type: application/json');
		$return = array(
		  'status'=>'1',
		  'subcategories' =>$res,
		);
		echo json_encode($return); 
		break;
		case  "GET_ITEM_ROW" :
		$item_id = (int)$input->post('item_id');
		$res =  $db->get('items',array('id' => $item_id));
		$item = [];
		$status= '0';
		if (count ( $res ) > 0 ){
			$status = '1';
			$item = $res[0];
		}
		header('Content-Type: application/json');
		$return = array(
		  'status'=>$status,
		  'item' =>$item,
		);
		echo json_encode($return); 
		break;
		case  "DELETE_PURCH_ITEM" :
		$purch_item_id = (int)$input->post('purch_item_id');
		$res =  $db->delete('purchase_items',array('id' => $purch_item_id));
		$status = '0';
		if ( $res ) {
			$status = '1';

		}
		header('Content-Type: application/json');
		$return = array(
		  'status'=>$status,
		);
		echo json_encode($return); 
		break;

		case  "UPDATE_CART" :
			$id = (int)$input->post('cart_id');
			$quantity = (int)$input->post('cart_qty');
			$q ="UPDATE `cart` SET `quantity`= '".$quantity."' WHERE id='".$id."'";
			$status = "0";
			if(  $db->execute( $q ) ){
				$status = '1';
			}
			header('Content-Type: application/json');
			$return = array(
			  'status' => $status,
			);
			echo json_encode($return); 
		break;

		case  "ADD_TO_CART" :
		$alert = "";
		$status = '0';
		$item_id = (int)$input->post('item_id');
		$quantity = (int)$input->post('quantity');
		$customer_id = (int)$session->get_userdata('shop_customer_id');
		$login_status = "1";
		if( $customer_id <= 0) {
			$alert = "Login required !";
			$login_status = "0";
		}else if ( $item_id <= 0 ){
			$alert = "Invalid item !";
		}else if ( $quantity <= 0 ) {
			$alert = "Invalid quantity !";
		}else{
			$cart_res  = $db->get_row ('cart',array('customer_id' => $customer_id, 'item_id' => $item_id));
			if ( $cart_res ) {
				$id = $cart_res->id;
				$q ="UPDATE `cart` SET `quantity`= quantity+".$quantity." WHERE id='".$id."'";
			}else{
				$q ="INSERT INTO `cart`( `customer_id`, `item_id`, `quantity`) VALUES ('".$customer_id."','".$item_id."','".$quantity."')";
			}
			if(  $db->execute( $q ) ){
				$alert = "Item added to cart !";
				$status = '1';
			}
		}
		header('Content-Type: application/json');
		$return = array(
		  'status'=>$status,
		  'alert'=>$alert,
		  'login_status'=>$login_status,
		);
		echo json_encode($return); 
		break;
	}
?>
