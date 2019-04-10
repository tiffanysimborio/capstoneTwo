    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.items'); ?></h2>
            </div>
             <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <script>
            $(document).ready(function() {
                $('#example').dataTable({
                    "fnDrawCallback": function( oSettings ) {
                      $('a.screenshot').imgPreview( {imgCSS: { width: 300, height: 300 }} );
                    },
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "<?php echo URL::to('ajax/item_basic'); ?>",
                    "iDisplayLength": 50,
                    "aaSorting": [[ 1, "asc" ]],
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                });
            });
            </script>
            <div class="product_list">
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display dark" id="example">
                    <thead>
                        <tr>
                            <th><?php echo __('site.code'); ?></th>
                            <th><?php echo __('site.name'); ?></th>
                            <th><?php echo __('site.category'); ?></th>
                            <th><?php echo __('site.location'); ?></th>
                            <th><?php echo __('site.quantity'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $item): ?>
                        <tr class="odd gradeX">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
