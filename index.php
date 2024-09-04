<?php
// session_start();
require_once "class/db_connect.php";
require_once("class/barangay.php");
require_once("class/cases.php");
require_once("class/admin.php");

$barangay = new Barangay();
$case = new Cases();
$count = new Cases();
$count1 = new Cases();
$count2 = new Cases();
$count3 = new Cases();
$lat = 0;
$lng = 0;
$lat1 = 0;
$lng1 = 0;

// $name = $_GET['name'];
if (isset($_POST['brgyID'])) {
    // Retrieve the brgyID
    $brgyID = $_POST['brgyID'];

    // Now, you can use $brgyID as needed
}
if (isset($_POST['selectedBarangay'])) {
    $brgyID = $_POST['selectedBarangay'];

    // First Function
    $users = $case->getAllValidCaseByBarangay($brgyID);

    if ($users && $users->num_rows > 0) {
        $heatmapData = [];

        while ($row = $users->fetch_assoc()) {
            // Check if 'latitude' and 'longitude' keys exist in $row before accessing them
            if (isset($row['latitude'], $row['longitude'])) {
                $lat = $row['latitude'];
                $lng = $row['longitude'];
                $heatmapData[] = [$lat, $lng]; // Push data to the array
            }
        }
    }

    $brgy = $barangay->getBrgyLocation($brgyID);

    if ($brgy && isset($brgy[0]['latitude'], $brgy[0]['longitude'])) {
        $lat1 = $brgy[0]['latitude'];
        $lng1 = $brgy[0]['longitude'];
        $barangayName = $brgy[0]['barangay'];
    }

    $counts = $count->countCase($brgyID);

    $counts1 = $count1->countAllCase($brgyID);

    $counts2 = $count2->countAllCasePerYear($brgyID);

    $counts3 = $count3->countAllValidCasePerYear($brgyID);

    // echo var_dump($counts, $counts2, $counts2, $counts3);
} else {
    // Handle the case when selectedBarangay is not set
    // echo json_encode(['error' => 'Selected barangay not provided.']);
}

// Now you can use $lat, $lng, $lat1, and $lng1 outside of their respective scopes if needed.
// $admin = new Admin();
// $user = $admin->getAdminName($email, $password);
// session_start();
$name = isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : '';


if (isset($_SESSION['brgyID'])) {
    // Retrieve the brgyID
    $brgyID = isset($_SESSION['brgyID']) ? $_SESSION['brgyID'] : '';

    $barangay = new Barangay();
    // $brgyID = isset($user['brgyID']) ? $user['brgyID'] : '';
    $result = $barangay->getBrgyName($brgyID);
    $barangay = new Barangay();
    $case = new Cases();
    $count = new Cases();
    $count1 = new Cases();
    $count2 = new Cases();
    $count3 = new Cases();
    $lat = [];
    $lng = [];
    $lat1 = [];
    $lng1 = [];
    $barangayName = ""; // Initialize $barangayName

    // $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

    $brgyID = $user[1];

    $users = $case->getAllValidCaseByBarangay($brgyID);

    if ($users && $users->num_rows > 0) {
        $heatmapData = [];

        while ($row = $users->fetch_assoc()) {
            // Assuming latitude and longitude are always present, handle null or empty values if necessary
            $lat = floatval($row['latitude']); // Convert to float
            $lng = floatval($row['longitude']); // Convert to float
            $heatmapData[] = [$lat, $lng];
        }
    }
    $brgyLocation = $barangay->getBrgyLocation($brgyID);

    if (!empty($brgyLocation) && isset($brgyLocation[0])) {
        $barangayName = $brgyLocation[0]["barangay"];
        $lat1 = $brgyLocation[0]["latitude"];
        $lng1 = $brgyLocation[0]["longitude"];
    } else {
        echo "No location information available for the given barangay ID.\n";
    }
    $counts = $count->countCase($brgyID);
    $counts1 = $count1->countAllCase($brgyID);
    $counts2 = $count2->countAllCasePerYear($brgyID);
    $counts3 = $count3->countAllValidCasePerYear($brgyID);
    $brgyLocation = $barangay->getBrgyLocation($brgyID);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETSTAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <link rel="stylesheet" href="style.css">
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.umd.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/leaflet-maptilersdk/v1.0.0/leaflet-maptilersdk.js"></script>
    <script src="https://unpkg.com/heatmap.js"></script>
    <script src="https://unpkg.com/leaflet.heat"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <link rel="manifest" href="/manifest.json"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  <style>
          body {
            background-color: #f8f9fa;
        }

        /* 
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 200%;
            margin-left: -200px;
            margin-right: auto;
            align-items: start;
        } */

        .container {
            /* margin-top: 50px; */
            background-color: #ffffff;
            /* padding: 20px; */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* .container .content {
            flex: 1;
            overflow-y: auto;
        } */

        h1 {
            color: black;
            font-weight: bold;
        }

        .left-sidebar--sticky-container {
            width: 100%;
        }

        .nav-tabs {
            margin-bottom: 1rem;
        }

        .modal-dialog {
            margin: 0 auto;
        }

        #main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        /* #map {
            width: 100%;
            Adjust width to match container
            height: 50vh;
            border: none;
            Remove border
        } */
        #map {
            /* max-width: 80%; */
            height: 50vh;
            /* margin: 10px auto; */
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;

        }

        #myChart,
        #pieChart {
            width: 300%;
            /* Adjust width to match container */
            border: black;
            /* Remove border */
        }




        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }
        }



        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: start;
        }

        @media (min-width: 768px) {
            .chart-container {
                flex-wrap: nowrap;
            }
        }

        canvas {
            max-width: 200%;
            height: auto;
        }

    </style>
