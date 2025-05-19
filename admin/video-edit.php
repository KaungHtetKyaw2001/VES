<?php 
require_once('header.php'); ?>

<?php

if(isset($_POST['form_specialitem'])) {
    
    $valid = 1;

    // if(empty($_POST['specialitem_title'])) {
    //     $valid = 0;
    //     $error_message .= 'Title can not be empty<br>';
    // }

    // if(empty($_POST['specialitem_content'])) {
    //     $valid = 0;
    //     $error_message .= 'Content can not be empty<br>';
    // }

    // $path = $_FILES['specialitem_banner']['name'];
    // $path_tmp = $_FILES['specialitem_banner']['tmp_name'];

    // if($path != '') {
    //     $ext = pathinfo( $path, PATHINFO_EXTENSION );
    //     $file_name = basename( $path, '.' . $ext );
    //     if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
    //         $valid = 0;
    //         $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
    //     }
    // }

    if($valid == 1) {

        // if($path != '') {
        //     // // removing the existing photo
        //     // $statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
        //     // $statement->execute();
        //     // $result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
        //     // foreach ($result as $row) {
        //     //     $specialitem_banner = $row['specialitem_banner'];
        //     //     unlink('../assets/uploads/'.$specialitem_banner);
        //     // }

        //     // // updating the data
        //     // $final_name = 'specialitem-banner'.'.'.$ext;
        //     // move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        //     // updating the database
        //     $statement = $pdo->prepare("UPDATE tbl_page SET specialitem_title=?,specialitem_content=?,specialitem_banner=?,specialitem_meta_title=?,specialitem_meta_keyword=?,specialitem_meta_description=? WHERE id=1");
        //     $statement->execute(array($_POST['specialitem_title'],$_POST['specialitem_content'],$final_name,$_POST['specialitem_meta_title'],$_POST['specialitem_meta_keyword'],$_POST['specialitem_meta_description']));
        // }

            // updating the database
            $statement = $pdo->prepare("UPDATE `videos` SET `v_url`=?,`v_date`=?,`v_uni_no`=? WHERE `v_id` = ?");
            $statement->execute(array($_POST['video_link'],$_POST['video_upload_date'],$_POST['video_unique_no'], $_POST['video_id']));


        
        // $success_message = 'Special Item Page Information is updated successfully.';
        echo "<script>window.alert('Special Item Page Information is updated successfully.')</script>";
			echo "<script>window.location='video.php'</script>";
        
    }
    
}


?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Page Settings</h1>
    </div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM videos WHERE v_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
foreach ($result as $row) 
{
    $v_id = $row['v_id'];
    $v_url = $row['v_url'];
    $v_date = $row['v_date'];
    $v_uni_no = $row['v_uni_no'];
}
?>


<section class="content" style="min-height:auto;margin-bottom: -30px;">
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
        </div>
    </div>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">
                            
                <div class="nav-tabs-custom">
                    <!-- <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">About Us</a></li>
                        <li><a href="#tab_2" data-toggle="tab">FAQ</a></li>
                        <li><a href="#tab_4" data-toggle="tab">Contact</a></li>
                        <li><a href="#tab_6" data-toggle="tab">Special Items</a></li>
                    </ul> -->

                        <!-- Special Item Page Content -->

                        <div class="tab-pane" id="tab_6">
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video ID * </label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="video_id" value="<?php echo $v_id; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video Link * </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="video_link" ><?php echo $v_url; ?></textarea>
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
                                            <input class="form-control" type="date" name="video_upload_date" value="<?php echo $v_date; ?>">
                                        </div>
                                    </div>             
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Video Unique Number </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="video_unique_no" style="height:100px;"><?php echo $v_uni_no; ?></textarea>
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
                                            <button type="submit" class="btn btn-success pull-left" name="form_specialitem">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
</section>

<?php require_once('footer.php'); ?>