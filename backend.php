<?php 

// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:breakfast.db');
$file_db->setAttribute(PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION);

if(!isset($_GET['action'])){
	header ("Location: ./index.html");
}else{
	switch ($_GET['action']){
		case 'install':
			//Action to initial create the database table
			$file_db->exec("CREATE TABLE IF NOT EXISTS voting (
					id INTEGER PRIMARY KEY,
					date INTEGER,
					rating INTEGER
					)");
			break;
		case 'addRating':

			//Get the time for
			$date = time();
			$rating = $_POST['rating'];

			if($rating > 0 and $rating <5){
				
				$insert = "INSERT INTO voting (date, rating)
						VALUES (:date, :rating)";
				$stmt = $file_db->prepare($insert);

				// Bind parameters to statement variables
				$stmt->bindParam(':date', $date);
				$stmt->bindParam(':rating', $rating);

				//And write the date into the database
				$stmt->execute();
			}
			//Redirect to index file
			header ("Location: ./index.html");
			break;

		case 'getRatings':

			//Select
			$result = $file_db->query('SELECT * FROM voting');

			echo '{
			  "cols": [
					{"id":"","label":"Date","pattern":"","type":"string"},
					{"id":"","label":"Rating","pattern":"","type":"number"}
					],
			  "rows": [';

			foreach($result as $row){

				echo '{"c":[{"v":"'.date('d/m/y',$row['date']).'"},{"v":'.$row['rating'].'}]},';
			}

			echo ' ]
	}';

			break;
	}
}

?>