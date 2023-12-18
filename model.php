<?php

$conn = mysqli_connect('localhost', 'f3matanacio', 'f3matanacio136', 'C354_f3matanacio');

// Error handling for the connection
if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
}

// Register a user
function register($u, $p, $em, $org)
{
     global $conn;
     $sql = "SELECT Username FROM Accounts WHERE (Username = '$u')";
     $result = mysqli_query($conn, $sql);

     if (mysqli_num_rows($result) > 0) {
          return false; // username already exists
     } else {
          $current_date = date("Ymd");
          $sql2 = "INSERT INTO Accounts VALUES (NULL, '$u', '$p', '$em', $current_date, '$org')";
          $result2 = mysqli_query($conn, $sql2);
          return $result2;
     }
}

// Update user's information
function updateAccount($uid, $u, $p)
{
     global $conn;
     $sql = "UPDATE Accounts SET Username = '$u', Password = '$p' WHERE (ID = '$uid')";
     $result = mysqli_query($conn, $sql);
     if (!$result) {
          die("Error updating account: " . mysqli_error($conn));
     }
     return $result;
}

// Delete an account
function deleteAccount($uid)
{
     global $conn;
     $sql = "DELETE FROM Accounts WHERE (ID = '$uid')";
     $result = mysqli_query($conn, $sql);
     return $result;
}

// Create a tournament (only as an Organizer)
function createTournament($gameID, $orgID, $name, $date, $player_count)
{
     global $conn;
     $sql = "INSERT INTO Tournaments VALUES (NULL, '$gameID', '$orgID', '$name', '$date', '$player_count')";
     $result = mysqli_query($conn, $sql);
     return $result;
}

// Search for tournaments
function searchTournaments($search_term, $search_game)
{
     global $conn;

     $sql = "SELECT * FROM Tournaments 
     WHERE (Name LIKE '%$search_term%' AND GameID = '$search_game')";

     $result = mysqli_query($conn, $sql);
     $tournaments = array();
     while ($row = mysqli_fetch_assoc($result)) {
          $tournaments[] = $row;
     }
     return $tournaments;
}

// Update tournament information (only as an Organizer)
function updateTournament($tid, $name, $gameID, $date, $pcount)
{
     global $conn;
     $sql = "UPDATE Tournaments SET GameID = '$gameID', Name = '$name', Date = '$date', `Player Count` = '$pcount' WHERE (TournamentID = '$tid')";
     $result = mysqli_query($conn, $sql);
     return $result;
}

// Delete a tournament (only as an Organizer)
function deleteTournament($tid)
{
     global $conn;
     $sql = "DELETE FROM Tournaments WHERE (TournamentID = '$tid')";
     $result = mysqli_query($conn, $sql);
     return $result;
}

// Register for a tournament (only as a user)
function registerForTournament($uid, $tid)
{
     global $conn;
     $current_date = date("Ymd");
     $sql = "INSERT INTO Registrations VALUES (NULL, '$uid', '$tid', $current_date)";
     $result = mysqli_query($conn, $sql);
     return $result;
}




/* 

     HELPER FUNCTIONS

*/

// Check if username and password are valid
function is_valid($u, $p)
{
     global $conn;
     $sql = "SELECT Username, Password FROM Accounts WHERE (Username = '$u' AND Password = '$p')";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0)
          return true;
     else
          return false;
}

// Check if user is an organizer
function isOrganizer($u)
{
     global $conn;
     $sql = "SELECT Organizer FROM Accounts WHERE (Username = '$u')";
     $result = mysqli_query($conn, $sql);

     $row = mysqli_fetch_assoc($result);
     return ($row['Organizer'] == 1);

     // return $result;
}

// Returns the ID of the account
function getAccountID($u)
{
     global $conn;
     $sql = "SELECT ID FROM Accounts WHERE (Username = '$u')";
     $result = mysqli_query($conn, $sql);

     $row = mysqli_fetch_assoc($result);
     return ($row) ? $row['ID'] : null;

}

// Get Tournaments ordered by date
function getTournamentsOrderedByDate()
{
     global $conn;
     $sql = "SELECT * FROM Tournaments ORDER BY Date ASC";
     $result = mysqli_query($conn, $sql);

     $tournaments = mysqli_fetch_all($result, MYSQLI_ASSOC);
     return $tournaments;
}

// Get Tournaments given the Organizer ID
function getYourTournaments($orgID)
{
     global $conn;
     $sql = "SELECT * FROM Tournaments WHERE OrganizerID = '$orgID'";
     $result = mysqli_query($conn, $sql);

     $tournaments = mysqli_fetch_all($result, MYSQLI_ASSOC);
     return $tournaments;
}

// Gets GameID given the name of the game
function getGameIdByName($name)
{
     global $conn;
     $sql = "SELECT GameID FROM Games WHERE (Name = '$name')";
     $result = mysqli_query($conn, $sql);
     if ($result) {
          $row = mysqli_fetch_assoc($result);
          return $row['GameID'];
     } else {
          return false;
     }

}


?>