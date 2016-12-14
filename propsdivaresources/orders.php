<?php
session_start();
include 'includes/class.orders.php';
include 'includes/class.downloads.php';
include 'includes/functions.php';

if(!isset($_SESSION['user']) || !$_SESSION['user']->is_login){
	redirect('login.php');
}


?>
<?php render('includes/header');?>

<div id="page-wrapper">
    	
<?php

$order = new Orders;
$downloads = new Downloads;

$cmd = isset($_GET['cmd'])?$_GET['cmd']:'';
$pid = isset($_GET['pid'])?$_GET['pid']:'';


switch($cmd){
case 'view':	
	if($pid == ''){
		header('Location: '.host_url().'/orders.php');
	}
	
	if(isset($_GET['rmf']) && $_GET['rmf']=='1'){	
		$fid = $_GET['fid'];	
		$pid = $_GET['pid'];
		if($downloads->delete($fid)){
			$_SESSION['success_message'] = 'The download file has been deleted.';			
		}		
	}

	$data['order'] = $order->get($pid);	
	if($data['order']){
		$data['order']->downloads = $downloads->get_all($pid);		
	}else{
		redirect('orders.php');
	}

	render('view_order',$data);

	break;
case 'add':	
	$message = '';
	if(isset($_POST['submit'])){			
		$aok = false;		
		$name = trim(htmlentities($_POST['name']));		
		if($name == '' || strlen($name) < 1){		
			$message .= '<div class="alert alert-danger">Please enter order number</div>';		
		}
		if($message == ''){
			$aok = true;
		}
		$param = array(
			'name'=>$name
			);	
		if($aok){
			$lastId = $order->add($param);
			$message = '<div class="alert alert-success">';
			$message .= 'The order has been saved successfully.'.'<br>';
			$message .='Add download files <a href="'.$_SERVER['PHP_SELF'].'?cmd=view&pid='.$lastId.'">here</a>';	
			$message .= '</div>';
		}
	}
	$data['title'] = 'Add New Order';
	$data['message'] = $message;
	render('add_order',$data);
	break;
case 'edit':	
	$message = '';
	if(isset($_POST['submit'])){		
		$aok = false;		
		$name = trim(htmlentities($_POST['name']));	
		$pid = trim(htmlentities($_POST['pid']));	
		if($name == '' || strlen($name) < 1){		
			$message .= '<div class="alert alert-danger">Please enter order number</div>';		
		}
		if($message == ''){
			$aok = true;
		}
		$param = array(
			'pid' => $pid,
			'name'=>$name
			);	
		if($aok){
			if($order->edit($param)){
				redirect('orders.php'.'?cmd=view&pid='.$pid);	
			}								
		}
	}
	
	$data['order'] = $order->get($pid);	
	if($data['order']){
		$data['title'] = 'Edit Order';
		$data['message'] = $message;
		render('edit_order',$data);	
	}else{
		redirect('orders.php');
	}
	break;
case 'delete':
	unset($SESSION['success_message']);
	if($pid == ''){
		header('Location: '.host_url().'/orders.php');
	}	
	$order->delete($pid);	
	redirect('orders.php');
	break;
default:	
	$data['title'] = 'Orders List';
	$data['orders'] = $order->get_all();
	render('list_orders',$data);

}
?>
</div>
<?php render('includes/footer'); ?>
