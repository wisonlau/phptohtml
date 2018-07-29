<?php

class Html
{
	private $doctype = '<!DOCTYPE html>';
    private $lang = 'cn';
    private $title = '';
    private $head= '';
    private $arrMeta = array('<meta charset="UTF-8">');
    private $arrStyle = array();
    private $arrJs = array();
    private $body = '';
    private $indent = '    ';
    private $eol = PHP_EOL;
	
	public static function ini()
    {
        return new self;
    }
	
	public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @param string $name 可选: author, description, keywords, generator, revised
     * @param string $content
     * @return self
     */
    public function setMetaName($name, $content)
    {
        $this->arrMeta[] = "<meta name='{$name}' content='{$content}'>";
        return $this;
    }
    
    /**
     * @param string $equiv 可选: content-type, expires, refresh, set-cookie
     * @param string $content
     * @return self
     */
    public function setMetaEquiv($equiv, $content)
    {
        $this->arrMeta[] = "<meta http-equiv='{$equiv}' content='{$content}'>";
        return $this;
    }
    
    /**
     * @param string $url <link rel='stylesheet' href='xxx.min.css'>
     * @return self
     */
    public function css($url)
    {
        $this->arrStyle[] = "<link rel='stylesheet' href='{$url}'>";
        return $this;
    }
    
    /**
     * @param string $url <script src="" type="text/javascript"></script>
     * @return self
     */
    public function js($url)
    {
        $this->arrJs[] = "<script type='application/javascript' src='{$url}'></script>";
        return $this;
    }
    
    /**
     * 组装 head 标签
     * @return $this
     */
    public function setHead()
    {
        $arrHead = array();
        
        $arrHead[] = '<head>';
        $arrHead[] = $this->indent.implode($this->eol.$this->indent, $this->arrMeta);
        $arrHead[] = $this->indent."<title> {$this->title} </title>";
        $arrHead[] = $this->indent.implode($this->eol.$this->indent, $this->arrStyle);
        $arrHead[] = $this->indent.implode($this->eol.$this->indent, $this->arrJs);
        $arrHead[] = '</head>';
        
        $this->head = implode($this->eol, $arrHead);
        
        return $this;
    }
    
    /**
     * @param $body
     * @return $this
     */
    public function setBody(body $body)
    {
        $this->body = $body->out();
        return $this;
    }
    
    /**
     * 输出HTML内容
     * @return string
     */
    public function out()
    {
        $str = $this->doctype.$this->eol;
        $str .= "<html lang='{$this->lang}'>".$this->eol;
        $str .= $this->head.$this->eol;
        $str .= $this->body.$this->eol;
        $str .= '</html>';
        
        return $str;
    }
    
}

/**
 * HTML标签公用属性, 内容, 缩进等
 * Trait attribute
 */
trait attribute
{
    //公用
    private $id = '';
    private $width = '';
    private $height = '';
    private $style = ''; //未用, 建议写成单独的style文件
    private $disabled = '';
    private $text = ''; //文字内容
    
    //input
    private $type = '';
    private $name = '';
    private $value = '';
    private $checked = '';
    private $selected = '';
    private $class = '';
    private $placeholer = '';
    
    // tr, td
    private $colspan = '';
    private $rowspan = '';
    private $align = '';
    private $valign = '';
    
    // table
    private $caption = '';
    private $thead = '';
    private $tbody = '';
    
    // form
    private $action = '';
    private $method = '';
    private $enctype = '';
    
    //其他
    public $indent = '    '; //缩进
    public $eol = PHP_EOL; //换行
    public $tag = '';
    
    public $arrOther = array(); // array('data-x' => '123', 'align' => 'left', ...)
    
    /**
     * 获取非空的属性元素
     */
    public function filterAttrs()
    {
        $attributes = array();
        
        $vars = get_object_vars($this);
        
        //删掉非键值对属性
        unset($vars['disabled']);
        unset($vars['checked']);
        unset($vars['selected']);
        
        //删掉无用属性
        unset($vars['text']);
        unset($vars['indent']);
        unset($vars['eol']);
        unset($vars['tag']);
        
        foreach ($vars as $name => $value) {
            !empty($value) && !is_array($value) && ($attributes[] = "{$name}='{$value}'");
        }
    
        !empty($this->disabled) && $attributes[] = 'disabled';
        !empty($this->checked) && $attributes[] = 'checked';
        !empty($this->selected) && $attributes[] = 'selected';
        
        if (!empty($attributes)) {
            return ' '.implode(' ', $attributes);
        } else {
            return '';
        }
    }
    
