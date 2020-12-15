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

        $this->line("Webcrawler here, crawl " . $this->argument('url'));

        $domain = $this->argument('url');
        $domain = str_replace("https://", "", $domain);
        $domain = str_replace("http://", "", $domain);

        //get first page URLS
        $urls = $this->getUrls($domain);
        $this->line('Internal URLS: ');
        foreach($urls['internal'] as $page){
            $this->line('Internal URL Parsed Links - ' . $page);

            //differenciate page and domain because right now they're one
            $pages = $this->getUrls($page);
            $this->line('Internal URL: ');
            foreach($pages['internal'] as $url){
                $this->line($url);
            }
            $this->line('External URLS: ');
            foreach($pages['external'] as $url){
                $this->line($url);
            }
            $this->line('Images: ');
            foreach($pages['images'] as $img){
                $this->line($img);
            }
        }
        $this->line('External URLS: ');
        foreach($urls['external'] as $url){
            $this->line($url);
        }
        $this->line('Images: ');
        foreach($urls['images'] as $img){
            $this->line($img);
        }

        //print URLS
        return 0;
    }

    public function getUrls($domain){
        $html = file_get_contents($this->argument('url'));
        
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);

        $links = $dom->getElementsByTagName('a');
        $images = $dom->getElementsByTagName('img');

        //urls
        $internalUrls = [];
        $externalUrls = [];
        $images = [];
        foreach($links as $link){
           // $this->line('link-'.$link->getAttribute('href'). '    domain-'.$domain);
            $domainPosition =  strpos($link->getAttribute('href'), $domain);
            if ($domainPosition !== false) {
                $internalUrls[] = $link->getAttribute('href');
            }else{
                $externalUrls[] = $link->getAttribute('href');
            }
        }

        //images 
        foreach($images as $img){
            $this->line();
            $images[] = $img->getAttribute('src');
        }

        $urls = [
            'internal' => array_unique($internalUrls),
            'external' => array_unique($externalUrls),
            'images'   => array_unique($images)
        ];
        return $urls;
    }
}
