<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Inventory</title>
    <meta name="viewport" content="width=device-width">
    <!-- <link href='http://fonts.googleapis.com/css?family=Headland+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'> -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <style>

    html, body {
        margin: 0;
        padding: 0;
    }
    body {
        /* font: 12px 'Helvetica', Arial, sans-serif; */
        font-family: 'Nunito', sans-serif;

        background-color: #F1F1F1;
        background-image: linear-gradient(141deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
   
    }  


    .container{
        height: 100vh;
        width: 100vw;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login {
        background-color: #FFFFFF;
        text-align: center;
        padding: 20px;
        width:300px;
        
        border-radius: 5px 5px 5px 5px;
        box-shadow: 2px 2px rgba(0,0,0,0.1);
    }
    h1 {
        font: bold 22px 'Nunito', sans-serif;
        margin: 0 0 10px;
    }
    label {
        display: block;
        margin: 0 0 10px;
    }
    p {
        padding: 0;
        margin: 10px 0;
    }
    input[type=text], input[type=password] {
        width: 90%;
        height: 25px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        border-radius: 4px 4px 4px 4px;
    }
    input[type=submit] {
        background-color: #f67280;
        /* background-image: linear-gradient(to bottom, #0088CC, #0044CC); */
        background-repeat: repeat-x;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        color: #ffffff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        border-radius: 4px 4px 4px 4px;
        border-style: none;
        border-width: 1px;
        box-shadow: 0 0 2px 0 #DCDCDC;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        line-height: 20px;
        margin-bottom: 0;
        padding: 6px 20px;
        text-align: center;
        vertical-align: middle;
    }



    </style>
</head>
<body>
    <div class="container">
        <div class="login" style="">
            <h1>Asset Management System</h1>
            <div class="message">
                <?php Vsession::cprint('status'); ?>
            </div>
            <?php echo Form::open(); ?>
            <?php echo Form::token(); ?>
            <p>
            <?php
            echo Form::label('username', 'Username');
            echo Form::text('username');
            ?>
            </p>
            <p>
            <?php
            echo Form::label('password', 'Password');
            echo Form::password('password');
            ?>
            </p>
            <p>
            <?php
            echo Form::submit('Login', array('name'=>'login'));
            ?>
            </p>
            <?php echo Form::close(); ?>
        </div>
    </div>
</body>
</html>
