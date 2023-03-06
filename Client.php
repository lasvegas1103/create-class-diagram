<?php

namespace ClassDiagram;

require_once "factory/ClassDiagramFactory.php";
require_once "lib/CreateFromClassDiagram.php";
require_once "lib/Parser.php";

use ClassDiagram\ClassDiagramFactory;
use ClassDiagram\CreateFromClassDiagram;
use ClassDiagram\Parser;
use Exception;

class Client
{
    private $classDiagramFactory;
    private $CreateFromClassDiagram;
    private $files;

    public function __construct($path)
    {
        $this->files = glob("{$path}*php");
        $this->CreateFromClassDiagram = new CreateFromClassDiagram();
    }

    /**
     * クラス図作成
     */
    public function execute(): void
    {
        try {
            // 1ファイルずつクラス図を出力
            for ($count = 0; $count < count($this->files); $count++) {
                // fileを1つずつ取得
                $target_file = $this->files[$count];
                // 取得した１つのファイルから読み込み、$textsへ格納
                $code = file_get_contents($target_file);

                $this->classDiagramFactory = new ClassDiagramFactory(
                    (new Parser())->parse($code),
                    $this->CreateFromClassDiagram
                );

                // クラス名
                $class = $this->classDiagramFactory->generateClass();
                $class->generate();
                // プロパティ名
                $classProperty = $this->classDiagramFactory->generateClassProperty();
                $classProperty->generate();
                // メソッド名
                $classMethod = $this->classDiagramFactory->generateClassMethod();
                $classMethod->generate();

                // 出力
                echo $this->CreateFromClassDiagram->output(). PHP_EOL;

                // クラス図テンプレート再作成
                $this->CreateFromClassDiagram->generateTemplate($isFirst=false);
                // 関係性初期化
                $this->CreateFromClassDiagram->initRelationship();
            }
        } catch(Exception $e) {
            throw new Exception($e);
        }
    }

    // private function getFileList($dir)
    // {
    //     $files = scandir($dir);
    //     $files = array_filter($files, function ($file) {
    //         return !in_array($file, array('.', '..'));
    //     });
    //     $list = [];
    //     foreach ($files as $file) {
    //         $fullpath = rtrim($dir, '/') . '/' . $file;
    //         if (is_file($fullpath)) {
    //             $list[] = $fullpath;
    //         }
    //         if (is_dir($fullpath)) {
    //             $list = array_merge($list, $this->getFileList($fullpath));
    //         }
    //     }
    //     return $list;
    // }
}
$stdout= fopen('php://stdout', 'w');
fwrite($stdout, "対象のpathを指定してください：");
$class = new Client(trim(fgets(STDIN)));
$class->execute();
