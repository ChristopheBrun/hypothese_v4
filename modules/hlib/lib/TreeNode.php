<?php

namespace app\modules\hlib\lib;

use yii\base\Component;


/**
 * Class Tree
 * @package app\modules\hlib\lib
 */
class TreeNode extends Component
{
    public $id = null;

    public $parentId = null;

    public $data = null;

    /** @var TreeNode[] */
    public $children = [];

    /**
     * @return bool
     */
    public function isRoot()
    {
        return is_null($this->parentId);
    }

    /**
     * @return bool
     */
    public function isLeaf()
    {
        return !$this->children;
    }

    /**
     * @param $childId
     * @return bool
     */
    public function hasChild($childId)
    {
        foreach ($this->children as $child) {
            if ($child->id == $childId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TreeNode $child
     * @param bool     $allowDuplicates
     * @return $this
     */
    public function addChild(TreeNode $child, $allowDuplicates = false)
    {
        if (!$allowDuplicates && $this->hasChild($child->id)) {
            return $this;
        }

        $child->parentId = $this->id;
        $this->children[] = $child;
        return $this;
    }

    /**
     * @param $childId
     * @return TreeNode|null
     */
    public function getChild($childId)
    {
        foreach ($this->children as $child) {
            if ($child->id == $childId) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @param $nodeId
     * @return TreeNode|null
     */
    public function getDescendant($nodeId)
    {
        if ($out = $this->getChild($nodeId)) {
            return $out;
        }

        foreach ($this->children as $child) {
            if ($out = $child->getDescendant($nodeId)) {
                return $out;
            }
        }

        return null;
    }

    /**
     * @param      $childId
     * @param bool $removeFirstInstanceOnly
     * @return $this
     */
    public function removeChild($childId, $removeFirstInstanceOnly = true)
    {
        foreach ($this->children as $i => $child) {
            if ($child->id == $childId) {
                unset($this->children[$i]);
                if ($removeFirstInstanceOnly) {
                    break;
                }
            }
        }

        $this->children = array_values($this->children);
        return $this;
    }

    /**
     * @param      $nodeId
     * @param bool $removeFirstInstanceOnly
     * @return $this
     */
    public function removeDescendant($nodeId, $removeFirstInstanceOnly = true)
    {
        $count = count($this->children);
        $this->removeChild($nodeId);
        if (count($this->children) < $count && $removeFirstInstanceOnly) {
            return $this;
        }

        foreach ($this->children as $child) {
            $child->removeDescendant($nodeId, $removeFirstInstanceOnly);
        }

        return $this;
    }

    /**
     * /**
     * @param array $keyMap
     * @return array
     */
    public function asArray(array $keyMap = ['id' => 'id', 'children' => 'children', 'data' => 'data'])
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $child->asArray($keyMap);
        }

        $out = [
            $keyMap['id'] => $this->id,
            $keyMap['children'] => $children,
        ];

        if ($keyMap['data']) {
            $out[$keyMap['data']] = $this->data;
        }

        return $out;
    }


}