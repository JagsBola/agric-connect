<?php

use App\Models\Db;
use App\Models\Post;
use App\Models\User;

class HomeController extends BaseController {

    /**
     * Get home page
     */
    public function index($page = null, $search = null) {
        $postsPerPage = 15;
        $post = new Post(new Db());
        $totalPosts = $post->getTotalPostsCount();
        $totalPages = ceil($totalPosts / $postsPerPage);

        $currentPage = is_numeric($page) ? max(1, min($totalPages, $page)) : 1;
        $startIndex = ($currentPage - 1) * $postsPerPage;

        $searchParam = isset($_GET['search']) ? $_GET['search'] : null;
        $posts = $post->getPosts($startIndex, $postsPerPage, null, $searchParam);

        $this->renderView('listings', compact('post', 'posts', 'totalPages', 'currentPage', 'searchParam'));
    }

    public function category($id=null, $page = null) {
        
        $postsPerPage = 15;
        $post = new Post(new Db());
        $totalPosts = $post->getTotalPostsCount($id);
        $totalPages = ceil($totalPosts / $postsPerPage);

        $currentPage = is_numeric($page) ? max(1, min($totalPages, $page)) : 1;
        $startIndex = ($currentPage - 1) * $postsPerPage;

        $searchParam = isset($_GET['search']) ? $_GET['search'] : null;
        $posts = $post->getPosts($startIndex, $postsPerPage, $id, $searchParam);

        $this->renderView('category-listings', compact('post', 'posts', 'totalPages', 'currentPage', 'searchParam'));
    }

    /**
     * Get listing page
     */
    public function listing($id, $name = '') {
        $post = new Post(new Db());
        $post = $post->getPost($id);
        $this->renderView('listing', compact('post'));
    }

}