    /**
     * 组装其他自定义的属性, onclick, data-x, ....
     * @return string
     */
    public function otherAttrs()
    {
        $attributes = array();
        if (!empty($this->arrOther)) {
            foreach ($this->arrOther as $k => $v) {
                $k = (string) $k;
                $v = (string) $v;
                $attributes = "{$k}='{$v}'";
            }
            
            return ' '.implode(' ', $attributes);
        } else {
            return '';
        }
    }
    
    /**
     * 获取赋了值的属性, 组装成字符串返回
     * @return string
     */
    public function getAttrs()
    {
        return $this->filterAttrs() . $this->otherAttrs();
    }
    
    /**
     * 每次调用out()方法后清空对象中的属性, 避免引起混乱
     */
    public function init()
    {
        //公用
        $this->id = '';
        $this->style = ''; //未用, 建议写成单独的style文件
        $this->disabled = '';
    
        //input
        $this->type = '';
        $this->name = '';
        $this->value = '';
        $this->checked = '';
        $this->selected = '';
        $this->class = '';
        $this->placeholer = '';
        $this->text = '';
    
        // tr, td
        $this->colspan = '';
        $this->rowspan = '';
        $this->align = '';
        $this->valign = '';
    
        // table
        $this->caption = '';
        $this->thead = '';
        $this->tbody = '';
    
        // form
        $this->action = '';
        $this->method = '';
        $this->enctype = '';
        
    }
    
    public function setId($v)
    {
        $this->id = $v;
        return $this;
    }
    
    public function setName($v)
    {
        $this->name = $v;
        return $this;
    }
    
    public function setType($v)
    {
        $this->type = $v;
        return $this;
    }
    
    public function setstyle($v)
    {
        $this->style = $v;
    }
    
    public function setValue($v)
    {
        $this->value = $v;
        return $this;
    }
    
    public function setDisabled($v)
    {
        $this->disabled = $v;
        return $this;
    }
    
    public function setPlaceholder($v)
    {
        $this->placeholer = $v;
        return $this;
    }
    
    public function setClass($v)
    {
        $this->class = $v;
        return $this;
    }
    
    public function setText($v)
    {
        $this->text = $v;
        return $this;
    }
    
    public function setCaption($v)
    {
        $this->caption = $v;
    }
    
    public function setThead($v)
    {
        $this->thead = $v;
    }
    
    public function setTbody($v)
    {
        $this->tbody = $v;
    }
    
    public function setAction($v)
    {
        $this->action = $v;
        return $this;
    }
    
    public function setMethod($v)
    {
        $this->method = $v;
        return $this;
    }
    
    public function setEnctype($v)
    {
        $this->enctype = $v;
        return $this;
    }
    
    public function setSelected($v)
    {
        $this->selected = $v;
        return $this;
    }
    
    public function setColspan($v)
    {
        $this->colspan = $v;
        return $this;
    }
    
    public function setRowspan($v)
    {
        $this->rowspan = $v;
        return $this;
    }
    
    public function setAlign($v)
    {
        $this->align = $v;
        return $this;
    }
    
    public function setValign($v)
    {
        $this->valign = $v;
        return $this;
    }
    
    public function setWidth($v)
    {
        $this->width = $v;
        return $this;
    }
    
    public function setHeight($v)
    {
        $this->height = $v;
        return $this;
    }
    
    
}

//注释
class comment
{
    public $indent = '    ';
    public $eol = '';
    public $text;
    
    public static function ini()
    {
        return new self;
    }
    
    public function setIndent($indent)
    {
        $this->indent = $indent;
        return $this;
    }
    
    public function eol($eol)
    {
        $this->eol = $eol;
        return $this;
    }
    
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    
    public function out()
    {
        return $this->indent."<!-- {$this->text} -->".$this->eol;
    }
}

class label
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<label'.$this->getAttrs().'>'.$this->text.'</label>';
        $this->init();
        return $this->indent.$str;
    }
}

class input
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<input'.$this->getAttrs().'>';
        $this->init();
        return $this->indent.$str;
    }
}

class checkbox
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<input'.$this->getAttrs().'>';
        $this->init();
        return $this->indent.$str;
    }
}

class radio
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<input'.$this->getAttrs().'>';
        $this->init();
        return $this->indent.$str;
    }
}

class select
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public $arrOption = array();
    
    public function option(option $option)
    {
        $this->arrOption[] = $option;
    }
    
    public function out()
    {
        $str = '<select'. $this->getAttrs() .'>'.$this->eol;
        
        foreach ($this->arrOption as $ok => $option) {
            $option->indent .= $this->indent;
            $this->arrOption[$ok] = $option->out();
        }
        
        $str .= implode($this->eol, $this->arrOption);
        $str .= $this->eol.$this->indent.'</select>';
    
        $this->init();
        return $this->indent.$str;
    }
}

class option
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<option'.$this->getAttrs().'>'.$this->text.'</option>';
    
        $this->init();
        return $this->indent.$str;
    }
}

