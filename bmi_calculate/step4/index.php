<?php
 
 $result = "";
 $bmi = 0;
 $interpretation = "";
 
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $name = htmlspecialchars($_POST['name']);
     $weight = floatval($_POST['weight']);
     $height = floatval($_POST['height']);
 
     if ($weight > 30 && $weight < 200 && $height > 1 && $height < 3 ) {
         $bmi = $weight / ($height * $height);
         if ($bmi < 18.5) {
             $interpretation = "Underweight";
         } elseif ($bmi < 25) {
             $interpretation = "Normal weight";
         } elseif ($bmi < 30) {
             $interpretation = "Overweight";
         } else {
             $interpretation = "Obesity";
         }
         $conn = new mysqli("localhost", "root", "", "bmi_db");
         if (!$conn->connect_error) {
             $stmt = $conn->prepare("INSERT INTO bmi_records (name, weight, height, bmi, interpretation) VALUES (?, ?, ?, ?, ?)");
             $stmt->bind_param("sddds", $name, $weight, $height, $bmi, $interpretation);
             $stmt->execute();
             $stmt->close();
             $conn->close();
         }
         $result = "<div class='alert alert-info'>Hello, $name. Your BMI is " . number_format($bmi, 2) . " ($interpretation).</div>";
     } else {
         $result = "<div class='alert alert-danger'>Invalid input values. Please enter realistic numbers.</div>";
     }
 }
 ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <title>BMI Calculator</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 </head>
 <body>
 <div class="container mt-5">
     <h1>BMI Calculator</h1>
     <?= $result ?>
     <form action="" method="post" class="mt-4">
         <div class="form-group">
             <label for="name">Name:</label>
             <input type="text" id="name" name="name" class="form-control" required>
         </div>
         <div class="form-group">
             <label for="weight">Weight (kg):</label>
             <input type="number" id="weight" name="weight" class="form-control" step="any" required>
         </div>
         <div class="form-group">
             <label for="height">Height (m):</label>
             <input type="number" id="height" name="height" class="form-control" step="any" required>
         </div>
         <button type="submit" class="btn btn-primary">Calculate</button>
     </form>
 
     <?php
     $conn = new mysqli("localhost", "root", "", "bmi_db");
     if (!$conn->connect_error) {
         $sql = "SELECT name, weight, height, bmi, interpretation, created_at FROM bmi_records ORDER BY created_at DESC LIMIT 5";
         $res = $conn->query($sql);
         if ($res->num_rows > 0) {
             echo "<h2 class='mt-5'>Previous Calculations</h2><table class='table table-striped'>";
             echo "<thead><tr><th>Name</th><th>Weight</th><th>Height</th><th>BMI</th><th>Interpretation</th><th>Date</th></tr></thead><tbody>";
             while ($row = $res->fetch_assoc()) {
                 echo "<tr><td>{$row['name']}</td><td>{$row['weight']}</td><td>{$row['height']}</td><td>" . number_format($row['bmi'], 2) . "</td><td>{$row['interpretation']}</td><td>{$row['created_at']}</td></tr>";
             }
             echo "</tbody></table>";
         }
         $conn->close();
     }
     ?>
 </div>
 </body>
 </html>
