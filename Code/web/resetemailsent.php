
<?php 
/*
SQL ERROR!

UPDATED:
This file finds the user's email in the database to reset password.
If e-mail not found displays error. 
Otherwise updates password in database & sends verification e-mail.

PREVIOUS VERSION:
Send e-mail and then access database!

*/
include('logoutheader.php');
?>
<div id="mainContainer">

	<div class="divider"></div>

	<div id="loginContainer" >
        			<?php 
				$toUserEmail = $_POST['toemail']; 
				$from = "service@snap2ask.com"; 
				$headers = "From:" . $from; 
				
				
				$email = $_POST['toemail'];
				$password = $_POST['newpass'];
			    //connect to databe
				$dbConnection = mysql_connect("localhost", "snap2ask", "snap2ask");
				if (!$dbConnection)
				{
					die("Connection failure: " . mysql_error());
				}
				mysql_select_db("snap2ask", $dbConnection) or die ("It couldn'tslect snap2ask database. Error: " . mysql_error());

				$getUser = "SELECT * from users WHERE email = '{$email}';";
				$user = mysql_query($getUser);
				$myUser = mysql_fetch_assoc($user);
				if ($myUser != NULL)
				{
					$resetPass = "UPDATE users set password = '{$password}' where id = {$myUser['id']};";
					if (!mysql_query($resetPass))
					{
						die ("Impossible to reset Password" . mysql_error());
					}
				
				$message = "Your new Snap2Ask password is: " . $_POST['newpass']; 
    			mail($toUserEmail,'New Password',$message,$headers);
				echo $_POST['toemail'] . "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass'];
				echo $_POST['toemail'] . "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass'];
				
				?>
		<h1>CHECK YOUR E-MAIL</H1>
			<p>
				<a href="http://mail.yahoo.com" name="yahoo" id="yahoo" class="email">Yahoo!</a>
				<a href="http://hotmail.com" name="yahoo" id="yahoo" class="email">Hotmail</a>
				<a href="http://gmail.com" name="yahoo" id="yahoo" class="email">Gmail</a>
			</p>
			<p>Your new password has been sent to <?php echo $_POST['toemail'] . "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass']?></p>
			<p>Confirm this password with the password received in your e-mail inbox!</p> 
			<p>Your new password has been sent to </p>
				<p>Confirm this password with the password received in your e-mail inbox!</p>
			</div>
			<p><a href="resetpass.php" name="login" id="login">Send another E-mail</a></p>
			<p><a href="index.php" name="login" id="login">Login</a></p>
<?php
}
    			else
				{
					echo "couldn't find email " . $email . " in our database\r\n";
				}
?>
			<div class="divider"></div>
			<?php include('footer.php');?>
		</div>
	</body>
	</html>