class form
{
    use attribute;
    
    const ENCTYPE_DEFAULT = 'application/x-www-form-urlencoded'; // 空格转换为 "+" 加号，特殊符号转换为 ASCII HEX 值
    const ENCTYPE_FILE = 'multipart/form-data'; // 文件上传
    const ENCTYPE_TEXT = 'text/plain'; // 空格转换为+, 其他特殊字符不做处理
    
    public $arrChild = array();
    
    public static function ini()
    {
        return new self;
    }
    
    /**
     * @param mixed $child 子元素对象, 必须有out方法, 并且返回HTML
     */
    public function append($child)
    {
        $this->arrChild[] = $child;
    }
    
    public function out()
    {
        $str = '<form'.$this->getAttrs().'>'.$this->eol;
        
        //补充缩进
        foreach ($this->arrChild as $ck => $child) {
            $child->indent .= $this->indent;
            $this->arrChild[$ck] = $child->out();
        }
        
        $str .= implode($this->eol, $this->arrChild);
        
        $str .= $this->eol.$this->indent.'</form>';
        
        $this->init();
        return $this->indent.$str;
    }
}

class table
{
    use attribute;
    
    public $arrTr = array();
    
    public static function ini()
    {
        return new self;
    }
    
    public function tr(tr $tr)
    {
        $this->arrTr[] = $tr;
    }
    
    public function out()
    {
        $str = '<table'.$this->getAttrs().'>'.$this->eol;
        
        foreach ($this->arrTr as $trk => $tr) {
            $tr->indent .= $this->indent;
            foreach ($tr->arrTd as $tdk => $td) {
                $td->indent .= $tr->indent;
                $tr->arrTd[$tdk] = $td->out();
            }
            
            $this->arrTr[$trk] = $tr->out();
        }
    
        $str .= implode($this->eol.$this->eol, $this->arrTr);
    
        $str .= $this->eol.$this->indent.'</table>';
    
        $this->init();
        return $this->indent.$str;
    }
}

class tr
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public $arrTd = array();
    
    public function td(td $td)
    {
        $this->arrTd[] = $td;
    }
    
    public function th(th $th)
    {
        $this->arrTd[] = $th;
    }
    
    public function out()
    {
        $str = '<tr'.$this->getAttrs().'>'.$this->eol;
        
        $str .= implode($this->eol, $this->arrTd);
        
        $str .= $this->eol.$this->indent.'</tr>';
    
        $this->init();
        return $this->indent.$str;
    }
}

class th
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<th'.$this->getAttrs().'>'.$this->text.'</td>';
        
        $this->init();
        return $this->indent.$str;
    }
}

class td
{
    use attribute;
    
    public static function ini()
    {
        return new self;
    }
    
    public function out()
    {
        $str = '<td'.$this->getAttrs().'>'.$this->text.'</td>';
        
        $this->init();
        return $this->indent.$str;
    }
}

class div
{
    use attribute;
    
    public $arrChild = array();
    
    public static function ini()
    {
        return new self;
    }
    
    public function append($child)
    {
        $this->arrChild[] = $child;
        return $this;
    }
    
    public function out()
    {
        $str = '<div'.$this->getAttrs().'>'.$this->eol;
        
        foreach ($this->arrChild as $ck => $child) {
            $child->indent .= $this->indent;
            $this->arrChild[$ck] = $child->out();
        }
        
        $str .= implode($this->eol, $this->arrChild);
        
        $str .= $this->eol.$this->indent.'</div>';
        
        return $this->indent.$str;
    }
}

//自定义闭合标签
class tag
{
    use attribute;
    
    public $arrChild = array();
    
    public static function ini($tag)
    {
        return new tag($tag);
    }
    
    public function __construct($tag)
    {
        $this->tag = $tag;
    }
    
    public function append($child)
    {
        $this->arrChild[] = $child;
        return $this;
    }
    
    public function out()
    {
        $str = '<'.$this->tag.$this->getAttrs().'>'.$this->eol;
        
        foreach ($this->arrChild as $ck => $child) {
            $child->indent .= $this->indent;
            $this->arrChild[$ck] = $child->out();
        }
        
        $str .= implode($this->eol, $this->arrChild);
        
        $str .= $this->eol.$this->indent."</{$this->tag}>";
        
        return $this->indent.$str;
    }
}

class body
{
    use attribute;
    
    public $arrChild = array();
    
    /**
     * @param mixed $child 子元素对象, 必须有out方法, 并且返回HTML文件
     */
    public function append($child)
    {
        $this->arrChild[] = $child->out();
    }
    
    public function out()
    {
        $str = '<body>'.$this->eol;
        $str .= implode($this->eol.$this->eol, $this->arrChild);
        $str .= $this->eol.'</body>';
    
        return $str;
    }
}
