<?php
/**
 * Demand Forecasting Dashboard View.
 */
global $mydb;

// Fetch all products
$mydb->setQuery("SELECT p.PROID, p.PRODESC, c.CATEGORIES, p.PROQTY FROM tblproduct p JOIN tblcategory c ON p.CATEGID = c.CATEGID ORDER BY p.PRODESC ASC");
$productList = $mydb->loadResultList();

$selectedProductId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($selectedProductId === 0 && !empty($productList)) {
    $selectedProductId = (int)$productList[0]->PROID;
}

// Fetch forecast from microservice
$forecastData = AIClient::call('/api/forecast/predict?product_id=' . $selectedProductId);
$accuracy = 90.0;
$forecastPoints = [];
$productName = "Product";

if (isset($forecastData['forecast'])) {
    $forecastPoints = $forecastData['forecast'];
    $accuracy = (float)$forecastData['accuracy'];
    $productName = $forecastData['product_name'];
} else {
    // Database fallback if microservice fails
    $mydb->setQuery("SELECT * FROM demand_forecasts WHERE product_id = {$selectedProductId} AND forecast_date >= CURRENT_DATE ORDER BY forecast_date ASC LIMIT 30");
    $dbPoints = $mydb->loadResultList();
    if ($dbPoints) {
        $accuracy = (float)$dbPoints[0]->accuracy_metric;
        foreach ($dbPoints as $row) {
            $forecastPoints[] = [
                'date' => $row->forecast_date,
                'predicted_demand' => (float)$row->predicted_demand,
                'recommended_reorder_qty' => (int)$row->recommended_reorder_qty
            ];
        }
    }
}

// Calculate summary stats
$totalPredicted = 0;
$maxReorder = 0;
foreach ($forecastPoints as $pt) {
    $totalPredicted += $pt['predicted_demand'];
    if ($pt['recommended_reorder_qty'] > $maxReorder) {
        $maxReorder = $pt['recommended_reorder_qty'];
    }
}
$avgPredicted = count($forecastPoints) > 0 ? round($totalPredicted / count($forecastPoints), 2) : 0;

// Format data for Chart.js
$chartLabels = [];
$chartData = [];
foreach ($forecastPoints as $pt) {
    $chartLabels[] = date('M d', strtotime($pt['date']));
    $chartData[] = $pt['predicted_demand'];
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-line-chart" style="color:var(--primary-light);"></i> Demand Forecasting <small>AI Engine</small></h1>
    </div>
</div>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; border:1px solid var(--border-color); box-shadow:var(--shadow);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px; padding:15px 20px;">
                <form method="GET" class="form-inline" style="margin:0;">
                    <input type="hidden" name="view" value="list">
                    <div class="form-group" style="margin-right:10px;">
                        <label for="product_id" style="font-weight:600; font-size:13px; color:var(--text-muted); margin-right:8px;">Select Product:</label>
                        <select name="product_id" id="product_id" class="form-control" onchange="this.form.submit()" style="border-radius:6px; font-weight:500;">
                            <?php foreach ($productList as $p) { ?>
                                <option value="<?php echo $p->PROID; ?>" <?php echo ($p->PROID == $selectedProductId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p->PRODESC) . " (" . $p->CATEGORIES . " - Stock: " . $p->PROQTY . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
                
                <div class="btn-group">
                    <a href="index.php?action=train&product_id=<?php echo $selectedProductId; ?>" class="btn btn-primary btn-sm" style="border-radius:6px; font-weight:600;">
                        <i class="fa fa-refresh"></i> Re-Train Selected
                    </a>
                    <a href="index.php?action=batch" class="btn btn-default btn-sm" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">
                        <i class="fa fa-database"></i> Run Batch Forecast (All Products)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Performance Metrics Row -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Model Accuracy</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;"><?php echo $accuracy; ?>%</div>
                <div style="font-size:11px; color:var(--text-muted);">Mean Absolute Percentage Error (MAPE)</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Avg Daily Predicted Demand</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo $avgPredicted; ?> units</div>
                <div style="font-size:11px; color:var(--text-muted);">Average expected daily checkout rate</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Reorder Alert Quantity</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;"><?php echo $maxReorder; ?> units</div>
                <div style="font-size:11px; color:var(--text-muted);">Recommended stock lead target threshold</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Box -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                30-Day Demand Trend Projection: <?php echo htmlspecialchars($productName); ?>
            </div>
            <div class="panel-body" style="background:var(--card-bg); height:320px; position:relative;">
                <canvas id="forecastChart" style="width:100%; height:100%;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Data Table -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; display:flex; flex-direction:column; height:362px;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Forecast Schedule
            </div>
            <div style="flex:1; overflow-y:auto; background:var(--card-bg);">
                <table class="table table-hover" style="font-size:13px; margin:0;">
                    <thead>
                        <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                            <th style="font-weight:600; padding:10px 15px;">Date</th>
                            <th style="font-weight:600; text-align:right; padding:10px 15px;">Demand</th>
                            <th style="font-weight:600; text-align:right; padding:10px 15px;">Reorder Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($forecastPoints)) { ?>
                            <tr>
                                <td colspan="3" class="text-center" style="color:var(--text-muted); padding:30px;">No forecasts calculated yet. Train the model to generate data.</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($forecastPoints as $row) { ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:10px 15px; font-weight:500;"><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                                    <td style="text-align:right; padding:10px 15px; color:var(--primary-light); font-weight:600;"><?php echo round($row['predicted_demand'], 1); ?></td>
                                    <td style="text-align:right; padding:10px 15px; font-weight:600; color:#f59e0b;"><?php echo $row['recommended_reorder_qty'] > 0 ? $row['recommended_reorder_qty'] : '-'; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script Injection -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('forecastChart').getContext('2d');
    var isDark = document.documentElement.classList.contains('dark-mode');
    
    var gridColor = isDark ? '#1e293b' : '#f1f5f9';
    var textColor = isDark ? '#94a3b8' : '#64748b';
    
    var forecastChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [{
                label: 'Projected Demand (Units)',
                data: <?php echo json_encode($chartData); ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor,
                        font: {
                            family: 'Inter',
                            size: 11
                        }
                    }
                },
                y: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor,
                        font: {
                            family: 'Inter',
                            size: 11
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Handle dark mode theme change in chart
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {
                const dark = document.documentElement.classList.contains('dark-mode');
                forecastChart.options.scales.x.grid.color = dark ? '#1e293b' : '#f1f5f9';
                forecastChart.options.scales.y.grid.color = dark ? '#1e293b' : '#f1f5f9';
                forecastChart.options.scales.x.ticks.color = dark ? '#94a3b8' : '#64748b';
                forecastChart.options.scales.y.ticks.color = dark ? '#94a3b8' : '#64748b';
                forecastChart.update();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });
});
</script>
