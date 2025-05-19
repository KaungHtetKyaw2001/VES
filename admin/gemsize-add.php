<?php require_once('header.php'); ?>
<?php include('uuid.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['gemsize_name'])) {
        $valid = 0;
        $error_message .= "Gem Size Name can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemsize WHERE gemsize_name=?");
    	$statement->execute(array($_POST['gemsize_name']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Size Name already exists<br>";
    	}
    }
	if(empty($_POST['gemsize_id'])) {
        $valid = 0;
        $error_message .= "Gem Size ID can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemsize WHERE gemsize_id=?");
    	$statement->execute(array($_POST['gemsize_id']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Size ID already exists<br>";
    	}
    }

    if($valid == 1) {

		// Saving data into the main table tbl_gemsize
		$statement = $pdo->prepare("INSERT INTO tbl_gemsize (gemsize_id,gemsize_name) VALUES (?,?)");
		$statement->execute(array($_POST['gemsize_id'],$_POST['gemsize_name']));
	
    	$success_message = 'Gem Size is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Gem Size</h1>
	</div>
	<div class="content-header-right">
		<a href="gemsize.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Gem Size Unique ID <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemsize_id" value="<?php echo gen_uuid();?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Gem Size <span>( in mm )*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemsize_name">
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