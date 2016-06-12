<?php

class FitBot
{
        private $conn;

        function __construct($mysqli) {
                $this->conn = $mysqli;
        }



        function getDaily()
        {
                return $this->getActivity("daily", date("dmY"));
        }

        function getWeekly()
        {
                return $this->getActivity("weekly", date("WY"));
        }

        function getActivity($table, $date)
        {

                $stmt = $this->conn->prepare("SELECT workoutid FROM ".$table." WHERE date= ?");

                $stmt->bind_param("s", $date);

                $stmt->execute();
                $stmt->store_result();

                $stmt->bind_result($workoutid);


                if($stmt->num_rows == 0)
                {
                        $stmt->close();
                        $workoutid = $this->createNewActivity($table, $date);
                }
                else
                {
                        $stmt->fetch();
                        $stmt->close();
                }

                return $this->getWorkout($table, $workoutid);
        }
        function getWorkout($table, $id)
        {
                $stmt = $this->conn->prepare("SELECT * FROM ".$table."_workouts WHERE id=?");
                $stmt->bind_param("i", $id);

                $stmt->execute();

                $result = $stmt->get_result();
                $obj = $result->fetch_assoc();
                return $obj; 
        }

        function createNewActivity($table, $date)
        {

                $stmt = $this->conn->prepare("SELECT id FROM ".$table."_workouts ORDER BY RAND() LIMIT 1;");
                $stmt->execute();
                $stmt->bind_result($id);
                $stmt->fetch();
                $stmt->close();


                $stmt = $this->conn->prepare("INSERT INTO ".$table." (workoutid, date) VALUES (?, ?);");
                $stmt->bind_param("ss", $id, $date);
                $stmt->execute();
                $stmt->close();

                return $id;
        }

}


?>
