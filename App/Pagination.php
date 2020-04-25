<?php

namespace App;

class Pagination
{
    public $per_page;
    private $result = [];

    public function getPagination($per_page, $active_value, $query, $query_params = null) {

        $this->per_page = $per_page;

        if (is_array($query)) {
            $count = $this->getCountByArray($query, $query_params);
        } else {
            $count = $this->getCount($query, $query_params);
        }



        $pageNumb = ceil($count / $this->per_page);

        for ($i = 0; $i < $pageNumb; $i++) {
            if ($i * $this->per_page == $active_value) {
                $active = 1;
            } else {
                $active = 0;
            }
            $this->result[] = [
                'active' => $active,
                'order' => $i + 1,
                'start' => $i * $this->per_page
            ];

        }

        return $this->result;

    }

    public function getCountByArray($query, $query_params = null) {

        if (isset($query['limit'])) {
            unset($query['limit']);
        }

        $items = \App::$db->selectInner($query, true, $query_params);

        if (!empty($items)) {
            return count($items);
        } else {
            return 0;
        }

    }

    public function getCount($query, $query_params = null) {

        $query = explode('LIMIT', $query);

        $items = \App::$db->execute($query[0], $query_params);

        if (!empty($items)) {
            return count($items);
        } else {
            return 0;
        }

    }
}

