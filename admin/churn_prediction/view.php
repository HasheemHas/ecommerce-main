<?php
/**
 * Customer Churn Risk Prediction View.
 */
global $mydb;

// Fetch churn scores from microservice
$churnResults = AIClient::call('/api/churn/batch', 'POST');
$churnList = [];

if (isset($churnResults['results'])) {
    $churnList = $churnResults['results'];
} else {
    // Database fallback
    $mydb->setQuery("
        SELECT c.CUSTOMERID, c.FNAME, c.LNAME, c.EMAILADD, cs.churn_probability, cs.risk_level, cs.top_risk_factors, cs.evaluated_at
        FROM churn_scores cs
        JOIN tblcustomer c ON cs.customer_id = c.CUSTOMERID
        ORDER BY cs.churn_probability DESC
    ");
    $dbList = $mydb->loadResultList();
    if ($dbList) {
        foreach ($dbList as $row) {
            $churnList[] = [
                'customer_id' => $row->CUSTOMERID,
                'customer_name' => $row->FNAME . ' ' . $row->LNAME,
                'churn_probability' => (float)$row->churn_probability,
                'risk_level' => $row->risk_level,
                'top_risk_factors' => json_decode($row->top_risk_factors, true),
                'evaluated_at' => $row->evaluated_at
            ];
        }
    }
}

// Calculate summary stats
$highRisk = 0;
$medRisk = 0;
$lowRisk = 0;
foreach ($churnList as $c) {
    if ($c['risk_level'] === 'High') $highRisk++;
    elseif ($c['risk_level'] === 'Medium') $medRisk++;
    else $lowRisk++;
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-frown-o" style="color:var(--primary-light);"></i> Churn Risk Analysis <small>AI Engine</small></h1>
    </div>
</div>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; border:1px solid var(--border-color); box-shadow:var(--shadow);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px; padding:15px 20px;">
                <div style="font-size:14px; font-weight:500; color:var(--text-muted);">
                    Evaluates customer checkout intervals, transaction volumes, and profiles via XGBoost.
                </div>
                <a href="index.php?action=train" class="btn btn-primary btn-sm" style="border-radius:6px; font-weight:600;">
                    <i class="fa fa-refresh"></i> Run Churn Model Scoring
                </a>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards Row -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left: 5px solid #ef4444; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">High Risk Customers</div>
                <div style="font-size:32px; font-weight:800; color:#ef4444; margin:10px 0;"><?php echo $highRisk; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Probability of churn exceeds 70%</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left: 5px solid #f59e0b; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Medium Risk Customers</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;"><?php echo $medRisk; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Probability between 30% and 70%</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left: 5px solid #22c55e; overflow:hidden;">
            <div class="panel-body" style="background: var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Low Risk / Active</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;"><?php echo $lowRisk; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Probability of churn below 30%</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Customer Churn Risk Scoring
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dash-table" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">ID</th>
                                <th style="font-weight:600; padding:15px;">Customer Name</th>
                                <th style="font-weight:600; padding:15px; width:220px;">Churn Probability</th>
                                <th style="font-weight:600; padding:15px;">Risk Level</th>
                                <th style="font-weight:600; padding:15px;">Top Risk Factors</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($churnList as $c) { 
                                $riskColor = '#22c55e'; // Green
                                $riskClass = 'label-success';
                                if ($c['risk_level'] === 'High') {
                                    $riskColor = '#ef4444'; // Red
                                    $riskClass = 'label-danger';
                                } elseif ($c['risk_level'] === 'Medium') {
                                    $riskColor = '#f59e0b'; // Orange
                                    $riskClass = 'label-warning';
                                }
                            ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:15px; font-weight:600;"><?php echo $c['customer_id']; ?></td>
                                    <td style="padding:15px; font-weight:600;"><?php echo htmlspecialchars($c['customer_name']); ?></td>
                                    <td style="padding:15px; vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div class="progress" style="height:8px; margin:0; flex:1; background:var(--border-color); border-radius:4px; overflow:hidden;">
                                                <div class="progress-bar" style="background:<?php echo $riskColor; ?>; width:<?php echo $c['churn_probability']; ?>%;"></div>
                                            </div>
                                            <span style="font-weight:700; width:45px; text-align:right;"><?php echo $c['churn_probability']; ?>%</span>
                                        </div>
                                    </td>
                                    <td style="padding:15px;">
                                        <span class="label <?php echo $riskClass; ?>" style="font-weight:600; font-size:11px; padding:3px 8px; border-radius:10px;"><?php echo $c['risk_level']; ?></span>
                                    </td>
                                    <td style="padding:15px;">
                                        <?php 
                                        if (empty($c['top_risk_factors'])) {
                                            echo '<span style="color:var(--text-muted); font-size:12px;">Active purchasing engagement</span>';
                                        } else {
                                            foreach ($c['top_risk_factors'] as $factor) {
                                                echo '<span class="label label-default" style="display:inline-block; font-size:10.5px; margin:2px; padding:3px 6px; border-radius:4px; font-weight:500; background:rgba(0,0,0,0.05); color:var(--text-main); border:1px solid var(--border-color);">' . htmlspecialchars($factor) . '</span>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td style="padding:15px; text-align:right;">
                                        <a href="javascript:void(0);" onclick="alert('Retaining incentive discount code sent via email to: <?php echo htmlspecialchars($c['customer_name']); ?>!')" 
                                           class="btn btn-default btn-xs" style="font-weight:600; border-color:var(--border-color);">
                                            <i class="fa fa-envelope-o"></i> Offer Incentive
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
