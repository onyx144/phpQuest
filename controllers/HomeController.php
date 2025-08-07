<?php

class HomeController extends Controller {
    public function index() {
        
        $this->render('home', [
            'title' => 'Главная страница',
            'userInfo' => $this->userInfo
        ]);
    }
} 