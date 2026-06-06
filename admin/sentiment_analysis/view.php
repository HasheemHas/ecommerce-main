<?php
/**
 * Sentiment Analysis View.
 */
global $mydb;

// Fetch all reviews
$mydb->setQuery("
    SELECT r.*, p.PRODESC, c.FNAME, c.LNAME 
    FROM product_reviews_sentiment r 
    JOIN tblproduct p ON r.product_id = p.PROID 
    JOIN tblcustomer c ON r.customer_id = c.CUSTOMERID 
    ORDER BY r.reviewed_at DESC
");
$reviews = $mydb->loadResultList();

// Aggregates
$total = count($reviews);
$pos = 0;
$neu = 0;
$neg = 0;
$fake = 0;

foreach ($reviews as $rev) {
    if ($rev->sentiment_label === 'Positive') $pos++;
    elseif ($rev->sentiment_label === 'Neutral') $neu++;
    else $neg++;
    
    if ($rev->is_fake) $fake++;
}

$posPct = $total > 0 ? round(($pos / $total) * 100, 1) : 0;
$neuPct = $total > 0 ? round(($neu / $total) * 100, 1) : 0;
$negPct = $total > 0 ? round(($neg / $total) * 100, 1) : 0;
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-comments-o" style="color:var(--primary-light);"></i> Sentiment Analysis <small>BERT Parser</small></h1>
    </div>
</div>

<!-- KPI Summaries -->
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left:4px solid #22c55e; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:15px;">
                <div style="font-size:11px; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Positive Reviews</div>
                <div style="font-size:28px; font-weight:800; color:#22c55e; margin:8px 0;"><?php echo $posPct; ?>%</div>
                <div style="font-size:10px; color:var(--text-muted);"><?php echo $pos; ?> out of <?php echo $total; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left:4px solid #64748b; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:15px;">
                <div style="font-size:11px; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Neutral Reviews</div>
                <div style="font-size:28px; font-weight:800; color:#64748b; margin:8px 0;"><?php echo $neuPct; ?>%</div>
                <div style="font-size:10px; color:var(--text-muted);"><?php echo $neu; ?> out of <?php echo $total; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left:4px solid #ef4444; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:15px;">
                <div style="font-size:11px; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Negative Reviews</div>
                <div style="font-size:28px; font-weight:800; color:#ef4444; margin:8px 0;"><?php echo $negPct; ?>%</div>
                <div style="font-size:10px; color:var(--text-muted);"><?php echo $neg; ?> out of <?php echo $total; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; border-left:4px solid #f59e0b; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:15px;">
                <div style="font-size:11px; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Spam/Fake Alerts</div>
                <div style="font-size:28px; font-weight:800; color:#f59e0b; margin:8px 0;"><?php echo $fake; ?> detected</div>
                <div style="font-size:10px; color:var(--text-muted);">Flagged by linguistic patterns</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sandbox Form -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Live Sentiment Sandbox
            </div>
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <form action="index.php?action=sandbox" method="POST" style="margin:0;">
                    <div class="form-group">
                        <label for="review_text" style="font-weight:600; font-size:12.5px; color:var(--text-muted);">Sample Review Text:</label>
                        <textarea name="review_text" id="review_text" rows="4" class="form-control" placeholder="Type customer review here..." required style="border-radius:6px; resize:none; font-size:13px;"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="rating" style="font-weight:600; font-size:12.5px; color:var(--text-muted); display:block;">Star Rating:</label>
                        <select name="rating" id="rating" class="form-control" style="border-radius:6px; font-size:13px; width:100px; display:inline-block;">
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block" style="border-radius:6px; font-weight:600; padding:10px;">
                        <i class="fa fa-cogs"></i> Analyze with BERT Model
                    </button>
                </form>
                
                <?php if (isset($sandboxResult)) { 
                    $color = '#22c55e';
                    if ($sandboxResult['sentiment_label'] === 'Negative') $color = '#ef4444';
                    elseif ($sandboxResult['sentiment_label'] === 'Neutral') $color = '#64748b';
                ?>
                    <hr style="border-top:1px solid var(--border-color); margin:20px 0 15px;">
                    <div style="background:var(--table-header-bg); padding:12px; border-radius:8px; border:1px solid var(--border-color); font-size:12.5px;">
                        <div style="font-weight:700; color:var(--primary); margin-bottom:8px; display:flex; align-items:center; justify-content:space-between;">
                            <span>Analysis Output:</span>
                            <span style="color:<?php echo $color; ?>; font-size:13px;"><?php echo $sandboxResult['sentiment_label']; ?></span>
                        </div>
                        <div style="margin-bottom:4px;"><strong>Score:</strong> <?php echo $sandboxResult['sentiment_score']; ?></div>
                        <div style="margin-bottom:4px;"><strong>Topics:</strong> <?php echo implode(', ', $sandboxResult['topics_extracted']); ?></div>
                        <div style="margin-bottom:4px;">
                            <strong>Fake Alert:</strong> 
                            <?php if ($sandboxResult['is_fake']) { ?>
                                <span class="label label-danger">YES (<?php echo $sandboxResult['is_fake_confidence']; ?>% Conf)</span>
                            <?php } else { ?>
                                <span class="label label-success">NO</span>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <!-- Table list -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Review Sentiment Database
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:12px 15px;">Product / Reviewer</th>
                                <th style="font-weight:600; padding:12px 15px;">Review Text</th>
                                <th style="font-weight:600; padding:12px 15px;">Rating</th>
                                <th style="font-weight:600; padding:12px 15px;">Sentiment</th>
                                <th style="font-weight:600; padding:12px 15px;">Topics</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:right;">Fake Check</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $rev) { 
                                $class = 'label-success';
                                if ($rev->sentiment_label === 'Negative') $class = 'label-danger';
                                elseif ($rev->sentiment_label === 'Neutral') $class = 'label-warning';
                                
                                $topics = json_decode($rev->topics_extracted, true);
                            ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:12px 15px;">
                                        <div style="font-weight:700; color:var(--primary-light);"><?php echo htmlspecialchars($rev->PRODESC); ?></div>
                                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">by <?php echo htmlspecialchars($rev->FNAME . ' ' . $rev->LNAME); ?></div>
                                    </td>
                                    <td style="padding:12px 15px; font-style:italic; max-width:250px;">"<?php echo htmlspecialchars($rev->review_text); ?>"</td>
                                    <td style="padding:12px 15px; white-space:nowrap;">
                                        <?php for ($i=1; $i<=5; $i++) {
                                            echo ($i <= $rev->rating) ? '★' : '☆';
                                        } ?>
                                    </td>
                                    <td style="padding:12px 15px;">
                                        <span class="label <?php echo $class; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:10px;"><?php echo $rev->sentiment_label; ?></span>
                                        <div style="font-size:10px; color:var(--text-muted); text-align:center; margin-top:2px;"><?php echo $rev->sentiment_score; ?></div>
                                    </td>
                                    <td style="padding:12px 15px;">
                                        <?php if ($topics) {
                                            foreach ($topics as $t) {
                                                echo '<span class="label label-default" style="display:inline-block; font-size:10px; margin:1px; background:rgba(0,0,0,0.03); color:var(--text-main); border:1px solid var(--border-color);">' . htmlspecialchars($t) . '</span>';
                                            }
                                        } ?>
                                    </td>
                                    <td style="padding:12px 15px; text-align:right;">
                                        <?php if ($rev->is_fake) { ?>
                                            <span class="label label-danger" title="Linguistic anomaly detected">🚨 Fake Alert (<?php echo $rev->is_fake_confidence; ?>%)</span>
                                        <?php } else { ?>
                                            <span class="label label-success">✓ Passed</span>
                                        <?php } ?>
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
