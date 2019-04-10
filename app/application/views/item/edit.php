    <div class="main container">
        <div class="head">
            <div class="title">
                <h2><?php echo $item->name ?> (<?php echo $item->quantity ?>)</h2>
            </div>
            <nav>
                <?php echo $item_buttons; ?>
            </nav>
        </div>
        <div class="body">
            <div class="edit_item add">
                <?php echo View::make('layout.blocks.nav_sections')->with('id', $item->id); ?>
                <?php echo Form::open_for_files(); ?>
                <div class="message">
                    <?php Vsession::cprint('status'); ?>
                </div>
                <div class="left">
                    <div class="row1">
                        <div class="col1">
                        <?php
                        echo Form::label('name', __('site.name'));
                        echo Form::text('name', (Input::get('name') != '') ? Input::get('name') : $item->name );
                        echo Form::label('location', __('site.location'));
                        echo Form::text('location', (Input::get('location') != '') ? Input::get('location') : $item->location );
                        echo Form::label('category', __('site.category'));
                        echo Form::select('category', $categories, $item->category_id);
                        echo Form::label('cost_total', __('site.total_cost'));
                        echo Form::text('cost_total', $item->cost_total, array('disabled' => 'disabled') );
                        ?>
                        </div>
                        <div class="col2">
                        <?php
                        echo Form::label('buying_price', __('site.buying_price'));
                        echo Form::text('buying_price', (Input::get('buying_price') != '') ? Input::get('buying_price') : $item->buying_price );
                        echo Form::label('selling_price', __('site.selling_price'));
                        echo Form::text('selling_price', (Input::get('selling_price') != '') ? Input::get('selling_price') : $item->selling_price );
                        echo Form::label('code', __('site.code'));
                        echo Form::text('code', (Input::get('code') != '') ? Input::get('code') : $item->code );
                        echo Form::label('income_total', __('site.income_total'));
                        echo Form::text('income_total', $item->income_total, array('disabled' => 'disabled') );
                        ?>
                        </div>
                    </div>
                    <div class="row1">
                        <?php
                        echo Form::label('description', __('site.description'));
                        echo Form::textarea('description', (Input::get('description') != '') ? Input::get('description') : $item->description, array('class' => 'half'));
                        ?>
                    </div>
                </div>
                <div class="right">
                    <div class="row1">
                        <?php
                        $image = array();
                        $image = glob('uploads/images/items/' . $item->id . '.*');
                        ?>
                        <?php if(!empty($image) && file_exists($image[0])): ?>
                        <div class="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 350px; height: 350px;">
                                <img src="<?php echo URL::base() . '/' . $image[0] ?>" />
                            </div>
                            <div>
                                <a href="<?php echo action('item/deleteimg/' . $item->id); ?>" class="btn"><?php echo __('site.remove'); ?></a>
                            </div>
                        </div>
                        <?php else: ?>
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
                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo __('site.remove'); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row1 submit">
                    <?php echo Form::submit(__('site.edit_item'), array('name'=>'submit', 'class' => 'btn btn-primary')); ?>
                </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>