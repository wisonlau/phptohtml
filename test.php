<?php

include('./Html.php');

//生成头部
$html = Html::ini()->setLang('en')->setTitle('自动生成HTML文件')->setMetaName('keyword', '自动生成HTML')
    ->css('http://aaa.css')
    ->css('http://bbb.css')
    ->js('http://jquery1.js')
    ->js('http://jquery2.js')
    ->setHead();

//生成table
$data = [
    ['a', 'b', 'c', 'd'],
    ['a', 'b', 'c', 'd'],
    ['a', 'b', 'c', 'd'],
    ['a', 'b', 'c', 'd'],
    ['a', 'b', 'c', 'd'],
];

$body = new body();
$table = table::ini()->setClass('mytable');

foreach ($data as $k1 => $tds) {
    $tr = tr::ini()->setId('tr_'.$k1);
    foreach ($tds as $k2 => $v2) {
        $td = td::ini()->setText($v2)->setId('td_'.$k1.'_'.$k2);
        $tr->td($td);
    }
    $table->tr($tr);
}

//把table加入body
$body->append($table);

//生成form表单
$form = form::ini()->setId('myform')->setClass('formclass')->setMethod('post')->setAction('http://www.test5.com/submit');

//给form加入文本框
$form->append(comment::ini()->setText('这里是注释的内容'));
$form->append(input::ini()->setId('a1')->setType('hidden')->setName('a1')->setValue(1));
$form->append(input::ini()->setId('a2')->setType('text')->setName('a2')->setValue(2));

//给form加入下拉框
$form->append(label::ini()->setText('下拉选框'));
$select = select::ini()->setId('select_id');
$options = ['a1' => 1, 'a2' => 2, 'a3' => 3];
foreach ($options as $name => $value) {
    $option = option::ini()->setText($name)->setValue($value);
    $value == 2 && $option->setSelected(TRUE);
    $select->option($option);
}
$form->append($select);

//给form添加提交按钮
$input = input::ini()->setType('submit')->setValue('提交');
$form->append($input);

//把form添加到body
$body->append($form);

//div嵌套
$label = label::ini()->setText('哈哈哈');
$div1 = div::ini()->setClass('d1')->append($label);
$div2 = div::ini()->setClass('d2')->append($div1);
$div3 = div::ini()->setClass('d3')->append($div2);
$body->append($div3);

//自定义标签嵌套(span)
$label = label::ini()->setText('自定义');
$span1 = tag::ini('span')->setClass('d1')->append($label);
$span2 = tag::ini('span')->setClass('d2')->append($span1);
$span3 = tag::ini('span')->setClass('d3')->append($span2);
$body->append($span3);

//自定义标签嵌套(li)
$label = label::ini()->setText('自定义');
$ul = tag::ini('ul')->setClass('ul1');
$li1 = tag::ini('li')->append(label::ini()->setText('li1')); //这里不能直接调用setText()去给li添加文字
$li2 = tag::ini('li')->append(label::ini()->setText('li2'));
$ul->append($li1)->append($li2);
$body->append($ul);

//注释, 自定义缩进
$indent = str_repeat('    ', 2);
$comment = comment::ini()->setIndent($indent)->setText('这里是注释的内容');
$body->append($comment);


//将body加入html
$str = $html->setBody($body)->out();

//写入文件
file_put_contents('./test.html', $str);

//输出HTML文档
echo $str;

echo 'over~';
