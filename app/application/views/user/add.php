    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.add_user'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="add_user add">
                <?php echo Form::open(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <div class="left">
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('username', __('site.username'));
                        echo Form::text('username', Input::get('username'));
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', Input::get('name'));
                        ?>
                        </div>
                        <div class="col2">
                        <?php
                        echo Form::label('password1', __('site.password'));
                        echo Form::password('password1');
                        echo Form::label('password2', __('site.password_again'));
                        echo Form::password('password2');
                        ?>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('email', __('site.email'));
                        echo Form::text('email', Input::get('email'));
                        echo Form::label('role', __('site.user_role'));
                        echo Form::select('role', $roles);
                        ?>
                        </div>
                    </div>
                </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.add_user'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>