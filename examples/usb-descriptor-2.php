<?php
require_once realpath(dirname(__FILE__) . '/../src/data/Descriptor.php');
use data\Descriptor;

class USBIADDescriptor extends Descriptor {
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bLength;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bDescriptorType;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bFirstInterface;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bInterfaceCount;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bFunctionClass;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bFunctionSubClass;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $bFunctionProtocol;
    
    /**
     * @descriptorField
     * @descriptorLength 1
     * @descriptorFormatter formatChar
     * @var int
     */
    private $iFunction;
    
    protected function formatChar($data) {
        return unpack('C', $data)[1];
    }
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