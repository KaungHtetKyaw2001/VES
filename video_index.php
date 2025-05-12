    <?php

    require_once 'Config/Functions.php';
    $Fun_call = new Functions();

    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Special Items Videos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script defer src="https://friconix.com/cdn/friconix.js"> </script>
    <link rel="stylesheet" href="Stylesheet/stylesheet.css">
</head>

<body>

    <div class="container-fluid">
        <div class="container">
            <ul class="nav justify-content-center bg-dark">
                <li class="nav-item">
                    <div class="nav-link heading">Special Items Videos</div>
                </li>
            </ul>
        </div>

        <div class="container">
            <div class="ins-box">
                <form method="post" id="video-ins">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12 col-lg-6 mb-0">
                            <input type="text" class="form-control" id="video_code" placeholder="Enter Youtube Video URL">
                        </div>
                        <div class="form-group col-sm-12 col-lg-2 mb-0">
                            <input type="submit" value="Upload" class="btn btn-outline-dark" data-toggle="modal"
                                data-target="#exampleModalCenter">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="container">
            <div class="ins-box" id="load_videos">

            </div>
        </div>
    </div>

    <!-- Insert Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Video Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <span id="ins_status"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END-Modal -->

    <!-- Update - Modal -->
    <div class="modal fade" id="UpdateModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Update Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="update_video">
                    <div class="youtube-video-r">
                        <iframe width="560" height="315" id="update_iframe" src="" frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    <div class="modal-body">
                        <div class="form-row justify-content-center">
                            <div class="form-group col-sm-12 col-lg-6 mb-0">
                                <input type="text" class="form-control" id="update_url" placeholder="Enter New Video URL">
                            </div>
                            <div class="form-group col-sm-12 col-lg-2 mb-0">
                                <input type="hidden" class="form-control" id="update_no" >
                                <input type="submit" value="Update" class="btn btn-outline-success">
                            </div>
                        </div>
                        <span class="text-center mt-1" id="upd_status" style="display: block;"></span>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal" id="update_reset">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End - Update - Modal -->

    <!-- Delete - Modal -->
    <div class="modal fade" id="DeleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <h4>Are You Sure want to Delete</h4>
                    <span id="de_status"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal" id="delete_reset">Close</button>
                    <button type="button" class="btn btn-outline-danger" id="video_delete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End - Delete - Modal -->

    <div class="all-v-btn btn btn-outline-dark">
        <a href="view.php"><i class="fi-xwluxl-table-wide fi-2x"></i></a>
    </div>



    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script type="text/javascript">
    
    $(document).ready(function (){

        $delete_no = '';

        $('#load_videos').load('Ajax/Load_gallery.php');

        $('#video-ins').on('submit', function(e){
            e.preventDefault();
            $video_url = $('#video_code').val().trim();
            $('#ins_status').text('');
            if($video_url != ''){
                $.ajax({
                    type: "POST",
                    url: "Ajax/Video_process.php",
                    data: { 'video_url' : encodeURIComponent($video_url) },
                    success: function (response) {
                        $json_res = JSON.parse(response);
                        if($json_res.status == 101){
                            $('#load_videos').load('Ajax/Load_gallery.php');
                            $('#ins_status').text('Successfully Video Added');
                            $("#video-ins").trigger("reset");
                        }
                        else{
                            console.log($json_res.msg);
                        }
                    }
                });
            }
            else{
                $('#ins_status').text('Please Enter Video Code');
            }
        });

        $(document).on('click', '#video_update', function(){
            $update_no = $(this).data('update_no');
            $('#upd_status').text('');
            $.getJSON("Ajax/Fetch_update.php", {'update_no' : encodeURIComponent($update_no)}, function(json_data){
                if(json_data.status == 200){
                    $('#update_no').val($update_no);
                    $('#update_iframe').attr('src', 'https://www.youtube.com/embed/'+json_data.code);
                }
                else{
                    console.log(json_data.code);
                }
            });
        });

        $('#update_video').on('submit', function(e){
            e.preventDefault();
            $upd_video_url = $('#update_url').val().trim();
            $upd_video_no = $('#update_no').val().trim();
            if($upd_video_url != '' && $upd_video_no != ''){
                $.ajax({
                    type: "POST",
                    url: "Ajax/Video_process.php",
                    data: { 
                        'upd_video_url' : encodeURIComponent($upd_video_url),
                        'upd_video_no' : encodeURIComponent($upd_video_no) },
                    success: function (response) {
                        $json_res = JSON.parse(response);
                        if($json_res.status == 104){
                            $('#update_reset').trigger('click');
                            $('#load_videos').load('Ajax/Load_gallery.php');
                            $("#update_video").trigger("reset");
                        }
                        else{
                            console.log($json_res.msg);
                        }
                    }
                });
            }
            else{
                if($upd_video_url == ''){
                    $('#upd_status').text('Please Enter Video Code');
                }
                if($upd_video_no == ''){
                    $('#upd_status').text('Video No Not Found');
                }
                
            }
        });

        $(document).on('click', '#video_delete', function(){
            $delete_no = $(this).data('delete_no');
        });

        $('#video_delete').on('click', function(){
            if($delete_no != ''){
                $.ajax({
                    type: "POST",
                    url: "Ajax/Video_process.php",
                    data: { 'de_video_no' : encodeURIComponent($delete_no) },
                    success: function (res) {
                        var json_data = JSON.parse(res);
                        if(json_data.status == '107'){
                            $('#video_delete').trigger('click');
                            $('#load_videos').load('Ajax/Load_gallery.php');
                        }
                        else{
                            console.log(json_data.msg);
                        }
                    }
                });
            }
            else{
                $('#de_status').text('Video No Not Found');
            }
        });



    });

    </script>

</body>

</html>