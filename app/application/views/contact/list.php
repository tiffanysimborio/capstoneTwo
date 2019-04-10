    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.contacts'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <script>
            $(document).ready(function() {
                $('#example').dataTable({
                    "iDisplayLength": 50,
                    "oLanguage": {"sUrl": "<?php echo URL::base(); ?>/app/assets/js/jquery.dataTables.<?php echo Config::get('application.language') ?>.txt"}
                });
            } );
            </script>
            <div class="contact_list add">
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th><?php echo __('site.name'); ?></th>
                            <th><?php echo __('site.description'); ?></th>
                            <th><?php echo __('site.created'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $contact): ?>
                        <tr class="odd gradeX">
                            <td><a href="<?php echo action('contact@edit/' . $contact->id) ?>"><?php echo $contact->name; ?></a></td>
                            <td><?php echo $contact->description; ?></td>
                            <td><?php echo $contact->created_at; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
