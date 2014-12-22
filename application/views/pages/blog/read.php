
<div class="container margin-bottom-20">
    <div class="row">
        <h1><?php echo SITE_NAME; ?>&nbsp;Blog</h1>                
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="text-right">
            <a href='javascript:history.go(-1);' class='btn btn-default'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp;Back</a>
        </div>
    </div>
</div>

<div class="col-lg-8">
    <div class="row-fluid">
        <section class="">
            <article class="blog">
                <h2 class="blog-title margin-bottom-20"><?php echo stripslashes($record["blog_title"]); ?></h2>
                <div class="blog-author-date margin-bottom-20">
                    <?php
                        $user_full_name = "Guest";

                        if (!empty($record["user_id"]) || $record["user_id"] != '0')
                        {
                            $user_full_name = $record["user_full_name"];
                        }
                    ?>
                    Posted by: <strong><?php echo ucwords($user_full_name); ?></strong> on <span><?php echo date("F d, Y", strtotime($record["blog_timestamp"])); ?></span>
                </div>

                <div class="">
                    <?php
                        $img_file_name = BLOG_IMG_PATH . "/" . getEncryptedString($record["blog_id"]) . ".jpg";
                        if (is_file($img_file_name))
                        {
                            echo '<img src="' . base_url($img_file_name) . '" alt="' . $record["blog_title"] . '" class="margin-bottom-20 max-width-100"/>';
                        }
                    ?>
                </div>

                <div class="blog-content">
                    <?php echo stripslashes($record["blog_content"]); ?>
                </div>
            </article>

            <hr />

            <!--  ==========  -->
            <!--  = Comments =  -->
            <!--  ==========  -->

            <section id="comments" class="comments-container">
                <div id="disqus_thread"></div>
                <script type="text/javascript">
                    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                    var disqus_shortname = '<?php echo DISQUS_SHORTNAME; ?>'; // required: replace example with your forum shortname

                    /* * * DON'T EDIT BELOW THIS LINE * * */
                    (function() {
                        var dsq = document.createElement('script');
                        dsq.type = 'text/javascript';
                        dsq.async = true;
                        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                    })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>

            </section>

        </section> <!-- /main content -->
    </div>
</div>

<!--  ==========  -->
<!--  = Sidebar =  -->
<!--  ==========  -->
<div  class="col-lg-4 margin-top-40">
    <aside>
        <div class="col-lg-12 col-xs-12">
            <!-- AddThis Button BEGIN -->
            <?php
                echo getAddThis();
            ?>
            <!-- AddThis Button END -->
        </div>

        <?php
            if (!empty($recent_blogs))
            {
                ?>
                <div class="col-lg-12 col-xs-12 margin-top-40">
                    <h3>Recent Blogs</h3>
                    <ul class="recent-blogs ">
                        <?php
                        foreach ($recent_blogs as $rKey => $rValue)
                        {
                            echo '<li><a title="' . $rValue["blog_title"] . '" href="' . base_url('blog/read/') . $rValue["blog_id"] . '">' . $rValue["blog_title"] . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        ?>

        <div class="chitika-ad margin-top-40">
            <?php echo getChitikaAd(); ?>
        </div>
    </aside> <!-- /sidebar -->         
</div>