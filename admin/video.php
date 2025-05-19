<?php
require_once('header.php');
?>

<?php
if(isset($_POST['form_specialitem'])) {
    
    $valid = 1;

    if($valid == 1) {

            // updating the database
            $statement = $pdo->prepare("INSERT INTO `videos`(`v_url`,`v_date`,`v_uni_no`) VALUES(?,?,?)");
            $statement->execute(array($_POST['video_link'],$_POST['video_upload_date'],$_POST['video_unique_no']));


        $success_message = 'Special Item Page Information is inserted successfully.';
        
    }
    
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Special Items - Videos</h1>
    </div>
</section>

<div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-hover table-striped">
			<thead>
			    <tr>
                    <th>No.</th>
			        <th>Video ID</th>
			        <th>Video Link</th>
                    <th>Video Upload Date</th>
                    <th>Video Unique Number</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM videos ORDER BY v_id ASC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['v_id']; ?></td>
                        <td><?php echo $row['v_url']; ?></td>
                        <td><?php echo $row['v_date']; ?></td>
                        <td><?php echo $row['v_uni_no']; ?></td>
	                    <td>
	                        <a href="video-edit.php?id=<?php echo $row['v_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
                            <a href="video-delete.php?id=<?php echo $row['v_id']; ?>" class="btn btn-danger btn-xs">Delete</a>
	                        <!-- <a href="#" class="btn btn-danger btn-xs" data-href="video-delete.php?id=<?php echo $row['v_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a> -->
	                    </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>

                        <div class="tab-pane" id="tab_6">
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <!-- <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video ID * </label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="video_id">
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video Link * </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="video_link" ></textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Existing Banner Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <img src="../assets/uploads/<?php echo $specialitem_banner; ?>" class="existing-photo" style="height:80px;">
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">New Banner Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <input type="file" name="specialitem_banner">
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video Upload Date</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="date" name="video_upload_date">
                                        </div>
                                    </div>             
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video Unique Number </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="video_unique_no" style="height:100px;"></textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Meta Description </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="specialitem_meta_description" style="height:100px;"><?php echo $specialitem_meta_description; ?></textarea>
                                        </div>
                                    </div>                                     -->
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form_specialitem">Insert</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>

<?php require_once('footer.php'); ?>