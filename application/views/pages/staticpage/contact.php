<?php
    createCaptcha();
    @$this->session->set_userdata("captcha_contact_answer_system", CAPTCHA_ANSWER);

    if (isset($post))
    {
        extract($post);
    }
   else if ($this->session->flashdata('post'))
    {
        extract($this->session->flashdata('post'));
    }
    else
    {
        $full_name = "";
        $user_email = "";
        $user_contact = "";
        $user_location = "";
        $user_message = "";
    }
?>

<div class="container">
    <div class="row-fluid">
        <h1>Contact Us</h1>
        <p>Your feedback is important to us. Help us making our services even better by providing your valuable suggestions.</p>
        <br/>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <div class="col-lg-8 col-xs-12">
            <form id="contact-form" class="validate-form" action="" method="post" autocomplete="off" role="form">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" class="form-control required" placeholder="Full Name" required="required" value='<?php echo $full_name; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Email:</label>
                    <input type="email" name="user_email" class="form-control required" placeholder="Your Email" required="required" value='<?php echo $user_email; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Contact:</label>
                    <input type="text" name="user_contact" class="form-control required" placeholder="Your Contact" required="required" maxlength="18" value='<?php echo $user_contact; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Location:</label>
                    <input type="text" name="user_location" class="form-control required gMapLocation" placeholder="Your Location" required="required" value='<?php echo $user_location; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Message:</label>
                    <textarea name="user_message" class="form-control required textarea-resize-none" rows="3" placeholder="Your Message" required="required" ><?php echo $user_message; ?></textarea>
                </div>
                <div class="form-group">
                    <label><?php echo CAPTCHA_QUESTION; ?>&nbsp;?:</label>
                    <input type="text" name="captcha_answer_user" class="form-control required" placeholder="Answer" required="required" maxlength="3"/>
                </div>

                <input type="submit" name="btn_submit" class="btn btn-primary btn-lg" value="Send"/>
            </form>
        </div>

        <div class="col-lg-4 col-xs-12">
            
        </div>
    </div>
</div>