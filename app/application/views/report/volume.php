    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.report_volume'); ?></h2>
            </div>
             <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="product_list">
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
            </div>
        </div>
    </div>
