<?php

class template {

    private $templateDir; //源文件所在目录
    private $compileDir;  //编译后文件目录
    private $leftTag = "{#"; //默认需要替换内容
    private $rightTag = "#}";
    private $currentTemp = ""; //当前正在编译的文件名
    private $outputHtml; //当前模板正在编译的html代码
    private $varPool = array(); //变量池

    public function __construct($templateDir, $compileDir, $leftTag=null, $rightTag=null) {
        $this->templateDir = $templateDir;
        $this->compileDir = $compileDir;
        if(!empty($leftTag)) $this->leftTag = $leftTag;
        if(!empty($rightTag)) $this->rightTag = $rightTag;
    }

    public function assign($tag, $var) {
        $this->varPool[$tag] = $var;
    }

    public function getVar($tag) {
        return $this->varPool[$tag];
    }

    public function getSourceTemplate($templateName, $ext='.html') {
        $this->currentTemp = $templateName;
        $sourceFileName = $this->templateDir.$this->currentTemp.$ext;
        $this->outputHtml = file_get_contents($sourceFileName);
    }

    public function compileTemplate($templateName=null, $ext=".html") {
        $templateName = empty($templateName)?$this->currentTemp:$templateName;
        //\{#\$(\w+)\#}
        $pattern = '/'.preg_quote($this->leftTag);
        $pattern .= ' *\$([a-zA-Z_]\w*) *';
        $pattern .= preg_quote($this->rightTag).'/';
        //$1子模式第一个参数
        $this->outputHtml = preg_replace($pattern, '<?php echo $this->getVar(\'$1\')?>', $this->outputHtml);

        $compiledFileName = $this->compileDir.md5($templateName).$ext;
        file_put_contents($compiledFileName, $this->outputHtml);
    }

    public function display($templateName=null,$ext=".html") {
        $templateName = empty($templateName)?$this->currentTemp:$templateName;
        include_once $this->compileDir.md5($templateName).$ext;
    }
}