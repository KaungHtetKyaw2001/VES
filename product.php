<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else{
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    } 
}

foreach($result as $row) {
    $j_name = $row['j_name'];
    $g_name = $row['g_name'];
    $p_old_price = $row['p_old_price'];
    $p_current_price = $row['p_current_price'];
    $g_size = $row['g_size'];
    $g_weight = $row['g_weight'];
    $j_size = $row['j_size'];
    $j_weight = $row['j_weight'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Getting all categories name for breadcrumb
$statement = $pdo->prepare("SELECT
                        t1.ecat_id,
                        t1.ecat_name,
                        t1.mcat_id,

                        t2.mcat_id,
                        t2.mcat_name,
                        t2.tcat_id,

                        t3.tcat_id,
                        t3.tcat_name

                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
    $tcat_name = $row['tcat_name'];
}


$p_total_view = $p_total_view + 1;

$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view,$_REQUEST['id']));


$statement = $pdo->prepare("SELECT * FROM tbl_gproduct_gemorigin WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$gemorigin_id[] = $row['gemorigin_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_gproduct_gemtype WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$gemtype_id[] = $row['gemtype_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_gproduct_gemsize WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$gemsize_id[] = $row['gemsize_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_gproduct_gemweight WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$gemweight_id[] = $row['gemweight_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_jproduct_jeweltype WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$jeweltype_id[] = $row['jeweltype_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_jproduct_goldtype WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$goldtype_id[] = $row['goldtype_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_jproduct_goldkind WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$goldkind_id[] = $row['goldkind_id'];
}

// $statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
// $statement->execute(array($_REQUEST['id']));
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
// foreach ($result as $row) {
//     $size[] = $row['size_id'];
// }

// $statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
// $statement->execute(array($_REQUEST['id']));
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
// foreach ($result as $row) {
//     $color[] = $row['color_id'];
// }


if(isset($_POST['form_review'])) {
    
    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();
    
    if($total) {
        $error_message = LANG_VALUE_68; 
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id'],$_POST['comment'],$_POST['rating']));
        $success_message = LANG_VALUE_163;    
    }
    
}

// Getting the average rating for this product
$t_rating = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$tot_rating = $statement->rowCount();
if($tot_rating == 0) {
    $avg_rating = 0;
} else {
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {
        $t_rating = $t_rating + $row['rating'];
    }
    $avg_rating = $t_rating / $tot_rating;
}

