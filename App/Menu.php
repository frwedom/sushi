<?php

namespace App;

class Menu
{

    public $table_name = prefix . 'menu';
    public $tree = [];

    public function __construct()
    {
        $this->tree = $this->getTree();
    }

    public function getTree($parentId = 0) {

        $query = [
            'select' => [
                ['id', true],
                ['connect', true],
                ['name', false],
                ['link', true],
                ['description', false],
                ['images', true],
            ],
            'first_table' => $this->table_name,
            'second_table' => $this->table_name . '_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ['active = 1', true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];

        $result = \App::$db->selectInner($query, true);

        $tree = $this->buildTree($result, $parentId);

        return $tree;
    }

    public function buildTree(array $elements, $parentId) {

        $branch = [];

        foreach ($elements as $element) {
            if ($element['connect'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
            }
        }

        return $branch;
    }

    public function search($array, $key, &$result) {

        if (is_array($array)) {
            foreach ($array as $k => $v) {

                if ($k == $key) {
                    $result[] = $array[$k];
                    break;
                } else {
                    $this->search($v['children'], $key, $result);
                }
            }
        }

        return;

    }

}

