<?php

namespace App\Service;

use App\Entity\Content;
use App\Entity\Feed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContentGenerator
{
    private $client;
    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function fetchContent(Feed $feed): void
    {
        $response = $this->client->request(
            'GET',
            $feed->getUrl()
        );

        $content = $response->getContent();

        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $contents = json_decode($json,TRUE);

        $this->saveFeedContent($feed, $contents);
    }

    private function saveFeedContent(Feed $feed, array $feedContents): void
    {
        foreach ($feedContents['channel']['item'] as $feedContent) {
            $pubDate = strtotime($feedContent['pubDate']);

            $content = new Content();
            $content
                ->setFeed($feed)
                ->setTitle($feedContent['title'])
                ->setLink($feedContent['link'])
                ->setDescription(is_string($feedContent['description']) ? $feedContent['description'] : '')
                ->setPubDate(new \DateTime($pubDate));

            $this->entityManager->persist($content);
        }

        $this->entityManager->flush();
    }
}
