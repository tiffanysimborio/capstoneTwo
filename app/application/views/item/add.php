    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo __('site.add_item'); ?></h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="add_item add">
                <?php echo Form::open_for_files(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <div class="left">
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', Input::get('name'));
                        echo Form::label('location', __('site.location'));
                        echo Form::text('location', Input::get('location'));
                        echo Form::label('category', __('site.category'));
                        echo Form::select('category', $categories);
                        ?>
                        </div>
                        <div class="col2">
                        <?php
                        echo Form::label('buying_price', __('site.buying_price'));
                        echo Form::text('buying_price', Input::get('buying_price'));
                        echo Form::label('selling_price', __('site.selling_price'));
                        echo Form::text('selling_price', Input::get('selling_price'));
                        echo Form::label('code', __('site.code'));
                        echo Form::text('code', Input::get('code'));
                        ?>
                        </div>
                    </div>
                    <div class="row1">
                        <?php
                        echo Form::label('description', __('site.description'));
                        echo Form::textarea('description', Input::get('description'), array('class' => 'half'));
                        ?>
                    </div>
                </div>
                <div class="right">
                    <div class="row1">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 350px; height: 350px;">
                                <img src="http://www.placehold.it/350x350/EFEFEF/AAAAAA&text=no+image" />
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 350px; max-height: 350px; line-height: 20px;"></div>
                            <div>
                                <span class="btn btn-file">
                                    <span class="fileupload-new"><?php echo __('site.select_image'); ?></span>
                                    <span class="fileupload-exists"><?php echo __('site.change'); ?></span>
                                    <input type="file" name="image" />
                                </span>
                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.add_item'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>