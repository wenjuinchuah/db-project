<?php
    include 'connect.php';

    $empID = $_GET['empID'];
    if (!isset($empID)) {
        echo 'ERROR';
    } else {
        $sql = "SELECT * FROM medical_officer WHERE EmpID = '$empID'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_array($result);
            if ($row != NULL) {
                $empName = $row[1];
            } else {
                echo 'Invalid Employee ID';
            }
        } else {
            echo 'Error: ' . $sql . '<br/>' . mysqli_error($conn);
        }
    }

    // Get Donor Sum
    $donorSum = 0;
    $sql = "SELECT * FROM donor";

    if ($result = mysqli_query($conn,$sql))
        $donorSum = mysqli_num_rows($result);
   
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>BDMS Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
</head>

<body class="w3-light-grey">

    <!-- Top container -->
    <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey"
            onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
        <button class="w3-bar-item w3-right w3-button"
            onclick="window.location.href='./index.php';"><i class="fa fa-sign-out"></i></button>
        <span class="w3-bar-item w3-hide-small">Blood Donation Management System</span>
    </div>

    <!-- Sidebar/menu -->
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
        <div class="w3-container w3-row">
            <div class="w3-col s4">
                <img src="./src/avatar2.png" class="w3-circle w3-margin-right" style="width:60px">
            </div>
            <div class="w3-col s8 w3-bar w3-padding-16">
                <span style="font-size: 16px">Welcome, <strong><?php echo strstr($empName, ' ', true) ?></strong></span><br>
            </div>
        </div>
        <hr>
        <div class="w3-container">
            <h5>Dashboard</h5>
        </div>
        <div class="w3-bar-block">
            <a href="./dashboard.php?empID=<?php echo $empID ?>" class="w3-bar-item w3-button w3-padding"><i class="fa fa-file-text-o fa-fw"></i>  Donor Registration</a>
            <a href="./donor_details.php?empID=<?php echo $empID ?>" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  Donor Details</a>
            <a href="./donation_list.php?empID=<?php echo $empID ?>" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Donation List</a><br><br>
        </div>
    </nav>


    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer"
        title="close side menu" id="myOverlay"></div>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px;margin-top:43px;">
        <?php include "header.php" ?>

        <div class="w3-container">
            <h5><b><i class="fa fa-file-text-o"></i> Donation Appointment</b></h5>
            <div class="w3-card-4 w3-white">
                <div class="w3-container w3-dark-grey">
                    <h5><b>Donation Details</b></h5>
                </div>
                <form class="w3-row-padding" action="./add_donation.php?empID=<?php echo $empID ?>" method="POST">
                    <div class="w3-half w3-padding-16">
                        <label>DonorID</label>
                        <?php
                            include 'connect.php';
                            $sql = "SELECT * FROM donor"; 

                            if ($result = mysqli_query($conn,$sql))
                                $donorSum = mysqli_num_rows($result);
                                echo "<select class=\"w3-select\" name=\"donorid\" required>";
                                for ($i = 0; $i < $donorSum; $i++) 
                                {
                                    $row = mysqli_fetch_row($result);
                                    echo  "<option value=\"$row[0]\" > $row[0] - $row[1] </option>";
                                }
                                echo "</select>";
                            mysqli_close($conn);                     
                        ?>
                    </div>
                    <div class="w3-half w3-padding-16">
                        <label>Date</label>
                        <input class="w3-input" type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="w3-third w3-padding-16">
                        <label>Donation Type</label>
                        <select class="w3-select" name="donationtype" required>
                            <option value="W" selected>Whole Blood</option>
                            <option value="A">Aphresis</option>
                        </select>
                    </div>
                    
                    <div class="w3-third w3-padding-16">
                        <label>Location  Type</label>
                        <select class="w3-select" name="locationtype" id="locationType" onchange="showLocation()" required>
                            <option value="B" selected>Blood Bank</option>
                            <option value="L">Local Health Centre</option>
                            <option value="M">Mobility Programme</option>
                        </select>
                    </div>
                    <div class="w3-third w3-padding-16">
                        <label>Location ID</label>
                        <select class="w3-select" type="text" name="locationid" id="location" required>
                        </select>          
                    </div>
                    <div class="w3-half w3-padding-16">
                        <label>Hemoglobin Level (g/dL)</label>
                        <input class="w3-input" type="text" name="hemoglobinlevel" required>
                    </div>
                    <div class="w3-half w3-padding-16">
                        <label>Fluid Volume (ml)</label>
                        <input class="w3-input" type="text" name="fluidvolume" required>
                    </div>  
                    <div class="w3-half w3-padding-16">
                        <label>Platelet Volume (ml)</label>
                        <input class="w3-input" type="text" name="plateletvolume" required>
                    </div> 
                    <div class="w3-half w3-padding-16">
                        <label>Plasma Volume (ml)</label>
                        <input class="w3-input" type="text" name="plasmavolume" required>
                    </div>  
                    <div class="w3-row-padding w3-padding-16">
                        <label>Packed Red Cell Volume (ml)</label>
                        <input class="w3-input" type="text" name="packedredcellvolume" placeholder="For Whole Blood Donation only">
                    </div>
                    <div class="w3-row-padding">
                        <b><button type="submit" class="w3-btn w3-block w3-round w3-green" name="addDonation">Add Donation</button></b
                                >
                    </div>
                    <br>
                </form>
            </div>
            <br>
        </div>
        <!-- End page content -->
    </div>

    <?php
        include 'connect.php';
        $counter = 0;
        $tableList = ['B', 'L', 'M'];

        foreach ($tableList as $i) {
            echo '<table class="w3-table-all" id="'.$i.'" style="display: none">';    
            $i = ($i == 'B') ? 'blood_bank' : (($i == 'L') ? 'local_health_centre' : 'mobile_blood_donation_program');
            $sql = "SELECT * FROM $i";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        mysqli_close($conn);
    ?>
    

    <script>
        // Get the Sidebar
        var mySidebar = document.getElementById("mySidebar");

        // Get the DIV with overlay effect
        var overlayBg = document.getElementById("myOverlay");

        // Toggle between showing and hiding the sidebar, and add overlay effect
        function w3_open() {
            if (mySidebar.style.display === 'block') {
                mySidebar.style.display = 'none';
                overlayBg.style.display = "none";
            } else {
                mySidebar.style.display = 'block';
                overlayBg.style.display = "block";
            }
        }

        // Close the sidebar with the close button
        function w3_close() {
            mySidebar.style.display = "none";
            overlayBg.style.display = "none";
        }

        // Check LocationType
        window.onload = function () {
            updateLocation();
        }

        // Update Location
        function updateLocation() {
            var input, location, table, tr, td, i;
            input = document.getElementById("locationType");
            location = document.getElementById("location");
            table = document.getElementById(input.value);
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                const option = document.createElement("option");
                option.value = td[0].textContent;
                option.innerHTML = td[0].textContent + " - " + td[1].textContent;
                document.getElementById("location").appendChild(option);
            }
        }

        // Show Location
        function showLocation() {
            var i, input;
            input = document.getElementById("location");
            for(i = input.options.length; i >= 0; i--) {
                input.remove(i);
            }
            updateLocation();
        }
    </script>

</body>

</html>