if(isset($_POST['form_add_to_cart'])) {

	// getting the currect stock of this product
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$current_p_qty = $row['p_qty'];
	}
	if($_POST['p_qty'] > $current_p_qty):
		$temp_msg = 'Sorry! There are only '.$current_p_qty.' item(s) in stock';
		?>
		<script type="text/javascript">alert('<?php echo $temp_msg; ?>');</script>
		<?php
	else:
    if(isset($_SESSION['cart_p_id']))
    {
        $arr_cart_p_id = array();
        // $arr_cart_size_id = array();
        // $arr_cart_color_id = array();
        $arr_cart_p_qty = array();
        $arr_cart_p_current_price = array();

        $i=0;
        foreach($_SESSION['cart_p_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_id[$i] = $value;
        }

        // $i=0;
        // foreach($_SESSION['cart_size_id'] as $key => $value) 
        // {
        //     $i++;
        //     $arr_cart_size_id[$i] = $value;
        // }

        // $i=0;
        // foreach($_SESSION['cart_color_id'] as $key => $value) 
        // {
        //     $i++;
        //     $arr_cart_color_id[$i] = $value;
        // }


        // $added = 0;
        // if(!isset($_POST['size_id'])) {
        //     $size_id = 0;
        // } else {
        //     $size_id = $_POST['size_id'];
        // }
        // if(!isset($_POST['color_id'])) {
        //     $color_id = 0;
        // } else {
        //     $color_id = $_POST['color_id'];
        // }
        for($i=1;$i<=count($arr_cart_p_id);$i++) {
            // if( ($arr_cart_p_id[$i]==$_REQUEST['id']) && ($arr_cart_size_id[$i]==$size_id) && ($arr_cart_color_id[$i]==$color_id) ) {
            //     $added = 1;
            //     break;
            // }
            if( ($arr_cart_p_id[$i]==$_REQUEST['id']) ) {
                $added = 1;
                break;
            }
        }
        if($added == 1) {
           $error_message1 = 'This product is already added to the shopping cart.';
        } else {

            $i=0;
            foreach($_SESSION['cart_p_id'] as $key => $res) 
            {
                $i++;
            }
            $new_key = $i+1;

            // if(isset($_POST['size_id'])) {

            //     $size_id = $_POST['size_id'];

            //     $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
            //     $statement->execute(array($size_id));
            //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
            //     foreach ($result as $row) {
            //         $size_name = $row['size_name'];
            //     }
            // } else {
            //     $size_id = 0;
            //     $size_name = '';
            // }
            
            // if(isset($_POST['color_id'])) {
            //     $color_id = $_POST['color_id'];
            //     $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
            //     $statement->execute(array($color_id));
            //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
            //     foreach ($result as $row) {
            //         $color_name = $row['color_name'];
            //     }
            // } else {
            //     $color_id = 0;
            //     $color_name = '';
            // }
          

            $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
            // $_SESSION['cart_size_id'][$new_key] = $size_id;
            // $_SESSION['cart_size_name'][$new_key] = $size_name;
            // $_SESSION['cart_color_id'][$new_key] = $color_id;
            // $_SESSION['cart_color_name'][$new_key] = $color_name;
            $_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'];
            $_SESSION['cart_p_current_price'][$new_key] = $_POST['p_current_price'];
            // $_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
            $_SESSION['cart_j_name'][$new_key] = $_POST['j_name'];
            $_SESSION['cart_g_name'][$new_key] = $_POST['g_name'];
            $_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];

            $success_message1 = 'Product is added to the cart successfully!';
        }
        
    }
    else
    {

        // if(isset($_POST['size_id'])) {

        //     $size_id = $_POST['size_id'];

        //     $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
        //     $statement->execute(array($size_id));
        //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        //     foreach ($result as $row) {
        //         $size_name = $row['size_name'];
        //     }
        // } else {
        //     $size_id = 0;
        //     $size_name = '';
        // }
        
        // if(isset($_POST['color_id'])) {
        //     $color_id = $_POST['color_id'];
        //     $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
        //     $statement->execute(array($color_id));
        //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        //     foreach ($result as $row) {
        //         $color_name = $row['color_name'];
        //     }
        // } else {
        //     $color_id = 0;
        //     $color_name = '';
        // }
        

        $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
        // $_SESSION['cart_size_id'][1] = $size_id;
        // $_SESSION['cart_size_name'][1] = $size_name;
        // $_SESSION['cart_color_id'][1] = $color_id;
        // $_SESSION['cart_color_name'][1] = $color_name;
        $_SESSION['cart_p_qty'][1] = $_POST['p_qty'];
        $_SESSION['cart_p_current_price'][1] = $_POST['p_current_price'];
        // $_SESSION['cart_p_name'][1] = $_POST['p_name'];
        $_SESSION['cart_j_name'][1] = $_POST['j_name'];
        $_SESSION['cart_g_name'][1] = $_POST['g_name'];
        $_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];

        $success_message1 = 'Product is added to the cart successfully!';
    }
	endif;
}
?>

<?php
if($error_message1 != '') {
    echo "<script>alert('".$error_message1."')</script>";
}
if($success_message1 != '') {
    echo "<script>alert('".$success_message1."')</script>";
    header('location: product.php?id='.$_REQUEST['id']);
}
?>


