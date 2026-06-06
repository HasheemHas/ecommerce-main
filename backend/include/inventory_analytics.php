<?php
require_once(LIB_PATH . DS . 'ml_config.php');

class InventoryAnalytics
{
    public static function refreshAlerts()
    {
        global $mydb;
        @$mydb->setQuery('DELETE FROM tbl_inventory_alerts WHERE CREATED_AT < DATE_SUB(NOW(), INTERVAL 7 DAY)');
        @$mydb->executeQuery();

        // Low stock
        $threshold = (int) ML_LOW_STOCK_THRESHOLD;
        $mydb->setQuery("SELECT PROID, PRODESC, PROQTY FROM tblproduct WHERE PROQTY <= {$threshold} AND PROQTY >= 0");
        foreach ($mydb->loadResultList() as $p) {
            self::upsertAlert((int) $p->PROID, 'low_stock', "Low stock: {$p->PRODESC} ({$p->PROQTY} left)");
        }

        $days = (int) ML_FAST_MOVING_DAYS;
        $minQty = (int) ML_FAST_MOVING_MIN_QTY;
        $mydb->setQuery("SELECT p.PROID, p.PRODESC, SUM(o.ORDEREDQTY) AS sold
            FROM tblorder o
            INNER JOIN tblproduct p ON p.PROID = o.PROID
            INNER JOIN tblsummary s ON s.ORDEREDNUM = o.ORDEREDNUM AND s.ORDEREDSTATS IN ('Confirmed','Delivered','Shipped')
            WHERE s.ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            GROUP BY p.PROID, p.PRODESC
            HAVING sold >= {$minQty}");
        foreach ($mydb->loadResultList() as $p) {
            self::upsertAlert((int) $p->PROID, 'fast_moving', "Fast moving: {$p->PRODESC} ({$p->sold} sold in {$days} days)");
        }

        $slowDays = (int) ML_SLOW_MOVING_DAYS;
        $mydb->setQuery("SELECT p.PROID, p.PRODESC, p.PROQTY,
            COALESCE(SUM(o.ORDEREDQTY), 0) AS sold
            FROM tblproduct p
            LEFT JOIN tblorder o ON o.PROID = p.PROID
            LEFT JOIN tblsummary s ON s.ORDEREDNUM = o.ORDEREDNUM AND s.ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL {$slowDays} DAY)
            WHERE p.PROQTY > 0
            GROUP BY p.PROID, p.PRODESC, p.PROQTY
            HAVING sold = 0");
        foreach ($mydb->loadResultList() as $p) {
            self::upsertAlert((int) $p->PROID, 'slow_moving', "Slow moving: {$p->PRODESC} (no sales in {$slowDays} days, stock {$p->PROQTY})");
        }
    }

    private static function upsertAlert($proid, $type, $message)
    {
        global $mydb;
        $proid = (int) $proid;
        $typeEsc = $mydb->escape_value($type);
        $msgEsc = $mydb->escape_value($message);
        $mydb->setQuery("SELECT INV_ALERT_ID FROM tbl_inventory_alerts WHERE PROID={$proid} AND ALERT_TYPE='{$typeEsc}' AND CREATED_AT >= DATE_SUB(NOW(), INTERVAL 1 DAY) LIMIT 1");
        if ($mydb->loadSingleResult()) {
            return;
        }
        $mydb->setQuery("INSERT INTO tbl_inventory_alerts (PROID, ALERT_TYPE, MESSAGE) VALUES ({$proid}, '{$typeEsc}', '{$msgEsc}')");
        @$mydb->executeQuery();
    }

    public static function getLowStockProducts()
    {
        global $mydb;
        $t = (int) ML_LOW_STOCK_THRESHOLD;
        $mydb->setQuery("SELECT p.*, c.CATEGORIES FROM tblproduct p
            LEFT JOIN tblcategory c ON c.CATEGID = p.CATEGID
            WHERE p.PROQTY <= {$t} ORDER BY p.PROQTY ASC");
        return $mydb->loadResultList();
    }

    public static function getMovementReport()
    {
        global $mydb;
        $days = (int) ML_FAST_MOVING_DAYS;
        $mydb->setQuery("SELECT p.PROID, p.PRODESC, p.PROQTY, c.CATEGORIES,
            COALESCE(SUM(o.ORDEREDQTY), 0) AS units_sold,
            COALESCE(SUM(o.ORDEREDQTY * o.ORDEREDPRICE), 0) AS revenue
            FROM tblproduct p
            LEFT JOIN tblcategory c ON c.CATEGID = p.CATEGID
            LEFT JOIN tblorder o ON o.PROID = p.PROID
            LEFT JOIN tblsummary s ON s.ORDEREDNUM = o.ORDEREDNUM
                AND s.ORDEREDSTATS IN ('Confirmed','Delivered','Shipped')
                AND s.ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            GROUP BY p.PROID, p.PRODESC, p.PROQTY, c.CATEGORIES
            ORDER BY units_sold DESC");
        return $mydb->loadResultList();
    }

    public static function getRecentAlerts($limit = 30)
    {
        global $mydb;
        $mydb->setQuery("SELECT a.*, p.PRODESC FROM tbl_inventory_alerts a
            INNER JOIN tblproduct p ON p.PROID = a.PROID
            ORDER BY a.CREATED_AT DESC LIMIT " . (int) $limit);
        return $mydb->loadResultList();
    }
}
