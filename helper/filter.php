<?php

class filter extends rex {

    private $table;
    private $search;
    private $additional_where;

    function set_table($val) {
        $this->table = $val;
    }

    function to_search($val) {
        $this->search = $val;
    }

    function add_where($val) {
        $this->additional_where = $val;
    }

    function render($select_cols = '*') {
        $this->db();
        $q = $this->db->query("SHOW COLUMNS FROM " . $this->table);
        $sql = ' select ' . $select_cols . ' from `' . $this->table . '` ';
        $where_clause = ' where (';
        foreach ($q->rows as $row) {
            if ($row['Extra'] != 'auto_increment') {//table id alma
                $where_clause.="`" . $this->table . "`.`" . $row['Field'] . "` like '%" . $this->search . "%' or ";
            }
        }
        $where_clause = substr($where_clause, 0, -3) . ') ' . $this->additional_where;
        return $sql . $where_clause;
    }

}

?>