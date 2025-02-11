<?php
session_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Performance Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Trebuchet MS, sans-serif;
            text-align: center;
            margin: 0;
            background-color: #f4f4f4;
            overflow-x : hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:rgb(255, 255, 255);
            padding: 15px 30px;
            color: white;
        }


        .logo img {
            width: 150px;
        }

        .top-right-buttons button {
            font-family: Trebuchet MS, sans-serif;
            background: none;
            border: none;
            color: black;
            font-size: 16px;
            margin-left: 15px;
            cursor: pointer;
        }

        .content {
            display: flex;
            min-height: 100vh;
        }

        .navbar {
            width: 300px;
            background:rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .navbar li {
            height: 40px;
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items : flex-start;
            padding: 0px 15px;
            margin: 10px 10px;
        }

        .navbar li a {
            color:#202224;
        }

        .navbar li:hover {
            box-shadow: inset 600px  0 0 0 rgb(185, 204, 255);
            cursor: pointer;
            transition: ease-out 0.9s ;
        }
        
        /*
        .navbar li a:hover {
            color:rgb(255, 255, 255);
        }
        */

        .navbar a button {
            font-family: Trebuchet MS, sans-serif;
            padding: 20px 80px;
            margin: 40px;
            font-size: 16px;
            cursor: pointer;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .navbar a button:hover {
            background-color: #3b5fc9;
        }

        a:link { text-decoration: none; }

        a:visited { text-decoration: none; }

        a:hover { text-decoration: none; }

        a:active { text-decoration: none; }

        .menu {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .pages {
            display: flex;
            flex-direction: column;
        }

        #currentPage {
            background-color:rgb(185, 204, 255);
            padding: 10px;
        }

        .divider {
            height: 1px;
            background:rgb(213, 213, 213);
            margin: 10px 15px;
        }

        .section-title {
            color:rgb(198, 198, 198);
            font-size: 14px;
            text-align: left;
            padding-top: 10px;
            padding-left: 20px;
            margin-bottom: 5px;
        }

        .bottom {
            margin-top: auto;
        }

        .container {
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            flex-grow: 1; /* Ensures centering within content area */
            height: 80vh;
            margin-left: 50px;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .result-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            height: 60%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .details-container {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #86abff;
            width: fit-content;
        }

        .details-item {
            display: inline-block;
            padding: 5px 15px;
            margin: 0 10px;
            font-weight: 500;
            color: white;
        }

        .details-divider {
            display: inline-block;
            color: #ccc;
            margin: 0 5px;
        }

        .no-data-message {
            text-align: center;
            color: #666;
            font-size: 1.1em;
            margin: 20px 0;
        }


    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="./pics/logo.png" alt="KinderWise Logo">
        </div>
        <div class="top-right-buttons">
            <button>🔔 Notifications</button>
            <button>👤 Profile</button>
        </div>
    </header>
    
    <div class = "content">
        <div class="navbar">
            <nav>
                <a href="principal.php"><button>Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                    <li><a href="student_performance.php"><div id="currentPage">📊 View Student Performance</div></a></li>
                    <li><a href="track_assessments.php">⏱️ Track Assessment Submission</a></li>
                    <li><a href="verify_results.php">📝 Verify Student Results</a></li>
                    <br></br>
                    <br></br>
                    
                </div>

                <div class="divider"></div>
                
                <div class="bottom">
                    <li>⚙️ Settings</li>
                    <li>↩️ Logout</li>
                </div>
    
            </nav>
        </div>

        <div class="container">
            <h1>Student Performance Analysis</h1>

            <?php
                // Get all classes
                $classQuery = "SELECT DISTINCT classCode FROM class ORDER BY classCode";
                $classResult = mysqli_query($conn, $classQuery);

                // Get all subjects
                $subjectQuery = "SELECT DISTINCT subjectName FROM subject ORDER BY subjectName";
                $subjectResult = mysqli_query($conn, $subjectQuery);

                // Get all assessment types
                $assessmentQuery = "SELECT DISTINCT assessmentType FROM assessment ORDER BY assessmentType";
                $assessmentResult = mysqli_query($conn, $assessmentQuery);
            ?>

            <div class="filters">
                <select id="classSelect">
                    <option value="">Select Class</option>
                    <?php while($row = mysqli_fetch_assoc($classResult)) { ?>
                        <option value="<?php echo $row['classCode']; ?>"><?php echo $row['classCode']; ?></option>
                    <?php } ?>
                </select>
                
                <select id="subjectSelect">
                    <option value="">Select Subject</option>
                    <?php while($row = mysqli_fetch_assoc($subjectResult)) { ?>
                        <option value="<?php echo $row['subjectName']; ?>"><?php echo $row['subjectName']; ?></option>
                    <?php } ?>
                </select>
                
                <select id="assessmentSelect">
                    <option value="">Select Assessment Type</option>
                    <?php while($row = mysqli_fetch_assoc($assessmentResult)) { ?>
                        <option value="<?php echo $row['assessmentType']; ?>"><?php echo $row['assessmentType']; ?></option>
                    <?php } ?>
                </select>
                <button onclick="fetchResults()">Confirm</button>
            </div>

            <h2>Grade Percentage Overview</h2>

            <div class="result-box">
                <div id="details" class="details-container"></div>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentChart = null;  // Variable to store the current chart instance

        function formatDetails(classVal, subjectVal, assessmentVal) {
            return `
                <span class="details-item">${classVal}</span>
                <span class="details-divider">|</span>
                <span class="details-item">${subjectVal}</span>
                <span class="details-divider">|</span>
                <span class="details-item">${assessmentVal}</span>
            `;
        }
        
        function fetchResults() {
            const classVal = document.getElementById("classSelect").value;
            const subjectVal = document.getElementById("subjectSelect").value;
            const assessmentVal = document.getElementById("assessmentSelect").value;
            
            if (!classVal || !subjectVal || !assessmentVal) {
                alert("Please select all filters");
                return;
            }
            
            // Show loading state
            document.getElementById("details").innerText = "Loading...";
            
            // Destroy existing chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }

            const url = `fetch_results.php?class=${encodeURIComponent(classVal)}&subject=${encodeURIComponent(subjectVal)}&assessment=${encodeURIComponent(assessmentVal)}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Check if all values in data array are 0
                    const hasData = data.some(value => value > 0);
                    
                    if (!hasData) {
                        document.getElementById("details").innerHTML = formatDetails(classVal, subjectVal, assessmentVal);
                        document.querySelector('.chart-container').innerHTML = "No available performance data for the selected filters.";
                        return;
                    }

                    document.getElementById("details").innerHTML = formatDetails(classVal, subjectVal, assessmentVal);
                    document.querySelector('.chart-container').innerHTML = '<canvas id="gradeChart"></canvas>';
                    generateChart(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById("details").innerHTML = "No available performance data for the selected filters.";
                    //document.getElementById("details").innerHTML = "Error loading data. Please try again.";
                    
                });
        }
        
        function generateChart(data) {
            const ctx = document.getElementById("gradeChart").getContext("2d");
            
            // Destroy existing chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }
            
            currentChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["A (80-100%)", "B (60-79%)", "C (50-59%)", "D (45-49%)", "E (40-44%)", "G (0-39%)"],
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            "#4caf50",  // Green for A
                            "#2196f3",  // Blue for B
                            "#ffeb3b",  // Yellow for C
                            "#ff9800",  // Orange for D
                            "#f44336",  // Red for E
                            "#9e9e9e"   // Grey for G
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value}%`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>
