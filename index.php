<?php 

// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:breakfast.db');
$file_db->setAttribute(PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION);

if(!isset($_GET['action'])){
	header ("Location: ./blank.html"); 	
}else{
	switch ($_GET['action']){
		case 'install':
			
			$file_db->exec("CREATE TABLE IF NOT EXISTS voting (
						id INTEGER PRIMARY KEY,
						date INTEGER,
						rating INTEGER
					)");
			break;
		case 'addRating':
			$date = time();
			$rating = $_POST['rating'];
			$insert = "INSERT INTO voting (date, rating)
                VALUES (:date, :rating)";
			$stmt = $file_db->prepare($insert);
			
			// Bind parameters to statement variables
			$stmt->bindParam(':date', $date);
			$stmt->bindParam(':rating', $rating);
			
			$stmt->execute();
			
			header ("Location: ./blank.html"); 	
			break;
			
		case 'getRatings':
			
			$result = $file_db->query('SELECT * FROM voting');
			
			echo '{
			  "cols": [
					{"id":"","label":"Date","pattern":"","type":"string"},
					{"id":"","label":"Rating","pattern":"","type":"number"}
				  ],
			  "rows": [';
			  
			foreach($result as $row){
				echo '{"c":[{"v":"'.date('d/m/y',$row['date']).'"},{"v":'.$row['rating'].'}]},';
				/*echo $row['date'];
				echo "\n";
				echo $row['rating'];
				echo "\n";*/
			}
			
			echo ' ]
			}';
			
			break;
	}
}



?>