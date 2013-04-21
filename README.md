data\Descriptor
================
Binary-Data-Description(BDD) is a framework for abstraction of data structure.  
The aim of BDD is to parse concrete data with pre-defined data structure.


Requirements
================
Requires PHP-5.4 or higher.


Example
================
Write class that inherits Descriptor class to present pre-defined data structure.  
Here is an example.  
USB IAD(Interface Association Descriptor) is a kind of USB Descriptor.  
IAD is used to describe that two or more interfaces are associated to the same function.

__IAD structure__

<table>
    <tr>
        <th>Offset</th>
        <th>Field</th>
        <th>Size</th>
        <th>Value</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>0</td>
        <td>bLength</td>
        <td>1</td>
        <td>Number</td>
        <td>Size of this descriptor in bytes.</td>
    </tr>
    <tr>
        <td>1</td>
        <td>bDescriptorType</td>
        <td>1</td>
        <td>Constant</td>
        <td>INTERFACE ASSOCIATION Descriptor.</td>
    </tr>
    <tr>
        <td>2</td>
        <td>bFirstInterface</td>
        <td>1</td>
        <td>Number</td>
        <td>Interface number of the first interface that is associated with this function.</td>
        </tr>
    <tr>
        <td>3</td>
        <td>bInterfaceCount</td>
        <td>1</td>
        <td>Number</td>
        <td>Number of contiguous interfaces that are associated with this function.</td>
    </tr>
    <tr>
        <td>4</td>
        <td>bFunctionClass</td>
        <td>1</td>
        <td>Class</td>
        <td>Class code (assigned by USB-IF).    A value of zero is not allowed in this descriptor.    If this field is FFH,  the function class is vendor-specific. All other values are reserved for assignment by the USB-IF.</td>
    </tr>
    <tr>
        <td>5</td>
        <td>bFunctionSubClass</td>
        <td>1</td>
        <td>SubClass</td>
        <td>Subclass code (assigned by USB-IF). If the bFunctionClass field is not set to FFH all values are reserved for assignment by the USB-IF.</td>
    </tr>
    <tr>
        <td>6</td>
        <td>bFunctionProtocol</td>
        <td>1</td>
        <td>Protocol</td>
        <td>Protocol code (assigned by USB-IF). These codes are qualified by the values of the bFunctionClass and bFunctionSubClass fields.</td>
    </tr>
    <tr>
        <td>7</td>
        <td>iFunction</td>
        <td>1</td>
        <td>Index</td>
        <td>Index of string descriptor describing this function.</td>
    </tr>
</table>

___Presentation of IAD in BDD___
```php
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
```

There are some rule.
