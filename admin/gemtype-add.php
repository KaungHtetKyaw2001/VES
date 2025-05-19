<?php require_once('header.php'); ?>
<?php include('uuid.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['gemtype_name'])) {
        $valid = 0;
        $error_message .= "Gem Type can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemtype WHERE gemtype_name=?");
    	$statement->execute(array($_POST['gemtype_name']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Type already exists<br>";
    	}
    }
	if(empty($_POST['gemtype_id'])) {
        $valid = 0;
        $error_message .= "Gem Type ID can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_gemtype WHERE gemtype_id=?");
    	$statement->execute(array($_POST['gemtype_id']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Gem Type ID already exists<br>";
    	}
    }

    if($valid == 1) {

		// Saving data into the main table tbl_gemtype
		$statement = $pdo->prepare("INSERT INTO tbl_gemtype (gemtype_id,gemtype_name) VALUES (?,?)");
		$statement->execute(array($_POST['gemtype_id'],$_POST['gemtype_name']));
	
    	$success_message = 'Gem Type is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Gem Type</h1>
	</div>
	<div class="content-header-right">
		<a href="gemtype.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Gem Type ID <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemtype_id" value="<?php echo gen_uuid();?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Gem Type Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="gemtype_name">
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