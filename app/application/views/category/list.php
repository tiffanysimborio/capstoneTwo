    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.categories'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <script>
            $(document).ready(function() {
                $('#example').dataTable({
                    "aaSorting": [[ 0, "desc" ]],
                    "iDisplayLength": 50,
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                });
            } );
            </script>
            <div class="categories_list">
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th><?php echo __('site.name'); ?></th>
                            <th><?php echo __('site.description'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $category): ?>
                        <tr class="odd gradeX">
                            <td><a href="<?php echo action('category@edit/' . $category->id) ?>"><?php echo $category->name; ?></a></td>
                            <td><?php echo $category->description; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
