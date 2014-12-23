<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <div class='text-right'>
            <a href='<?php echo base_url('blog/write'); ?>' class='btn btn-default'><i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;Write a Blog</a>
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
                                        <a href="<?php echo base_url('blog/read/' . $value['blog_id']); ?>" class=''><span class='glyphicon glyphicon-file'></span>&nbsp;&nbsp;<?php echo $value["blog_title"]; ?></a><span class='time'>(<?php echo getTimeAgo(strtotime($value["blog_timestamp"])); ?>)</span>
                                        <div class='pull-right'>
                                            <a href='<?php echo base_url('blog/read/' . $value["blog_id"]); ?>' title='View Blog' class='black'><span class='glyphicon glyphicon-search'></span></a>&nbsp;&nbsp;
                                            <a href='<?php echo base_url('blog/delete/' . $value["blog_id"]); ?>' title='Remove Permanently' onclick="return confirm('Are you sure to remove?');" class='black'><span class='glyphicon glyphicon-trash'></span></a>
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