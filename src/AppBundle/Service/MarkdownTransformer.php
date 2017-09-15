<?php

namespace AppBundle\Service;

use Doctrine\Common\Cache\Cache;
use Knp\Bundle\MarkdownBundle\Parser\ParserManager;

class MarkdownTransformer
{
    private $markdownParser;
    private $cache;
    
    public function __construct(ParserManager $markdownParser, Cache $cache)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
    }
    
    public function parse($str)
    {
        $cache = $this->cache;
    
        $key = md5($str);
        
        if ($cache->contains($key)) {
            return $cache->fetch($key);
        }
        
        sleep(2);
        
        $str = $this->markdownParser
            ->transform($str);
    
        $cache->save($key, $str);
        
        return $str;
    }
}