<?php

use App\Models\Db;
use App\Models\Auth;
use App\Models\User;
use Faker\Factory as Faker;

beforeEach(function () {
    // Set up a test database or any other necessary preparations
    $this->db = new Db(true); // true indicates using SQLite for testing
    $this->db->createTables(); // Automatically create tables on instantiation
    $this->faker = Faker::create();// Initialize Faker
    $this->auth = new Auth($this->db);// Initialize Auth
});

it('can register a user', function () {
    $name = $this->faker->name();
    $email = $this->faker->email();
    $address = $this->faker->address();
    $password = $this->faker->password();
    $type = $this->faker->randomElement(['buyer', 'farmer']);

    $user = $this->auth->registerUser($name, $email, $address, $type, $password);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe($name);
    expect($user->email)->toBe($email);
    expect($user->type)->toBe($type);
});

it('can login a user', function () {
    $name = $this->faker->name();
    $email = $this->faker->email();
    $address = $this->faker->address();
    $password = $this->faker->password();
    $type = $this->faker->randomElement(['buyer', 'farmer']);

    $user = $this->auth->registerUser($name, $email, $address, $type, $password);

    $loggedInUser = $this->auth->loginUser($email, $password);

    expect($loggedInUser->name)->toBe($name);
    expect($loggedInUser->type)->toBe($type);
    expect($loggedInUser->email)->toBe($email);
    expect($this->auth->isAuthenticated())->toBeTrue();
    expect($loggedInUser)->toBeInstanceOf(User::class);
    expect($this->auth->amIthisUser($loggedInUser->id))->toBeTrue();
});

// Add more test methods for other Auth class functions...

afterEach(function () {
    // Clean up any resources or database entries after each test
    unlink(__DIR__ . '/../' . $_ENV['DB_NAME'] . '_test.db'); // Replace with the actual path to your SQLite database file
});
