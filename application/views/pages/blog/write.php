<?php
    $user_full_name = "";
    $user_email = "";
    $blog_title= "";
    $blog_content = "";

    if (isset($record))
    {
        extract($record);
    }
?>

<div class="container margin-bottom-20">
    <div class="row-fluid">
        <h1 class="inline"><span class="light"><?php echo SITE_NAME; ?></span> Blog</h1>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <!--  ==========  -->
        <!--  = Main content =  -->
        <!--  ==========  -->
        <section class="blog">

            <form class="form-horizontal validate-form" method='post' action='' enctype="multipart/form-data">
                <?php
                    if (!isset($this->session->userdata["user_id"]))
                    {
                        ?>
                        <div class="margin-bottom-20">
                            <label for="user_full_name" >Your Full Name <span class="required">*</span></label>
                            <input class="form-control required" name="user_full_name" id="user_full_name" type="text" required="required" placeholder="Your Full Name" value='<?php echo $user_full_name; ?>'/>
                        </div>

                        <div class="margin-bottom-20">
                            <label for="user_email" >Your Email <span class="required">*</span></label>
                            <input class="form-control required" name="user_email" id="user_email" type="text" required="required" placeholder="Your Email" value='<?php echo $user_email; ?>'/>
                        </div>
                        <?php
                    }
                ?>

                <div class="margin-bottom-20">
                    <label for="blog_title" >Blog Title <span class="required">*</span></label>
                    <input class="form-control required" name="blog_title" id="blog_title" type="text" required="required" placeholder="Blog Title" value='<?php echo $blog_title; ?>'/>
                </div>

                <div class="margin-bottom-20">
                    <label for="blog_content">Blog Content <span class="required">*</span></label>
                    <textarea class="form-control ckeditor required" name="blog_content" id="blog_content" type="text" placeholder="Blog Content" required="required"><?php echo $blog_content; ?></textarea>
                </div>

                <div class="margin-bottom-20">
                    <label for="blog_img" >Header Image:</label>
                    <div class='position-relative'>
                        <a class='btn btn-default' href='javascript:;'>
                            Choose Image...
                            <input type="file" class="custom-input-file" name="blog_img" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-file-info"></span>
                    </div>
                </div>

                <div class="">		
                    <input class="btn btn-primary btn-lg" name="btn_submit" value="Submit" type="submit">
                </div>
            </form>

        </section> <!-- /main content -->

    </div>
</div> <!-- /container -->

<script type='text/javascript' src='<?php echo base_url(JS_PATH . "/ckeditor/ckeditor.js"); ?>'></script>