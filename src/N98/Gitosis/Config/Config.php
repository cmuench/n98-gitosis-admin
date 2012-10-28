<?php

namespace N98\Gitosis\Config;

use Zend\Config\Config as ZendConfig;
use Zend\Config\Writer\Ini as Writer;

class Config
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var array
     */
    protected $gitosis;

    /**
     * @var array
     */
    protected $repos = array();

    /**
     * @var array
     */
    protected $groups = array();

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->readFile();
    }

    protected function readFile()
    {
        if (!file_exists($this->filename)) {
            throw new \RuntimeException('Gitosis config "' . $this->filename . '" file does not exist.');
        }

        $data = parse_ini_file($this->filename, true, INI_SCANNER_RAW);

        foreach ($data as $sectionName => $sectionData) {
            if ($sectionName == 'gitosis') {
                $sectionType = $sectionTypeName = 'gitosis';
            } else {
                list($sectionType, $sectionTypeName) = explode(' ', $sectionName);
            }

            switch ($sectionType) {
                case 'gitosis':
                    $this->gitosis = new Gitosis($sectionTypeName, $sectionData);
                    break;

                case 'repo':
                    $this->repos[$sectionTypeName] = new Repository($sectionTypeName, $sectionData);
                    break;

                case 'group':
                    $this->groups[$sectionTypeName] = new Group($sectionTypeName, $sectionData);
                    break;

                default:
            }
        }
        $this->addImplicitReposFromGroups();


        ksort($this->repos);
        ksort($this->groups);
    }

    /**
     * Adds repositories from group entries without an explicit repository section
     */
    protected function addImplicitReposFromGroups()
    {
        $implicitRepos = array();
        foreach ($this->groups as $group) {
            $implicitRepos = array_merge($group->getWritable(), $group->getReadonly(), $implicitRepos);
        }
        foreach ($implicitRepos as $implicitRepo) {
            if (!isset($this->repos[$implicitRepo])) {
                $this->repos[$implicitRepo] = new Repository($implicitRepo);
            }
        }
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Returns a single group by name
     *
     * @param string $name
     * @return Group
     */
    public function getGroup($name)
    {
        if (!isset($this->groups[$name])) {
            throw new \InvalidArgumentException('Group does not exist');
        }
        return $this->groups[$name];
    }

    /**
     * @return array
     */
    public function getRepositories()
    {
        return $this->repos;
    }

    /**
     * Returns a single repository by name
     *
     * @param string $name
     * @return Repository
     */
    public function getRepository($name)
    {
        if (!isset($this->repos[$name])) {
            throw new \InvalidArgumentException('Repository does not exist');
        }
        return $this->repos[$name];
    }

    /**
     * Save config
     */
    public function save()
    {
        $iniWriter = new Writer();
        $iniWriter->setNestSeparator(null);
        $iniString = $iniWriter->toString($this->buildData());
        $iniString = str_replace('"', '', $iniString);
        file_put_contents($this->filename, $iniString);
    }

    /**
     * Build array for config file generation
     *
     * @return array
     */
    protected function buildData()
    {
        $config = new ZendConfig(array(), true);

        $config->gitosis = $this->gitosis->toArray();

        foreach ($this->getGroups() as $group) {
            $config->{'group ' . $group->getName()} = $group->toArray();
        }

        foreach ($this->getRepositories() as $repository) {
            $config->{'repo ' . $repository->getName()} = $repository->toArray();
        }

        return $config;
    }
}