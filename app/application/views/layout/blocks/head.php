<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>The Inventory</title>
    <meta name="viewport" content="width=device-width">
    <script>
        var SITEURL = '<?php echo URL::to(); ?>';
        var CURRENT_URL = '<?php echo URL::to(Request::uri()); ?>';
        var BASEURL = '<?php echo URL::base(); ?>';
    </script>

    <?php echo Asset::styles(); ?>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.jqueryui.min.css" rel="stylesheet">
    <?php echo Asset::scripts(); ?>

    <script>
    $(document).ready(function() {
        $('.main').on('click', '.delete',  function(e) {
            e.preventDefault();
            var settings={animation:700,buttons:{cancel:{action:function(){Apprise("close")},className:"gray",id:"cancel",text:"<?php echo __('site.cancel'); ?>"},confirm:{action:function(){window.location=$(e.target).attr("href")},className:"red",id:"delete",text:"<?php echo __('site.delete'); ?>"}},input:false,override:false}
            Apprise('<p class="center"><?php echo __('site.sure_delete'); ?></p>', settings);
        });
    });
    </script>





</head>
<body>