<nav class="sections ">
                    <ul>
                        <li><a href="<?php echo action('item/edit/' . $id); ?>" class="sections"><?php echo __('site.edit_item'); ?></a></li>
                        <li><a href="<?php echo action('transaction/list/overview/' . $id); ?>" class="sections"><?php echo __('site.transactions'); ?></a></li>
                        <li><a href="<?php echo action('item/checkin/' . $id); ?>" class="sections"><?php echo __('site.check_in_item'); ?></a></li>
                        <li><a href="<?php echo action('item/checkout/' . $id); ?>" class="sections"><?php echo __('site.check_out_item'); ?></a></li>
                    </ul>
                </nav>
