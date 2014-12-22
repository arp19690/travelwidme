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

            <form class="upload-multi-photos upload" method="post" action="<?php // echo base_url('upload/album/' . $album_key);                    ?>" enctype="multipart/form-data">
                <div id="drop">
                    <a class="select-multi-photos">Browse</a>
                    <input type="file" name="album_images[]" multiple="multiple" class="multi-photo-input"/>
                </div>
            </form>

            <form class="upload after-image-upload-form hide" action="<?php echo base_url('user/afterUploadSaveAllAjax');?>" method="POST">
                <a href="<?php echo base_url('view/album/' . $album_key); ?>" class="btn btn-success btn-sm clearfix pull-left "><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Go to album</a>
                <a href="#" class="btn btn-success btn-sm clearfix pull-right save-btn hide"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Save</a>
                <ul>
                    <!-- The file uploads will be shown here -->

                </ul>
            </form>

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



<link href="<?php echo base_url(JS_PATH . "/../file-uploader/css/style.css"); ?>" rel="stylesheet" />