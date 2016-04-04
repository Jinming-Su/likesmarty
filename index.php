<?php

require_once 'template.class.php';
//dirname取上级目录
$baseDir = str_replace('\\', '/', dirname(__FILE__));
$temp = new template($baseDir.'/source/',$baseDir.'/compiled/');

$temp->assign('pagetitle','山寨版的smarty');
$temp->assign('test',"女神你好");

$temp->getSourceTemplate('index');
$temp->compileTemplate();
$temp->display();