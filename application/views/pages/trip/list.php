<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <div class='text-right'>
            <a href='<?php echo base_url('trip/plan'); ?>' class='btn btn-default'><span class='glyphicon glyphicon-briefcase'></span>&nbsp;&nbsp;Add New Trip</a>
        </div>
        <br/>

        <div class="col-lg-12 col-xs-12 container">
            <div class="col-lg-7 col-xs-12 margin-bottom-20 ">
                <div class="my-trips-list">
                    <ul class="col-lg-12 col-xs-12">
                        <?php
                            if (!empty($record))
                            {
//                                prd($record);
                                foreach ($record as $key => $value)
                                {
                                    ?>
                                    <li class="">
                                        <a href="<?php echo getTripUrl($value["url_key"]); ?>" class=''><span class='glyphicon glyphicon-map-marker'></span>&nbsp;&nbsp;<?php echo $value["trip_title"]; ?></a><span class='time'>(<?php echo getTimeAgo(strtotime($value["trip_timestamp"])); ?>)</span>
                                        <div class='pull-right'>
                                            <a href='<?php echo base_url("trip/interestedPeople/".$value["url_key"]); ?>' class='black' title='Interested People'><span class='glyphicon glyphicon-user'></span></a>&nbsp;&nbsp;
                                            <a href='<?php echo base_url('trip/plan/edit/' . $value["url_key"]); ?>' class='black' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;
                                            <a href='<?php echo base_url('trip/remove/' . $value["url_key"]); ?>' title='Remove Permanently' onclick="return confirm('Are you sure to remove?');" class='black'><span class='glyphicon glyphicon-trash'></span></a>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            else
                            {
                                echo '<p class="margin-bottom-20 margin-top-20">No results found</p>';
                            }
                        ?>
                    </ul>
                </div>

                <?php
                    //                prd($pagination);
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
</div>