    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.user_roles'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <script>
            $(document).ready(function() {
                $('#example').dataTable({
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                });
            } );
            </script>
            <div class="roles_list">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th><?php echo __('site.level'); ?></th>
                            <th><?php echo __('site.name'); ?></th>
                            <th><?php echo __('site.description'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($permissions as $id => $permission): ?>
                        <tr class="odd gradeX">
                            <td><?php echo $permission['level']; ?></td>
                            <td><?php echo $permission['name']; ?></td>
                            <td>
                                <?php foreach($permission['permissions'] as $list): ?>
                                    <?php echo $list . ', ' ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>