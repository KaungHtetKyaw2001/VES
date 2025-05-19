<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top level category<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a mid level category<br>";
    }

    if(empty($_POST['ecat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select an end level category<br>";
    }

	if(empty($_POST['g_code'])) {
        $valid = 0;
        $error_message .= "Product name can not be empty<br>";
    }

    if(empty($_POST['g_name'])) {
        $valid = 0;
        $error_message .= "Product name can not be empty<br>";
    }

	if(empty($_POST['p_created_date'])) {
        $valid = 0;
        $error_message .= "Created date can not be empty<br>";
    }

    if(empty($_POST['p_current_price'])) {
        $valid = 0;
        $error_message .= "Current Price can not be empty<br>";
    }

	if(empty($_POST['g_size'])) {
        $valid = 0;
        $error_message .= "Size value can not be empty<br>";
    }

	if(empty($_POST['g_weight'])) {
        $valid = 0;
        $error_message .= "Weight value can not be empty<br>";
    }

    if(empty($_POST['p_qty'])) {
        $valid = 0;
        $error_message .= "Quantity can not be empty<br>";
    }

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a featured photo<br>';
    }


    if($valid == 1) {

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) )
        {
        	$photo = array();
            $photo = $_FILES['photo']["name"];
            $photo = array_values(array_filter($photo));

        	$photo_temp = array();
            $photo_temp = $_FILES['photo']["tmp_name"];
            $photo_temp = array_values(array_filter($photo_temp));

        	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row) {
				$next_id1=$row[10];
			}
			$z = $next_id1;

            $m=0;
            for($i=0;$i<count($photo);$i++)
            {
                $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
		        if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' ) {
		            $final_name1[$m] = $z.'.'.$my_ext1;
                    move_uploaded_file($photo_temp[$i],"../assets/uploads/product_photos/".$final_name1[$m]);
                    $m++;
                    $z++;
		        }
            }

            if(isset($final_name1)) {
            	for($i=0;$i<count($final_name1);$i++)
		        {
		        	$statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo,p_id) VALUES (?,?)");
		        	$statement->execute(array($final_name1[$i],$ai_id));
		        }
            }            
        }

		$final_name = 'product-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );


		//CHECKING GEM CODE CONDITION
		$g_code=$_POST['g_code'];
		$query=$pdo->prepare("SELECT * FROM tbl_product WHERE g_code=?");
		$query->execute([$g_code]);
		$result=$query->rowCount();
		if($result > 0){
			$error_message="<span class='text-danger' style='color:white'>This Gem Code already exists'</span>"	;
		}
		else{
			//Saving data into the main table tbl_product
			$statement = $pdo->prepare("INSERT INTO tbl_product(
				g_code,
				g_name,
				p_name,
				p_product_type,
				p_old_price,
				p_current_price,
				g_size,
				g_weight,
				p_qty,
				p_featured_photo,
				p_description,
				p_short_description,
				p_feature,
				p_condition,
				p_return_policy,
				p_total_view,
				p_is_featured,
				p_is_active,
				p_created_date,
				ecat_id
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$statement->execute(array(
							$_POST['g_code'],
							$_POST['g_name'],
							$_POST['g_name'],
							'Gem',
							$_POST['p_old_price'],
							$_POST['p_current_price'],
							$_POST['g_size'],
							$_POST['g_weight'],
							$_POST['p_qty'],
							$final_name,
							$_POST['p_description'],
							$_POST['p_short_description'],
							$_POST['p_feature'],
							$_POST['p_condition'],
							$_POST['p_return_policy'],
							0,
							$_POST['p_is_featured'],
							$_POST['p_is_active'],
							$_POST['p_created_date'],
							$_POST['ecat_id']
						));

			if(isset($_POST['gemorigin'])) {
			foreach($_POST['gemorigin'] as $value) {
			$statement = $pdo->prepare("INSERT INTO tbl_gproduct_gemorigin (gemorigin_id,p_id) VALUES (?,?)");
			$statement->execute(array($value,$ai_id));
			}
			}

			if(isset($_POST['gemtype'])) {
			foreach($_POST['gemtype'] as $value) {
			$statement = $pdo->prepare("INSERT INTO tbl_gproduct_gemtype (gemtype_id,p_id) VALUES (?,?)");
			$statement->execute(array($value,$ai_id));
			}
			}
				
			$success_message = 'Product is added successfully.';
			}
		}
		//Saving data into the main table tbl_product
	// 	$statement = $pdo->prepare("INSERT INTO tbl_product(
	// 									g_code,
	// 									g_name,
	// 									p_name,
	// 									p_product_type,
	// 									p_old_price,
	// 									p_current_price,
	// 									g_size,
	// 									g_weight,
	// 									p_qty,
	// 									p_featured_photo,
	// 									p_description,
	// 									p_short_description,
	// 									p_feature,
	// 									p_condition,
	// 									p_return_policy,
	// 									p_total_view,
	// 									p_is_featured,
	// 									p_is_active,
	// 									p_created_date,
	// 									ecat_id
	// 								) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	// 	$statement->execute(array(
	// 									$_POST['g_code'],
	// 									$_POST['g_name'],
	// 									$_POST['g_name'],
	// 									'Gem',
	// 									$_POST['p_old_price'],
	// 									$_POST['p_current_price'],
	// 									$_POST['g_size'],
	// 									$_POST['g_weight'],
	// 									$_POST['p_qty'],
	// 									$final_name,
	// 									$_POST['p_description'],
	// 									$_POST['p_short_description'],
	// 									$_POST['p_feature'],
	// 									$_POST['p_condition'],
	// 									$_POST['p_return_policy'],
	// 									0,
	// 									$_POST['p_is_featured'],
	// 									$_POST['p_is_active'],
	// 									$_POST['p_created_date'],
	// 									$_POST['ecat_id']
	// 								));

	// 	if(isset($_POST['gemorigin'])) {
	// 			foreach($_POST['gemorigin'] as $value) {
	// 			$statement = $pdo->prepare("INSERT INTO tbl_gproduct_gemorigin (gemorigin_id,p_id) VALUES (?,?)");
	// 			$statement->execute(array($value,$ai_id));
	// 		}
	// 	}

	// 	if(isset($_POST['gemtype'])) {
	// 			foreach($_POST['gemtype'] as $value) {
	// 			$statement = $pdo->prepare("INSERT INTO tbl_gproduct_gemtype (gemtype_id,p_id) VALUES (?,?)");
	// 			$statement->execute(array($value,$ai_id));
	// 		}
	// 	}
							
    // 	$success_message = 'Product is added successfully.';
    // }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Gem Product</h1>
	</div>
	<div class="content-header-right">
		<a href="gproduct.php" class="btn btn-primary btn-sm">View All</a>
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

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group" style="margin-top:20px;"><h3 class="col-sm-3 text-center"><b>GEM GROUP</b></h3></div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Gem Product Code <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="g_code" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Gem Product Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="g_name" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Created Date <span></span></label>
							<div class="col-sm-4">
								<input type="date" name="p_created_date" class="form-control">
							</div>
						</div>		
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Old Price <br><span style="font-size:10px;font-weight:normal;">(In USD)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_old_price" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Current Price <span>*</span><br><span style="font-size:10px;font-weight:normal;">(In USD)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_current_price" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Size <span>*</span><br><span style="font-size:10px;font-weight:normal;">(In mm)</span></label>
							<div class="col-sm-4">
								<input type="text" name="g_size" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Weight <span>*</span><br><span style="font-size:10px;font-weight:normal;">(In g)</span></label>
							<div class="col-sm-4">
								<input type="text" name="g_weight" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Quantity <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_qty" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Gem Origin</label>
							<div class="col-sm-4">
								<select name="gemorigin[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_gemorigin ORDER BY gemorigin_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['gemorigin_id']; ?>"><?php echo $row['gemorigin_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Gem Type</label>
							<div class="col-sm-4">
								<select name="gemtype[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_gemtype ORDER BY gemtype_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['gemtype_id']; ?>"><?php echo $row['gemtype_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
                        <!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Gem Size</label>
							<div class="col-sm-4">
								<select name="gemsize[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_gemsize ORDER BY gemsize_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['gemsize_id']; ?>"><?php echo $row['gemsize_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div> -->
                        <!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Gem Weight</label>
							<div class="col-sm-4">
								<select name="gemweight[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_gemweight ORDER BY gemweight_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['gemweight_id']; ?>"><?php echo $row['gemweight_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div> -->
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Other Photos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                            <tr>
			                                <td>
			                                    <div class="upload-btn">
			                                        <input type="file" name="photo[]" style="margin-bottom:5px;">
			                                    </div>
			                                </td>
			                                <td style="width:28px;"><a href="javascript:void()" class="Delete btn btn-danger btn-xs">X</a></td>
			                            </tr>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Short Description</label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="30" rows="10" id="editor2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Features</label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Conditions</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Return Policy</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Featured?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<hr>
						<div class="form-group" style="margin-top:20px;"><h3 class="col-sm-3 text-center"><b>MENU GROUP</b></h3></div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Main Menu Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat">
									<option value="">Select Main Menu</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['tcat_id']; ?>"><?php echo $row['tcat_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Sub Menu Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat">
									<option value="">Select Sub Menu</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Last Menu Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat">
									<option value="">Select Last Menu</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Add Product</button>
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</section>

<?php require_once('footer.php'); ?>