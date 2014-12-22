<div class="container-fluid">
    <div class="row-fluid">
        <h1><?php echo $page_title; ?></h1>
        <br/>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <p class="text-right clearfix"><a href="<?php echo goBack(); ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Back</a></p><br/>

            <?php
                if ($user_id == @$this->session->userdata['user_id'] && isset($this->session->userdata['user_id']))
                {
                    ?>
                    <a href="#addAlbumModal" role="button" data-toggle="modal" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;Create New Album</a>

                    <!--Add Album Modal Box-->
                    <div class="modal fade" id="addAlbumModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;Create New Album</h4>
                                </div>
                                <form id="change-image-form" class="validate-form" enctype="multipart/form-data" action="<?php echo base_url('user/createAlbum'); ?>" method="post" autocomplete="off">
                                    <input type="hidden" class="input-album-privacy" name="album_privacy" value="public"/>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Album name:</label>
                                            <input type="text" name="album_name" maxlength="200" class="form-control required" placeholder="Album Name" required="required" value=''/>
                                        </div>
                                        <div class="form-group">
                                            <label>Description:</label>
                                            <textarea name="album_description" maxlength="200" rows="3" class="form-control required textarea-resize-none" placeholder="Album Description" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!--                                        <div class="pull-left">
                                                                                    <div class="btn-group select-privacy-btns">
                                                                                        <button type="button" class="btn btn-default active" id="public" title="Public"><span class="glyphicon glyphicon-globe"></span></button>
                                                                                        <button type="button" class="btn btn-default" id="connects" title="Only Connects"><span class="glyphicon glyphicon-user"></button>
                                                                                    </div>
                                                                                </div>-->
                                        <input type="submit" class="btn btn-primary" value="Create"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                echo '<hr/>';
            ?>

            <ul class="all-albums">

                <?php
                    if (isset($record) && !empty($record))
                    {
                        foreach ($record as $key => $value)
                        {
                            ?>
                            <li>
                                <a href="<?php echo getAlbumViewPath($value['album_key']); ?>" title="View album">
                                    <?php
                                    $imagePath = getAlbumImageUrl($value['image_name']);
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" alt="image" class="img-rounded width-100 lazy" data-original="<?php echo $imagePath; ?>"/>
                                    <p class="album-name"><?php echo stripslashes($value['album_name']); ?></p>
                                    <p class="album-description"><?php echo stripslashes($value['album_description']); ?></p>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    else
                    {
                        echo '<h4 class="text-center">No albums found.</h4>';
                    }
                ?>
            </ul>

            <?php
                if (!empty($pagination))
                {
                    ?>
                    <div class="row margin-top-20">
                        <div class="col-lg-12">
                            <?php
                            echo $pagination;
                            ?>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>

        <div class="col-lg-5 text-right pull-right">
            <div class="chitika-ad">
                <?php echo getChitikaAd(); ?>
            </div>
            <div class="chitika-ad margin-top-20">
                <?php echo getChitikaAd(); ?>
            </div>
        </div>
    </div>
</div>