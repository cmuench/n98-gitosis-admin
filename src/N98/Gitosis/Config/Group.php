<?php

namespace N98\Gitosis\Config;

class Group
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
    public function __construct($name, array $data)
    {
        $this->name = $name;
        if (isset($data['members'])) {
            $this->members = array_unique(explode(' ', $data['members']));
        }
        if (isset($data['readonly'])) {
            $this->readonly = array_unique(explode(' ', $data['readonly']));
        }
        if (isset($data['writable'])) {
            $this->writable = array_unique(explode(' ', $data['writable']));
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
}