<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <div class="breadcrumb mb_30">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$tcat_id.'&type=top-category' ?>"><?php echo $tcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$mcat_id.'&type=mid-category' ?>"><?php echo $mcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$ecat_id.'&type=end-category' ?>"><?php echo $ecat_name; ?></a></li>
                        <li>></li>
                        <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                            {
                                ?>
							<li><?php echo $g_name; ?></li>
                            <?php
                            }
                            ?>
                        <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                            {
                                ?>
							<li><?php echo $j_name; ?></li>
                            <?php
                            }
                            ?>
                    </ul>
                </div>

				<div class="product">
					<div class="row">
						<div class="col-md-5 wow animate__animated animate__slideInLeft" data-wow-offset="150" data-wow-delay="1">
							<!-- <ul class="prod-slider">
                                
								<li style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>);">
                                    <a class="popup" href="assets/uploads/<?php echo $p_featured_photo; ?>"></a>
								</li>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <li style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>);">
                                        <a class="popup" href="assets/uploads/product_photos/<?php echo $row['photo']; ?>"></a>
                                    </li>
                                    <?php
                                }
                                ?>
							</ul> -->
                            <div class="prod-slider">
                                    <a class="popup"><img src="assets/uploads/<?php echo $p_featured_photo; ?>" alt="" class="magnify-image" data-magnify-src="assets/uploads/<?php echo $p_featured_photo; ?>"></a>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <a class="popup"><img src="assets/uploads/product_photos/<?php echo $row['photo']; ?>" alt="" class="magnify-image" data-magnify-src="assets/uploads/product_photos/<?php echo $row['photo']; ?>"></a>
                                    <?php
                                }
                                ?>
                            </div>  
							<div id="prod-pager">
								<a data-slide-index="0" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>"></div></a>
                                <?php
                                $i=1;
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>"></div></a>
                                    <?php
                                    $i++;
                                }
                                ?>
							</div>
						</div>
						<div class="col-md-7 wow animate__animated animate__slideInRight" data-wow-offset="150" data-wow-delay="1">
                            <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                            {
                                ?>
							<div class="p-title"><h2><?php echo $g_name; ?></h2></div>
                            <?php
                            }
                            ?>
                            <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                            {
                                ?>
							<div class="p-title"><h2><?php echo $j_name; ?></h2></div>
                            <?php
                            }
                            ?>
							<div class="p-review">
								<div class="rating">
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }                                    
                                    ?>
                                </div>
							</div>
							<div class="p-short-des">
								<p>
									<?php echo $p_short_description; ?>
								</p>
							</div>
                            <form action="" method="post">
                            <div class="p-quantity">
                                <div class="row">
                                    <!-- GEM ORIGIN -->
                                    <?php if(isset($gemorigin_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                    {
                                        ?>
                                    <div class="col-md-6 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gem Origin" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_gemorigin");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['gemorigin_id'],$gemorigin_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['gemorigin_name'];  ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>

                                    <!-- GEM TYPE -->
                                    <?php if(isset($gemtype_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gem Type" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_gemtype");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['gemtype_id'],$gemtype_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['gemtype_name'];  ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>
                                
                                    <!-- Jewel TYPE -->
                                    <?php if(isset($jeweltype_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Jewel Type" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_jeweltype");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['jeweltype_id'],$jeweltype_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['jeweltype_name'];  ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>

                                    <!-- Gold Type -->
                                    <?php if(isset($goldtype_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gold Type" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_goldtype");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['goldtype_id'],$goldtype_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['goldtype_name'];  ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>

                                    <!-- Gold Kind -->
                                    <?php if(isset($goldkind_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gold Kind" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_goldkind");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['goldkind_id'],$goldkind_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['goldkind_name'];  ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <!-- GEM Size -->
                                    <!-- <?php if(isset($gemsize_id)): ?>
                                    <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gem Size" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_gemsize");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['gemsize_id'],$gemsize_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['gemsize_name']; ?> <?php echo " ( in mm )"; ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?> -->
                                
                                    <!-- GEM Weight -->
                                    <?php if(isset($gemweight_id)): ?>
                                    <!-- <?php 
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                    {
                                        ?>
                                    <div class="col-md-12 mb_20">
                                            <span class="p-price" style="font-size:14px;"><?php echo "Gem Weight" ?></span><br>
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_gemweight");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['gemweight_id'],$gemweight_id)) {
                                                        ?>
                                                        <span style="font-size:24px;"><b><?php echo $row['gemweight_name']; ?> <?php echo "( in Ct ) " ?></b></span>
                                                        <?php
                                                    }
                                                }
                                            ?> -->

                                            <!-- <select hidden name="gemorigin_id" class="form-control select2" style="width:auto;">
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_gemorigin");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if(in_array($row['gemorigin_id'],$gemorigin_id)) {
                                                        ?>
                                                        <option hidden value="<?php echo $row['gemorigin_id']; ?>"><?php echo $row['gemorigin_name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select> -->
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php endif; ?>
                                    <div class="col-md-12 mb_20">
                                        <span class="p-price" style="font-size:14px;"><?php echo "Size" ?></span><br>
                                        <?php 
                                        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                        {
                                            ?>
                                            <span style="font-size:24px;"><b><?php echo $g_size ?></b></span>
                                        <?php
                                        }
                                        ?>
                                        <?php 
                                        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                        {
                                            ?>
                                            <span style="font-size:24px;"><b><?php echo $j_size ?></b></span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 mb_20">
                                        <span class="p-price" style="font-size:14px;"><?php echo "Weight" ?></span><br>
                                        <?php 
                                        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                        {
                                            ?>
                                            <span style="font-size:24px;"><b><?php echo $g_weight ?></b></span>
                                        <?php
                                        }
                                        ?>
                                        <?php 
                                        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                        {
                                            ?>
                                            <span style="font-size:24px;"><b><?php echo $j_weight ?></b></span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
							<div class="p-price">
                                <span style="font-size:14px;"><?php echo LANG_VALUE_54; ?></span><br>
                                <span>
                                    <?php if($p_old_price!=''): ?>
                                        <del><?php echo LANG_VALUE_1; ?><?php echo $p_old_price; ?></del>
                                    <?php endif; ?> 
                                        <?php echo LANG_VALUE_1; ?><?php echo $p_current_price; ?>
                                </span>
                            </div>
                            <input type="hidden" name="p_current_price" value="<?php echo $p_current_price; ?>">
                            <!-- <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                            {
                                ?>
							<input type="hidden" name="g_name" value="<?php echo $g_name; ?>">
                            <?php
                            }
                            ?>
                            <?php 
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                            {
                                ?>
							<input type="hidden" name="j_name" value="<?php echo $j_name; ?>">
                            <?php
                            }
                            ?> -->
                            <input type="hidden" name="g_name" value="<?php echo $g_name; ?>">
                            <input type="hidden" name="j_name" value="<?php echo $j_name; ?>">
                            <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">
							<div class="p-quantity">
                                <?php echo LANG_VALUE_55; ?> <br>
								<input type="number" class="input-text qty" step="1" min="1" max="" name="p_qty" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
							</div>
							<div class="btn-cart btn-cart1">
                                <input type="submit" value="<?php echo LANG_VALUE_154; ?>" name="form_add_to_cart">
							</div>
                            </form>
							<div class="share">
                                <?php echo LANG_VALUE_58; ?> <br>
								<div class="sharethis-inline-share-buttons"></div>
							</div>
						</div>
					</div>

					<div class="row wow animate__animated animate__slideInUp" data-wow-offset="150" data-wow-delay="1">
						<div class="col-md-12">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?php echo LANG_VALUE_59; ?></a></li>
								<li role="presentation"><a href="#feature" aria-controls="feature" role="tab" data-toggle="tab"><?php echo LANG_VALUE_60; ?></a></li>
                                <li role="presentation"><a href="#condition" aria-controls="condition" role="tab" data-toggle="tab"><?php echo LANG_VALUE_61; ?></a></li>
                                <li role="presentation"><a href="#return_policy" aria-controls="return_policy" role="tab" data-toggle="tab"><?php echo LANG_VALUE_62; ?></a></li>
                               <!-- <li role="presentation"><a href="#review" aria-controls="review" role="tab" data-toggle="tab"><?php echo LANG_VALUE_63; ?></a></li> -->
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
									<p>
                                        <?php
                                        if($p_description == '') {
                                            echo LANG_VALUE_70;
                                        } else {
                                            echo $p_description;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane" id="feature" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_feature == '') {
                                            echo LANG_VALUE_71;
                                        } else {
                                            echo $p_feature;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="condition" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_condition == '') {
                                            echo LANG_VALUE_72;
                                        } else {
                                            echo $p_condition;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="return_policy" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_return_policy == '') {
                                            echo LANG_VALUE_73;
                                        } else {
                                            echo $p_return_policy;
                                        }
                                        ?>
                                    </p>
                                </div>
								<div role="tabpanel" class="tab-pane" id="review" style="margin-top: -30px;">

                                    <div class="review-form">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * 
                                                            FROM tbl_rating t1 
                                                            JOIN tbl_customer t2 
                                                            ON t1.cust_id = t2.cust_id 
                                                            WHERE t1.p_id=?");
                                        $statement->execute(array($_REQUEST['id']));
                                        $total = $statement->rowCount();
                                        ?>
                                        <h2><?php echo LANG_VALUE_63; ?> (<?php echo $total; ?>)</h2>
                                        <?php
                                        if($total) {
                                            $j=0;
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                $j++;
                                                ?>
                                                <div class="mb_10"><b><u><?php echo LANG_VALUE_64; ?> <?php echo $j; ?></u></b></div>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width:170px;"><?php echo LANG_VALUE_75; ?></th>
                                                        <td><?php echo $row['cust_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_76; ?></th>
                                                        <td><?php echo $row['comment']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_78; ?></th>
                                                        <td>
                                                            <div class="rating">
                                                                <?php
                                                                for($i=1;$i<=5;$i++) {
                                                                    ?>
                                                                    <?php if($i>$row['rating']): ?>
                                                                        <i class="fa fa-star-o"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-star"></i>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php
                                            }
                                        } else {
                                            echo LANG_VALUE_74;
                                        }
                                        ?>
                                        
                                        <h2><?php echo LANG_VALUE_65; ?></h2>
                                        <?php
                                        if($error_message != '') {
                                            echo "<script>alert('".$error_message."')</script>";
                                        }
                                        if($success_message != '') {
                                            echo "<script>alert('".$success_message."')</script>";
                                        }
                                        ?>
                                        <?php if(isset($_SESSION['customer'])): ?>

                                            <?php
                                            $statement = $pdo->prepare("SELECT * 
                                                                FROM tbl_rating
                                                                WHERE p_id=? AND cust_id=?");
                                            $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
                                            $total = $statement->rowCount();
                                            ?>
                                            <?php if($total==0): ?>
                                            <form action="" method="post">
                                            <div class="rating-section">
                                                <input type="radio" name="rating" class="rating" value="1" checked>
                                                <input type="radio" name="rating" class="rating" value="2" checked>
                                                <input type="radio" name="rating" class="rating" value="3" checked>
                                                <input type="radio" name="rating" class="rating" value="4" checked>
                                                <input type="radio" name="rating" class="rating" value="5" checked>
                                            </div>                                            
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Write your comment (optional)" style="height:100px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-default" name="form_review" value="<?php echo LANG_VALUE_67; ?>">
                                            </form>
                                            <?php else: ?>
                                                <span style="color:red;"><?php echo LANG_VALUE_68; ?></span>
                                            <?php endif; ?>


                                        <?php else: ?>
                                            <p class="error">
												<?php echo LANG_VALUE_69; ?> <br>
												<a href="login.php" style="color:red;text-decoration: underline;"><?php echo LANG_VALUE_9; ?></a>
											</p>
                                        <?php endif; ?>                         
                                    </div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<div class="product bg-gray pt_70 pb_70">
    <div class="container">
        <div class="row wow animate__animated animate__slideInUp" data-wow-offset="150" data-wow-delay="1">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo LANG_VALUE_155; ?></h2>
                    <h3><?php echo LANG_VALUE_156; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_id!=?");
                    $statement->execute(array($ecat_id,$_REQUEST['id']));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">
                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);"></div>
                                <div class="overlay"></div>
                            </div>
                            <div class="text">
                                <?php 
                                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Jewellery'");
                                {
                                    ?>
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['j_name']; ?></a></h3>
                                <?php
                                }
                                ?>
                                <?php 
                                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_product_type='Gem'");
                                {
                                    ?>
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['g_name']; ?></a></h3>
                                <?php
                                }
                                ?>
                                <h4>
                                    <?php echo LANG_VALUE_1; ?><?php echo $row['p_current_price']; ?> 
                                    <?php if($row['p_old_price'] != ''): ?>
                                    <del>
                                        <?php echo LANG_VALUE_1; ?><?php echo $row['p_old_price']; ?>
                                    </del>
                                    <?php endif; ?>
                                </h4>
                                <div class="rating">
                                    <?php
                                    $t_rating = 0;
                                    $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                    $statement1->execute(array($row['p_id']));
                                    $tot_rating = $statement1->rowCount();
                                    if($tot_rating == 0) {
                                        $avg_rating = 0;
                                    } else {
                                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result1 as $row1) {
                                            $t_rating = $t_rating + $row1['rating'];
                                        }
                                        $avg_rating = $t_rating / $tot_rating;
                                    }
                                    ?>
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo LANG_VALUE_154; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
