    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.add_category') ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="add_category add">
                <?php echo Form::open_for_files(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                    <div class="row1">
                        <?php
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', Input::get('name'), array('class' => 'wide'));
                        ?>
                    </div>
                    <div class="row1">
                        <?php
                        echo Form::label('description', __('site.description'));
                        echo Form::textarea('description', Input::get('description'), array('class' => 'wide'));
                        ?>
                    </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.add_category'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>