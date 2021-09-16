<?php

namespace App\DataFixtures;

use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $content1 = new Content();
        $content1
            ->setFeed($this->getReference('feed1'))
            ->setTitle('Managing Third-Party Authentications In Mautic Plugins')
            ->setDescription('Managing Third-Party Authentications In Mautic Plugins Managing Third-Party Authentications In Mautic Plugins')
            ->setLink('https://www.axelerant.com/blog/managing-third-party-authentications-mautic-plugins')
            ->setPubDate(new \DateTime());

        $manager->persist($content1);

        $content1 = new Content();
        $content1
            ->setFeed($this->getReference('feed1'))
            ->setTitle('Using Drupal API To Create A Cross-Platform Menu')
            ->setDescription('Using Drupal API To Create A Cross-Platform Menu')
            ->setLink('https://www.axelerant.com/blog/using-drupal-api-create-cross-platform-menu')
            ->setPubDate(new \DateTime());

        $manager->persist($content1);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FeedFixtures::class,
        ];
    }
}
