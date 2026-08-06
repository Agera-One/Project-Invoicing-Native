<?php

class InvoiceController extends Controller
{
    public function index()
    {
        $data['ref_no'] = $this->model('Item')->getAll();
        $this->view('invoice/index');
    }

    public function edit($id, $customer_id, $pic_id) {

    }
}
