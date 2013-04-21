<?php
require_once realpath(dirname(__FILE__) . '/../src/data/Descriptor.php');
use data\Descriptor;
use data\DescriptorField;

$data = 'こんにちは';
$descriptor = new Descriptor();
$descriptor->addField(new DescriptorField('ko', 3))
        ->addField(new DescriptorField('n', 3))
        ->addField(new DescriptorField('ni', 3))
        ->addField(new DescriptorField('ti', 3))
        ->addField(new DescriptorField('ha', 3));
$descriptor->setData($data);

foreach ($descriptor as $field) {
    var_dump($field->getData());
}