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

        $this->getLinks();
        return 0;
    }

    public function getLinks(){
        $html = file_get_contents($this->argument('url'));
        
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        foreach ($links as $link){
            $this->line('Title: ' . $link->nodeValue . 'Url: ' . $link->getAttribute('href'));
        }
    }
}
