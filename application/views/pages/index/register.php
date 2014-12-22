<?php
    $first_name = "";
    $last_name = "";
    $user_email = "";
    $user_gender = "";
    $username = "";

    if ($this->session->flashdata("post"))
    {
        extract($this->session->flashdata("post"));
    }
?>
<div class="container margin-bottom-40 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">

    <div class="row margin-bottom-20 clearfix text-center">
        <a href="<?php echo base_url("login/facebook?next=" . current_url()); ?>" class="btn btn-primary btn-lg margin-top-20 facebook-blue-btn" title="Login with facebook" id="fbLoginButton"><span class="facebook-initial">f</span>&nbsp;&nbsp;Login with facebook</a>
        <p class="margin-top-20">Or,</p>
        <hr/>
    </div>

    <div class="row margin-bottom-20 clearfix">
        <div class="col-lg-12">
            <h1>Sign Up with Email</h1>
            <a href='javascript:history.go(-1);' class='btn btn-default pull-right'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
        </div>
    </div>

    <div class="row">
        <!--  ==========  -->
        <!--  = Main content =  -->
        <!--  ==========  -->
        <section class="">

            <form class="form-horizontal validate-form" method='post' action='' enctype="multipart/form-data">
                <div class="margin-bottom-20 clearfix">
                    <div class="col-lg-6">
                        <label for="first_name" >First Name <span class="required">*</span></label>
                        <input class="form-control required" name="first_name" id="first_name" type="text" required="required" placeholder="First Name" value='<?php echo $first_name; ?>'/>
                    </div>
                    <div class="col-lg-6">
                        <label for="last_name" >Last Name <span class="required">*</span></label>
                        <input class="form-control required" name="last_name" id="last_name" type="text" required="required" placeholder="Last Name" value='<?php echo $last_name; ?>'/>
                    </div>
                </div>

                <div class="margin-bottom-20 clearfix">
                    <div class="col-lg-6">
                        <label for="user_gender" >Gender <span class="required">*</span></label>
                        <select class="form-control required" name="user_gender" id="user_gender" required="required">
                            <?php
                                $gender_Array = array(
                                    "" => "Select",
                                    "male" => "Male",
                                    "female" => "Female",
                                );

                                foreach ($gender_Array as $key => $value)
                                {
                                    $gender_selected = "";
                                    if ($user_gender == $key)
                                        $gender_selected = "selected='selected'";

                                    echo '<option value="' . $key . '" ' . $gender_selected . '>' . $value . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="username" >Username <span class="required">*</span></label>
                        <input class="form-control required" name="username" id="username" type="text" required="required" placeholder="Username" value='<?php echo $username; ?>'/>
                    </div>
                </div>

                <div class="margin-bottom-20 clearfix">
                    <div class="col-lg-6">
                        <label for="user_email" >Email <span class="required">*</span></label>
                        <input class="form-control required" name="user_email" id="user_email" type="text" required="required" placeholder="Email" value='<?php echo $user_email; ?>'/>
                    </div>
                    <div class="col-lg-6">
                        <label for="user_password" >Password <span class="required">*</span></label>
                        <input class="form-control required" name="user_password" id="user_password" type="password" required="required" placeholder="Password" value=''/>
                    </div>
                </div>

                <div class="margin-bottom-20 clearfix">
                    <div class="col-lg-12">
                        <label for="user_img" >Profile Image:</label>
                        <div class='position-relative'>
                            <a class='btn btn-default' href='javascript:;'>
                                Choose Image...
                                <input type="file" class="custom-input-file" name="user_img" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                            </a>
                            &nbsp;
                            <span class='label label-info' id="upload-file-info"></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center">	
                    <input class="btn btn-primary btn-lg disable-btn" name="btn_submit" value="Register" type="submit">
                </div>
            </form>

        </section> <!-- /main content -->

    </div>

    <div class="row margin-top-40 text-right">
        <a href="<?php echo base_url('login'); ?>">Already registered?</a>
    </div>
</div> <!-- /container -->