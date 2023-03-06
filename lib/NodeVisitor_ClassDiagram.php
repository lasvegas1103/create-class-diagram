<?php

namespace PhpParser;

use PhpParser\Node;

class NodeVisitor_ClassDiagram extends \PhpParser\NodeVisitorAbstract
{
    private $nodeClass = [];
    private $nodeClassProperty = [];
    private $nodeClassMethod = [];

    public function __construct()
    {
    }

    /**
    * クラス図で必要な要素を取得
    *
    * @param Node[]
    */
    public function enterNode(Node $node): void
    {
        $this->filterNodeClass($node);
        $this->filterNodeClassProperty($node);
        $this->filterNodeClassMethod($node);
    }

    /**
     * classでクラス図に必要な情報を返す
     *
     * @param Node[]
     * @return $this->nodeClass[]
     *
     */
    public function afterTraverse(array $nodes): array
    {
        $nodes = [
             "class" => $this->nodeClass
             ,"classProperty" => $this->nodeClassProperty
             ,"classMethod" => $this->nodeClassMethod

        ];
        return $nodes;
    }

    /**
     * （Class用）クラス図に必要な要素を取得
     * @param Node[]
     * @return array $this->nodeClass[]
     */
    private function filterNodeClass(Node $node): array
    {
        if ($node instanceof Node\Stmt\Class_) {
            // クラス名
            $name = $node->name->name ?? "";
            // 継承
            $extends = $node->extends ?? "";
            // インターフェイス
            $implements = $node->implements ?? "";
            // 抽象クラスか
            $isAbstract = $node->flags === 16 ? true : false;

            $this->nodeClass[] = [
                "name" => $name
               ,"extends" => $extends
               ,"implements" => $implements
               ,"isAbstract" => $isAbstract
            ];
        } elseif ($node instanceof Node\Stmt\Interface_) {
            // インターフェイス名
            $name = $node->name->name ?? "";
            $this->nodeClass[] = [
                 "name" => $name
                ,"interfaceFlag" => true
            ];
        }
        return $this->nodeClass;
    }

    /**
     * （ClassProperty用）クラス図に必要な要素を取得
     * @param Node[]
     * @return array $this->nodeClassProperty[]
     */
    private function filterNodeClassProperty(Node $node): array
    {
        if ($node instanceof Node\Stmt\Property) {
            // flags = 1 → public
            // flags = 2 → protected
            // flags = 4 → private
            $this->nodeClassProperty[] = [
                "name" => $node->props[0]->name->name ?? ""
                ,"type" => $node->type->parts[0] ?? $node->type->name ?? ""
                ,"flags" => $node->flags ?? ""
            ];
        }
        return $this->nodeClassProperty;
    }

    /**
     * （ClassMethod用）クラス図に必要な要素を取得
     * @param Node[]
     * @return array $this->nodeClassMethod[]
     */
    private function filterNodeClassMethod(Node $node): array
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            // メソッド名
            $name = $node->name->name ?? "";
            // アクセス修飾子
            $flags = $node->flags ?? "";
            // パラメータ
            $params = [];
            foreach ($node->params as $param) {
                $params[] = [
                    // 型
                    "type" => $param->type?->parts[0] ?? $param->type?->name ?? ""
                    // 変数名
                   ,"varName" => $param->var?->name
                ];
            }
            // 戻り値
            $returnType = $node->returnType->name ?? "";

            $this->nodeClassMethod[] = [
                "name" => $name
               ,"flags" => $flags
               ,"params" => $params
               ,"returnType" => $returnType
            ];
        }

        return $this->nodeClassMethod;
    }
}
