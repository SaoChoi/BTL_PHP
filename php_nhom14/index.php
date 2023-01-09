<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="Style/login.css">
	<link rel="stylesheet" type="text/css" href="FontAwesome\css\all.css">
	<link rel="icon" href="Images\Logo.PNG" type="image/x-icon">
	<title>Log in</title>
	<?php
	session_start();
	require __DIR__ . '.\common\configdb.php';
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $database);
	mysqli_set_charset($conn, "utf8");
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
		exit();
	}
	if (isset($_POST['Login'])) {
		$user_name = "";
		$pass_word = "";
		if ($_POST['txtusername'] == null || $_POST['txtpassword'] == null || $_POST['txtusername'] == null && $_POST['txtpassword'] == null) {
			echo '<script language ="javascript">alert("Tài khoản hoặc mật khẩu không chính xác");</script>';
		} else {
			$user_name = $_POST['txtusername'];
			$pass_word = $_POST['txtpassword'];
		}

		$queryLogin = "SELECT * FROM employee e WHERE e.UserName = '" . $user_name . "' AND e.Password = '" . $pass_word . "';";

		$rows = mysqli_query($conn, $queryLogin);
		$user_role;
		$user_id;
		$count = mysqli_num_rows($rows);
		while ($dataUser = mysqli_fetch_assoc($rows)) {
			$user_role = $dataUser['RoleId'];
			$user_id =  $dataUser['Id'];
		};

		if ($count == 1) {
			 $_SESSION['isLogin'] = true;
			$_SESSION['role'] = $user_role;
			$_SESSION['userId'] = $user_id;

			header("Location: TrangChu.php");
		} else {
			echo '<script language ="javascript">alert("Đăng nhập thất bại");</script>';
		}
	}
	mysqli_close($conn);
	?>
</head>

<body>
	<div id="container">
		<div id="left">
			<img id="image" src="Images/warehouse2.jpg" alt="WareHouse" />
		</div>
		<div id="right">
			<div id="loginform">
				<form action="" method="post">
					<table>
						<tr>
							<td>
								<p>Username:</p>
							</td>
						</tr>
						<tr>
							<td>
								<input class="inputstyle" placeholder="Enter your username" type="text" name="txtusername">

							</td>
						</tr>
						<tr>
							<td>
								<p>Password:</p>
							</td>
						</tr>
						<tr>
							<td>
								<input class="inputstyle" placeholder="Enter your password" type="password" name="txtpassword">
							</td>
						</tr>
						<tr>
							<td>
								<input id="loginbutton" type="submit" value="Sign in" name="Login">
							</td>
						</tr>
						<tr>
							<td>
								<p style="text-align: center; font-size: 14px; color: lightslategrey;">Forgot your password? <a href="https://facebook.com">Contact the manager</a> to retrieve</p>
							</td>
						</tr>
						<tr>
							<td>
								<p style="text-align:center; font-size:14px; padding-top: 30px;">Powered by Nhom 14</p>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>

</html>