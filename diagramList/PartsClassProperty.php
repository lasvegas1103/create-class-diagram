<?php

namespace ClassDiagram;

require_once "interface/IGenerateClassDiagram.php";
require_once "lib/Util.php";

use ClassDiagram\IGenerateClassDiagram;
use ClassDiagram\Util;

class PartsClassProperty implements IGenerateClassDiagram
{
    private $nodes;
    private $createDiagram;

    public function __construct(array $nodes, CreateFromClassDiagram $createDiagram)
    {
        $this->createDiagram = $createDiagram;
        if (empty($nodes["classProperty"])) {
            // 空文字で置換
            $this->createDiagram->replace("#property#", "");
        }
        foreach ($nodes["classProperty"] as $classProperty) {
            if (empty($classProperty["name"])) {
                throw new \Exception("classProperty名が取得できません");
            }
        }

        $this->nodes = $nodes["classProperty"];
    }

    /**
     * classProperty箇所を生成
     * @return void
     */
    public function generate(): void
    {
        $replaceChar = "";
        foreach ($this->nodes as $node) {
            // アクセス修飾子
            [$beforeAccessModifier, $afterAccessModifier] = (new Util())->getAccessModifier($node["flags"]);
            // 型
            $type = !empty($node["type"]) ? $node["type"]. " " : $node["type"];
            // プロパティ名
            $name = $node["name"];
            $replaceChar .= "{$beforeAccessModifier}{$type}{$name}{$afterAccessModifier}". PHP_EOL;
        }
        $this->createDiagram->replace("#property#", $replaceChar);
    }
}
