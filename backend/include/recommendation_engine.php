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
        try {
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

            // NATIVE PHP Recommendation Engine Algorithm
            $scores = [];
            
            // Normalize popularity scores
            $max_pop = 1;
            if (!empty($popularity)) {
                $max_pop = max($popularity);
            }
            
            foreach ($products as $p) {
                $pid = $p['proid'];
                $pop = isset($popularity[$pid]) ? (float)$popularity[$pid] : 0.0;
                $scores[$pid] = ($pop / $max_pop) * 30.0; // Up to 30 points
            }
            
            if ($customerId > 0) {
                // Category affinity content-based filtering
                $user_cat_vector = [];
                foreach ($browse_history as $bh) {
                    if ($bh['customer_id'] == $customerId && $bh['categid']) {
                        $cat = $bh['categid'];
                        $user_cat_vector[$cat] = (isset($user_cat_vector[$cat]) ? $user_cat_vector[$cat] : 0.0) + 1.0;
                    }
                }
                foreach ($purchase_history as $ph) {
                    if ($ph['customer_id'] == $customerId && $ph['categid']) {
                        $cat = $ph['categid'];
                        $user_cat_vector[$cat] = (isset($user_cat_vector[$cat]) ? $user_cat_vector[$cat] : 0.0) + 3.0;
                    }
                }
                
                // Cosine Similarity between user affinity vector and product one-hot category vector
                $mag1 = 0.0;
                foreach ($user_cat_vector as $v) {
                    $mag1 += $v * $v;
                }
                $mag1 = sqrt($mag1);
                
                if ($mag1 > 0) {
                    foreach ($products as $p) {
                        $pid = $p['proid'];
                        $cat_id = $p['categid'];
                        if ($cat_id && isset($user_cat_vector[$cat_id])) {
                            $sim = $user_cat_vector[$cat_id] / ($mag1 * 1.0);
                            $scores[$pid] += $sim * 40.0; // Up to 40 points
                        }
                    }
                }
                
                // Collaborative Filtering (User-User Jaccard similarity style)
                $user_views = [];
                $product_views = [];
                foreach ($browse_history as $bh) {
                    $uid = $bh['customer_id'];
                    $pid = $bh['proid'];
                    if ($uid && $pid) {
                        if (!isset($user_views[$uid])) {
                            $user_views[$uid] = [];
                        }
                        $user_views[$uid][$pid] = true;
                        
                        if (!isset($product_views[$pid])) {
                            $product_views[$pid] = [];
                        }
                        $product_views[$pid][$uid] = true;
                    }
                }
                
                $target_views = isset($user_views[$customerId]) ? $user_views[$customerId] : [];
                if (!empty($target_views)) {
                    $similar_users = [];
                    foreach (array_keys($target_views) as $item) {
                        if (isset($product_views[$item])) {
                            foreach (array_keys($product_views[$item]) as $peer) {
                                if ($peer != $customerId) {
                                    $peer_views = isset($user_views[$peer]) ? $user_views[$peer] : [];
                                    
                                    // Calculate Jaccard similarity
                                    $intersection = count(array_intersect_key($target_views, $peer_views));
                                    $union = count($target_views + $peer_views);
                                    $jaccard = ($union > 0) ? ($intersection / $union) : 0.0;
                                    
                                    $similar_users[$peer] = isset($similar_users[$peer]) ? max($similar_users[$peer], $jaccard) : $jaccard;
                                }
                            }
                        }
                    }
                    
                    arsort($similar_users);
                    $top_peers = array_slice($similar_users, 0, 5, true);
                    
                    foreach ($top_peers as $peer => $similarity) {
                        if (isset($user_views[$peer])) {
                            foreach (array_keys($user_views[$peer]) as $item) {
                                if (!isset($target_views[$item])) {
                                    if (!isset($scores[$item])) {
                                        $scores[$item] = 0.0;
                                    }
                                    $scores[$item] += $similarity * 20.0; // Up to 20 points
                                }
                            }
                        }
                    }
                }
                
                // Exclude products already bought recently
                $purchased_ids = [];
                foreach ($purchase_history as $ph) {
                    if ($ph['customer_id'] == $customerId) {
                        $purchased_ids[$ph['proid']] = true;
                    }
                }
                foreach (array_keys($purchased_ids) as $pid) {
                    if (isset($scores[$pid])) {
                        unset($scores[$pid]);
                    }
                }
            }
            
            // Sort and select top product IDs
            arsort($scores);
            $ids = array_slice(array_keys($scores), 0, $limit);

            if (!empty($ids)) {
                $idList = implode(',', array_map('intval', $ids));
                $mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
                    WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROID IN ({$idList}) AND p.PROQTY>0");
                $productsList = $mydb->loadResultList();
                
                if ($productsList) {
                    // Sort by order returned by scoring
                    $orderMap = array_flip($ids);
                    usort($productsList, function ($a, $b) use ($orderMap) {
                        $posA = isset($orderMap[(int)$a->PROID]) ? $orderMap[(int)$a->PROID] : 999;
                        $posB = isset($orderMap[(int)$b->PROID]) ? $orderMap[(int)$b->PROID] : 999;
                        return $posA <=> $posB;
                    });
                    return array_slice($productsList, 0, $limit);
                }
            }
        } catch (Throwable $e) {
            // Fail-safe
        }

        return self::fallbackProducts($limit);
    }

    private static function fallbackProducts($limit)
    {
        try {
            global $mydb;
            $mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
                WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROQTY>0
                ORDER BY pr.PRONEW DESC, p.PROID DESC LIMIT " . (int) $limit);
            return $mydb->loadResultList();
        } catch (Throwable $e) {
            return [];
        }
    }
}
