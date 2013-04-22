<?php
require_once realpath(dirname(__FILE__) . '/../src/data/Descriptor.php');
use data\Descriptor;

/**
 * @property int $bLength           \data\DescriptorField 1
 * @property int $bDescriptorType   \data\DescriptorField 1
 * @property int $bFirstInterface   \data\DescriptorField 1
 * @property int $bInterfaceCount   \data\DescriptorField 1
 * @property int $bFunctionClass    \data\DescriptorField 1
 * @property int $bFunctionSubClass \data\DescriptorField 1
 * @property int $bFunctionProtocol \data\DescriptorField 1
 * @property int $iFunction         \data\DescriptorField 1
 */
class USBIADDescriptor extends Descriptor {
    
}

$usbIADDescriptor = new USBIADDescriptor("\x08\x0b\x00\x02\x0e\x03\x00\x04");


print 'bLength          : ' . $usbIADDescriptor->bLength . PHP_EOL;
print 'bDescriptorType  : ' . $usbIADDescriptor->bDescriptorType . PHP_EOL;
print 'bFirstInterface  : ' . $usbIADDescriptor->bFirstInterface . PHP_EOL;
print 'bInterfaceCount  : ' . $usbIADDescriptor->bInterfaceCount . PHP_EOL;
print 'bFunctionClass   : ' . $usbIADDescriptor->bFunctionClass . PHP_EOL;
print 'bFunctionSubClass: ' . $usbIADDescriptor->bFunctionSubClass . PHP_EOL;
print 'bFunctionProtocol: ' . $usbIADDescriptor->bFunctionProtocol . PHP_EOL;
print 'iFunction        : ' . $usbIADDescriptor->iFunction . PHP_EOL;