</head>


<body>
    <!-- <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="col-md-10 p-1 mt-2 my-auto mx-auto"> -->

    <div class="b-example-divider"></div>
    <div class="container">
        <!-- <div class="wrapper"> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="feature-box">
                        <h1>PETSTAT</h1>
                        <p>A Pet Registry System For Tracking and Analyzing Dogs and Cats Population</p>
                    </div>
                
        <div>
            <div class="container mt-2 p-3">
                <strong>Filter: </strong>
                <select class="form-select mb-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="">Select...</option>
                    <option value="filterCasesIndex.php">Cases</option>
                    <option value="filterPetIndex.php">Pets</option>
                </select>
            </div>


            
            </div>
            
            <div class="col d-flex flex-column h-sm-200">
                <main class="row overflow-auto">
                    <div class="col-md-10 p-1 mt-2 my-auto mx-auto">
                        <!-- <div class="container mt-2 p-3">
                            <strong>Filter: </strong>
                            <select class="form-select mb-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option value="">Select...</option>
                                <option value="brgyFilterCase.php">Cases</option>
                                <option value="brgyFilterPet.php">Pets</option>
                            </select>
                        </div> -->
                        <div class="container mt-2 p-3">
                            <!-- <h1 class="text-center">Pet Statistic for Barangay <?php echo $barangayName ?></h1> -->
                            <div id="map"></div>
                            <script>
                                const key = 'A8yOIIILOal2yE0Rvb63';

                                // Use PHP to echo the latitude and longitude values
                                const lat1 = <?php echo $lat1; ?>;
                                const lng1 = <?php echo $lng1; ?>;

                                const map = L.map('map').setView([lat1, lng1], 15);

                                const mtLayer = L.maptilerLayer({
                                    apiKey: key,
                                    style: "8a85054c-5879-4e0b-b2f8-7f9564b6e3f8", //optional
                                }).addTo(map);

                                var heatmapData = [];

                                // Create heatmap layer using Leaflet Heatmap Overlay plugin
                                var heat = L.heatLayer(heatmapData, {
                                    radius: 20,
                                    blur: 30,
                                    maxZoom: 18,
                                    max: .3, // Remove concentrationFactor from here
                                    gradient: {
                                        0.5: 'green'
                                    }
                                }).addTo(map);

                                $(document).ready(function() {
                                    // Listen for changes in the select element
                                    $("#barangay").change(function() {
                                        // Get the selected value
                                        var selectedBarangay = $(this).val();

                                        // Create a form dynamically
                                        var form = $('<form action="Vcase_heatmaps.php" method="POST">' +
                                            '<input type="hidden" name="selectedBarangay" value="' + selectedBarangay + '">' +
                                            '</form>');

                                        // Append the form to the body and submit it
                                        $('body').append(form);
                                        form.submit();
                                    });
                                });

                                // Update heatmap data and redraw the layer when needed
                                function updateHeatmapData(newData) {
                                    heatmapData = newData;
                                    heat.setLatLngs(newData).redraw();
                                }

                                <?php
                                // JavaScript block with PHP values
                                if (isset($heatmapData)) {
                                    echo "var initialData = " . json_encode($heatmapData) . ";";
                                    echo "updateHeatmapData(initialData);"; // Update the heatmap initially with fetched data
                                }
                                ?>
                            </script>
                        </div>
                        <div class="container-fluid">
                            <div class="row overflow-auto">
                                <div class="col-md-8 mx-auto">
                                    <div class="chart-container mt-2 justify-content">
                                        <!-- <div class="chart-container d-flex  mt-2 justify-content-center mx-auto"> -->
                                        <!-- <canvas class="container col-12 m-1" id="myChart"></canvas>
                                        <script>
                                            const options = {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                // Other chart options...
                                            };
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Data for the chart
                                                <?php
                                                // JavaScript block with PHP values for the chart
                                                if (isset($counts, $counts1)) {
                                                    // Create an array with all month names
                                                    $allMonths = [
                                                        'January', 'February', 'March', 'April', 'May', 'June',
                                                        'July', 'August', 'September', 'October', 'November', 'December'
                                                    ];

                                                    // Initialize labels and data arrays
                                                    $labels = [];
                                                    $data = [];
                                                    $data1 = [];


                                                    // Loop through all months
                                                    foreach ($allMonths as $monthName) {
                                                        // Check if there is data for the current month in $counts
                                                        $found = false;
                                                        foreach ($counts as $count) {
                                                            $countMonthName = date('F', mktime(0, 0, 0, $count['month'], 1, $count['year']));
                                                            if ($countMonthName === $monthName) {
                                                                $found = true;
                                                                // Extract 'count_per_month' value as data
                                                                $data[] = $count['count_per_month'];
                                                                break;
                                                            }
                                                        }

                                                        // If no data for the current month in $counts, set count_per_month to 0
                                                        if (!$found) {
                                                            $data[] = 0;
                                                        }

                                                        // Check if there is data for the current month in $counts1
                                                        $found1 = false;
                                                        foreach ($counts1 as $count1) {
                                                            $countMonthName = date('F', mktime(0, 0, 0, $count1['month'], 1, $count1['year']));
                                                            if ($countMonthName === $monthName) {
                                                                $found1 = true;
                                                                // Extract 'count_per_month' value as data1
                                                                $data1[] = $count1['count_per_month'];
                                                                break;
                                                            }
                                                        }

                                                        // If no data for the current month in $counts1, set count_per_month to 0
                                                        if (!$found1) {
                                                            $data1[] = 0;
                                                        }


                                                        // Add the month name to labels only once
                                                        $labels[] = $monthName;
                                                    }

                                                    echo "var labels = " . json_encode($labels) . ";";
                                                    echo "var data = " . json_encode($data) . ";";
                                                    echo "var data1 = " . json_encode($data1) . ";";
                                                }
                                                ?>

                                                // Create the chart
                                                var ctx = document.getElementById('myChart').getContext('2d');
                                                var myChart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: labels,
                                                        datasets: [{
                                                                label: 'Reported Bite Case',
                                                                data: data1,
                                                                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                                                borderColor: 'rgba(0, 123, 255, 1)',
                                                                borderWidth: 1,
                                                            },
                                                            {
                                                                label: 'Confirm Bite Case',
                                                                data: data,
                                                                backgroundColor: 'rgba(255, 0, 0, 0.5)',
                                                                borderColor: 'rgba(255, 0, 0, 1)',
                                                                borderWidth: 1,
                                                            }
                                                        ]
                                                    },
                                                    options: {
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </script> -->
                                        <!-- <canvas class="container col-12 p-1 m-1" id="pieChart" style="max-width: 300px; max-height:300px"></canvas>
                                        <script>
                                            // ... (existing scripts)
                                            // Data for the pie chart
                                            <?php
                                            // Initialize arrays for data2 and data3
                                            $data2 = [];
                                            $data3 = [];

                                            // Check if there is data for the current year in $counts2
                                            $found2 = false;
                                            foreach ($counts2 as $count2) {
                                                // Extract 'count_per_year' value as data2
                                                $data2[Date('Y')] = $count2['count_per_year'];
                                                $found2 = true;
                                            }

                                            // If no data for the current year in $counts2, set count_per_year to 0
                                            if (!$found2) {
                                                $data2[Date('Y')] = 0;
                                            }

                                            // Check if there is data for the current year in $counts3
                                            $found3 = false;
                                            foreach ($counts3 as $count3) {
                                                // Extract 'count_per_year' value as data3
                                                $data3[Date('Y')] = $count3['count_per_year'];
                                                $found3 = true;
                                            }

                                            // If no data for the current year in $counts3, set count_per_year to 0
                                            if (!$found3) {
                                                $data3[Date('Y')] = 0;
                                            }

                                            // Calculate the percentage of $data3 with respect to $data2
                                            $slicePercentage = ($data2[Date('Y')] > 0) ? ($data3[Date('Y')] / $data2[Date('Y')]) * 100 : 0;

                                            // Calculate the percentage of the remaining data (whole pizza)
                                            $remainingPercentage = 100 - $slicePercentage;

                                            // Use $slicePercentage and $remainingPercentage in the JavaScript block
                                            echo "var slicePercentage = " . json_encode($slicePercentage) . ";";
                                            echo "var remainingPercentage = " . json_encode($remainingPercentage) . ";";
                                            ?>




                                            // Create the pie chart
                                            // Create the pie chart
                                            // Create the pie chart
                                            var ctxPie = document.getElementById('pieChart').getContext('2d');
                                            var myPieChart = new Chart(ctxPie, {
                                                type: 'pie', // Use 'doughnut' type for a pie chart with a hole
                                                data: {
                                                    labels: ['Reported Bite Case', 'Confirmed Bite Cases'],
                                                    datasets: [{
                                                        data: [remainingPercentage, slicePercentage],
                                                        backgroundColor: ['rgba(255, 0, 0, 0.5)', 'rgba(0, 123, 255, 0.5)'],
                                                        borderColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 123, 255, 1)'],
                                                        borderWidth: 1,
                                                    }],
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    legend: {
                                                        display: true,
                                                        position: 'bottom',
                                                        labels: {
                                                            fontColor: 'black',
                                                        },
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Bite Case Distribution', // Set your desired title here
                                                        fontSize: 18,
                                                    },
                                                },
                                            });
                                        </script> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
                <div class="col-md-6"></div>
            </div>
        </div>

            <!-- </div>
                </main>
            </div> -->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>