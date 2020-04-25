<?php

namespace App;

class Breadcrumbs
{
    private $bread_start = [];

    public function getBreads($table_name, $translations_table, $id, $type) {

        if ($type == 0) {
            $ex_table_name = explode('_', $table_name);
            $table_name = prefix . $ex_table_name[1];

            $ex_translations_table_name = explode('_', $table_name);
            $translations_table = prefix . $ex_translations_table_name[1] . '_translations';
        }

        $query = [
            'select' => [
                ['connect', true],
                ['url', true],
                ['name', false],
            ],
            'first_table'   => $table_name,
            'second_table'  => $translations_table,
            'join'          => [
                ['id', '=', 'connect']
            ],
            'where'         => [
                ["id = :id", true, 'AND']
            ],
            'order'         => [
                ['id DESC', true],
            ],
        ];
        $query_params = [
            'id' => $id,
        ];

        $bread = \App::$db->selectInner($query, true, $query_params);

//        \App::$functions->dumper($this->getTree($table_name, 0));

        if (count($bread) > 0) {

            $row = $bread[0];
            $this->bread_start += [$row['url'] => $row['name']];



            for ($i = 0; $i < 20; $i++) {

                $query = [
                    'select' => [
                        ['connect', true],
                        ['url', true],
                        ['name', false],
                    ],
                    'first_table'   => $table_name,
                    'second_table'  => $translations_table,
                    'join'          => [
                        ['id', '=', 'connect']
                    ],
                    'where'         => [
                        ["id = :id", true, 'AND']
                    ],
                    'order'         => [
                        ['id DESC', true],
                    ],
                ];
                $query_params = [
                    'id' => $row['connect'],
                ];

                $subbread = \App::$db->selectInner($query, true, $query_params);

                if (count($subbread) > 0) {
                    foreach ($subbread as $row) {
                        $this->bread_start += [$row['url'] => $row['name']];
                    }
                } else {
                    break;
                }


            }
        }

        $first_bread = $this->getFirstBread($table_name)[0];

        $this->bread_start += [$first_bread['default_connect'] => $first_bread['name']];

        return array_reverse($this->bread_start, true);

    }

    public function getFirstBread($table_name) {
        $query = [
            'first_table' => prefix . 'admin_articles',
            'second_table' => prefix . 'admin_articles_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["controller = :controller", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'controller' => str_replace(prefix, "", $table_name)
        ];

        $article = \App::$db->selectInner($query, true, $query_params);

        return $article;
    }

    public function getTree($table_name, $parentId = 0) {

        $query = [
            'select' => [
                ['connect', true],
                ['url', true],
                ['name', false],
            ],
            'first_table' => $table_name,
            'second_table' => $table_name . '_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ['active = 1', true, 'AND'],
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

}

