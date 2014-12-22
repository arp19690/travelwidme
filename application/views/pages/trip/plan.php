<?php
    if (isset($record))
    {
        extract($record);
        $from_date = str_replace("-", "/", $from_date);
        $to_date = str_replace("-", "/", $to_date);
        $key = $url_key;
    }
    else
    {
        $trip_title = "";
        $trip_purpose = "";
        $trip_destination = "";
        $from_date = "";
        $to_date = "";
        $num_members = "";
        $avg_budget = "";
        $trip_expectations = "";
        $trip_detail = "";
        $user_about = "";
        $user_hobbies = "";
        $previous_places = "";
        $user_languages = "";
        $key = "";
    }
?>

<div class="container-fluid">
    <div class="row">
        <h1>Add New Trip</h1>
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right margin-bottom-20'><span class='glyphicon glyphicon-arrow-left'></span>&nbsp;Back</a>

        <div class="">
            <div class="jumbotron plan-a-trip col-lg-12">
                <form class='validate-form' action='<?php echo base_url('trip/plan'); ?>' method='post' autocomplete="off" enctype="multipart/form-data" role="form">
                    <input type='hidden' name='key' value='<?php echo $key; ?>'/>
                    <div class="container">
                        <div class="row-fluid col-lg-8">
                            <p>Title:</p>
                            <input type='text' class='form-control required' name='trip_title' required="required" maxlength="200" placeholder="Title" value='<?php echo $trip_title; ?>'/>
                        </div>
                    </div>

                    <h1 class="title margin-top-40">Tell 'em more about your plans</h1>

                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-4 clearfix">
                            <p>I'd like to:</p>
                            <select class="form-control required" name="trip_purpose" required="required">
                                <?php
                                    $purpose_array = array(
//                                        "host" => "Host", 
                                        "travel" => "Travel"
                                        );
                                    foreach ($purpose_array as $pKey => $pValue)
                                    {
                                        $purpose_selected = "";
                                        if (empty($trip_purpose) && $pKey == "travel")
                                            $purpose_selected = "selected='selected'";
                                        elseif ($trip_purpose == $pKey)
                                            $purpose_selected = "selected='selected'";

                                        echo '<option value="' . $pKey . '" ' . $purpose_selected . '>' . $pValue . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="row-fluid col-lg-8 clearfix">
                            <p>My destination would be:</p>
                            <input type='text' class='form-control gMapLocation required' name='trip_destination' required="required" placeholder="Choose your destination" value='<?php echo $trip_destination; ?>'/>
                        </div>
                    </div>

                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-4 clearfix">
                            <p>From:</p>
                            <input type='text' name='from_date' class='form-control datepicker' id="from" required="required" placeholder="From date" value='<?php echo $from_date ?>'/>
                        </div>

                        <div class="row-fluid col-lg-4 clearfix">
                            <p>To:</p>
                            <input type='text' name='to_date' class='form-control datepicker' id="to" required="required" placeholder="To date" value='<?php echo $to_date; ?>'/>
                        </div>

                        <div class="row-fluid col-lg-4 clearfix">
                            <p>Number of persons for it:</p>
                            <select class="form-control" name="num_members" required="required">
                                <?php
                                    $members_array = array("1", "2", "3", "4", "5", "6", "7+");
                                    foreach ($members_array as $key => $value)
                                    {
                                        $num_members_selected = "";
                                        if ($value == $num_members)
                                            $num_members_selected = "selected='selected'";

                                        echo '<option value="' . $value . '" ' . $num_members_selected . '>' . $value . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-4 clearfix">
                            <p>Average budget per person:</p>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type='text' name='avg_budget' class='form-control required add-comma-digits' required="required" placeholder="Average budget in USD" value='<?php echo $avg_budget; ?>'/>
                            </div>
                        </div>
                        
                        <div class="row-fluid col-lg-8 clearfix">
                            <p>Tell 'em all about it in detail (Overview):</p>
                            <textarea name="trip_detail" required="required" minlength="120" placeholder="Tell 'em all about it in detail" class="form-control required textarea-resize-none"><?php echo $trip_detail; ?></textarea>
                        </div>
                    </div>

                    <h1 class="title margin-top-40">Tell 'em something about yourself</h1>

                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-6 clearfix">
                            <p>About yourself:</p>
                            <textarea name="user_about" required="required" placeholder="About yourself" class="form-control required textarea-resize-none"><?php echo $user_about ?></textarea>
                        </div>

                        <div class="row-fluid col-lg-6 clearfix">
                            <p>Your hobbies:</p>
                            <textarea name="user_hobbies" required="required" placeholder="Your Hobbies" class="form-control required textarea-resize-none"><?php echo $user_hobbies; ?></textarea>
                        </div>
                    </div>

                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-6 clearfix">
                            <p>Places you've been to:</p>
                            <textarea name="previous_places" required="required" placeholder="Places you've been to" class="form-control required textarea-resize-none"><?php echo $previous_places; ?></textarea>
                        </div>

                        <div class="row-fluid col-lg-6 clearfix">
                            <p>Languages you speak:</p>
                            <textarea name="user_languages" required="required" placeholder="Languages you speak" class="form-control required textarea-resize-none"><?php echo $user_languages; ?></textarea>
                        </div>
                    </div>

                    <div class='container margin-top-20'>                   
                        <div class="row-fluid col-lg-6 clearfix">
                            <p>Your expectations/any guidelines/rules:</p>
                            <textarea name="trip_expectations" required="required" placeholder="Your expectations/guidelines/rules" class="form-control required textarea-resize-none"><?php echo $trip_expectations; ?></textarea>
                        </div>
                    </div>

                    <h1 class="title margin-top-40">Show 'em who you are and those who are already on</h1>

                    <div class='container margin-top-20'>
                        <div class='row-fluid'>
                            <div class='col-lg-6'>
                                <p>Trip Header Image:</p>
                                <div class='position-relative margin-top-20'>
                                    <a class='btn btn-default' href='javascript:;'>
                                        Choose Image...
                                        <input type="file" class="custom-input-file required" required="required" name="trip_header_img" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                    </a>
                                    &nbsp;
                                    <span class='label label-info' id="upload-file-info"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='container margin-top-20'>
                        <div class='row-fluid'>
                            <div class='col-lg-12'>
                                <p>More Images:</p>
                            </div>

                            <div class=''>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                            </div>

                            <div class=''>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                            </div>

                            <div class=''>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='position-relative margin-top-20'>
                                        <a class='btn btn-default' href='javascript:;'>
                                            Choose Image...
                                            <input type="file" class="custom-input-file" name="trip_img[]" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                                        </a>
                                        &nbsp;
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br/>
                    <br/>

                    <div class='container margin-top-20 text-center'>
                        <div class='row'>
                            <input type='checkbox' value='1' checked="checked" id='agree' class="required" required="required"/>&nbsp;&nbsp;<label for='agree'>I agree to the <a href='<?php echo base_url('terms'); ?>' target="_blank">Terms &amp; Conditions</a> of <?php echo SITE_NAME; ?></label>
                        </div>
                    </div>
                    <div class='container margin-top-20'>
                        <div class="row-fluid col-lg-6 clearfix col-lg-offset-3">
                            <input type='submit' name='btn_submit' class='btn btn-block btn-info btn-lg disable-btn' id="plan-form-submit" value='Submit'/>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url(CSS_PATH . "/cupertino/jquery-ui-1.10.4.custom.min.css"); ?>" rel="stylesheet">
<script type='text/javascript' src="<?php echo base_url(JS_PATH . "/jquery-ui-1.10.4.custom.min.js"); ?>"></script>
<script type='text/javascript'>
                                            $("#from").datepicker({
                                                defaultDate: "+1w",
                                                changeMonth: true,
                                                changeYear: true,
                                                numberOfMonths: 1,
                                                minDate: 0,
                                                onClose: function(selectedDate) {
                                                    $("#to").datepicker("option", "minDate", selectedDate);
                                                }
                                            });
                                            $("#to").datepicker({
                                                defaultDate: "+1w",
                                                changeMonth: true,
                                                changeYear: true,
                                                numberOfMonths: 1,
                                                onClose: function(selectedDate) {
                                                    $("#from").datepicker("option", "maxDate", selectedDate);
                                                }
                                            });

//                                                $("#plan-form-submit").click(function(event) {
//                                                    if ($("#agree").val() != "1")
//                                                    {
//                                                        event.preventDefault();
//                                                        alert('Please check');
//                                                    }
//                                                });
</script>