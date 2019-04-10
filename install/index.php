<?php 

// Turn off all error reporting
error_reporting(0);

require 'install.php';

$errors  = array();
$success = false;
$install = new Install();

$check_req = $install->check_requirements();
if( ! empty($check_req)) $errors = $check_req;
 

if(isset($_POST['submit']) && empty($check_req))
{

    if($_POST['database_host'] != ''&& $_POST['database_name'] != '' && $_POST['database_username'] != '')
    {
        $check_db = $install->check_connect(
            $_POST['database_host'],
            $_POST['database_name'],
            $_POST['database_username'],
            ($_POST['database_password'] != '') ? $_POST['database_password'] : null
        );

        if($check_db)
        {
            $install->create_tables();
            
            $install->set_config();
            
            $success = true;
        }
        else
        {
            $errors[] = 'Database connection error';
        }
    }
    else
    {
        $errors[] = 'These field are required!';
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Inventory</title>
    <meta name="viewport" content="width=device-width">
    <link href='http://fonts.googleapis.com/css?family=Headland+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <style>
    html, body {
        margin: 0;
        padding: 0;
    }
    body {
        font: 12px 'Helvetica', Arial, sans-serif;
        background-color: #F1F1F1;
    }
    .login {
        background-color: #FFFFFF;
        text-align: center;
        padding: 20px;
        width:300px;
        border:10px solid #E3E3E3;
        border-radius: 5px 5px 5px 5px;
        margin: 80px auto 0;
    }
    .login table {
		width: 100%;
    }
    .status {
        padding: 5px;
        background-color: #FFE38B;
        color: #705D14;
        font-weight: bold;
        margin: 0 0 10px;
    }
    h1 {
        font: bold 22px 'Headland One', 'Helvetica', Arial, sans-serif;
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
    input, select {
        margin: 0 0 10px;
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
        background-color: #006DCC;
        background-image: linear-gradient(to bottom, #0088CC, #0044CC);
        background-repeat: repeat-x;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        color: #FFFFFF;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        border-radius: 4px 4px 4px 4px;
        border-style: solid;
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
        <div class="login">
            <h1>The Inventory</h1>
            <?php if(!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                <p class="status"><?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if($success === true): ?>

            <p>Thanks for installing!</p>
            <p>You can now use the site, just by going to the root folder</p>
            <p>You can log in with the username "admin" and password "password"</p>
            <p>Make sure you delete the "install" folder and change admin password</p>

            <?php endif; ?>

            <?php if(empty($check_req) && $success === false): ?>
            <form action="" method="post">
    			<table class="form">
    				<tr>
    					<td>
    						<label>MySQL Host</label>
    						<input type="text" name="database_host" value="<?php echo isset($_POST['database_host']) ? $_POST['database_host'] : '';?>" />
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<label>MySQL Database</label>
    						<input type="text" name="database_name" value="<?php echo isset($_POST['database_host']) ? $_POST['database_name'] : '';?>" />
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<label>MySQL Username</label>
    						<input type="text" name="database_username" value="<?php echo isset($_POST['database_host']) ? $_POST['database_username'] : '';?>" />
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<label>MySQL Password</label>
    						<input type="password" name="database_password" value="<?php echo isset($_POST['database_host']) ? $_POST['database_password'] : '';?>" />
    					</td>
    				</tr>
                    <tr>
                    <td>
                        <label>Timezone</label>
                        <select name="timezone">
                        <?php 
                        $timezones = timezone_identifiers_list();

                        foreach($timezones as $timezone)
                        {
                          echo '<option';
                          echo $timezone == 'UTC' ? ' selected' : '';
                          echo '>' . $timezone . '</option>' . "\n";
                        }

                        echo '</select>' . "\n";
                        ?>
                        </select>   
                    </td>
                </tr>
    				<tr>
    					<td>
    						<input type="submit" name="submit" value="Submit" />
    					</td>
    				</tr>
    			</table>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
