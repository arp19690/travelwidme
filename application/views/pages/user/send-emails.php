<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <br/>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <h3>We would just keep it simple silly, you can choose to either receive email notifications and important announcements from us or NO emails at all.</h3>
        <br/>
        <h4>We want to keep it as simple as possible, no lengthy choices, just one button.</h4>
    </div>
</div>

<div class="container-fluid margin-top-40">
    <div class="row">
        <div class="col-lg-3">
            <h3>Send me emails:-</h3>
        </div>
        <div class="col-lg-9">
            <div class="btn-group">
                <?php
                    $yes_active = "active";
                    $no_active = "";
                    $text = "Yes";

                    if ($send_emails == "0")
                    {
                        $yes_active = "";
                        $no_active = "active";
                        $text = "No";
                    }
                ?>
                <a href="<?php echo base_url('user/sendEmails/1'); ?>" class="btn btn-default <?php echo $yes_active; ?>">Yes</a>
                <a href="<?php echo base_url('user/sendEmails/0'); ?>" class="btn btn-default <?php echo $no_active; ?>" onclick="return confirm('Are you sure you dont want any emails?');">No</a>
            </div>

            <p class="margin-top-20">Current status: <strong><?php echo $text; ?></strong></p>
        </div>
    </div>
</div>