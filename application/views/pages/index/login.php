<?php
    $blog_title = "";
    $blog_content = "";

    if (isset($record))
    {
        extract($record);
    }
?>
<div class="container col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">

    <div class="row margin-bottom-20 clearfix text-center">
        <a href="<?php echo base_url("login/facebook?next=" . current_url()); ?>" class="btn btn-primary btn-lg margin-top-20 facebook-blue-btn" title="Login with facebook" id="fbLoginButton"><span class="facebook-initial">f</span>&nbsp;&nbsp;Login with facebook</a>
        <p class="margin-top-20">Or,</p>
        <hr/>
    </div>

    <div class="row margin-bottom-20 clearfix">
        <h1>Login with Email</h1>
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
    </div>

    <div class="row">
        <!--  ==========  -->
        <!--  = Main content =  -->
        <!--  ==========  -->
        <section class="">

            <form class="form-horizontal validate-form" method='post' action='' enctype="multipart/form-data">
                <div class="margin-bottom-20 clearfix">

                    <label for="user_email" >Email <span class="required">*</span></label>
                    <input class="form-control required" name="user_email" id="user_email" type="email" required="required" placeholder="Email" value='<?php echo $blog_title; ?>'/>

                </div>

                <div class="margin-bottom-20 clearfix">

                    <label for="user_password" >Password <span class="required">*</span></label>
                    <input class="form-control required" name="user_password" id="user_password" type="password" required="required" placeholder="Password" value='<?php echo $blog_title; ?>'/>

                </div>

                <div class="col-lg-12 text-center">	
                    <input class="btn btn-primary btn-lg disable-btn" name="btn_submit" value="Login" type="submit">
                </div>
            </form>

        </section> <!-- /main content -->

    </div>

    <div class="row margin-top-40 text-right">
        <a href="<?php echo base_url('signup'); ?>">New user, Sign up?</a><br/>
        <a href="<?php echo base_url('forgotPassword'); ?>">Forgot password?</a>
    </div>
</div> <!-- /container -->