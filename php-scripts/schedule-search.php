<?php

require('writter.php');

$select = $_POST['select'];

$name = $_POST['name'];

if ((!empty($select)) && (!empty($name))) {
	if($select === 'Преподаватель'){
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
	}
	if($select === 'Группа/класс'){
		$query = "SELECT distinct class FROM `schedule` WHERE class LIKE '%$name%' GROUP BY class LIMIT 5";

		$result = mysqli_query($connection, $query);

		echo "<ul class='t-ul'>";

		while ($res = mysqli_fetch_array($result)) {
			?>
			<li class="t-li" onclick='fill("<?php echo $res['class']; ?>")'>
				<a class="t-a">
					<?php echo $res['class'];?>
				</a>
			</li>
			<?php
		}
			
		echo'</ul>';
	}
	if($select === 'Учащийся'){
		$query = "SELECT full_name FROM `students` WHERE full_name LIKE '%$name%' GROUP BY full_name LIMIT 5";

		$result = mysqli_query($connection, $query);

		echo "<ul class='t-ul'>";

		while ($res = mysqli_fetch_array($result)) {
			?>
			<li class="t-li" onclick='fill("<?php echo $res['full_name']; ?>")'>
				<a class="t-a">
					<?php echo $res['full_name'];?>
				</a>
			</li>
			<?php
		}
			
		echo'</ul>';
	}
}

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