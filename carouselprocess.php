<?php

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Connect to database
  $con = new mysqli("localhost", "root", "", "new_light");
  
  // Check connection
  if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
  
  // Validate input data for slide 1
  $title1 = $_POST["title1"];
  $author_name1 = $_POST["author_name1"];
  $description1 = $_POST["description1"];
  $image1 = $_FILES["image1"]["name"];
  $target_dir = "./assets/carouselimage/";
  $target_file1 = $target_dir . basename($_FILES["image1"]["name"]);
  $imageFileType1 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));
  if ($title1 == "" || $author_name1 == "" || $description1 == "" || $image1 == "") {
    die("Please fill all required fields for slide 1");
  }
  
  // Validate input data for slide 2
  $title2 = $_POST["title2"];
  $author_name2 = $_POST["author_name2"];
  $description2 = $_POST["description2"];
  $image2 = $_FILES["image2"]["name"];
  $target_file2 = $target_dir . basename($_FILES["image2"]["name"]);
  $imageFileType2 = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));
  if ($title2 == "" || $author_name2 == "" || $description2 == "" || $image2 == "") {
    die("Please fill all required fields for slide 2");
  }
  
  // Validate input data for slide 3
  $title3 = $_POST["title3"];
  $author_name3 = $_POST["author_name3"];
  $description3 = $_POST["description3"];
  $image3 = $_FILES["image3"]["name"];
  $target_file3 = $target_dir . basename($_FILES["image3"]["name"]);
  $imageFileType3 = strtolower(pathinfo($target_file3,PATHINFO_EXTENSION));
  if ($title3 == "" || $author_name3 == "" || $description3 == "" || $image3 == "") {
    die("Please fill all required fields for slide 3");
  }
  
  // Check if image file is a actual image or fake image
  $check1 = getimagesize($_FILES["image1"]["tmp_name"]);
  $check2 = getimagesize($_FILES["image2"]["tmp_name"]);
  $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
  if($check1 === false || $check2 === false || $check3 === false) {
    die("Slide image files must be an image");
  }
  
  // Allow certain file formats
  if($imageFileType1 != "jpg" && $imageFileType1 != "png" && $imageFileType1 != "jpeg" && $imageFileType1 != "gif" 
     || $imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif"
     || $imageFileType3 != "jpg" && $imageFileType3 != "png" && $imageFileType3 != "jpeg" && $imageFileType3 != "gif") {
    die("Only JPG, JPEG, PNG & GIF files are allowed for slide images");
  }
  
  // Check if file already

if($imageFileType1 != "jpg" && $imageFileType1 != "png" && $imageFileType1 != "jpeg" && $imageFileType1 != "gif" 
     || $imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif"
     || $imageFileType3 != "jpg" && $imageFileType3 != "png" && $imageFileType3 != "jpeg" && $imageFileType3 != "gif") {
    die("Only JPG, JPEG, PNG & GIF files are allowed for slide images");
  }
  
  // Check if file already

if (file_exists($target_file1) || file_exists($target_file2) || file_exists($target_file3)) {
die("Sorry, image file already exists. Please change the file name and try again.");
}

// Check if image file size is less than 5MB
if ($_FILES["image1"]["size"] > 5000000 || $_FILES["image2"]["size"] > 5000000 || $_FILES["image3"]["size"] > 5000000) {
die("Sorry, image file is too large. Please upload a file smaller than 5MB.");
}

// Move uploaded image file to target directory
if (move_uploaded_file($_FILES["image1"]["tmp_name"], $target_file1)
&& move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file2)
&& move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file3)) {

        $latest_version = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $latest_version = $row["version_number"];
        }    

// Increment the version number in the carousel table
$stmt = $con->prepare("UPDATE carousel SET version_number = version_number + 1");
$stmt->execute();
$stmt->close();

        // Insert the new data into the carousel table with the new version number
        $new_version = $latest_version + 1;
        $stmt = $con->prepare("INSERT INTO carousel (version_number, image, title, author_name, description) VALUES (?, ?, ?, ?, ?),
    (?, ?, ?, ?, ?),
    (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "issssissssissss",
            $new_version,
            $image1,
            $title1,
            $author_name1,
            $description1,
            $new_version,
            $image2,
            $title2,
            $author_name2,
            $description2,
            $new_version,
            $image3,
            $title3,
            $author_name3,
            $description3
        );
        $stmt->execute();
        $stmt->close();

    // Redirect back to admin page with success message
    echo '<script type="text/javascript"> 
        window.alert("Carousel Data Successfuly Inserted");
        setTimeout(function() {
            window.location.href = "index.php";
        }, 500); // wait for 0.5 seconds before redirecting
      </script>';
exit();
} else {
die("Sorry, there was an error uploading your file.");
}
} else {
die("Please fill in all required fields.");
}