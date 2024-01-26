<?php

use App\Models\Db;
use App\Models\User;
use App\Models\Post;

class UserController extends BaseController {

    /**
     * Get user profile
     */
    public function profile($id){
        
        $this->checkAuth();
        $user = new User($this->db, $id);
        $posts = $user->getMyPosts(10, 'DESC');

        $this->renderView('profile', compact('user', 'posts'));
    }

    /**
     * Get user profile
     */
    public function myProfile(){
        $this->checkAuth();
        $this->renderView('my-profile');
    }


    /**
     * Update user profile
     */
    public function updateProfile(){
        $errors = [];

        $this->checkAuth();

        if($this->isPost()){
            
            $auth = new User($this->db);
            $name = $this->post('name');
            $file = $this->files('image');
            $address = $this->post('address');
            
            //validate
            
            if(empty($name)){
                $errors['name'] = 'Name is required';
            }
            if(empty($address)){
                $errors['address'] = 'Address is required';
            }

            $allowedTypes = ['image/jpeg', 'image/png'];


            if(!empty($file['name']) && !in_array($file['type'], $allowedTypes)){
                $errors['image'] = 'Image must be a jpeg or png file';
            }

            if(!empty($file['name']) && $file['size'] >= 1000000){
                $errors['image'] = 'Image size must not be greater than 1MB';
            }

            $this->addToFlashSession('errors', $errors);

            $image = $this->handleImageUpload($file, 'images/users/');

            $status = $auth->updateProfile($name, $address, $image);
            
            $messsage = $status ? 'successfully' : 'failed';

            $this->addToFlashSession('success', 'Profile update'. $messsage);


            $this->myProfile();

        }else{
            $this->redirectTo('/my-profile');
        }

    }
}
