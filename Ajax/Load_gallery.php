<?php

require_once '../Config/Functions.php';
$Fun_call = new Functions();

$fetch_video = $Fun_call->select_order('videos', 'v_id', 'DESC');

?>

                <table class="table table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Videos</th>
                        <th scope="col">Date</th>
                        <th scope="col">Operation</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; if($fetch_video){ foreach($fetch_video as $video_data){ ?>
                      <tr class="v-set">
                        <th scope="row"><?php echo $i; $i++; ?></th>
                        <td>
                            <iframe width="265" height="150" src="https://www.youtube.com/embed/<?php echo $video_data['v_url']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </td>
                        <td><?php echo $video_data['v_date']; ?></td>
                        <td>
                            <button type="button" id="video_update" class="btn btn-outline-success" data-toggle="modal" data-target="#UpdateModalCenter" data-update_no="<?php echo $video_data['v_uni_no']; ?>">
                                Update
                            </button>
                            <button type="button" id="video_delete" class="btn btn-outline-danger" data-toggle="modal" data-target="#DeleteModalCenter" data-delete_no="<?php echo $video_data['v_uni_no']; ?>">
                                Delete
                            </button>
                        </td>
                      </tr>
                    <?php }}else{ echo "<tr><td colspan='4'><h1>Sorry Videos Not Found</h1></td></tr>"; } ?>
                    </tbody>
                </table>