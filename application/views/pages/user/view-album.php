<div class="container-fluid">
    <div class="row-fluid">
        <h1><?php echo $page_title; ?></h1>
        <p class="album-description-text"><?php echo $album_description; ?></p>
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
                    <a href="<?php echo base_url('upload/album/' . $album_key); ?>" class="btn btn-success btn-block"><span class="glyphicon glyphicon glyphicon-open"></span>&nbsp;&nbsp;Upload Photos</a>

                    <div class="text-right font-12 margin-top-20">
                        <a href="<?php echo base_url('delete/album/' . $album_key); ?>" onclick="return confirm('Sure you want to delete this album?');">Delete Album</a>
                    </div>
                    <hr/>
                    <?php
                }
            ?>

            <ul class="all-photos">

                <?php
                    if (isset($record) && !empty($record))
                    {
                        $i = 1;
                        foreach ($record as $key => $value)
                        {
                            $imagePath = getAlbumImageUrl($value['image_name']);
                            ?>
                            <li id="li_<?php echo $i; ?>">
                                <div class="album-image-div">
                                    <a href="<?php echo base_url('view/photo/' . $album_key . "/" . $value['photo_id']); ?>" class="">
                                        <div class="image-block">
                                            <img src="<?php echo $imagePath; ?>" alt="image" class="img-rounded width-100 lazy" data-original="<?php echo $imagePath; ?>"/>
                                            <p class="photo-name"><?php echo $value['photo_name']; ?></p>
                                        </div>
                                    </a>
                                    <?php
                                    if ($user_id == @$this->session->userdata['user_id'] && isset($this->session->userdata['user_id']))
                                    {
                                        ?>
                                        <div class="album-image-delete-div">
                                            <a href="javascript:void(0);" title="Remove" rel="<?php echo base_url('delete/photo/' . $album_key . '/' . $value['image_name']); ?>" id="li_<?php echo $i; ?>" class="remove-photo-ajax"><span class="glyphicon glyphicon-remove"></span></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </li>
                            <?php
                            $i++;
                        }
                    }
                    else
                    {
                        echo '<h4 class="text-center">No photos found.</h4>';
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