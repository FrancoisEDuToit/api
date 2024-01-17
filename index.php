<?php
    //Create Connection
    $conn = new mysqli('localhost', 'root', '', 'game3');

    //Correct Data
    $jsondata = file_get_contents('db.json');
    $correctedJson = str_replace('"id"', '"|id"', $jsondata);
    file_put_contents('db.json',$correctedJson);


    //Get Data
    $data = json_decode($correctedJson, true);
    $posts =  $data['posts'];


    $i = 0;
    while($i < count($posts))
    {

    $id = $data['posts'][$i]['|id'];
    $username = $data['posts'][$i]['|username'];
    $email = $data['posts'][$i]['|email'];
    $score = $data['posts'][$i]['|score'];
    $highscore = $data['posts'][$i]['|highscore'];

    $sql = "SELECT * FROM data WHERE Username = '$username'";

    $result = $conn->query($sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {

            $oldHighscore = $result->fetch_assoc()['Highscore'];
            
            if ($oldHighscore < $highscore) {
                $sqlInsert = "UPDATE data SET Highscore='$highscore' WHERE Username = '$username'";

                if ($conn->query($sqlInsert) === TRUE) {
                    echo "Updated";
                }
            }
        } else {
            $sqlInsert = "INSERT INTO data(Username, Email, Score, Highscore)
            VALUES('$username', '$email', '$score', '$highscore')";

            if ($conn->query($sqlInsert) === TRUE) {
                echo "Inserted successfully";
            }
        }
    } else {
        echo 'Error';
    }
    

    $i++;

    }

    mysqli_close($conn);

?>