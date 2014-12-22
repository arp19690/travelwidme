<div class="container margin-top-40 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">

    <div class="row margin-bottom-20 clearfix">
        <h1>Change Password</h1>
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right clearfix'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
    </div>

    <div class="row">
        <!--  ==========  -->
        <!--  = Main content =  -->
        <!--  ==========  -->
        <section class="">

            <form class="form-horizontal validate-form" method='post' action='' enctype="multipart/form-data">
                <div class="margin-bottom-20 clearfix">

                    <label for="new_password" >New Password <span class="required">*</span></label>
                    <input class="form-control required" name="new_password" id="new_password" type="password" required="required" placeholder="New Password"/>

                </div>
                
                <div class="margin-bottom-20 clearfix">

                    <label for="confirm_password" >Confirm Password <span class="required">*</span></label>
                    <input class="form-control required" name="confirm_password" id="confirm_password" type="password" required="required" placeholder="Change Password"/>

                </div>

                <div class="col-lg-12 text-center">	
                    <input class="btn btn-primary btn-lg" name="btn_submit" value="Submit" type="submit">
                </div>
            </form>

        </section> <!-- /main content -->

    </div>
</div> <!-- /container -->