<?php

use App\Models\Db;
use App\Models\Auth;
use App\Models\User;
use App\Models\Utility;

class AdminController extends BaseController {

    /**
     * Register user
     */
    public function index() {
        $this->checkAdminAuth();
        $users = $this->utility->getAllUsers();

        $this->renderView('admin-index', compact('users'));
    }

    public function blockUser(){
        $this->checkAdminAuth();
        $userId = $this->post('user_id');
        $status = (new User($this->db, $userId ))->deactivate();

        $this->addToFlashSession('success', 'Operation succesfull');

        return $this->index();
    }

    public function activateUser(){
        $this->checkAdminAuth();
        $userId = $this->post('user_id');
        $status = (new User($this->db, $userId ))->activate();

        $this->addToFlashSession('success', 'Operation succesfull');

        return $this->index();
    }
 
}
