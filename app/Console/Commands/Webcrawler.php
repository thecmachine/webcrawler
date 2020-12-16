<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Webcrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webcrawler:crawl {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Webcrawler Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //  http://wiprodigital.com

        // laravel handles this behind the scenes now, leaving for posterity
        if(!$this->argument('url')){
            $this->error("Url argument is required");
        }

        // quick script title
        $this->info("Webcrawler here, crawl " . $this->argument('url'));

        // grab URL argument and remove http and https 
        $domain = $this->argument('url');
        $domain = str_replace("https://", "", $domain);
        $domain = str_replace("http://", "", $domain);

        //get first page URLS
        $urls = $this->getUrls($domain);
        $this->info('MAIN PAGE Internal URLS: ');
        foreach($urls['internal'] as $page){
            $this->info('Internal URL Parsed Links - ' . $page);

            $pages = $this->getUrls($domain);
            $this->info('SubpageInternal URLS: ');
            foreach($pages['internal'] as $url){
                $this->line($url);
            }
            $this->info('Subpage External URLS: ');
            foreach($pages['external'] as $url){
                $this->line($url);
            }
            $this->info('SubPage Images: ');
            foreach($pages['images'] as $img){
                $this->line($img);
            }
        }
        $this->info('MAIN PAGE External URLS: ');
        foreach($urls['external'] as $url){
            $this->line($url);
        }
        $this->info('MAIN PAGE Images: ');
        foreach($urls['images'] as $img){
            $this->line($img);
        }

        return 0;
    }

    public function getUrls($domain){
        //get URL's DOM and load without errors
        $html = file_get_contents($this->argument('url'));
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);

        //grab anchor and image tags from DOM
        $links = $dom->getElementsByTagName('a');
        $images = $dom->getElementsByTagName('img');

        //parse internal and external urls
        $internalUrls = [];
        $externalUrls = [];
        foreach($links as $link){           
            $domainPosition =  strpos($link->getAttribute('href'), $domain);
            if ($domainPosition !== false) {
                $internalUrls[] = $link->getAttribute('href');
            }else{
                $externalUrls[] = $link->getAttribute('href');
            }
        }

        //parse image urls
        $imageUrls = [];
        foreach($images as $img){
            $imageUrls[] = $img->getAttribute('src');
        }

        //format return value with unique array values
        $urls = [
            'internal' => array_unique($internalUrls),
            'external' => array_unique($externalUrls),
            'images'   => array_unique($imageUrls)
        ];
        return $urls;
    }
}
