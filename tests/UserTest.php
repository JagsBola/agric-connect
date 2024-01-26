<?php

use Faker\Factory;
use App\Models\Db;
use App\Models\User;
use App\Models\Auth;
use App\Models\DbSeed;

function buildPostDetails($faker) {
    return [
        'unit' => 'Kg',
        'image' => $faker->url(),
        'price' =>  rand(50,50000),
        'category_id' => rand(1,4),
        'location' => $faker->city(),
        'available_quantity' => $faker->numberBetween($min = 1, $max = 100)
    ];
}

beforeEach(function () {    
    // Set up a test database or any other necessary preparations
    $this->db = new Db(true); // true indicates using SQLite for testing
    $this->db->createTables(); // Automatically create tables on instantiation
    $this->auth = new Auth($this->db);
    $this->seed = new DbSeed($this->db);
    $this->seed->seedPostCategories();

    // Initialize Faker
    $this->faker = Factory::create();

    // Provide fake test data
    $userEmail = $this->faker->email();
    $userPswd = $this->faker->password();

    // Register a new user with fake data
    $this->auth->registerUser($this->faker->name(), $userEmail, $this->faker->address(), 'buyer', $userPswd);

    // Login user to create a new instance of the User class
    $this->user = $this->auth->loginUser($userEmail, $userPswd);

    $partyEmail = $this->faker->email();
    $partyPswd = $this->faker->password();

    // Register and Create a new instance of the User class for the party
    $this->party = $this->auth->registerUser($this->faker->name(), $partyEmail, $this->faker->address(), 'Seller', $partyPswd);
});

test('Constructor', function () {
    expect($this->user)->toBeInstanceOf(User::class);
    expect($this->party)->toBeInstanceOf(User::class);
});

test("User has a public key", function () {
    expect($this->user->getPublicKey())->not->toBeEmpty();
    expect($this->party->getPublicKey())->not->toBeEmpty();
});

test("User has a private key", function () {
    expect($this->user->getPrivateKey())->not->toBeNull();
    expect($this->party->getPrivateKey())->toBeNull();
});

test('User can update profile', function () {
    $name = $this->faker->name();
    $image = $this->faker->url(); 
    $address = $this->faker->address();

    //Act 
    $status = $this->user->updateProfile($name, $address, $image);

    //Assert
    expect($status)->toBeTrue();
    expect($this->user->name)->toBe($name);
    expect($this->user->image)->toBe($image);
    expect($this->user->address)->toBe($address);
});

test('User can send Encrypted message', function () {
    $message = $this->faker->sentence();
    expect($this->user->sendMessage($this->party, $message))->not->toBeNull();
});

test('User can create Post', function () {
    $name = $this->faker->name();
    $content = $this->faker->sentence();
    $details = buildPostDetails($this->faker);
    expect($this->user->createPost($name, $content, $details))->toBeTrue();
});

test('User can update Post', function () {
    $name = $this->faker->name();
    $content = $this->faker->sentence();
    $details = buildPostDetails($this->faker);
    $id = $this->db->getConnection()->lastInsertId();
    $status = $this->user->updatePost($id, $name, $content, $details);
    $post = $this->user->getPost($id);
    expect($status)->toBeTrue();
});

test('User can get a particular Post by Id', function () {
    $name = $this->faker->name();
    $content = $this->faker->sentence();
    $details = buildPostDetails($this->faker);
    $postId = $this->user->createPost($name, $content, $details);
    $post = $this->user->getPost($postId);
    expect($post)->not->toBeNull();
    expect($post['content'])->toBe($content);
});

test('User can get his/her posts', function () {
    $name = $this->faker->name();
    $content = $this->faker->sentence();
    $details = buildPostDetails($this->faker);
    $this->user->createPost($name, $content, $details);
    $posts = $this->user->getMyPosts();
    expect(count($posts))->toBeGreaterThan(0); 
    expect($posts[0]['content'])->toBe($content);
});

test('User can delete Post', function () {
    $name = $this->faker->name();
    $content = $this->faker->sentence();
    $details = buildPostDetails($this->faker);
    $this->user->createPost($name, $content, $details);
    $postId = $this->db->getConnection()->lastInsertId();
    expect($this->user->deletePost($postId))->toBeTrue();
    expect($this->user->getPost($postId))->toBeFalse();
});

afterEach(function () {
    session_destroy();
    // Clean up any resources or database entries after each test
    unlink(__DIR__ . '/../' . $_ENV['DB_NAME'] . '_test.db'); // Replace with the actual path to your SQLite database file
});
