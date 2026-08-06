<?php

class ItemController extends Controller
{
    public function index()
    {
        $data['ref_no'] = $this->model('Item')->getAll();
        $this->view('item/index');
    }

    public function edit($id) {

    }
}
