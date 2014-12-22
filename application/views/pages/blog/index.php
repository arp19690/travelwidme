<div class="container margin-bottom-20">
    <div class="row">
        <h1><?php echo SITE_NAME; ?>&nbsp;Blogs</h1>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12 text-right">
            <a href='<?php echo base_url('blog/write'); ?>' class='btn btn-default '><i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;Write a Blog</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <section class="col-lg-9 blog-block">
            <?php
                if (!empty($record))
                {
                    foreach ($record as $key => $value)
                    {
//                    prd($value);
                        $blog_id = $value["blog_id"];
                        $title = stripslashes($value["blog_title"]);
                        $link = base_url('blog/read/' . $blog_id);
                        $description = stripslashes(getNWordsFromString(strip_tags($value["blog_content"]), 100));
                        $date = date("F d, Y", strtotime($value["blog_timestamp"]));
                        $author = "Guest";

                        if (!empty($value["user_id"]) || $value["user_id"] != '0')
                        {
                            $author = ucwords($value["user_full_name"]);
                        }
                        ?>
                        <article class="blog-article">
                            <div class="">
                                <?php
                                $img_file_name = BLOG_IMG_PATH . "/" . getEncryptedString($blog_id) . ".jpg";
                                if (is_file($img_file_name))
                                {
                                    echo '<a href="' . $link . '"><img src="' . base_url($img_file_name) . '" alt="' . $title . '" class="margin-bottom-20 max-width-100 lazy" data-original="' . base_url($img_file_name) . '"/></a>';
                                }
                                ?>
                                <h2 class="blog-title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
                                <div class="blog-author-date">
                                    Posted by: <strong><?php echo $author; ?></strong> on <span><?php echo $date; ?></span>
                                </div>

                                <div class="blog-content"><?php echo $description; ?></div>

                                <a href="<?php echo $link; ?>" title="Read more" class="btn btn-primary"><span class="glyphicon glyphicon-share"></span>&nbsp;&nbsp;CONTINUE READING...</a>
                            </div>
                        </article>
                        <?php
                    }
                }
                else
                {
                    echo '<p>No blogs found.</p>';
                }

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
        </section> <!-- /main content -->

        <section class="col-lg-3 margin-top-40">
            <div class="chitika-ad">
                <?php echo getChitikaAd(); ?>
            </div>

            <div class="chitika-ad margin-top-20">
                <?php echo getChitikaAd(); ?>
            </div>
        </section>
    </div>
</div> <!-- /container -->

<?php
    if (!isset($this->session->userdata["user_id"]))
    {
        ?>
        <script>
            $(".write-a-blog").click(function(event) {
                event.preventDefault();
                alert('Please login in order to write your own blog');
            });
        </script>
        <?php
    }
?>