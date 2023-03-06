<?php

namespace ClassDiagram;

class CreateFromClassDiagram
{
    private $template;
    private string $relationship;

    public function __construct()
    {
        $this->generateTemplate(true);
        $this->initRelationship();
    }

    /**
     * template作成
     * @return string
     */
    public function generateTemplate(bool $isFirst): void
    {
        $classDiagramChar = $isFirst ? "classDiagram" : "";
        $template = <<<END
        {$classDiagramChar}
            class #class# { #interface# #abstract# #property# #method# }
END;
        $this->template = $template;
    }

    /**
     * templateにはめ込んでいく
     * @return void
     */
    public function replace(string $search, string $replace): void
    {
        $this->template = str_replace($search, $replace, $this->template);
    }

    /**
     *
     */
    public function addRelationship(string $relationship): void
    {
        $this->relationship .= $relationship. PHP_EOL;
    }

    /**
     *
     */
   public function initRelationship(): void
   {
       $this->relationship = "";
   }

    /**
     * クラス図出力
     * @return string
     */
    public function output(): string
    {
        return $this->template. PHP_EOL. $this->relationship;
    }
}
