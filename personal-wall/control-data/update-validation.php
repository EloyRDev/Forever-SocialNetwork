<?php
require("../../src/php/functions.php");
session_start();
// $_SESSION["user"] = findItem("../../JSON/users.json", "id", 0);

$usersData = json_decode(file_get_contents("../../JSON/users.json"));

if (isset($_POST)) {
   if ($_POST["username"] != $_SESSION["user"]["username"]) {
      $username = $_POST["username"];
      if (empty($username) || strlen($username) < 3) {
         echo json_encode([
            "type" => "error",
            "input" => "username",
            "message" => "This information is required. (more than 3 characteres)"
         ]);
         die();
      }
   }
   $userCompareUserName = $usersData[findIndex($usersData, "username", $_POST["username"])];
   if ($userCompareUserName->id != $_SESSION["loggedUserID"] && $userCompareUserName->username == $_POST["username"]) {
      echo json_encode([
         "type" => "error",
         "input" => "email",
         "message" => "Already exist an user with this username."
      ]);
      die();
   }
   if ($_POST["email"] != $_SESSION["user"]["email"]) {
      if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || empty($_POST["email"])) {
         echo json_encode([
            "type" => "error",
            "input" => "email",
            "message" => "Please entry a valid email."
         ]);
         die();
      }
   }

   $userCompareEmail = $usersData[findIndex($usersData, "email", $_POST["email"])];
   if ($userCompareEmail && $userCompareEmail->id != $_SESSION["loggedUserID"] && $userCompareEmail->email == $_POST["email"]) {
      echo json_encode([
         "type" => "error",
         "input" => "email",
         "message" => "Already exist an user with this email."
      ]);
      die();
   }

   
   if ($_POST["description"] != $_SESSION["user"]["description"]) {
      if (empty($_POST["description"]) || strlen($_POST["description"]) > 100) {
         echo json_encode([
            "type" => "error",
            "input" => "description",
            "message" => "Please entry a valid description. Less than 100 characters."
         ]);
         die();
      }
   }

   $userCompareDescription = $usersData[findIndex($usersData, "description", $_POST["description"])];
   if ($userCompareDescription && $userCompareDescription->id != $_SESSION["loggedUserID"] && $userCompareDescription->description == $_POST["description"]) {
      echo json_encode([
         "type" => "error",
         "input" => "description",
         "message" => "Already exist an user with this description."
      ]);
      die();
   }

   
   // if ($_POST["pictureID"] != $_SESSION["user"]["pictureID"]) {
   //    if (empty($_POST["pictureID"]) || strlen($_POST["pictureID"]) > 100) {
   //       echo json_encode([
   //          "type" => "error",
   //          "input" => "pictureID",
   //          "message" => "Please entry a valid description. Less than 100 characters."
   //       ]);
   //       die();
   //    }
   // }

   // $userComparePictureID = $usersData[findIndex($usersData, "pictureID", $_POST["pictureID"])];
   // if ($userComparePictureID && $userComparePictureID->id != $_SESSION["loggedUserID"] && $userComparePictureID->pictureID == $_POST["pictureID"]) {
   //    echo json_encode([
   //       "type" => "error",
   //       "input" => "pictureID",
   //       "message" => "Already exist an user with this pictureID."
   //    ]);
   //    die();
   // }

   if (empty($_POST["password"]) || strlen($_POST["password"]) < 6) {
      echo json_encode([
         "type" => "error",
         "input" => "password",
         "message" => "Your password is required. (More than 6 characters)"
      ]);
      die();
   }

   if ($_POST["password"] != $_POST["password-confirm"]) {
      echo json_encode([
         "type" => "error",
         "input" => "password-confirm",
         "message" => "Password and confirm Password aren't equals"
      ]);
      die();
   }


   //Update User

   $usersData[findIndex($usersData, "id", $_SESSION["loggedUserID"])]->username = $_POST["username"];
   $usersData[findIndex($usersData, "id", $_SESSION["loggedUserID"])]->email = $_POST["email"];
   $usersData[findIndex($usersData, "id", $_SESSION["loggedUserID"])]->description = $_POST["description"];
   $usersData[findIndex($usersData, "id", $_SESSION["loggedUserID"])]->main_picture_id = $_POST["portrait-id"];
   updateJson("../../JSON/users.json", $usersData);


   $_SESSION["user"] = findItem("../../JSON/users.json", "id", $_SESSION["loggedUserID"]);
   $_SESSION["loggedUserID"] = $_SESSION["user"]["id"];
   unset($_SESSION["user"]["id"]);
   unset($_SESSION["user"]["password"]);
   echo json_encode([
      "type" => "ok",
      "userData" => $_SESSION["user"]
   ]);
   
   die();
}
