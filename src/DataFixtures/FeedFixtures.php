<?php

namespace App\DataFixtures;

use App\Entity\Feed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeedFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $feed1 = new Feed();
        $feed1->setUrl('https://www.axelerant.com/tag/drupal-planet/feed');

        $manager->persist($feed1);

        $feed2 = new Feed();
        $feed2->setUrl('http://feeds.bbci.co.uk/news/technology/rss.xml');

        $manager->persist($feed2);

        $feed3 = new Feed();
        $feed3->setUrl('https://feeds.feedburner.com/symfony/blog');

        $manager->persist($feed3);

        $manager->flush();
    }
}
