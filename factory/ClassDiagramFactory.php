<?php

namespace ClassDiagram;

require_once "diagramList/PartsClass.php";
require_once "diagramList/PartsClassMethod.php";
require_once "diagramList/PartsClassProperty.php";
require_once "interface/IGenerateClassDiagram.php";
require_once "lib/CreateFromClassDiagram.php";

use ClassDiagram\PartsClass;
use ClassDiagram\PartsClassMethod;
use ClassDiagram\PartsClassProperty;
use ClassDiagram\IGenerateClassDiagram;
use ClassDiagram\CreateFromClassDiagram;

class ClassDiagramFactory
{
    private array $nodes;
    private CreateFromClassDiagram $createDiagram;

    public function __construct(array $nodes, CreateFromClassDiagram $createDiagram)
    {
        $this->nodes = $nodes;
        $this->createDiagram = $createDiagram;
    }

    /**
     * class解析
     * @return new PartsClass()
     */
    public function generateClass(): IGenerateClassDiagram
    {
        return new PartsClass($this->nodes, $this->createDiagram);
    }

    /**
     * プロパティ解析
     * @return new PartsClassProperty()
     */
    public function generateClassProperty(): IGenerateClassDiagram
    {
        return new PartsClassProperty($this->nodes, $this->createDiagram);
    }

    /**
     * メソッド解析
     * @return new PartsClassMethod()
     */
    public function generateClassMethod(): IGenerateClassDiagram
    {
        return new PartsClassMethod($this->nodes, $this->createDiagram);
    }
}
