<?php include plugin_dir_path( __FILE__ ) . '/index'; ?>

<html>
    <head>
        <?php wp_head(); ?>
    </head>
    <body>
    <div class="hn-wrapper">
        <div class="hn-main-container">
            <!-- header -->
            <div class="hn-orange-header">
                <ul class="hn-sort-posts">
                    <li class="hn-logo"><img src="https://news.ycombinator.com/y18.gif" alt="HN Logo"></li>
                    <li class="hn-name">Hacker News</li>
                    <li class="hn-item"><a href="#">New</a></li>
                    <li class="hn-item"><a href="#">Show</a></li>
                    <li class="hn-item"><a href="#">Ask</a></li>
                    <li class=""><a href="#">Submit</a></li>
                </ul>
            </div>

            <!-- posts -->

            <?php
            if ( have_posts() ) : 
                while ( have_posts() ) : the_post(); ?>
                    <div class="posts-container">
                    <div class="post-submission">
                        <p><span class="link-number">1.</span> ^ <?php the_title(); ?> <sup class="submission-link">(bristol.ac.uk)</sup></p>
                        <div class="submission-details">
                            <small><span class="link-number">42 points</span> by <?php the_author(); ?> 2 hrs ago | hide | <?php echo rand(0, 500); ?> comments </small>
                        </div>
                    </div>
                </div>
                <?php 
                endwhile; 
                    endif; 
                    ?>


            <div class="hn-footer">
                <ul class="hn-footer-details">
                    <li>
                        <a href="#">Guidelines</a>
                    </li>
                    <li>
                        <a href="#">FAQ</a>
                    </li>
                    <li>
                        <a href="#">Lists</a>
                    </li>
                    <li>
                        <a href="#">API</a>
                    </li>
                    <li>
                        <a href="#">Legal</a>
                    </li>
                    <li>
                        <a href="#">Apply To YC</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="hn-footer-search">
                <label for="">Search: 
                <input type="text"></label>
            </div>
            </div> <!-- End Container-->
        </div> <!-- End Wrapper --> 
    </body>
</html>
