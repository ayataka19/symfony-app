<?php

namespace App\Twig;

class HelloNode extends \Twig_Node
{
    public function __construct($line, $tag = null)
    {
        parent::__construct([], [], $line, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo 'Hello!'")
            ->raw(";\n");
    }
}
