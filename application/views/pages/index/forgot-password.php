<?php
    $blog_title = "";
    $blog_content = "";

    if (isset($record))
    {
        extract($record);
    }
?>
<div class="container margin-top-40 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">

    <div class="row margin-bottom-20 clearfix">
        <h1>Forgot Password</h1>
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right clearfix'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
    </div>

    <div class="row">
        <!--  ==========  -->
        <!--  = Main content =  -->
        <!--  ==========  -->
        <section class="">

            <form class="form-horizontal validate-form" method='post' action='' enctype="multipart/form-data">
                <div class="margin-bottom-20 clearfix">

                    <label for="user_email" >Email <span class="required">*</span></label>
                    <input class="form-control required" name="user_email" id="user_email" type="text" required="required" placeholder="Email" value='<?php echo $blog_title; ?>'/>

                    <p class="">Input your email here and we will take care of it.</p>
                </div>

                <div class="col-lg-12 text-center">	
                    <input class="btn btn-primary btn-lg disable-btn" name="btn_submit" value="Submit" type="submit">
                </div>
            </form>

        </section> <!-- /main content -->

    </div>

    <div class="row margin-top-40 text-right">
        <a href="<?php echo base_url('login'); ?>">Login?</a><br/>
        <a href="<?php echo base_url('register'); ?>">Sign up?</a>
    </div>
</div> <!-- /container -->


<script type='text/javascript' src='<?php echo base_url(JS_PATH . "/ckeditor/ckeditor.js"); ?>'></script>