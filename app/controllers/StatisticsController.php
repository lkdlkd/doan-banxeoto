<?php
require_once __DIR__ . '/../models/StatisticsModel.php';

class StatisticsController
{
    private $statisticsModel;

    public function __construct()
    {
        $this->statisticsModel = new StatisticsModel();
    }

    public function dashboard()
    {
        // Lấy tham số lọc
        $period = $_GET['period'] ?? 'month'; // day, month, year
        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');

        // Lấy dữ liệu thống kê
        $chartData = [];
        $chartLabels = [];

        if ($period === 'day') {
            // Thống kê theo ngày trong tháng
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $startDate = "$year-$month-01";
            $endDate = "$year-$month-$daysInMonth";

            $revenueData = $this->statisticsModel->getRevenueByDay($startDate, $endDate);

            // Tạo mảng đầy đủ các ngày
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf("%s-%s-%02d", $year, $month, $day);
                $chartLabels[] = $day;

                // Tìm doanh thu cho ngày này
                $revenue = 0;
                foreach ($revenueData as $data) {
                    if ($data['date'] === $date) {
                        $revenue = $data['revenue'];
                        break;
                    }
                }
                $chartData[] = $revenue;
            }
        } elseif ($period === 'month') {
            // Thống kê theo tháng trong năm
            $revenueData = $this->statisticsModel->getRevenueByMonth($year);

            $monthNames = [
                'Th1',
                'Th2',
                'Th3',
                'Th4',
                'Th5',
                'Th6',
                'Th7',
                'Th8',
                'Th9',
                'Th10',
                'Th11',
                'Th12'
            ];

            // Tạo mảng đầy đủ 12 tháng
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = $monthNames[$m - 1];

                // Tìm doanh thu cho tháng này
                $revenue = 0;
                foreach ($revenueData as $data) {
                    if ($data['month'] == $m) {
                        $revenue = $data['revenue'];
                        break;
                    }
                }
                $chartData[] = $revenue;
            }
        } else {
            // Thống kê theo năm (5 năm gần nhất)
            $endYear = date('Y');
            $startYear = $endYear - 4;

            $revenueData = $this->statisticsModel->getRevenueByYear($startYear, $endYear);

            // Tạo mảng đầy đủ các năm
            for ($y = $startYear; $y <= $endYear; $y++) {
                $chartLabels[] = $y;

                // Tìm doanh thu cho năm này
                $revenue = 0;
                foreach ($revenueData as $data) {
                    if ($data['year'] == $y) {
                        $revenue = $data['revenue'];
                        break;
                    }
                }
                $chartData[] = $revenue;
            }
        }

        // Lấy thống kê tổng quan
        $overviewStats = $this->statisticsModel->getOverviewStats();

        // Lấy so sánh doanh thu
        $revenueComparison = $this->statisticsModel->getRevenueComparison($period === 'year' ? 'year' : 'month');

        // Lấy top xe bán chạy
        $topCars = $this->statisticsModel->getTopSellingCars(5);

        // Lấy thống kê theo thương hiệu
        $brandStats = $this->statisticsModel->getRevenueByBrand();

        // Lấy thống kê theo trạng thái đơn hàng
        $orderStatusStats = $this->statisticsModel->getOrdersByStatus();

        // Lấy thống kê phương thức thanh toán
        $paymentStats = $this->statisticsModel->getPaymentMethodStats();

        // Lấy doanh thu trung bình
        $avgStats = $this->statisticsModel->getAverageRevenue();

        // Lấy top khách hàng
        $topCustomers = $this->statisticsModel->getTopCustomers(5);

        // Load view
        require_once __DIR__ . '/../views/admin/statistics/dashboard.php';
    }
}
