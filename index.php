<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title></title>
        <title>Chart.js Demo</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
        </style>
    </head>
    <body>
        <nav>
            <div id="nav-text">Chart.js Demo</div>
        </nav>
        <div id="container">
            <div id="top-section">
                <div class="text">Destination:</div>
                <select name="destination" id="destination">
                    <option selected="selected">---</option>
                </select>
                <div class="text">Price Range: £<span id="min-price">0</span> - £<span id="max-price">1000</span></div>
                <div id="slider"></div>
            </div>
            <div id="graph-blocks-container">

                <div class="graph-block">
                    <div class="graph-block-title">
                        Tickets price comparison by Airline
                    </div>
                    <div class="graph-block-graph">
                        <p>Graph will load once you choose a destination.</p>
                        <img src="img/loading.svg" class="loading-img" />
                        <canvas id="graph1" class="graph" style="width: 100%; height: 300px"></canvas>
                    </div>
                </div>

                <div class="graph-block">
                    <div class="graph-block-title">
                        Touristic Attractions Distribution
                    </div>
                    <div class="graph-block-graph">
                        <p>Graph will load once you choose a destination.</p>
                        <img src="img/loading.svg" class="loading-img" />
                        <canvas id="graph2" class="graph" style="width: 100%; height: 300px"></canvas>
                    </div>
                </div>

                <div class="graph-block">
                    <div class="graph-block-title">
                        Average daily accommodation cost
                    </div>
                    <div class="graph-block-graph">
                        <p>Graph will load once you choose a destination.</p>
                        <img src="img/loading.svg" class="loading-img" />
                        <canvas id="graph3" class="graph" style="width: 100%; height: 300px"></canvas>
                    </div>
                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <script src="js/app.js"></script>
    </body>
</html>