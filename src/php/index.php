<?

$conn = new mysqli("mariadb", "root", "secret", "db_name");

$conn->query("CREATE TABLE IF NOT EXISTS users (name TEXT)");

$sql = "SELECT name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		echo $row['name']."<br>";
	}
} else {
	echo "0 results";
	$conn->query("INSERT INTO users VALUES('John'), ('Jack'), ('Jane')");
}
$conn->close();
