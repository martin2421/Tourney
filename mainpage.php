<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="styles-main.css">
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
     <title>Tourney</title>
</head>

<body>
     <header>
          <!-- Logo -->
          <a href="#">
               <img id="logo" src="images/logo.svg" alt="logo">
          </a>

          <!-- Title -->
          <h1>Tourney</h1>

          <!-- User Icon -->
          <img id="user-icon" src="images/user-icon.svg" alt="user-icon" onclick="showAccountModal()">

          <!-- User Account Modal -->
          <div id="myModal" class="modal">
               <div class="modal-content">
                    <span id="closeAccountModal" class="close">&times;</span>
                    <form id="accountForm" method='post' action='controller.php'>

                         <input type='hidden' name='page' value='MainPage'>

                         <label for="username">Username:</label>
                         <input type="text" id="username" name="username" required><br><br>
                         <label for="password">Password:</label>
                         <input type="password" id="password" name="password" required><br><br>
                         <div class="flex-btns">
                              <button type="button" id="updateBtn">Update</button>
                              <button type="button" id="deleteBtn">Delete</button>
                         </div>
                    </form>
               </div>
          </div>

     </header>

     <main>

          <!-- Main Section -->
          <section id="main-section">
               <h2>Upcoming Tournaments</h2>
               <div id="tournament-container" class="grid-container">
                    <!-- 
                         php code that echoes div elements from an SQL
                         search of tournaments (ordered by date)
                     -->
                    <?php

                    if (!function_exists('getTournamentsOrderedByDate')) {
                         include_once 'model.php';
                    }

                    $tournaments = getTournamentsOrderedByDate();
                    foreach ($tournaments as $tournament): ?>
                         <div class="grid-item">
                              <?php $gameImage = 'images/game' . $tournament['GameID'] . '.jpg'; ?>
                              <img src="<?php echo $gameImage; ?>" alt="Game Image">
                              <h3>
                                   <?php echo $tournament['Name']; ?>
                              </h3>
                              <p>Organizer ID:
                                   <?php echo $tournament['OrganizerID']; ?>
                              </p>
                              <p>Date:
                                   <?php echo $tournament['Date']; ?>
                              </p>
                              <input id="registerBtn" type="button" value="Register"
                                   data-tid="<?php echo $tournament['TournamentID']; ?>">
                              <p id="registrationResult" style="display:none">
                                   Registered!
                              </p>
                         </div>
                    <?php endforeach; ?>
               </div>

               <br><br>

               <!-- 
                    Display the Your Tournaments Section
                    only if a user is an organizer
               -->
               <div id='your-tournaments-result' style='display:none'>
                    <?php
                    if (!empty($display_your_tournaments)) {
                         echo $display_your_tournaments;
                    }
                    ?>
               </div>

          </section>

          <!-- Sidebar -->
          <div id="sidebar">

               <!-- Search Form -->
               <form method="post" action="controller.php">
                    <input type='hidden' name='page' value='MainPage'>
                    <input type='hidden' name='command' value='SearchTournaments'>

                    <label for="tournament-input">Search Tournament</label>
                    <input type="text" id="tournament-input" name="TournamentName"
                         placeholder="Enter name of tournament"><br>

                    <label for="game-input">Search Game</label>
                    <select name="GameInput" id="game-input">
                         <option value="1">Fortnite</option>
                         <option value="2">Warzone</option>
                         <option value="3">Apex Legends</option>
                         <option value="4">CS:GO</option>
                         <option value="5">Valorant</option>
                         <option value="6">Call of Duty</option>
                         <option value="7">Rainbow Six Siege</option>
                         <option value="8">League of Legends</option>
                    </select><br><br><br>

                    <input type="button" id="submit-search" name="SubmitSearch" value='Search'>
               </form>


               <!-- Display the Tournament Creation Section
                    only if a user is an organizer -->
               <?php
               if ($is_organizer) {
                    echo $display_organizer_code;
               }
               ?>
          </div>

          <!-- Search Results Modal -->
          <div id="myModal2" class="modal">
               <div class="modal-content2">
                    <span id="closeSearchModal" class="close">&times;</span>

                    <!-- Table of Search Results -->
                    <div id="search-results-table"></div>

               </div>
          </div>

     </main>

</body>

<!-- Signout Form -->
<form id="signout-form" method="post" action="controller.php">
     <input type="hidden" name="page" value="MainPage">
     <input type="hidden" name="command" value="SignOut">
     <input type="submit" id='button-signout' value='Sign Out'><br><br>
