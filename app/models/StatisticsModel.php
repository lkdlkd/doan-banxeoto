<?php
require_once __DIR__ . '/BaseModel.php';

class StatisticsModel extends BaseModel {
    
    /**
     * Lấy thống kê doanh thu theo ngày trong khoảng thời gian
     */
    public function getRevenueByDay($startDate, $endDate) {
        $query = "SELECT 
                    DATE(o.created_at) as date,
                    SUM(o.price) as revenue,
                    COUNT(o.id) as order_count
                  FROM orders o
                  WHERE o.status = 'completed'
                    AND DATE(o.created_at) BETWEEN ? AND ?
                  GROUP BY DATE(o.created_at)
                  ORDER BY date ASC";
        
        return $this->query($query, [$startDate, $endDate]);
    }
    
    /**
     * Lấy thống kê doanh thu theo tháng trong năm
     */
    public function getRevenueByMonth($year) {
        $query = "SELECT 
                    MONTH(o.created_at) as month,
                    SUM(o.price) as revenue,
                    COUNT(o.id) as order_count
                  FROM orders o
                  WHERE o.status = 'completed'
                    AND YEAR(o.created_at) = ?
                  GROUP BY MONTH(o.created_at)
                  ORDER BY month ASC";
        
        return $this->query($query, [$year]);
    }
    
    /**
     * Lấy thống kê doanh thu theo năm
     */
    public function getRevenueByYear($startYear, $endYear) {
        $query = "SELECT 
                    YEAR(o.created_at) as year,
                    SUM(o.price) as revenue,
                    COUNT(o.id) as order_count
                  FROM orders o
                  WHERE o.status = 'completed'
                    AND YEAR(o.created_at) BETWEEN ? AND ?
                  GROUP BY YEAR(o.created_at)
                  ORDER BY year ASC";
        
        return $this->query($query, [$startYear, $endYear]);
    }
    
    /**
     * Lấy top xe bán chạy nhất
     */
    public function getTopSellingCars($limit = 10) {
        $limit = intval($limit); // Đảm bảo là số nguyên
        $query = "SELECT 
                    c.id,
                    c.name,
                    c.price,
                    b.name as brand_name,
                    COUNT(o.id) as total_sold,
                    SUM(o.price) as total_revenue
                  FROM cars c
                  LEFT JOIN brands b ON c.brand_id = b.id
                  LEFT JOIN orders o ON c.id = o.car_id AND o.status = 'completed'
                  GROUP BY c.id, c.name, c.price, b.name
                  HAVING total_sold > 0
                  ORDER BY total_sold DESC
                  LIMIT {$limit}";
        
        return $this->query($query);
    }
    
    /**
     * Lấy thống kê tổng quan
     */
    public function getOverviewStats() {
        // Tổng doanh thu
        $totalRevenue = $this->query(
            "SELECT SUM(price) as total FROM orders WHERE status = 'completed'"
        )[0]['total'] ?? 0;
        
        // Tổng đơn hàng hoàn thành
        $totalOrders = $this->query(
            "SELECT COUNT(*) as total FROM orders WHERE status = 'completed'"
        )[0]['total'] ?? 0;
        
        // Tổng khách hàng
        $totalCustomers = $this->query(
            "SELECT COUNT(*) as total FROM users WHERE role = 'user'"
        )[0]['total'] ?? 0;
        
        // Doanh thu tháng này
        $monthRevenue = $this->query(
            "SELECT SUM(price) as total FROM orders 
             WHERE status = 'completed' 
             AND MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        )[0]['total'] ?? 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_customers' => $totalCustomers,
            'month_revenue' => $monthRevenue
        ];
    }
    
    /**
     * So sánh doanh thu với tháng/năm trước
     */
    public function getRevenueComparison($type = 'month') {
        if ($type === 'month') {
            // So sánh với tháng trước
            $currentMonth = $this->query(
                "SELECT SUM(price) as total FROM orders 
                 WHERE status = 'completed' 
                 AND MONTH(created_at) = MONTH(CURRENT_DATE())
                 AND YEAR(created_at) = YEAR(CURRENT_DATE())"
            )[0]['total'] ?? 0;
            
            $lastMonth = $this->query(
                "SELECT SUM(price) as total FROM orders 
                 WHERE status = 'completed' 
                 AND MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                 AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))"
            )[0]['total'] ?? 0;
            
            return [
                'current' => $currentMonth,
                'previous' => $lastMonth,
                'change_percent' => $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0
            ];
        } else {
            // So sánh với năm trước
            $currentYear = $this->query(
                "SELECT SUM(price) as total FROM orders 
                 WHERE status = 'completed' 
                 AND YEAR(created_at) = YEAR(CURRENT_DATE())"
            )[0]['total'] ?? 0;
            
            $lastYear = $this->query(
                "SELECT SUM(price) as total FROM orders 
                 WHERE status = 'completed' 
                 AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR))"
            )[0]['total'] ?? 0;
            
            return [
                'current' => $currentYear,
                'previous' => $lastYear,
                'change_percent' => $lastYear > 0 ? (($currentYear - $lastYear) / $lastYear) * 100 : 0
            ];
        }
    }
    
    /**
     * Thống kê doanh thu theo thương hiệu
     */
    public function getRevenueByBrand() {
        $query = "SELECT 
                    b.id,
                    b.name,
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(o.price) as total_revenue
                  FROM brands b
                  LEFT JOIN cars c ON b.id = c.brand_id
                  LEFT JOIN orders o ON c.id = o.car_id AND o.status = 'completed'
                  GROUP BY b.id, b.name
                  HAVING total_revenue > 0
                  ORDER BY total_revenue DESC";
        
        return $this->query($query);
    }
    
    /**
     * Thống kê đơn hàng theo trạng thái
     */
    public function getOrdersByStatus() {
        $query = "SELECT 
                    status,
                    COUNT(*) as count,
                    SUM(price) as total_amount
                  FROM orders
                  GROUP BY status
                  ORDER BY count DESC";
        
        return $this->query($query);
    }
    
    /**
     * Thống kê theo phương thức thanh toán
     */
    public function getPaymentMethodStats() {
        $query = "SELECT 
                    payment_method,
                    COUNT(*) as count,
                    SUM(price) as total_amount
                  FROM orders
                  WHERE status = 'completed'
                  GROUP BY payment_method
                  ORDER BY count DESC";
        
        return $this->query($query);
    }
    
    /**
     * Doanh thu trung bình
     */
    public function getAverageRevenue() {
        $query = "SELECT 
                    AVG(price) as avg_order,
                    MAX(price) as max_order,
                    MIN(price) as min_order
                  FROM orders
                  WHERE status = 'completed'";
        
        return $this->query($query)[0] ?? ['avg_order' => 0, 'max_order' => 0, 'min_order' => 0];
    }
    
    /**
     * Thống kê khách hàng mua nhiều nhất
     */
    public function getTopCustomers($limit = 5) {
        $limit = intval($limit);
        $query = "SELECT 
                    u.id,
                    u.full_name,
                    u.email,
                    COUNT(o.id) as total_orders,
                    SUM(o.price) as total_spent
                  FROM users u
                  LEFT JOIN orders o ON u.id = o.user_id AND o.status = 'completed'
                  WHERE u.role = 'user'
                  GROUP BY u.id, u.full_name, u.email
                  HAVING total_orders > 0
                  ORDER BY total_spent DESC
                  LIMIT {$limit}";
        
        return $this->query($query);
    }
}
