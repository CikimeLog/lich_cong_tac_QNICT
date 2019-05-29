<?php
if (isset($_SESSION['username']) == false) {
	// Nếu người dùng chưa đăng nhập thì chuyển hướng website sang trang đăng nhập
	header('Location: ../manager/login.php');
}else {
	if (isset($_SESSION['auth']) == true) {
		// Ngược lại nếu đã đăng nhập
		$auth = $_SESSION['auth'];
		// Kiểm tra quyền của người đó có phải là admin hay không
		if ($auth == '0') {
			// Nếu không phải admin thì xuất thông báo
			echo "Bạn không đủ quyền truy cập vào trang này<br>";
			echo "<a href='http://localhost:8080/lichcongtac/manager/login.php'> Click để đăng nhập </a>";
			exit();
		}
	}
}
?>