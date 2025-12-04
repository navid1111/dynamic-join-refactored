<?php

namespace App\Services;

class ReportQueryBuilder
{
    public function build(array $reportConfig): string
    {
        $tables = $reportConfig['tables'];
        $joins = $reportConfig['joins'] ?? [];
        $selectColumns = [];
        $emptyMap = [];
        $aliasofTables = [];
        $tableNames = [];
        $currentItr = 0;

        // Detect duplicate keys across all tables
        $duplicateKeys = $this->duplicateKeys($reportConfig);

        // Build SELECT clause and aliases (matching original logic)
        foreach ($tables as $tableDef) {
            foreach ($tableDef as $tablename => $columns) {
                $tableNames[] = $tablename;
                if (isset($emptyMap[$tablename])) {
                    $emptyMap[$tablename]['count']++;
                } else {
                    $emptyMap[$tablename]['count'] = 1;
                }
                $tempAlias = '';
                for ($i = 0; $i < $emptyMap[$tablename]['count']; $i++) {
                    $tempAlias .= $tablename;
                }
                $aliasofTables[] = $tempAlias;

                // Use alias for column naming when table appears multiple times
                $columnPrefix = $tempAlias;

                foreach ($columns as $column) {
                    // Track columns per alias, not per table name
                    $trackKey = $tempAlias.'_'.$column;
                    if (isset($emptyMap[$trackKey])) {
                        $emptyMap[$trackKey]++;
                    } else {
                        $selectColumns[] = in_array($column, $duplicateKeys)
                            ? "{$columnPrefix}.$column as {$columnPrefix}_{$column}"
                            : "$tablename.$column";
                        $emptyMap[$trackKey] = 1;
                    }
                }
            }
            $currentItr++;
        }

        // Build JOIN clauses (matching original logic)
        $selectColumns = implode(', ', $selectColumns);
        $numberOfIteration = 1;
        $joinClauses = '';
        $tableAlias = $tableNames[0].' '.$aliasofTables[0];
        $joinClauses .= $tableAlias.' ';
        foreach ($joins as $join) {
            $joinClauses .= "{$join['join_type']} JOIN {$tableNames[$numberOfIteration]} {$aliasofTables[$numberOfIteration]}";
            // Use aliases for the ON clause
            $leftAlias = $aliasofTables[0];
            $rightAlias = $aliasofTables[$numberOfIteration];
            $onCommand = $join['join_type'] !== 'cross' ? " ON {$leftAlias}.{$join['left_column']} = {$rightAlias}.{$join['right_column']}" : '';
            $joinClauses .= $onCommand;
            $numberOfIteration++;
        }

        return "SELECT $selectColumns FROM $joinClauses";
    }

    protected function duplicateKeys(array $data): array
    {
        $duplicateKeys = [];
        $encounteredKeys = [];
        foreach ($data['tables'] as $tables) {
            foreach ($tables as $tablename => $columns) {
                foreach ($columns as $column) {
                    if (in_array($column, $encounteredKeys)) {
                        $duplicateKeys[] = $column;
                    } else {
                        $encounteredKeys[] = $column;
                    }
                }
            }
        }

        return $duplicateKeys;
    }
}
