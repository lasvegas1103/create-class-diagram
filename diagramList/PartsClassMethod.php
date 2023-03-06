<?php

namespace ClassDiagram;

require_once "interface/IGenerateClassDiagram.php";
require_once "lib/Util.php";

use ClassDiagram\IGenerateClassDiagram;
use ClassDiagram\Util;

class PartsClassMethod implements IGenerateClassDiagram
{
    private $nodes;
    private $createDiagram;

    public function __construct(array $nodes, CreateFromClassDiagram $createDiagram)
    {
        $this->createDiagram = $createDiagram;
        if (empty($nodes["classMethod"])) {
            // 空文字で置換
            $this->createDiagram->replace("#method#", "");
        }
        foreach ($nodes["classMethod"] as $classMethodNode) {
            if (empty($classMethodNode["name"])) {
                throw new \Exception("classMethod名が取得できません");
            }
        }

        $this->nodes = $nodes["classMethod"];
    }

    /**
     * classMethod箇所を生成
     * @return void
     */
    public function generate(): void
    {
        $replaceChar = "";
        foreach ($this->nodes as $node) {
            // アクセス修飾子
            [$beforeAccessModifier, $afterAccessModifier] = (new Util())->getAccessModifier($node["flags"]);
            // メソッド名
            $name = $node["name"];
            // パラメータ
            $params = "";
            foreach ($node["params"] as $param) {
                $type = $param["type"] ?? "";
                $varName = $param["varName"] ?? "";
                $params .= $type. " ". $varName. ", ";
            }
            // 余分なカンマ削除
            $params = preg_replace('/,\s$/', "", $params);
            // 戻り値
            $returnType = !empty($node["returnType"]) ? ": ". $node["returnType"] : "";
            $replaceChar .= "{$beforeAccessModifier}{$name}({$params}){$afterAccessModifier}{$returnType}". PHP_EOL;
        }
        $this->createDiagram->replace("#method#", $replaceChar);
    }
}
