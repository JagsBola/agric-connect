<?php

use Faker\Factory;
use App\Models\Db;
use App\Models\User;
use App\Models\Auth;
use App\Models\DbSeed;

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

test('EncryptMessage', function () {
    $message = $this->faker->sentence();
    $encryptedMessage = $this->user->encryptMessage($this->party->getPublicKey(), $message);
    expect($encryptedMessage)->not->toBeEmpty();
});

test('DecryptMessage', function () {
    $message = $this->faker->sentence();
    $encryptedMessage = $this->user->encryptMessage($this->party->getPublicKey(), $message);
    $decryptedMessage = $this->user->decryptMessage($this->party->getPublicKey(), $encryptedMessage);
    expect($decryptedMessage)->toBe($message);
});

test('getCategories', function () {
    $cats = $this->user->getCategories();
    expect(count($cats))->toBeGreaterThan(0); 
});

afterEach(function () {
    session_destroy();
    // Clean up any resources or database entries after each test
    unlink(__DIR__ . '/../../' . $_ENV['DB_NAME'] . '_test.db'); // Replace with the actual path to your SQLite database file
});