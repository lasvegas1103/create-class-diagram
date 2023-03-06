<?php

namespace ClassDiagram;

class Util
{
    /**
     * アクセス修飾子の記号を取得
     * @param string $flag
     * @return array [$beforeAccessModifier, $afterAccessModifier]
     */
    public function getAccessModifier(string $flag): array
    {
        // アクセス修飾子
        switch ($flag) {
            case "1":
                // public
                $beforeAccessModifier = "+";
                $afterAccessModifier = "";
                break;
            case "2":
                // protected
                $beforeAccessModifier = "#";
                $afterAccessModifier = "";
                break;
            case "4":
                // private
                $beforeAccessModifier = "-";
                $afterAccessModifier = "";
                break;
            case "9":
                // static
                $afterAccessModifier = "$";
                $beforeAccessModifier = "";
                break;
            case "17":
                // abstract
                $afterAccessModifier = "*";
                $beforeAccessModifier = "";
                break;
            default:
                $beforeAccessModifier = "";
                $afterAccessModifier = "";
        }
        return [$beforeAccessModifier, $afterAccessModifier];
    }
}
