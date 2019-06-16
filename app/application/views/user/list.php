    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.users'); ?></h2>
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
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                });
            });
            </script>
            <div class="users_list">
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th><?php echo __('site.name'); ?></th>
                            <th><?php echo __('site.username'); ?></th>
                            <th><?php echo __('site.email'); ?></th>
                            <th><?php echo __('site.user_role'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $user): ?>
                        <tr class="odd gradeX">
                            <td><a href="<?php echo action('user@edit') . '/' . $user->id; ?>"><?php echo $user->name; ?></a></td>
                            <td><?php echo $user->username; ?></td>
                            <td><?php echo $user->email; ?></td>
                            <td><?php echo $user->rolename; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

