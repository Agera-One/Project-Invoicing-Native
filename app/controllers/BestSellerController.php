<?php

class BestSellerController extends BaseController
{
    private $item;

    public function __construct()
    {
        parent::__construct();
        $this->item = $this->model('item');
    }

    public function index()
    {
        $number = 1;

        $allowed_periods = ['all', 'yearly', 'monthly', 'weekly'];
        $period = $_GET['period'] ?? 'all';
        $period_label = 'All Time';

        $validated = $this->item->validatorPeriod($period, $period_label);
        $period_label = $validated['period_label'];

        $top_products = $this->item->getBestSeller($validated['where_condition'], $this->companyId);

        $datas = [
            'number' => $number,
            'period' => $period,
            'allowed_periods' => $allowed_periods,
            'period_label' => $period_label,
            'top_products' => $top_products,
        ];

        $this->view('best-seller/index', $datas);
    }
}
