<?php

namespace ClassDiagram;

require_once "./vendor/autoload.php";
require_once "./lib/NodeVisitor_ClassDiagram.php";

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor_ClassDiagram;

class Parser
{
    private $code;
    private $parser;
    private $traverser;

    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
    }

    /**
     * PHPファイルを解析
     * @return array $stmts
     */
    public function parse(string $code): array
    {
        $this->traverser->addVisitor(new NodeVisitor_ClassDiagram());
        try {
            $stmts = $this->parser->parse($code);
            $stmts = $this->traverser->traverse($stmts);
            return $stmts;
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()}\n");
        }
    }
}
