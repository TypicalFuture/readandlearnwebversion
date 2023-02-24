<?php 

global $catAdd;

$table = get_addCheckMenu();
$tabl = $table[0];

$arr = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

if (!empty($tabl))
{
foreach ($table as $tabl) : 
    $groupName = $tabl['groupName'];?>
    <div class="check">
        <div class="parTitle"><p><?=$tabl['vid']?></p></div>
        <div class="name"><p><?=$tabl['name']?></p></div>
        <p class="parTeacher">Педагог</p>
        <div class="teacher"><p><?=$tabl['teacherName']?></p></div>
        <p class="parDay">Расписание</p>
        <? for($i = 0; $i < 7; $i++)
        {
            $gdac = get_dayAddCheck();
            $gdacS = $gdac[0];
            
            if (!empty($gdacS)){
            ?>
                <div class="day"><p><?=$gdacS['day']?></p></div>
        
        <div class="time"><p><?=substr($gdacS['time'], 0, 5)."-".substr($gdacS['timeTo'], 0, 5)?></p></div>
        <?  }
        }?>
        <p class="par">Место проведения</p>
        <div class="address"><p><?=$tabl['address']?></p></div>
        <form class="buttonAdd" method="POST">
            <button type="submit" name="btn" value="<?=$groupName?>" class="addString">Записаться</button>
        </form>
        <?
        $vid = $tabl['vid'];
        $name = $tabl['name'];
        $teacher = $tabl['teacherName'];
        $fio = $_SESSION['user']['full_name'];
        $class = $_SESSION['user']['class'];
        $dob = $_SESSION['user']['dob'];  
        ?>
        
    </div>
<?php endforeach;



    if(isset($_POST['btn']))
    {  
        $gogo = $_POST['btn'];
        $query = "SELECT * FROM posts WHERE studentName='$fio' and groupName='$gogo'";
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        if (mysqli_num_rows($result) > 0)
        {
            echo "Вы уже записаны на этот кружок";
        }
        else{
            $query = "INSERT INTO posts (studentName, groupName) VALUES ('$fio','$gogo')";
            if ($query)
            {
                mysqli_query($connection, $query); 
            }
        }
    }
}

?>