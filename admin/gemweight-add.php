<?php require_once('header.php'); ?>
<?php include('uuid.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['gemweight_name'])) {
        $valid = 0;
        $error_message .= "Gem Weight Name can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemweight WHERE gemweight_name=?");
    	$statement->execute(array($_POST['gemweight_name']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Weight Name already exists<br>";
    	}
    }
	if(empty($_POST['gemweight_id'])) {
        $valid = 0;
        $error_message .= "Gem Weight ID can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemweight WHERE gemweight_id=?");
    	$statement->execute(array($_POST['gemweight_id']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Weight ID already exists<br>";
    	}
    }

    if($valid == 1) {

		// Saving data into the main table tbl_gemweight
		$statement = $pdo->prepare("INSERT INTO tbl_gemweight (gemweight_id,gemweight_name) VALUES (?,?)");
		$statement->execute(array($_POST['gemweight_id'],$_POST['gemweight_name']));
	
    	$success_message = 'Gem Weight is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Gem Weight</h1>
	</div>
	<div class="content-header-right">
		<a href="gemweight.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Gem Weight Unique ID <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemweight_id" value="<?php echo gen_uuid();?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Gem Weight Name <span>( in Ct )*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemweight_name">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>