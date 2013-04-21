<?php
namespace data;

class DescriptorField {
    
    /**
     *
     * @var string
     */
    private $name;
    
    /**
     *
     * @var int
     */
    private $length;
    
    /**
     *
     * @var string
     */
    private $data;
    
    private $dataFormatter;
    
    public function __construct($name, $length, callable $dataFormatter = null) {
        $this->name = $name;
        $this->length = $length;
        $this->dataFormatter = $dataFormatter;
    }
    
    /**
     * 
     * @param string $data
     * @param int $offset
     * @return int
     */
    public function setData($data, $offset = 0) {
        if (strlen($data) < $this->length) {
            throw new \Exception('data must be longer than defined data length(' . $this->length . ')' . PHP_EOL);
        }
        $this->data = substr($data, $offset, $this->length);
        return $this->length;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getData() {
        $dataFormatter = $this->dataFormatter;
        return $dataFormatter ? $dataFormatter($this->data) : $this->data;
    }
}

/**
 * Description of Descriptor
 *
 * @author oasynnoum
 */
class Descriptor implements \IteratorAggregate {
    /**
     *
     * @var array
     */
    private $fields = [];
    
    public final function __construct($data = null, $offset = 0) {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            $docComment = $property->getDocComment();
            if (!$docComment) continue;
            if (!preg_match('/@descriptorField/', $docComment)) continue;
            
            $matches = [];
            if (preg_match('/@descriptorLength\s+(\d+)/', $docComment, $matches)) {
                $length = (int)$matches[1];
                $name = $property->getName();
                $formatter = preg_match('/@descriptorFormatter\s+([_\w][_\w\d]+)/', $docComment, $matches) ? $matches[1] : null;
                $this->addField(new DescriptorField($name, $length, $formatter ? $reflection->getMethod($formatter)->getClosure($this) : null));
            }
        }
        
        if ($data) $this->setData($data, $offset);
    }
    
    /**
     * 
     * @param \data\DescriptorField $field
     * @return \data\Descriptor
     * @throws \Exception
     */
    public final function addField(DescriptorField $field) {
        foreach ($this->fields as $_field) {
            if ($_field->getName() == $field->getName()) {
                throw new \Exception('specified field name(' . $_field->getName() . ') is already exist');
            }
        }
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * 
     * @param string $data
     * @param int $offset
     * @throws \Exception
     */
    public final function setData($data, $offset = 0) {
        $lenght = $this->getLength();
        if (strlen($data) > $lenght) {
            throw new \Exception('data length must be longer than sum of fileds length(' . $lenght . ')' . PHP_EOL);
        }
        foreach ($this->fields as $field) {
            $offset += $field->setData($data, $offset);
        }
    }
    
    /**
     * 
     * @param int|string $index
     * @return \data\DescriptorField
     */
    public final function getField($index) {
        if (is_int($index)) {
            return $this->getFieldByIndex($index);
        } else {
            return $this->getFieldByName($index);
        }
    }
    
    /**
     * 
     * @param int $index
     * @return \data\DescriptorField
     */
    public final function getFieldByIndex($index) {
        return $this->fields[$index];
    }
    
    /**
     * 
     * @param string $name
     * @return \data\DescriptorField
     */
    public final function getFieldByName($name) {
        $field = null;
        foreach ($this->fields as $_field) {
            if ($_field->getName() == $name) {
                $field = $_field;
                break;
            }
        }
        return $field;
    }
    
    public function __set($name, $data) {
        $field = $this->getFieldByName($name);
        if (!$field) {
            throw new \Exception('specified field(' . $name . ') does not exist.' . PHP_EOL);
        }
        $field->setData($data);
    }
    
    public function __get($name) {
        $field = $this->getFieldByName($name);
        if (!$field) {
            throw new \Exception('specified field(' . $name . ') does not exist.' . PHP_EOL);
        }
        return $field ? $field->getData() : null;
    }

    /**
     * 
     * @return int
     */
    public final function getLength() {
        $length = 0;
        foreach ($this->fields as $field) {
            $length += $field->getLength();
        }
        return $length;
    }

    public function getIterator() {
        return new \ArrayIterator($this->fields);
    }
}
