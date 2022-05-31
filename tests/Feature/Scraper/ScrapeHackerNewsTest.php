<?php

namespace Tests\Feature\Scraper;

use App\Models\Article;
use DOMDocument;
use Goutte\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class ScrapeHackerNewsTest extends TestCase
{
    use RefreshDatabase;

    public function testCommand(): void
    {
        Carbon::setTestNow(Carbon::parse('2022-05-30'));

        $domCrawler = new Crawler();

        $domCrawler->add(file_get_contents(__DIR__ . '/hacker_news.html'));

        $this->mock(Client::class, function (MockInterface $mock) use ($domCrawler) {
           $mock->shouldReceive('request')->andReturn($domCrawler);
        });

        Article::factory()->create([
            'title'       => 'Updated Article',
            'external_id' => '31548268',
            'points'      => 1,
            'link'        => 'https://link.to_article',
        ]);

        Article::factory()->create([
            'title'       => 'Deleted Article',
            'external_id' => '31544988',
            'points'      => 0,
            'deleted_at'  => Carbon::now(),
            'link'        => 'https://link.to_article',
        ]);

        Artisan::call('scrape:hacker_news');

        $this->assertDatabaseHas('articles', [
            'title'       => 'Updated Article',
            'external_id' => '31548268',
            'points'      => 420,
            'deleted_at'  => null,
            'link'        => 'https://link.to_article',
        ]);

        $this->assertDatabaseHas('articles', [
            'title'       => 'Deleted Article',
            'external_id' => '31544988',
            'points'      => 333,
            'deleted_at'  => Carbon::now(),
            'link'        => 'https://link.to_article',
        ]);

        $this->assertDatabaseHas('articles', [
            'title'       => 'New Article',
            'external_id' => '31549050',
            'points'      => 123,
            'deleted_at'  => null,
            'link'        => 'https://link.to_article',
        ]);
    }
}
