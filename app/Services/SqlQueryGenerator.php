<?php

namespace App\Services;

class SqlQueryGenerator
{
    public function generateSqlQuery(array $data, array $duplicateKeys): string
    {
        $tables = $data['tables'];
        $joins = $data['joins'] ?? [];
        $tableAliases = [];
        $tableNames = [];
        $selects = [];
        $from = '';
        $joinClauses = [];

        // Assign aliases for tables if same table appears more than once
        foreach ($tables as $tableDef) {
            foreach ($tableDef as $table => $columns) {
                $count = array_count_values($tableNames)[$table] ?? 0;
                $alias = $table.($count > 0 ? $table.str_repeat('s', $count) : '');
                $tableAliases[] = $alias;
                $tableNames[] = $table;
            }
        }

        // Build SELECT clause
        $tableIdx = 0;
        foreach ($tables as $tableDef) {
            foreach ($tableDef as $table => $columns) {
                $alias = $tableAliases[$tableIdx];
                foreach ($columns as $col) {
                    if (in_array($col, $duplicateKeys)) {
                        $selects[] = "$alias.$col as {$alias}_{$col}";
                    } else {
                        $selects[] = "$alias.$col";
                    }
                }
                $tableIdx++;
            }
        }

        // FROM clause
        $from = $tableAliases[0];

        // JOIN clauses
        foreach ($joins as $join) {
            $leftIdx = array_search($join['left_table'], $tableNames);
            $rightIdx = array_search($join['right_table'], array_slice($tableNames, $leftIdx + 1));
            $rightIdx = $rightIdx === false ? $leftIdx + 1 : $rightIdx + $leftIdx + 1;
            $leftAlias = $tableAliases[$leftIdx];
            $rightAlias = $tableAliases[$rightIdx];
            $joinType = $join['join_type'];
            $joinClause = "$joinType JOIN {$join['right_table']} $rightAlias ON $leftAlias.{$join['left_column']} = $rightAlias.{$join['right_column']} ";
            $joinClauses[] = $joinClause;
        }

        $sql = 'SELECT '.implode(', ', $selects).' FROM '.$from;
        if ($joinClauses) {
            $sql .= ' '.implode(' ', $joinClauses);
        }

        return $sql;
    }
}
