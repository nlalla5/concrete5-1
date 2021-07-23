<?php

namespace Concrete\Tests\Application\UserInterface\ContextMenu;

use Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem;
use Concrete\Tests\TestCase;

class ContextMenuTest extends TestCase
{
    public function testBasicMenuDivider()
    {
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem(new \Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem());
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><div class="dropdown-divider"></div></div></div></div>', $html);
    }

    public function testBasicMenuLink()
    {
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem(new \Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem('http://concrete5.org', 'concrete5.org'));
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="http://concrete5.org">concrete5.org</a></div></div></div>', $html);
    }

    public function testAddTreeNodeCategory()
    {
        $category = new \Concrete\Core\Tree\Node\Type\Category();
        $category->treeNodeName = 'Test';
        $category->treeNodeID = 14;
        $item = new \Concrete\Core\Tree\Menu\Item\Category\AddCategoryItem($category);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="add-node" dialog-title="Add Category" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/add/category?treeNodeID=14">Add Category</a></div></div></div>', $html);
    }

    public function testAddTreeNodeTopic()
    {
        $category = new \Concrete\Core\Tree\Node\Type\Category();
        $category->treeNodeName = 'Test';
        $category->treeNodeID = 14;
        $item = new \Concrete\Core\Tree\Menu\Item\Topic\AddTopicItem($category);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="add-node" dialog-title="Add Topic" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/add/topic?treeNodeID=14">Add Topic</a></div></div></div>', $html);
    }

    public function testEditTreeNodeCategory()
    {
        $category = new \Concrete\Core\Tree\Node\Type\Category();
        $category->treeNodeName = 'Test';
        $category->treeNodeID = 20;
        $item = new \Concrete\Core\Tree\Menu\Item\Category\EditCategoryItem($category);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="edit-node" dialog-title="Edit Category" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/edit/category?treeNodeID=20">Edit Category</a></div></div></div>', $html);
    }

    public function testCloneTreeNode()
    {
        $topic = new \Concrete\Core\Tree\Node\Type\Topic();
        $topic->treeNodeName = 'Topic A';
        $topic->treeNodeID = 10;
        $item = new \Concrete\Core\Tree\Menu\Item\CloneItem($topic);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="clone-node" data-tree-node-id="10">Clone Topic</a></div></div></div>', $html);

        $category = new \Concrete\Core\Tree\Node\Type\Category();
        $category->treeNodeName = 'Cats';
        $category->treeNodeID = 5;
        $item = new \Concrete\Core\Tree\Menu\Item\CloneItem($category);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="clone-node" data-tree-node-id="5">Clone Category</a></div></div></div>', $html);
    }

    public function testEditTreeNodeTopic()
    {
        $topic = new \Concrete\Core\Tree\Node\Type\Topic();
        $topic->treeNodeName = 'Topic A';
        $topic->treeNodeID = 10;
        $item = new \Concrete\Core\Tree\Menu\Item\Topic\EditTopicItem($topic);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="edit-node" dialog-title="Edit Topic" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/edit/topic?treeNodeID=10">Edit Topic</a></div></div></div>', $html);
    }

    public function testDeleteTreeNode()
    {
        $topic = new \Concrete\Core\Tree\Node\Type\Topic();
        $topic->treeNodeName = 'Topic A';
        $topic->treeNodeID = 10;
        $item = new \Concrete\Core\Tree\Menu\Item\DeleteItem($topic);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="delete-node" dialog-title="Delete Topic" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/delete?treeNodeID=10">Delete</a></div></div></div>', $html);
    }

    public function testEditPermissionsTreeNode()
    {
        $topic = new \Concrete\Core\Tree\Node\Type\Category();
        $topic->treeNodeName = 'Dogs';
        $topic->treeNodeID = 3;
        $item = new \Concrete\Core\Tree\Menu\Item\EditPermissionsItem($topic);
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\PopoverMenu();
        $menu->addItem($item);
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="popover fade"><div class="arrow"></div><div class="popover-inner"><div class="dropdown-menu"><a class="dropdown-item" href="#" data-tree-action="edit-node" dialog-title="Edit Permissions" data-tree-action-url="http://www.dummyco.com/path/to/server/index.php/ccm/system/dialogs/tree/node/permissions?treeNodeID=3" dialog-width="520" dialog-height="450">Edit Permissions</a></div></div></div>', $html);
    }

    public function testBasicDropdownMenu()
    {
        $menu = new \Concrete\Core\Application\UserInterface\ContextMenu\DropdownMenu();
        $menu->addItem(new LinkItem('#', 'Testing'));
        $menu->addItem(new \Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem());
        $menu->addItem(new LinkItem('#', 'Testing'));
        $html = (string) $menu->getMenuElement();
        $this->assertEquals('<div class="dropdown-menu"><a class="dropdown-item" href="#">Testing</a><div class="dropdown-divider"></div><a class="dropdown-item" href="#">Testing</a></div>', $html);

    }
}
