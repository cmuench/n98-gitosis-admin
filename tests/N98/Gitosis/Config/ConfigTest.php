<?php
/**
 * Copyright (c) 2012 Chistian Münch
 *
 * https://github.com/cmuench/n98-gitosis-admin
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
 *
 * @author Christian Münch <christian@muench-worms.de>
 */

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function readFile()
    {
        $file = __DIR__ . '/../../../_files/gitosis.conf';
        $reader = new N98\Gitosis\Config\Config($file);

        /**
         * Repositories
         */
        $repos = $reader->getRepositories();
        $this->assertCount(3, $repos); // 2 explicit declared and 1 implicit "gitosis-admin"
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
        $this->assertEquals('bar@example.com', $members[0]);
        $this->assertEquals('foo@example.com', $members[1]);

        // Add and remove writable
        $devGroup->addWritable('foo1234');
        $this->assertCount(2, $devGroup->getWritable());
        $devGroup->removeWritable('foo1234');
        $this->assertCount(1, $devGroup->getWritable());

        // Add and remove readonly
        $devGroup->addReadonly('foo1234');
        $this->assertCount(1, $devGroup->getReadonly());
        $devGroup->removeReadonly('foo1234');
        $this->assertCount(0, $devGroup->getReadonly());

        // add a already existing member
        $devGroup->addUser('foo@example.com');
        $this->assertCount(2, $devGroup->getMembers());

        // add a new user
        $devGroup->addUser('zoz@example.com');
        $this->assertCount(3, $devGroup->getMembers());

        // remove new user
        $devGroup->removeUser('zoz@example.com');
        $this->assertCount(2, $devGroup->getMembers());
    }
}