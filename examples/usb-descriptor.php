<?php
require_once realpath(dirname(__FILE__) . '/../src/data/Descriptor.php');
use data\Descriptor;
use data\DescriptorField;

$data = base64_decode('CAsAAg4DAAQ=');
$formatter = function($d) {return unpack('C', $d)[1];};
$usbIADDescriptor = new Descriptor();
$usbIADDescriptor->addField(new DescriptorField('bLength', 1, $formatter))
        ->addField(new DescriptorField('bDescriptorType', 1, $formatter))
        ->addField(new DescriptorField('bFirstInterface', 1, $formatter))
        ->addField(new DescriptorField('bInterfaceCount', 1, $formatter))
        ->addField(new DescriptorField('bFunctionClass', 1, $formatter))
        ->addField(new DescriptorField('bFunctionSubClass', 1, $formatter))
        ->addField(new DescriptorField('bFunctionProtocol', 1, $formatter))
        ->addField(new DescriptorField('iFunction', 1, $formatter));
$usbIADDescriptor->setData($data);

print 'bLength          : ' . $usbIADDescriptor->bLength . PHP_EOL;
print 'bDescriptorType  : ' . $usbIADDescriptor->bDescriptorType . PHP_EOL;
print 'bFirstInterface  : ' . $usbIADDescriptor->bFirstInterface . PHP_EOL;
print 'bInterfaceCount  : ' . $usbIADDescriptor->bInterfaceCount . PHP_EOL;
print 'bFunctionClass   : ' . $usbIADDescriptor->bFunctionClass . PHP_EOL;
print 'bFunctionSubClass: ' . $usbIADDescriptor->bFunctionSubClass . PHP_EOL;
print 'bFunctionProtocol: ' . $usbIADDescriptor->bFunctionProtocol . PHP_EOL;
print 'iFunction        : ' . $usbIADDescriptor->iFunction . PHP_EOL;