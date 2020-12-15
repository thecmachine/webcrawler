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

        //get first page URLS
        $urls = $this->getUrls();
        $this->line('Internal URLS: ');
        foreach($urls['internal'] as $url){
            $this->line($url);
        }
        $this->line('External URLS: ');
        foreach($urls['external'] as $url){
            $this->line($url);
        }

        //print URLS
        return 0;
    }

    public function getUrls(){
        $html = file_get_contents($this->argument('url'));
        
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');

        $internalUrls = [];
        $externalUrls = [];
        $images = [];
        foreach($links as $link){
           
            $domain = $this->argument('url');
            $domain = str_replace("https://", "", $domain);
            $domain = str_replace("http://", "", $domain);
            $domainPosition =  strpos($link->getAttribute('href'), $domain);
            if ($domainPosition !== false) {
                $internalUrls[] = $link->getAttribute('href');
            }else{
                $externalUrls[] = $link->getAttribute('href');
            }
        }

        $urls = [
            'internal' => array_unique($internalUrls),
            'external' => array_unique($externalUrls),
            'images'   => array_unique($images)
        ];
        return $urls;
    }
}
