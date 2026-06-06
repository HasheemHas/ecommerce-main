<?php
/**
 * Shipping Tracker View.
 */
global $mydb;

// Fetch all tracking packages
$mydb->setQuery("SELECT * FROM shipping_tracking ORDER BY tracking_id DESC");
$packages = $mydb->loadResultList();

$selectedTrackingId = isset($_GET['tracking_id']) ? (int)$_GET['tracking_id'] : 0;
if ($selectedTrackingId === 0 && !empty($packages)) {
    $selectedTrackingId = (int)$packages[0]->tracking_id;
}

// Fetch details for the selected package
$selectedPkg = null;
foreach ($packages as $pkg) {
    if ($pkg->tracking_id == $selectedTrackingId) {
        $selectedPkg = $pkg;
        break;
    }
}

// Fetch updates log
$updates = [];
if ($selectedPkg) {
    $mydb->setQuery("SELECT * FROM shipping_updates WHERE tracking_id = {$selectedTrackingId} ORDER BY updated_at DESC");
    $updates = $mydb->loadResultList();
}

// Aggregates
$activeCount = 0;
$delayedCount = 0;
foreach ($packages as $p) {
    if ($p->status !== 'Delivered') $activeCount++;
    if ($p->status === 'Delayed') $delayedCount++;
}
?>

<!-- Leaflet.js Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck" style="color:var(--primary-light);"></i> Shipping Tracker <small>Logistics Suite</small></h1>
    </div>
</div>

