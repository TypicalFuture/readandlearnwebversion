<?php

require('connect.php');

if (isset($_POST['search'])){

	$name = $_POST['search'];

	$query = "SELECT teacherName FROM `teachers` WHERE teacherName LIKE '%$name%' LIMIT 5";

	$result = mysqli_query($connection, $query);

	echo "<ul class='t-ul'>";

	while ($res = mysqli_fetch_array($result)) {
		?>
		<li class="t-li" onclick='fill("<?php echo $res['teacherName']; ?>")'>
			<a class="t-a">
				<?php echo $res['teacherName'];?>
			</a>
		</li>
		<?php
	}
		
	echo'</ul>';
};
?>