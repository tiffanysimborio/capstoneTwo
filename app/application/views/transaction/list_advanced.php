    <div class="main container wide">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.transactions') . ' ' .  __('site.advanced_view'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <script>
            $(document).ready(function() {
                $('#example').dataTable( {
                    "fnDrawCallback": function( oSettings ) {
                      $('a.screenshot').imgPreview( {imgCSS: { width: 300, height: 300 }} );
                    },
                    "aoColumns": [
                        { "sWidth": "7%" },
                        { "sWidth": "7%" },
                        { "sWidth": "7%" },
                        { "sWidth": "5%" },
                        { "sWidth": "10%" },
                        null,
                        null,
                        { "sWidth": "10%" },
                        { "sWidth": "5%" },
                        { "sWidth": "8%" },
                        { "sWidth": "5%" }
                    ],
                    "bAutoWidth": true,
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "<?php echo URL::to('ajax/transaction_advanced/' . $id); ?>",
                    "iDisplayLength": 50,
                    "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 6 ] }],
                    "aaSorting": [[ 0, "desc" ]],
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                } );
            } );
            </script>
            <div class="transactions_list_advanced add">
                <?php if(isset($id)): ?>
                <?php echo View::make('layout.blocks.nav_sections')->with('id', $id); ?>
                <?php endif; ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th class="tid">Id</th>
                            <th><?php echo __('site.code'); ?></th>
                            <th><?php echo __('site.date'); ?></th>
                            <th><?php echo __('site.type'); ?></th>
                            <th><?php echo __('site.contact'); ?></th>
                            <th><?php echo __('site.item'); ?></th>
                            <th><?php echo __('site.note'); ?></th>
                            <th><?php echo __('site.user'); ?></th>
                            <th><?php echo __('site.quantity'); ?></th>
                            <th><?php echo __('site.price'); ?></th>
                            <th><?php echo __('site.edit'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
