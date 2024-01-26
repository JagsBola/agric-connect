<?php

use App\Models\Db;
use App\Models\User;
use App\Models\Post;

class PostController extends BaseController {

    public function create() {
        $errors = [];
        $this->checkAuth();
        $auth = new User($this->db);
        !$auth->checkIsSeller() ? $this->redirectTo('/listings') : null;
        $post = new Post($this->db);
        $categories = $post->getCategories();
        $this->renderView('create-post', compact('categories', 'errors'));
    }

    public function edit($id) {
        $errors = [];
        $this->checkAuth();
        $Post = new Post($this->db);
        $post = $Post->getPost($id);

        $categories = $Post->getCategories();

        $this->renderView('edit-post', compact('post', 'categories', 'errors'));
    }

    public function store() {
        $this->checkAuth();
        $auth = new User($this->db);

        $details = [
            'unit' => $this->post('unit'),
            'price' => $this->post('price'),
            'location' => $this->post('location'),
            'category_id' => $this->post('category_id'),
            'available_quantity' => $this->post('available_quantity'),
            'image' => $this->handleImageUpload($this->files('image'), 'images/posts/'),
        ];

        $auth->createPost($this->post('name'), $this->post('content'), $details);

        $this->redirectTo('listing?id='.$this->db->getConnection()->lastInsertId());
    }

    public function update() {

        $this->checkAuth();
        $auth = new User($this->db);

        $details = [
            'unit' => $this->post('unit'),
            'price' => $this->post('price'),
            'location' => $this->post('location'),
            'category_id' => $this->post('category'),
            'available_quantity' => $this->post('available_quantity'),
            'image' => $this->handleImageUpload($this->files('image'), 'images/posts/'),
        ];

        //$this->dd($details);

        $auth->updatePost($this->post('postId'), $this->post('name'), $this->post('content'), $details);

        $this->redirectTo('listing?id='.$this->post('postId'));
    }

    public function myListings($page = null) {
        $this->checkAuth();
        $postsPerPage = 15;
        $auth = new User($this->db);
        $totalPosts = $auth->getMyTotalPostsCount();
        $totalPages = ceil($totalPosts / $postsPerPage);

        $currentPage = is_numeric($page) ? max(1, min($totalPages, $page)) : 1;
        $startIndex = ($currentPage - 1) * $postsPerPage;

        $posts = $auth->getMyPosts($startIndex, $postsPerPage);

        $this->renderView('my-listings', compact('posts', 'totalPages', 'currentPage'));
    }

    /**
     * Delete a post
     */
    public function delete() {
        $this->checkAuth();
        $auth = new User($this->db);
        $auth->deletePost($this->post('id'));

        $this->redirectTo('/listings');
    }

    protected function handleImageUpload($file, $path = 'images') {
        $imageTmpName = $file['tmp_name'];
        $imageExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imageName = uniqid().'.'.$imageExtension;
        $imagePath = $path.$imageName;
        move_uploaded_file($imageTmpName, $imagePath);

        return $imagePath;
    }


    private function replaceImageUrls($content, $newImageUrl) {
        // Replace the existing image URLs in the content with the new image URL
        $existingImageUrls = $this->extractImageUrls($content);

        foreach($existingImageUrls as $oldImageUrl) {
            $content = str_replace($oldImageUrl, $newImageUrl, $content);
        }

        return $content;
    }

    private function extractImageUrls($content) {
        // Use a regular expression to extract image URLs from the content
        $pattern = '/<img.*?src=["\'](.*?)["\'].*?>/i';
        preg_match_all($pattern, $content, $matches);

        return $matches[1];
    }


}
