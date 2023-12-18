<?php

// Starts session
session_start();

// redirect user to startpage at the beginning
if (empty($_POST['page'])) {
     $display_modal_window = 'no-modal-window';
     include('startpage.php');
     exit();
}

include('model.php'); // include model

if ($_POST['page'] == 'StartPage') {
     $command = $_REQUEST['command'];

     switch ($command) {

          case 'SignIn':
               $username = $_POST['Username'];
               $password = $_POST['Password'];

               if (is_valid($username, $password)) {
                    $_SESSION['signedin'] = "YES";
                    $_SESSION['username'] = $username;
                    $_SESSION['userID'] = getAccountID($username);

                    $is_organizer = isOrganizer($username);

                    // if user is an organizer
                    if ($is_organizer) {
                         $is_organizer = true;
                         $display_organizer_code =
                              "<br><hr><br>
               
                         <!-- Create Tournament Form -->
                         <form method='post' action='controller.php'>
                              <input type='hidden' name='page' value='MainPage'>
                              <input type='hidden' name='command' value='CreateTournament'>
                              
                              <label for='tournament-name'>Create Tournament</label>
                              <input type='text' id='tournament-name' name='TournamentName' placeholder='Enter name of tournament'><br>
                              
                              <label for='game-input2'>Select Game</label>
                              <select name='GameInput2' id='game-input2'>
                                   <option value='Fortnite'>Fortnite</option>
                                   <option value='Warzone'>Warzone</option>
                                   <option value='Apex Legends'>Apex Legends</option>
                                   <option value='CS:GO'>CS:GO</option>
                                   <option value='Valorant'>Valorant</option>
                                   <option value='Call of Duty'>Call of Duty</option>
                                   <option value='Rainbow Six Siege'>Rainbow Six Siege</option>
                                   <option value='League of Legends'>League of Legends</option>
                              </select><br><br>

                              <label for='player-count'>No. of Players</label>
                              <input type='number' name='PlayerCount' id='player-count'><br><br>
                              
                              <label for='date-input'>Date</label>
                              <input type='datetime-local' name='DateInput' id='date-input'><br><br><br>
                              
                              <input type='button' id='submit-creation' name='SubmitSearch' value='Create'>
                         </form>
                         
                         <br><hr><br>
               
                         <!-- Update Tournament Form -->
                         <form method='post' action='controller.php'>
                              <input type='hidden' name='page' value='MainPage'>
                              <input type='hidden' name='command' value='UpdateTournament'>
                              
                              <label for='new-id'>Existing Tournament ID</label>
                              <input type='number' id='new-id' name='NewID'><br>
                              
                              <label for='new-tournament-name'>Update Tournament</label>
                              <input type='text' id='new-tournament-name' name='NewTournamentName' placeholder='Enter name of tournament'><br>
                              
                              <label for='new-game-input2'>Select Game</label>
                              <select name='NewGameInput2' id='new-game-input2'>
                                   <option value='1'>Fortnite</option>
                                   <option value='2'>Warzone</option>
                                   <option value='3'>Apex Legends</option>
                                   <option value='4'>CS:GO</option>
                                   <option value='5'>Valorant</option>
                                   <option value='6'>Call of Duty</option>
                                   <option value='7'>Rainbow Six Siege</option>
                                   <option value='8'>League of Legends</option>
                              </select><br><br>

                              <label for='new-player-count'>No. of Players</label>
                              <input type='number' name='NewPlayerCount' id='new-player-count'><br><br>
                              
                              <label for='new-date-input'>Date</label>
                              <input type='datetime-local' name='NewDateInput' id='new-date-input'><br><br><br>
                              
                              <input type='button' id='submit-update' name='SubmitUpdate' value='Update'>
                         </form>

                         <br><hr><br>
               
                         <!-- Delete Tournament Form -->
                         <form method='post' action='controller.php'>
                              <input type='hidden' name='page' value='MainPage'>
                              <input type='hidden' name='command' value='DeleteTournament'>
                              
                              <label for='delete-id'>Tournament ID</label>
                              <input type='number' id='delete-id' name='DeleteID'><br>

                              <input type='button' id='submit-delete' name='SubmitDelete' value='Delete'>
                         ";

                         // $display_your_tournaments would go here

                         include('mainpage.php');
                    } else {
                         include('mainpage.php');
                    }

               } else {
                    $display_modal_window = 'signin';
                    $error_msg_username_signin = '* Non-existing username or';
                    $error_msg_password_signin = '* wrong password';
                    include('startpage.php');
               }
               break;

          case 'SignUp':
               $username = $_POST['Username'];
               $password = $_POST['Password'];
               $email = $_POST['Email'];
               $organizer = isset($_POST['Organizer']) ? 1 : 0;

               if (register($username, $password, $email, $organizer)) {
                    $display_modal_window = 'signin';
                    include('startpage.php');
               } else {
                    $_SESSION['username'] = $username; // ???????
                    $display_modal_window = 'signup';
                    $error_msg_username_signup = '<br>* Username already exists';
                    include('startpage.php');
               }

               break;

          default:
               break;
     }
     exit();

} else if ($_POST['page'] == 'MainPage') {

     $command = $_REQUEST['command'];

     switch ($command) {

          case 'SignOut':
               session_unset();
               session_destroy();
               $display_modal_window = 'no-modal-window';
               include('startpage.php');
               break;

          case 'UpdateAccount':
               $uid = $_SESSION['userID'];
               $username = $_POST['username']; // fetching new username
               $password = $_POST['password']; // fetching new password

               $result = updateAccount($uid, $username, $password);
               if ($result) {
                    echo "Account updated successfully!";
               } else {
                    echo "Failed to update account.";
               }
               break;

          case 'DeleteAccount':
               $uid = $_SESSION['userID'];
               $result = deleteAccount($uid);

               if ($result) {
                    echo "Account deleted successfully!";
               } else {
                    echo "Failed to delete account.";
               }
               break;

          case 'RegisterForTournament':
               $uid = $_SESSION['userID'];
               $tid = $_POST['tid'];

               $result = registerForTournament($uid, $tid);
               if ($result) {
                    echo "Account registered successfully!";
               } else {
                    echo "Failed to register.";
               }
               break;

          case 'CreateTournament':
               $orgID = $_SESSION['userID'];
               $gameID = getGameIdByName($_POST['GameInput2']);
               $name = $_POST['TournamentName'];
               $date = $_POST['DateInput'];
               $player_count = $_POST['PlayerCount'];

               $result = createTournament($gameID, $orgID, $name, $date, $player_count);
               if ($result) {
                    echo "Tournament created successfully!";
               } else {
                    echo "Failed to create tournament.";
               }
               break;

          case 'SearchTournaments':
               $search_term = $_POST['TournamentName'];
               $selected_game = $_POST['GameInput'];
               $result = searchTournaments($search_term, $selected_game);
               // $data_from_search = json_encode($result);
               // echo $data_from_search;

               if ($result) {
                    echo "Search successful!";
                    $str = "<table border=1>";
                    $str .= "<tr>"; // the first row for table heads
                    foreach ($result[0] as $k => $v)
                         $str .= "<th>" . $k . "</th>";
                    $str .= "</tr>";

                    for ($i = 0; $i < count($result); $i++) {
                         $str .= "<tr>";
                         foreach ($result[$i] as $k => $v)
                              $str .= "<td>" . $v . "</td>";
                         $str .= "</tr>";
                    }
                    $str .= "</table>";
               } else {
                    $str = "No tournament found!";
               }
               echo $str;
               break;

          case 'UpdateTournament':
               $tid = $_POST['NewID'];
               $name = $_POST['NewTournamentName'];
               $gameID = $_POST['NewGameInput2'];
               $date = $_POST['NewDateInput'];
               $pcount = $_POST['NewPlayerCount'];
               $result = updateTournament($tid, $name, $gameID, $date, $pcount);

               if ($result) {
                    echo "Tournament updated successfully!";
               } else {
                    echo "Failed to update tournament.";
               }
               break;

          case 'DeleteTournament':
               $tid = $_POST['DeleteID'];
               $result = deleteTournament($tid);

               if ($result) {
                    echo "Tournament deleted successfully!";
               } else {
                    echo "Failed to delete tournament.";
               }
               break;

          default:
               break;
     }

} else {
     echo 'Unknown page error!';
     exit();
}
?>