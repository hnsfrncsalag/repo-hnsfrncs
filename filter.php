<?php
session_start();
require_once "class/db_connect.php";
require_once("class/barangay.php");
require_once("class/cases.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.umd.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/leaflet-maptilersdk/v1.0.0/leaflet-maptilersdk.js"></script>
    <script src="https://unpkg.com/heatmap.js"></script>
    <script src="https://unpkg.com/leaflet.heat"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        #main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        #map {
            height: 50vh;
            width: 50vw;
            margin: 10px auto;
            border: 2px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        #myChart, #pieChart {
            height: 50vh;
            width: 50vw;
            margin: 10px auto;
            border: 2px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Filter Pet Cases</h2>

            <?php
function filterData($barangay, $caseType, $petType, $vaccination, $min_date, $max_date) {
    $query = "SELECT * FROM `case` c LEFT JOIN `pet` p ON c.petID = p.petID LEFT JOIN `vaccination` v ON p.petID = v.petID LEFT JOIN `geolocation` g ON g.geoID = c.caseGeoID LEFT JOIN `barangay` b ON b.brgyID = c.brgyID
              WHERE 1"; // Start with 1 to ensure the WHERE clause is always valid

    $conditions = [];
    if (!empty($barangay)) {
        $conditions[] = "c.brgyID = '{$barangay}'";
    }
    if (!empty($caseType)) {
        $conditions[] = "c.caseType = {$caseType}";
    }
    if (!empty($petType)) {
        $conditions[] = "p.petType = {$petType}";
    }
    if (!empty($vaccination)) {
        $conditions[] = "v.vacStatus = {$vaccination}";
    }
    if (!empty($min_date) && !empty($max_date)) {
        $conditions[] = "c.date BETWEEN '{$min_date}' AND '{$max_date}'";
    }

    if (!empty($conditions)) {
        $query .= " AND " . implode(' AND ', $conditions);
    }

    $query .= " GROUP BY c.caseID";

    return $query;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_destroy();

    // Start a new session
    session_start();

    $barangay = isset($_POST['barangay']) ? $_POST['barangay'] : null;
    $caseType = isset($_POST['caseType']) ? $_POST['caseType'] : null;
    $petType = isset($_POST['petType']) ? $_POST['petType'] : null;
    $vaccination = isset($_POST['vaccination']) ? $_POST['vaccination'] : null;
    $min_date = isset($_POST['min_date']) ? $_POST['min_date'] : null;
    $max_date = isset($_POST['max_date']) ? $_POST['max_date'] : null;


    global $conn;

    $query = filterData($barangay, $caseType, $petType, $vaccination, $min_date, $max_date);
    
    $result = mysqli_query($conn, $query);
    // Fetch results and use them
    // echo var_dump($result);
    while ($row = mysqli_fetch_assoc($result)) {
        var_dump($row['caseID']);
        if (isset($row['latitude'], $row['longitude'])) {
            $lat = $row['latitude'];
            echo var_dump($lat);
            $lng = $row['longitude'];
            echo var_dump($lng);
            $heatmapData[] = [$lat, $lng]; // Push data to the array


        }
    }
    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }
    $barangay1 = new Barangay();
    $brgy = $barangay1->getFilterLocation($barangay);

    if ($brgy && isset($brgy[0]['latitude'], $brgy[0]['longitude'])) {
        $lat1 = $brgy[0]['latitude'];
        $lng1 = $brgy[0]['longitude'];
        $barangayName = $brgy[0]['barangay'];
    }
    $case = new Cases();
    $count = new Cases();
    $count1 = new Cases();
    $count2 = new Cases();
    $count3 = new Cases();
    $counts = $count->countCase($barangay); 

    $counts1 = $count1->countAllCase($barangay);

    $counts2 = $count2->countAllCasePerYear($barangay);

    $counts3 = $count3->countAllValidCasePerYear($barangay);
}
?>

<!-- The rest of your HTML code -->

            <!-- Filter form -->
            <form method="post">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="barangay">Barangay:</label>
                        <select id="barangay" class="form-control" name="barangay">
                            <option value="">Select Barangay</option>
                            <?php
                            global $conn;
                            $query = "SELECT brgyID, barangay FROM barangay";
                            $result = mysqli_query($conn, $query);

                            // Loop through the query results and generate options
                            while ($row = mysqli_fetch_assoc($result)) {
                                $brgyID = $row['brgyID'];
                                $barangay = $row['barangay'];
                                echo "<option value='$brgyID'>$barangay</option>";
                            }
                            // Release the result set
                            mysqli_free_result($result);

                            // Check for errors
                            if (!$result) {
                                die("Database query failed.");
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="caseType">Case Type:</label>
                        <select name="caseType" id="caseType" class="form-control">
                            <option value="">All</option>
                            <option value="0">Bite Case</option>
                            <option value="1">Death Case</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="petType">Pet Type:</label>
                        <select name="petType" id="petType" class="form-control">
                            <option value="">All</option>
                            <option value="0">Dog</option>
                            <option value="1">Cat</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="vaccination">Vaccination:</label>
                        <select name="vaccination" id="vaccination" class="form-control">
                            <option value="">All</option>
                            <option value="0">Not Vaccinated</option>
                            <option value="1">Vaccinated</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="min_date">From:</label>
                        <input type="date" name="min_date" id="min_date" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="max_date">To:</label>
                        <input type="date" name="max_date" id="max_date" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>
</div>
<div id="main-content">
            <!-- <h1>Bite Case Report for <?php echo $brgy ?></h1> -->
            <div id="map"></div>
            <script>
    function updateHeatmapData(newData) {
        heatmapData = newData;
        heat.setLatLngs(newData).redraw();
    }

    function initializeMap() {
        const key = 'A8yOIIILOal2yE0Rvb63';
        // Use PHP to echo the latitude and longitude values
        const lat1 = <?php echo isset($lat1) ? $lat1 : 0; ?>;
        const lng1 = <?php echo isset($lng1) ? $lng1 : 0; ?>;
        
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
            max: .3,
        }).addTo(map);

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
    }

    initializeMap(); // Call the function to initialize the map
</script>
<canvas id="myChart"></canvas>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

                // Add the month name to labels only once
                $labels[] = $monthName;
            }

            echo "var labels = " . json_encode($labels) . ";";
            echo "var data = " . json_encode($data) . ";";
        }
        ?>

        // Create the chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Confirmed Bite Cases',
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                }]
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
</script>

<canvas id="pieChart" style="max-width: 300px; max-height: 300px;"></canvas>
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
</script>

<!-- Bootstrap JS and Popper.js CDN (required for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