<!-- KPI Rows -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Active Shipments</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo $activeCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Packages currently in transit or out for delivery</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Logistics Alert Alerts</div>
                <div style="font-size:32px; font-weight:800; color:#ef4444; margin:10px 0;"><?php echo $delayedCount; ?> delayed</div>
                <div style="font-size:11px; color:var(--text-muted);">Shipments exceeding original ETA windows</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Avg Delivery Velocity</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;">24.5 hrs</div>
                <div style="font-size:11px; color:var(--text-muted);">Average elapsed time from pickup to delivery</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Map Box -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary); display:flex; align-items:center; justify-content:space-between;">
                <span>Logistics Route Map: Order #<?php echo $selectedPkg ? $selectedPkg->order_number : ''; ?></span>
                <?php if ($selectedPkg) { ?>
                    <span class="label label-info" style="font-size:11px;"><?php echo $selectedPkg->carrier; ?> - <?php echo $selectedPkg->tracking_number; ?></span>
                <?php } ?>
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:10px;">
                <?php if ($selectedPkg) { ?>
                    <div id="map" style="height:380px; border-radius:8px;"></div>
                <?php } else { ?>
                    <div style="height:380px; display:flex; align-items:center; justify-content:center; color:var(--text-muted);">
                        No package selected for mapping.
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <!-- Delivery Timeline / Updates -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; display:flex; flex-direction:column; height:442px;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Tracking Updates
            </div>
            <div style="flex:1; overflow-y:auto; background:var(--card-bg); padding:15px;">
                <?php if (empty($updates)) { ?>
                    <div style="text-align:center; color:var(--text-muted); padding:30px;">No updates logged.</div>
                <?php } else { ?>
                    <ul class="timeline" style="list-style:none; padding:0; margin:0; position:relative;">
                        <?php foreach ($updates as $index => $u) { 
                            $circleColor = $index === 0 ? '#3b82f6' : '#94a3b8';
                        ?>
                            <li style="position:relative; padding-left:25px; margin-bottom:20px;">
                                <!-- Timeline Line -->
                                <?php if ($index < count($updates) - 1) { ?>
                                    <div style="position:absolute; left:6px; top:12px; bottom:-25px; width:2px; background:var(--border-color);"></div>
                                <?php } ?>
                                <!-- Bullet Dot -->
                                <div style="position:absolute; left:2px; top:4px; width:10px; height:10px; border-radius:50%; background:<?php echo $circleColor; ?>;"></div>
                                
                                <div style="font-weight:700; font-size:12.5px;"><?php echo htmlspecialchars($u->location); ?></div>
                                <div style="font-size:12px; color:var(--text-muted); margin-top:2px;"><?php echo htmlspecialchars($u->status_details); ?></div>
                                <div style="font-size:10px; color:var(--text-muted); margin-top:4px;"><?php echo date('M d, H:i a', strtotime($u->updated_at)); ?></div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Shipments Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Logistics Packages Registry
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Order #</th>
                                <th style="font-weight:600; padding:15px;">Carrier</th>
                                <th style="font-weight:600; padding:15px;">Tracking Number</th>
                                <th style="font-weight:600; padding:15px;">Status</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">ETA / Actual</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Update Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packages as $pkg) { 
                                $statusLbl = 'label-info';
                                if ($pkg->status === 'Delivered') $statusLbl = 'label-success';
                                elseif ($pkg->status === 'Delayed') $statusLbl = 'label-danger';
                                elseif ($pkg->status === 'Out for Delivery') $statusLbl = 'label-warning';
                                
                                $isSelected = ($pkg->tracking_id == $selectedTrackingId);
                            ?>
                                <tr style="border-bottom:1px solid var(--border-color); <?php echo $isSelected ? 'background:rgba(59,130,246,0.03);' : ''; ?>">
                                    <td style="padding:15px; font-weight:700;">
                                        <a href="index.php?tracking_id=<?php echo $pkg->tracking_id; ?>" style="color:inherit;">
                                            #<?php echo $pkg->order_number; ?>
                                        </a>
                                    </td>
                                    <td style="padding:15px;"><?php echo htmlspecialchars($pkg->carrier); ?></td>
                                    <td style="padding:15px; font-family:monospace;"><?php echo htmlspecialchars($pkg->tracking_number); ?></td>
                                    <td style="padding:15px; vertical-align:middle;">
                                        <span class="label <?php echo $statusLbl; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $pkg->status; ?></span>
                                    </td>
                                    <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                        <?php if ($pkg->actual_delivery) { ?>
                                            <span style="color:#22c55e; font-weight:600;">✓ Delivered <?php echo date('Y-m-d', strtotime($pkg->actual_delivery)); ?></span>
                                        <?php } else { ?>
                                            <span>ETA: <?php echo date('Y-m-d', strtotime($pkg->eta_delivery)); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td style="padding:15px; text-align:right;">
                                        <?php if ($pkg->status !== 'Delivered') { ?>
                                            <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#updateModal-<?php echo $pkg->tracking_id; ?>" style="font-weight:600; border-color:var(--border-color);">
                                                Update Route
                                            </button>
                                        <?php } else { ?>
                                            <span style="font-size:12px; color:var(--text-muted);">Concluded</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                
                                <!-- Update Modal for each active package -->
                                <div class="modal fade" id="updateModal-<?php echo $pkg->tracking_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content" style="border-radius:12px; overflow:hidden; text-align:left;">
                                            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                                                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Update Route: Order #<?php echo $pkg->order_number; ?></h4>
                                            </div>
                                            <form action="index.php?action=update" method="POST">
                                                <input type="hidden" name="tracking_id" value="<?php echo $pkg->tracking_id; ?>">
                                                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                                                    <div class="form-group">
                                                        <label for="status-<?php echo $pkg->tracking_id; ?>" style="font-weight:600; font-size:13px; color:var(--text-muted);">Status:</label>
                                                        <select name="status" id="status-<?php echo $pkg->tracking_id; ?>" class="form-control" style="border-radius:6px;">
                                                            <option value="In Transit" <?php echo $pkg->status === 'In Transit' ? 'selected' : ''; ?>>In Transit</option>
                                                            <option value="Out for Delivery" <?php echo $pkg->status === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                                            <option value="Delivered">Delivered</option>
                                                            <option value="Delayed" <?php echo $pkg->status === 'Delayed' ? 'selected' : ''; ?>>Delayed</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lat-<?php echo $pkg->tracking_id; ?>" style="font-weight:600; font-size:13px; color:var(--text-muted);">Current Latitude:</label>
                                                                <input type="number" step="0.0001" name="current_lat" id="lat-<?php echo $pkg->tracking_id; ?>" class="form-control" value="<?php echo $pkg->current_lat; ?>" required style="border-radius:6px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lng-<?php echo $pkg->tracking_id; ?>" style="font-weight:600; font-size:13px; color:var(--text-muted);">Current Longitude:</label>
                                                                <input type="number" step="0.0001" name="current_lng" id="lng-<?php echo $pkg->tracking_id; ?>" class="form-control" value="<?php echo $pkg->current_lng; ?>" required style="border-radius:6px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="details-<?php echo $pkg->tracking_id; ?>" style="font-weight:600; font-size:13px; color:var(--text-muted);">Update Details Comments:</label>
                                                        <input type="text" name="details" id="details-<?php echo $pkg->tracking_id; ?>" class="form-control" placeholder="e.g. Scanned at regional center." required style="border-radius:6px;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Save Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($selectedPkg) { ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var origLat = <?php echo $selectedPkg->origin_lat; ?>;
    var origLng = <?php echo $selectedPkg->origin_lng; ?>;
    var currLat = <?php echo $selectedPkg->current_lat; ?>;
    var currLng = <?php echo $selectedPkg->current_lng; ?>;
    var destLat = <?php echo $selectedPkg->dest_lat; ?>;
    var destLng = <?php echo $selectedPkg->dest_lng; ?>;
    
    // Initialize Leaflet Map
    var map = L.map('map').setView([currLat, currLng], 9);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    var warehouseIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/3067/3067184.png', // Warehouse pin
        iconSize: [28, 28]
    });
    
    var truckIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/1048/1048329.png', // Truck pin
        iconSize: [32, 32]
    });
    
    var homeIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/25/25694.png', // Home pin
        iconSize: [28, 28]
    });
    
    L.marker([origLat, origLng], {icon: warehouseIcon}).addTo(map).bindPopup("<b>Bacolod Main Warehouse</b><br>Origin Point");
    L.marker([currLat, currLng], {icon: truckIcon}).addTo(map).bindPopup("<b>Package Position</b><br>Coordinates: " + currLat + ", " + currLng).openPopup();
    L.marker([destLat, destLng], {icon: homeIcon}).addTo(map).bindPopup("<b>Customer Address</b><br>Destination Point");
    
    // Draw route polylines
    var controlPoints = [
        [origLat, origLng],
        [currLat, currLng],
        [destLat, destLng]
    ];
    L.polyline(controlPoints, {color: '#3b82f6', weight: 4, dashArray: '8, 8'}).addTo(map);
});
</script>
<?php } ?>
