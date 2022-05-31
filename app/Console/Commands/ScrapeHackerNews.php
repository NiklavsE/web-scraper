<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHackerNews extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scrape:hacker_news';

    /**
     * @var string
     */
    protected $description = 'Scrape hackernews frontpage';

    private string $url = 'https://news.ycombinator.com/';

    private Client $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;

        parent::__construct();
    }

    public function handle(): int
    {
        $crawler = $this->client->request('GET', $this->url);

        $entities = [];

        $crawler->filter('.athing')->each(function (Crawler $node) use (&$entities, $crawler) {
            $title      = $node->filter('.titlelink')->text();
            $link       = $node->filter('.titlelink')->attr('href');
            $externalId = $node->attr('id');

            $subTextRow = $crawler->filter('#' . $externalId . ' + tr');

            // adverts don't have points...
            $points = str_replace(
                ' points',
                '',
                0 !== $subTextRow->filter('.subtext .score')->count() ?
                    $subTextRow->filter('.subtext .score')->text() :
                    ''
            );

            $date   = $subTextRow->filter('.age')->attr('title');

            $entities[] = [
                'title'        => $title,
                'link'         => $link,
                'external_id'  => $externalId,
                'points'       => $points,
                'date_created' => $date,
            ];
        });

        foreach ($entities as $entity) {
            Article::upsert(
                [
                    $entity
                ],
                [
                    'external_id' => $entity['external_id']
                ],
                ['points']
            );
        }

        return 0;
    }
}