</form>

<!-- jQuery -->

<script>

     // Variables
     const accountModal = document.getElementById('myModal');
     const searchModal = document.getElementById('myModal2');
     const closeAccountModal = document.getElementById('closeAccountModal');
     const closeSearchModal = document.getElementById('closeSearchModal');

     function showAccountModal() {
          accountModal.style.display = "block";
     }
     function hideAccountModal() {
          accountModal.style.display = "none";
     }
     function showSearchModal() {
          searchModal.style.display = "block";
     }
     function hideSearchModal() {
          searchModal.style.display = "none";
     }

     closeAccountModal.addEventListener('click', () => {
          hideAccountModal();
     });
     closeSearchModal.addEventListener('click', () => {
          hideSearchModal();
     });


     /* 
     
          Update And Delete Accounts

     */

     // Close the modal when the close button is clicked
     $(".close").click(function () {
          hideAccountModal();
          hideSearchModal();
     });

     // Close the modal when clicking outside the modal
     window.onclick = function (event) {
          if (event.target == accountModal) {
               hideAccountModal();
          } else if (event.target == searchModal) {
               hideSearchModal();
          }
     };

     // Debugging line to log the user ID to the console
     console.log("$_SESSION['userID'] => " + <?php echo $_SESSION['userID']; ?>);


     /* 
     
          jQuery and AJAX Operations

     */


     // AJAX - Update User Account
     $(document).ready(function () {
          $('#updateBtn').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         $('#myModal').css('display', 'none');
                         console.log(this.responseText);
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=UpdateAccount&username=" + $('#username').val() + "&password=" + $('#password').val();

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Delete User
     $(document).ready(function () {
          $('#deleteBtn').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         $('#myModal').css('display', 'none');
                         console.log(this.responseText);
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=DeleteAccount";

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Register User
     $(document).ready(function () {
          $('#registerBtn').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         console.log(this.responseText);
                         alert("User Registered!");
                         $('#registerBtn').css("display", "none");
                         $('#registrationResult').css("display", "block");
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=RegisterForTournament&tid=" + $(this).data("tid");

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Create Tournament
     $(document).ready(function () {
          $('#submit-creation').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         console.log(this.responseText);
                         alert("Tournament Created!");

                         $('#tournament-container').load('mainpage.php #tournament-container');
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=CreateTournament&GameInput2=" + $('#game-input2').val() + "&TournamentName=" + $('#tournament-name').val() + "&DateInput=" + $('#date-input').val() + "&PlayerCount=" + $('#player-count').val();

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Search for tournaments
     $(document).ready(function () {
          $('#submit-search').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         // let r = createTable(this.responseText);
                         // $('#search-results-table').html(r);
                         $('#myModal2').css('display', 'block');
                         let response = this.responseText;
                         let startIndex = response.indexOf('<');
                         let str = response.substring(startIndex);

                         $('#search-results-table').html(str);
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=SearchTournaments&TournamentName=" + $('#tournament-input').val() + "&GameInput=" + $('#game-input').val();

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Update Tournament
     $(document).ready(function () {
          $('#submit-update').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         console.log(this.responseText);
                         alert("Tournament Updated!");

                         $('#tournament-container').load('mainpage.php #tournament-container');
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=UpdateTournament&NewID=" + $('#new-id').val() + "&NewGameInput2=" + $('#new-game-input2').val() + "&NewTournamentName=" + $('#new-tournament-name').val() + "&NewDateInput=" + $('#new-date-input').val() + "&NewPlayerCount=" + $('#new-player-count').val();

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     // AJAX - Delete Tournament
     $(document).ready(function () {
          $('#submit-delete').click(function () {
               var xhttp = new XMLHttpRequest(); // create an AJAX object
               xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         console.log(this.responseText);
                         alert("Tournament Deleted!");

                         $('#tournament-container').load('mainpage.php #tournament-container');
                    }
               };

               var controller = "controller.php";
               var query = "page=MainPage&command=DeleteTournament&DeleteID=" + $('#delete-id').val();

               console.log("Query: " + query);
               xhttp.open("POST", controller, true);
               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhttp.send(query);

          });
     });

     document.addEventListener('DOMContentLoaded', () => {
          const signout_btn = document.getElementById('button-signout');
          const signout_form = document.getElementById('signout-form');

          // Submit SignOut Form
          signout_btn.addEventListener('click', () => {
               signout_form.submit();
          });
     });


</script>

</html>