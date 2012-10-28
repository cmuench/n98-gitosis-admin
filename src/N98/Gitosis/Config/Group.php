<?php

namespace N98\Gitosis\Config;

class Group implements ElementInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $members = array();

    /**
     * @var array
     */
    protected $writable = array();

    /**
     * @var array
     */
    protected $readonly = array();

    /**
     * @param string $name
     * @param array $data
     */
    public function __construct($name, array $data = array())
    {
        $this->name = $name;
        if (isset($data['members'])) {
            $this->members = array_unique(explode(' ', $data['members']));
            sort($this->members);
        }
        if (isset($data['readonly'])) {
            $this->readonly = array_unique(explode(' ', $data['readonly']));
            sort($this->readonly);
        }
        if (isset($data['writable'])) {
            $this->writable = array_unique(explode(' ', $data['writable']));
            sort($this->writable);
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $readonly
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }

    /**
     * @return array
     */
    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param array $writable
     */
    public function setWritable($writable)
    {
        $this->writable = $writable;
    }

    /**
     * @return array
     */
    public function getWritable()
    {
        return $this->writable;
    }

    /**
     * @param array $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array();

        if (isset($this->members) && count($this->members) > 0) {
            $data['members'] = implode(' ', $this->members);
        }

        if (isset($this->readonly) && count($this->readonly) > 0) {
            $data['readonly'] = implode(' ', $this->readonly);
        }

        if (isset($this->writable) && count($this->writable) > 0) {
            $data['writable'] = implode(' ', $this->writable);
        }

        return $data;
    }

}