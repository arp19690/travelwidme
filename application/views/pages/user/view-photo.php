<div class="container-fluid">
    <div class="row-fluid">
        <h1><?php echo $page_title; ?></h1>
        <a href="<?php echo getPublicProfileUrl($owner_username); ?>">View Profile</a>&nbsp;&nbsp;|&nbsp;
        <a href="<?php echo base_url('albums/' . $owner_username); ?>">View Albums</a>&nbsp;&nbsp;|&nbsp;
        <a href="<?php echo goBack(); ?>">Go back</a>
        <br/>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">

            <?php
                if (!empty($photo_pagination))
                {
                    ?>
                    <div class="row margin-top-20">
                        <div class="col-lg-12">
                            <?php
                            echo $photo_pagination;
                            ?>
                        </div>
                    </div>
                    <?php
                }
            ?>

            <div class="">
                <div class="clearfix">
                    <img src="<?php echo getAlbumImageUrl($record[0]['image_name']); ?>" alt="image" class="width-100"/>
                </div>
                <div class="clearfix">
                    <div class="pull-left">
                        <p><?php echo $record[0]['photo_name']; ?></p>
                        <p class="album-description-text"><?php echo $record[0]['photo_description']; ?></p>
                    </div>
                    <?php
                        if (isset($this->session->userdata['user_id']) && $owner_username == $this->session->userdata['username'])
                        {
                            ?>
                            <div class="pull-right">
                                <a href="#editImageModal" role="button" data-toggle="modal">Edit</a>&nbsp;&nbsp;|&nbsp;
                                <a href="<?php echo base_url('delete/photo-noax/' . $record[0]['album_key'] . "?image=" . $record[0]['image_name']); ?>" onclick="return confirm('Sure to remove this picture?');">Remove</a>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <hr/>

            <div class="clearfix">                
                <div class="pull-left font-16">
                    <?php echo number_format($totalLikesAndDislikes['likes']); ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo number_format($totalLikesAndDislikes['dislikes']); ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;&nbsp;
                </div>
                <?php
                    if (isset($this->session->userdata['user_id']))
                    {
//                        prd($record);
                        $like_text = "Like";
                        $dislike_text = "Dislike";
                        $disable_like = "";
                        $disable_dislike = "";
                        if ($record[0]['pld_user_id'] == $this->session->userdata['user_id'])
                        {
                            foreach ($record as $tmpKey => $tmpValue)
                            {
                                if ($tmpValue['like_dislike'] == '1')
                                {
                                    $disable_like = "disabled='disabled'";
                                    $like_text = "Liked";
                                }
                                if ($tmpValue['like_dislike'] == '0')
                                {
                                    $disable_dislike = "disabled='disabled'";
                                    $dislike_text = "Disliked";
                                }
                            }
                        }
                        ?>
                        <div class="pull-right pld">
                            <a href="javascript:void(0);" class="btn btn-default" id="1" onclick="likeDislikePhotoAjax('<?php echo base_url("user/likeDislikePhotoAjax"); ?>', '<?php echo $record[0]['image_name']; ?>', 1, $(this).children('span.pld-text').html());" <?php echo $disable_like; ?>>
                                <span class="glyphicon glyphicon-thumbs-up">                                    
                                </span>&nbsp;&nbsp;<span class="pld-text"><?php echo $like_text; ?></span>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-default" id="0" onclick="likeDislikePhotoAjax('<?php echo base_url("user/likeDislikePhotoAjax"); ?>', '<?php echo $record[0]['image_name']; ?>', 0, $(this).children('span.pld-text').html());" <?php echo $disable_dislike; ?>>
                                <span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;&nbsp;
                                <span class="pld-text"><?php echo $dislike_text; ?></span>
                            </a>
                        </div>
                        <?php
                    }
                ?>
            </div>

            <hr/>

            <div class="clearfix margin-top-20">
                <h2>Comments</h2>
                <?php
                    $no_comments_exists = TRUE;
                    if (!empty($record))
                    {
                        ?>
                        <ul class="margin-top-20 photo-comment-list">
                            <?php
//                            prd($record);
                            $i = 1;
                            foreach ($record as $key => $value)
                            {
                                if (!empty($value['user_comment']))
                                {
                                    $no_comments_exists = FALSE;
                                    $loggedin_user_fullname = "";
                                    if (isset($this->session->userdata['user_id']))
                                        $loggedin_user_fullname = $this->session->userdata['first_name'] . " " . $this->session->userdata['last_name'];

                                    $comment_user_fullname = $value['comment_user_fullname'];
                                    if (strcmp($loggedin_user_fullname, $comment_user_fullname) == 0)
                                    {
                                        $comment_user_fullname = "You";
                                    }
                                    ?>
                                    <li id="li_<?php echo $i; ?>">
                                        <div class="user-image">
                                            <img src="<?php echo getUserImage($value['user_id'], $value['user_facebook_id'], NULL, 100, 100); ?>" alt="<?php echo $value['comment_user_fullname']; ?>" class="width-100 img-rounded"/>
                                        </div>
                                        <div class="comment-details">
                                            <div class="user-name-and-time">
                                                <a href="#" class="user-name"><?php echo $comment_user_fullname; ?></a>                                                
                                                <?php
                                                if ($value['user_id'] == @$this->session->userdata['user_id'] && isset($this->session->userdata['user_id']))
                                                {
                                                    ?>
                                                    <a href="#" title="Remove" class="font-12 black remove-comment-link" rel="<?php echo base_url('delete/comment/' . $value['pc_id']); ?>" id="li_<?php echo $i; ?>"><span class="glyphicon glyphicon-remove"></span></a>
                                                    <?php
                                                }
                                                ?>
                                                <p class="time-ago"><?php echo getTimeAgo(strtotime($value['pc_timestamp'])); ?></p>
                                            </div>

                                            <div class="user-comment">
                                                <p class="comment-text"><?php echo nl2br(stripslashes($value['user_comment'])); ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            <span id="new-comments-here"></span>
                        </ul>
                        <?php
                    }
                    else
                    {
                        $no_comments_exists = FALSE;
                    }

                    if ($no_comments_exists == TRUE)
                    {
                        echo '<p class="no-comments-found margin-bottom-20">No comments found...</p>';
                    }

                    if (isset($this->session->userdata['user_id']))
                    {
                        ?>

                        <div class="write-comment-block row-fluid">
                            <textarea rows="2" class="textarea-resize-none form-control" placeholder="Write your comment here ..."></textarea>
                            <a href="javascript:void(0);" onclick="writeCommentOnPhoto('<?php echo base_url('user/addCommentAjax'); ?>', '<?php echo $record[0]['image_name']; ?>', $('.write-comment-block textarea').val());" class="btn btn-default pull-right"><span class="glyphicon glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;Send</a>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>

        <div class="col-lg-5 text-right pull-right">
            
        </div>
    </div>
</div>

<!--Edit Image Description Modal Box-->
<div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Image Details</h4>
            </div>
            <form id="change-image-form" class="" enctype="multipart/form-data" action="<?php echo base_url('user/editImageDetails'); ?>" method="post" autocomplete="off">
                <input name="next" type="hidden" value="<?php echo current_url(); ?>"/>
                <input name="image_name" type="hidden" value="<?php echo $record[0]['image_name']; ?>"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Photo Name:</label>
                        <input type="text" name="photo_name" class="form-control" placeholder="Photo Name" value='<?php echo $record[0]['photo_name']; ?>'/>
                    </div>
                    <div class="form-group">
                        <label>Photo Description:</label>
                        <textarea name="photo_description" rows="3" maxlength="200" class="form-control textarea-resize-none" placeholder="Photo Description"><?php echo $record[0]['photo_description']; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>