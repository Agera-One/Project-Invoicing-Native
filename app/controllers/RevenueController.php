<?php

class RevenueController extends BaseController {
    private $payment;

    public function __construct()
    {
        parent::__construct();
        $this->payment = $this->model('payment');
    }

    public function index() {
        $number = 1;
        $period = $_GET['period'] ?? 'daily';
        $validate = $this->payment->validatorPeriod($period);
        $omsets = $this->payment->sumRevenuePeriod($validate['periodKeyExpr'], $validate['periodLabelExpr'], $this->companyId, $validate['limit']);

        $datas = [
            'number' => $number,
            'period' => $period,
            'omsets' => $omsets,
        ];

        $this->view('revenue/index', $datas);
    }
}