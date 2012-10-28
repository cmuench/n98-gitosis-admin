<?php

namespace N98\Gitosis\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function readFile()
    {
        $file = __DIR__ . '/../../../_files/gitosis.conf';
        $reader = new Config($file);

        /**
         * Repositories
         */
        $repos = $reader->getRepositories();
        $this->assertCount(2, $repos);
        $this->assertInternalType('array', $repos);

        $repo1 = $reader->getRepository('repo1');
        $this->assertInstanceOf('N98\Gitosis\Config\Repository', $repo1);
        $this->assertEquals('repo1', $repo1->getName());
        $this->assertEquals('Sample description file', $repo1->getDescription());
        $this->assertEquals('foo@example.com', $repo1->getOwner());
        $this->assertFalse($repo1->getGitweb());
        $this->assertTrue($repo1->getDaemon());

        $repo2 = $reader->getRepository('repo-2-with-minus');
        $this->assertInstanceOf('N98\Gitosis\Config\Repository', $repo2);
        $this->assertEquals('repo-2-with-minus', $repo2->getName());
        $this->assertEquals('Sample description file for second repo', $repo2->getDescription());
        $this->assertEquals('bar@example.com', $repo2->getOwner());
        $this->assertTrue($repo2->getGitweb());
        $this->assertFalse($repo2->getDaemon());

        /**
         * Groups
         */
        $groups = $reader->getGroups();
        $this->assertCount(3, $groups);
        $this->assertInternalType('array', $groups);

        $devGroup = $reader->getGroup('developer');
        $writable = $devGroup->getWritable();
        $this->assertInternalType('array', $writable);
        $this->assertCount(1, $writable);
        $this->assertEquals('repo1', $writable[0]);
        $members = $devGroup->getMembers();
        $this->assertInternalType('array', $members);
        $this->assertCount(2, $members);
        $this->assertEquals('foo@example.com', $members[0]);
        $this->assertEquals('bar@example.com', $members[1]);
    }
}