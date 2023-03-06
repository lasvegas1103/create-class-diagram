<?php

namespace ClassDiagram;

require_once "interface/IGenerateClassDiagram.php";

use ClassDiagram\IGenerateClassDiagram;

class PartsClass implements IGenerateClassDiagram
{
    private $nodes;
    private $createDiagram;

    public function __construct(array $nodes, CreateFromClassDiagram $createDiagram)
    {
        if (empty($nodes["class"])) {
            throw new \Exception("classの要素が存在しません");
        }
        foreach ($nodes["class"] as $classNode) {
            if (empty($classNode["name"])) {
                throw new \Exception("class名が取得できません");
            }
        }

        $this->nodes = $nodes["class"];
        $this->createDiagram = $createDiagram;
    }

    /**
     * class箇所を生成
     * @return void
     */
    public function generate(): void
    {
        $class = $this->nodes[0]["name"];
        $extends = $this->nodes[0]["extends"];
        $implements = $this->nodes[0]["implements"];
        $interfaceChar = !empty($this->nodes[0]["interfaceFlag"]) ? "<<interface>>" : "";
        $abstractChar = !empty($this->nodes[0]["isAbstract"]) ? "<<abstract>>" : "";
        $this->createDiagram->replace("#class#", $class);
        $this->createDiagram->replace("#interface#", $interfaceChar);
        $this->createDiagram->replace("#abstract#", $abstractChar);

        // クラス間の関係性も生成する
        if (!empty($extends)) {
            $this->createDiagram->addRelationship("{$class}..|>{$extends}");
        }
        if (!empty($implements)) {
            array_walk($implements, fn ($implement) => $this->createDiagram->addRelationship("{$class}..|>{$implement}"));
        }
    }
}
