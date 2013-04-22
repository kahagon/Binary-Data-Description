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
    
    /**
     *
     * @var callable
     */
    private $dataFormatter;
    
    /**
     * 
     * @param int $name this field name
     * @param int $length this field length
     * @param callable $dataFormatter function for formatting raw data
     */
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
     * @throws \Exception throws Exception if size of data is shorter than field length
     */
    public function setData($data, $offset = 0) {
        if (strlen($data) < $this->length) {
            throw new \Exception('data must be longer than defined data length(' . $this->length . ')' . PHP_EOL);
        }
        $this->data = substr($data, $offset, $this->length);
        return $this->length;
    }
    
    /**
     * return this field length
     * @return int
     */
    public function getLength() {
        return $this->length;
    }
    
    /**
     * return this field name
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * return actual data of this field
     * @return mixed
     */
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
    
    /**
     * 
     * @param string $data data to parse
     * @param int $offset the offset to apply data
     * @throws \Exception throws Exception if size of data is shorter than field total length
     */
    public function __construct($data = null, $offset = 0) {
        $reflection = new \ReflectionClass($this);
        $matches = [];
        $classDocComment = $reflection->getDocComment();
        $pattern = '/@property\s+(string|int|integer|float|double)\s+\$([_\w][_\w\d]*)\s+\\\data\\\DescriptorField\s+(\d+)/';
        $numMatches = preg_match_all($pattern, $classDocComment, $matches);
        if ($numMatches) {
            $fieldTypeList = $matches[1];
            $fieldNameList = $matches[2];
            $fieldLengthList = $matches[3];
            for ($i = 0; $i < $numMatches; $i++) {
                $name = $fieldNameList[$i];
                $length = $fieldLengthList[$i];
                $type = $fieldTypeList[$i];
                $formatter = null;
                switch ($type) {
                    case 'int':
                    case 'integer':
                        $formatter = $reflection->getMethod('formatInt')->getClosure();
                        break;
                    case 'float':
                        $formatter = $reflection->getMethod('formatFloat')->getClosure();
                        break;
                    case 'double':
                        $formatter = $reflection->getMethod('formatDouble')->getClosure();
                        break;
                    case 'long':
                        $formatter = $reflection->getMethod('formatLong')->getClosure();
                        break;
                    default:
                    case 'string':
                        break;
                }
                $this->addField(new DescriptorField($name, $length, $formatter));
            }
        }
        
        if ($data) $this->setData($data, $offset);
    }
    
    public static function formatFloat($data) {
        return self::format("f", $data);
    }
    
    public static function formatDouble($data) {
        return self::format("d", $data);
    }
    
    public static function formatLong($data) {
        return self::format("l", $data);
    }
    
    public static function formatInt($data) {
        return self::format("i", $data);
    }
    
    public static function format($format, $data) {
        $dataLength = strlen($data);
        if ($dataLength < 4) {
            $lack = 4 - $dataLength;
            for ($i = 0; $i < $lack; $i++) {
                $data = $data . "\x00";
            }
        }
        
        return unpack($format, $data)[1];
    }
    
    /**
     * 
     * @param \data\DescriptorField $field
     * @return \data\Descriptor
     * @throws \Exception throws Exception if given field name already exists
     */
    public function addField(DescriptorField $field) {
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
     * @throws \Exception throws Exception if size of data is shorter than field total length
     */
    public function setData($data, $offset = 0) {
        $length = $this->getLength();
        if (strlen($data) < $length) {
            throw new \Exception('data length must be longer than sum of fileds length(' . $length . ')' . PHP_EOL);
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
    public function getField($index) {
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
    public function getFieldByIndex($index) {
        return $this->fields[$index];
    }
    
    /**
     * 
     * @param string $name
     * @return \data\DescriptorField
     */
    public function getFieldByName($name) {
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
    public function getLength() {
        $length = 0;
        foreach ($this->fields as $field) {
            $length += $field->getLength();
        }
        return $length;
    }

    public function getIterator() {
        return new \ArrayIterator($this->fields);
    }
    
    public function unpack($format, $data) {
        return unpack($format, $data);
    }
}
