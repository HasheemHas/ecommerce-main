<?php

class AnalyticsDashboard
{
    public static function salesByMonth($months = 6)
    {
        global $mydb;
        $months = (int) $months;
        $mydb->setQuery("SELECT DATE_FORMAT(ORDEREDDATE, '%Y-%m') AS month_label,
            SUM(PAYMENT) AS total_sales, COUNT(*) AS order_count
            FROM tblsummary
            WHERE ORDEREDSTATS IN ('Confirmed','Delivered','Shipped','Pending')
            AND ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL {$months} MONTH)
            GROUP BY month_label ORDER BY month_label ASC");
        return $mydb->loadResultList();
    }

    public static function ordersByStatus()
    {
        global $mydb;
        $mydb->setQuery("SELECT ORDEREDSTATS AS status_label, COUNT(*) AS cnt FROM tblsummary GROUP BY ORDEREDSTATS");
        return $mydb->loadResultList();
    }

    public static function paymentMethodBreakdown()
    {
        global $mydb;
        $mydb->setQuery("SELECT PAYMENTMETHOD AS method_label, COUNT(*) AS cnt, SUM(PAYMENT) AS total
            FROM tblsummary GROUP BY PAYMENTMETHOD ORDER BY cnt DESC");
        return $mydb->loadResultList();
    }

    public static function topProducts($limit = 8)
    {
        global $mydb;
        $mydb->setQuery("SELECT p.PRODESC, SUM(o.ORDEREDQTY) AS qty
            FROM tblorder o
            INNER JOIN tblproduct p ON p.PROID = o.PROID
            GROUP BY p.PROID, p.PRODESC
            ORDER BY qty DESC LIMIT " . (int) $limit);
        return $mydb->loadResultList();
    }

    public static function customerActivityLast7Days()
    {
        global $mydb;
        $mydb->setQuery("SELECT DATE(ORDEREDDATE) AS day_label, COUNT(DISTINCT CUSTOMERID) AS customers
            FROM tblsummary
            WHERE ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY day_label ORDER BY day_label ASC");
        return $mydb->loadResultList();
    }

    public static function kpiSummary()
    {
        global $mydb;
        $kpi = new stdClass();
        $mydb->setQuery("SELECT COUNT(*) AS c FROM tblcustomer");
        $kpi->total_customers = (int) $mydb->loadSingleResult()->c;

        $mydb->setQuery("SELECT COUNT(*) AS c FROM tblsummary WHERE ORDEREDSTATS='Pending'");
        $kpi->pending_orders = (int) $mydb->loadSingleResult()->c;

        $mydb->setQuery("SELECT COALESCE(SUM(PAYMENT),0) AS t FROM tblsummary WHERE ORDEREDSTATS IN ('Confirmed','Delivered','Shipped') AND MONTH(ORDEREDDATE)=MONTH(NOW())");
        $kpi->monthly_revenue = (float) $mydb->loadSingleResult()->t;

        $mydb->setQuery("SELECT COUNT(*) AS c FROM tblproduct WHERE PROQTY <= " . (int) ML_LOW_STOCK_THRESHOLD);
        $kpi->low_stock_count = (int) $mydb->loadSingleResult()->c;

        $mydb->setQuery("SELECT COUNT(*) AS c FROM tbl_fraud_alerts WHERE IS_RESOLVED=0");
        $r = @$mydb->loadSingleResult();
        $kpi->fraud_alerts = $r ? (int) $r->c : 0;

        return $kpi;
    }
}
