<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM videos WHERE v_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: video.php');
		exit;
	}
}
?>

<?php

	// // Getting photo ID to unlink from folder
	// $statement = $pdo->prepare("SELECT * FROM tbl_page WHERE v_id=?");
	// $statement->execute(array($_REQUEST['id']));
	// $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	// foreach ($result as $row) {
	// 	$vudei = $row['v_url'];
	// }

	// // Unlink the photo
	// if($photo!='') {
	// 	unlink('../assets/uploads/'.$photo);	
	// }

	// Delete from tbl_slider
	$statement = $pdo->prepare("DELETE FROM videos WHERE v_id = ?");
	$statement->execute(array($_REQUEST['id']));

	header('location: video.php');
?>