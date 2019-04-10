<header>
            <div class="first">
                <div class="container">
                    <h1>Asset Management System</h1>
                    <nav>
                        <ul>
                            <li><a href="<?php echo action('item@list'); ?>"><?php echo __('site.inventory'); ?></a></li>
                            <li><a href="<?php echo action('transaction@list/overview'); ?>">Log</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="second">
                <div class="container">
                    <nav>
                        <ul>
                            <li>
                                <a href="<?php echo action('user@edit/' . Auth::user()->id); ?>"><?php echo Auth::user()->name; ?></a>
                            </li>
                            <?php if( Auth::is('Admin') ): ?>
                            <li>
                                <a href="<?php echo action('setting@site'); ?>"><?php echo __('site.settings'); ?></a>
                                <ul>
                                    <li><a href="<?php echo action('setting@site'); ?>"><?php echo __('site.settings'); ?></a></li>
                                    <li><a href="<?php echo action('user@list'); ?>"><?php echo __('site.user_list'); ?></a></li>
                                    <li><a href="<?php echo action('user@roles'); ?>"><?php echo __('site.user_roles'); ?></a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                            <li><a href="<?php echo action('auth@logout'); ?>"><?php echo __('site.logout'); ?></a></li>
                        </ul>
                    </nav>
                    <nav>
                        <ul>
                            <?php foreach($submenu as $name => $link): ?>
                            <li>
                                <a href="<?php echo action($link); ?>"><?php echo $name; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
    </header>