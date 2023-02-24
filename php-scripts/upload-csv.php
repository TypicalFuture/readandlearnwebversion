<?php

require('updater.php');

var_dump($_POST);

if (isset($_POST['selector'])) {
	/*var_dump($_FILES);
	var_dump($_POST['selector']);
	*/
	$selector = $_POST['selector'];
	
	/*echo $_FILES['file']['name'];
	*/
	move_uploaded_file($_FILES['file']['tmp_name'], "../tmp/".$_FILES['file']['name']);
            
            $file = fopen('../tmp/'.$_FILES['file']['name'].'', 'r');

            while (!feof($file)) {
                $massiv = fgetcsv($file, 1024, ';');
                #var_dump($massiv);
                $j = count($massiv);
                if ($j > 1) {

                    switch ($selector) {
                        case 'infoadd':
                            echo '<script>alert("ИНФОАДД")</script>';
                            $query = "INSERT INTO `infoadd` (`vid`, `name`, `groupName`, `category`, `ageGroupFrom`, `ageGroupTo`, `address`, `teacherName`, `free`, `trainingPeriod`, `lessonType`, `categoryAdd`) VALUES ('".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."', '".$massiv[5]."', '".$massiv[6]."', '".$massiv[7]."', '".$massiv[8]."', '".$massiv[9]."', '".$massiv[10]."', '".$massiv[11]."')";
                            if ($query){
                                 mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                            }
                            break;
                        
                        case 'addschedule':
                            echo '<script>alert("АДДШЕДУЛЯ")</script>';
                            $query = "INSERT INTO `addschedule` (`id`, `groupName`, `teacherName`, `day`, `time`, `timeTo`) VALUES (NULL, '".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."')";
                            if ($query){
                                 mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                             }
                            break;

                        case 'posts':
                            echo '<script>alert("ПОСТС")</script>';
                            $query = "INSERT INTO `posts` (`studentName`, `groupName`) VALUES ('".$massiv[0]."', '".$massiv[1]."')";
                            if ($query){
                                 mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                             }
                            break;
                    }

                    
                }
            }

            fclose($file);

            unlink("../tmp/".$_FILES['file']['name']);

            unset ($_POST['submit']);
}

else{
	echo "Неа";
}


 


?>