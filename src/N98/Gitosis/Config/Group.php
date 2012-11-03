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
            $this->members = array_unique(explode(' ', trim($data['members'])));
            sort($this->members);
        }
        if (isset($data['readonly'])) {
            $this->readonly = array_unique(explode(' ', trim($data['readonly'])));
            sort($this->readonly);
        }
        if (isset($data['writable'])) {
            $this->writable = array_unique(explode(' ', trim($data['writable'])));
            sort($this->writable);
        }
    }

    /**
     * @param string $username
     * @return Group
     */
    public function addUser($username)
    {
        $this->members[] = $username;
        $this->members = array_unique($this->members);

        return $this;
    }

    /**
     * @param string $username
     * @return Group
     */
    public function removeUser($username)
    {
        if (($key = array_search($username, $this->members)) !== false) {
            unset($this->members[$key]);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @return Group
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;

        return $this;
    }

    /**
     * @return array
     */
    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param string $readonly
     * @return Group
     */
    public function addReadonly($readonly)
    {
        $this->readonly[] = $readonly;
        $this->readonly = array_unique($this->readonly);

        return $this;
    }

    /**
     * @param string $readonly
     * @return Group
     */
    public function removeReadonly($readonly)
    {
        if (($key = array_search($readonly, $this->readonly)) !== false) {
            unset($this->readonly[$key]);
        }

        return $this;
    }

    /**
     * @param array $writable
     * @return Group
     */
    public function setWritable($writable)
    {
        $this->writable = $writable;

        return $this;
    }

    /**
     * @param string $writable
     * @return Group
     */
    public function addWritable($writable)
    {
        $this->writable[] = $writable;
        $this->writable = array_unique($this->writable);

        return $this;
    }

    /**
     * @param string $writable
     * @return Group
     */
    public function removeWritable($writable)
    {
        if (($key = array_search($writable, $this->writable)) !== false) {
            unset($this->writable[$key]);
        }

        return $this;
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
     * @return Group
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param $repositoryName
     * @return bool
     */
    public function hasWriteAccessToRepository($repositoryName)
    {
        return in_array($repositoryName, $this->writable);
    }

    /**
     * @param $repositoryName
     * @return bool
     */
    public function hasReadonlyAccessToRepository($repositoryName)
    {
        return in_array($repositoryName, $this->readonly);
    }

    /**
     * Removes readonly and writeable access to a repository
     *
     * @param string $repositoryName
     * @return Grpoui
     */
    public function removeRepositoryAccess($repositoryName)
    {
        if ($this->hasReadonlyAccessToRepository($repositoryName)) {
            $this->removeReadonly($repositoryName);
        }
        if ($this->hasWriteAccessToRepository($repositoryName)) {
            $this->removeWritable($repositoryName);
        }

        return $this;
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