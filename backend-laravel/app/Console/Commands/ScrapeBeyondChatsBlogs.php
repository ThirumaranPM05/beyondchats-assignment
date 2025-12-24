<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Article;

class ScrapeBeyondChatsBlogs extends Command
{
    protected $signature = 'scrape:beyondchats';
    protected $description = 'Scrape 5 oldest blog articles from BeyondChats';

    private const BASE_URL = 'https://beyondchats.com';

    public function handle()
    {
        $this->info('Starting BeyondChats blog scraping...');

        $client = HttpClient::create();
        $crawler = new Crawler();

        // Fetch blog listing
        $response = $client->request('GET', self::BASE_URL . '/blogs/');
        $html = $response->getContent();

        $crawler->addHtmlContent($html);

        // Extract article links
        $links = $crawler->filter('a')->each(fn ($node) => $node->attr('href'));

        $articleLinks = collect($links)
            ->filter(fn ($link) =>
                $link &&
                str_starts_with($link, '/blogs/') &&
                $link !== '/blogs/'
            )
            ->map(fn ($link) => self::BASE_URL . $link)
            ->unique()
            ->values();

        // Pick 5 oldest
        $oldestArticles = $articleLinks->reverse()->take(5);

        foreach ($oldestArticles as $url) {
            $this->scrapeSingleArticle($client, $url);
        }

        $this->info('Scraping completed successfully.');
    }

    private function scrapeSingleArticle($client, $url)
    {
        if (Article::where('source_url', $url)->exists()) {
            $this->line("Skipping existing article: $url");
            return;
        }

        $response = $client->request('GET', $url);
        $html = $response->getContent();

        $crawler = new Crawler($html);

        $title = $crawler->filter('h1')->first()->text('');
        $content = $crawler->filter('article')->text('');

        if (!$title || !$content) {
            $this->error("Failed to parse article: $url");
            return;
        }

        Article::create([
            'title' => trim($title),
            'slug' => Str::slug($title),
            'content' => trim($content),
            'source_url' => $url,
            'type' => 'original',
        ]);

        $this->info("Saved: $title");
    }
}
