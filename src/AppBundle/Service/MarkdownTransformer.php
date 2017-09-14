<?php

namespace AppBundle\Service;


use Knp\Bundle\MarkdownBundle\Parser\ParserManager;

class MarkdownTransformer
{
    private $markdownParser;
    
    public function __construct(ParserManager $markdownParser)
    {
    
        $this->markdownParser = $markdownParser;
    }
    
    public function parse($str)
    {
        return $this->markdownParser
            ->transform($str);
    }
}