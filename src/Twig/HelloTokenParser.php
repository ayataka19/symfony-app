<?php

namespace App\Twig;

class HelloTokenParser extends \Twig_TokenParser
{
    public function parse(\Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new HelloNode($token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'hello';
    }
}
