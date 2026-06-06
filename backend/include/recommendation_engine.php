<?php
require_once(LIB_PATH . DS . 'ml_config.php');

/**
 * ML-style product recommendation (collaborative + content-based scoring in PHP)
 */
class RecommendationEngine
{
    public static function trackView($proid, $categid = null)
    {
        global $mydb;
        $proid = (int) $proid;
        if ($proid <= 0) {
            return;
        }
        $customerId = isset($_SESSION['CUSID']) ? (int) $_SESSION['CUSID'] : 'NULL';
        $sessionId = session_id();
        $sessionEsc = $mydb->escape_value($sessionId);
        $categVal = $categid ? (int) $categid : 'NULL';
        $mydb->setQuery("INSERT INTO tbl_browse_history (CUSTOMERID, PROID, CATEGID, SESSION_ID) VALUES ({$customerId}, {$proid}, {$categVal}, '{$sessionEsc}')");
        @$mydb->executeQuery();
    }

    public static function getRecommendations($customerId = null, $limit = 8)
    {
        global $mydb;
        $limit = max(1, min(20, (int) $limit));
        $customerId = $customerId ?: (isset($_SESSION['CUSID']) ? (int) $_SESSION['CUSID'] : 0);

        // Fetch popularity (confirmed orders counts)
        $popularity = [];
        $mydb->setQuery("SELECT o.PROID, SUM(o.ORDEREDQTY) AS popularity
            FROM tblorder o
            INNER JOIN tblproduct p ON p.PROID = o.PROID
            INNER JOIN tblsummary s ON s.ORDEREDNUM = o.ORDEREDNUM AND s.ORDEREDSTATS IN ('Confirmed','Delivered','Shipped')
            WHERE p.PROQTY > 0 AND p.PROSTATS = 'Available'
            GROUP BY o.PROID");
        $popList = $mydb->loadResultList();
        if ($popList) {
            foreach ($popList as $row) {
                $popularity[(int)$row->PROID] = (float)$row->popularity;
            }
        }

        // Fetch browse history
        $browse_history = [];
        $mydb->setQuery("SELECT CUSTOMERID, PROID, CATEGID FROM tbl_browse_history");
        $bhList = $mydb->loadResultList();
        if ($bhList) {
            foreach ($bhList as $row) {
                $browse_history[] = [
                    'customer_id' => $row->CUSTOMERID ? (int)$row->CUSTOMERID : null,
                    'proid' => (int)$row->PROID,
                    'categid' => $row->CATEGID ? (int)$row->CATEGID : null
                ];
            }
        }

        // Fetch purchase history
        $purchase_history = [];
        $mydb->setQuery("SELECT s.CUSTOMERID, o.PROID, p.CATEGID FROM tblorder o
            INNER JOIN tblproduct p ON p.PROID = o.PROID
            INNER JOIN tblsummary s ON s.ORDEREDNUM = o.ORDEREDNUM");
        $phList = $mydb->loadResultList();
        if ($phList) {
            foreach ($phList as $row) {
                $purchase_history[] = [
                    'customer_id' => (int)$row->CUSTOMERID,
                    'proid' => (int)$row->PROID,
                    'categid' => $row->CATEGID ? (int)$row->CATEGID : null
                ];
            }
        }

        // Fetch all available products
        $products = [];
        $mydb->setQuery("SELECT PROID, CATEGID FROM tblproduct WHERE PROQTY > 0 AND PROSTATS = 'Available'");
        $pList = $mydb->loadResultList();
        if ($pList) {
            foreach ($pList as $row) {
                $products[] = [
                    'proid' => (int)$row->PROID,
                    'categid' => $row->CATEGID ? (int)$row->CATEGID : null
                ];
            }
        }

        $payload = [
            'customer_id' => (int)$customerId,
            'browse_history' => $browse_history,
            'purchase_history' => $purchase_history,
            'products' => $products,
            'popularity' => $popularity,
            'limit' => (int)$limit
        ];

        $res = MLBridge::runPython('recommend', $payload);

        if ($res && isset($res['ok']) && $res['ok'] && !empty($res['product_ids'])) {
            $ids = $res['product_ids'];
            $idList = implode(',', array_map('intval', $ids));
            $mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
                WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROID IN ({$idList}) AND p.PROQTY>0");
            $productsList = $mydb->loadResultList();
            
            if ($productsList) {
                // Sort by order returned by Python
                $orderMap = array_flip($ids);
                usort($productsList, function ($a, $b) use ($orderMap) {
                    $posA = isset($orderMap[(int)$a->PROID]) ? $orderMap[(int)$a->PROID] : 999;
                    $posB = isset($orderMap[(int)$b->PROID]) ? $orderMap[(int)$b->PROID] : 999;
                    return $posA <=> $posB;
                });
                return array_slice($productsList, 0, $limit);
            }
        }

        return self::fallbackProducts($limit);
    }

    private static function fallbackProducts($limit)
    {
        global $mydb;
        $mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
            WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROQTY>0
            ORDER BY pr.PRONEW DESC, p.PROID DESC LIMIT " . (int) $limit);
        return $mydb->loadResultList();
    }
}
