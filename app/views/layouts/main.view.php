<?php layout('head') ?>
    <div class="content-area">
        <?php if(isLoggedIn()) : ?>
            <div class="navbar"><?php layout('navbar') ?></div>
            <div class="sidebar me-5"><?php layout('sidebar') ?></div>
        <?php endif ?>
        

        <div class="content"><?=$content?></div>

        <!-- <div class="footer"><?php layout('footer')?></div> -->
    </div>

<?php layout('foot'); ?>

