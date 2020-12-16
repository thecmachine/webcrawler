<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WebcrawlerTest extends TestCase
{
    /**
     * Verify Domain is stripped of HTTPS 
     * to filter internal vs external URLs
     *
     * @return void
     */
    public function testUrlStrippedOfHttps()
    {
        $domain = 'https://google.com';
        $domain = str_replace("https://", "", $domain);

        $this->assertStringNotContainsString("https://", $domain);
    }

     /**
     * Verify Domain is stripped of HTTP 
     * to filter internal vs external URLs
     *
     * @return void
     */
    public function testUrlStrippedOfHttp()
    {
        $domain = 'http://google.com';
        $domain = str_replace("http://", "", $domain);

        $this->assertStringNotContainsString("http://", $domain);
    }

    /**
     * Verify URL is Internal
     *
     * @return void
     */
    public function testUrlIsInternal(){
        $domainPosition =  strpos("http://google.com/img.jpg", "google.com");
        $internalUrl = false;
        if ($domainPosition !== false) {
            $internalUrl = true;
        }
        $this->assertTrue($internalUrl);
    }

    /**
     * Verify URL is External
     *
     * @return void
     */
    public function testUrlIsExternal(){
        $domainPosition =  strpos("http://google.com/img.jpg", "bing.com");
        $internalUrl = false;
        if ($domainPosition !== false) {
            $internalUrl = true;
        }
        $this->assertFalse($internalUrl);
    }


}
