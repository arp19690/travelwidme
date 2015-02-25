<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <a href='javascript:history.go(-1);' class='btn btn-default pull-right margin-bottom-20'><span class='glyphicon glyphicon-arrow-left'></span>&nbsp;Back</a>
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
                                        <div class="col-lg-2 text-center">
                                            <img src="<?php echo getUserImage($value['user_id'],$value['user_facebook_id'], NULL, 75, 75); ?>" alt="image" class="img-circle my-trip-interested-img"/>
                                        </div>

                                        <div class="col-lg-7">
                                            <h3><?php echo ucwords($value['first_name'] . ' ' . $value['last_name']); ?></h3>
                                            <p class="interested-msg-font"><?php echo stripslashes($value['interested_message']); ?></p>
                                        </div>

                                        <div class='col-lg-3 text-right'>
                                            <a href='<?php echo getPublicProfileUrl($value['username']); ?>' class='' title='View Profile' target="_blank">View Profile</a>&nbsp;&nbsp;<span class='glyphicon glyphicon-share'></span>
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
                
            </div>
        </div>        
    </div>
</div>