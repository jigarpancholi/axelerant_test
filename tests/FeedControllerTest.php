<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeedControllerTest extends WebTestCase
{
    public function testFeedSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Feeds');
    }

    public function testFeedFailure(): void
    {
        $client = static::createClient();

        // test e.g. the profile page
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(302);
    }

    public function testFeedAdd(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $client->clickLink('Create new');

        $this->assertSelectorTextContains('h4', 'Create new Feed');

        $crawler = $client->submitForm('Save', [
            'feed[url]' => 'https://www.google.com',
        ]);

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testFeedEdit(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $client->clickLink('Edit');

        $this->assertSelectorTextContains('h4', 'Edit Feed');

        $crawler = $client->submitForm('Update', [
            'feed[url]' => 'https://www.yahoo.com',
        ]);

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testFeedDelete(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('Delete');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }
}
