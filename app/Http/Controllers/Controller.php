<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Filter DataTable columns
     */
    protected function filterDatatable($datatables, $listColumn = [])
    {
        $whereFilter = function ($query, $column, $keyword) {
            return $query->orWhere($column, 'like', "%{$keyword}%");
        };

        foreach ($listColumn as $key => $value) {
            $datatables = $datatables->filterColumn($key, function ($query, $keyword) use ($key, $value, $whereFilter) {
                foreach ($value as $k => $v) {
                    $whereFilter($query, $v, $keyword);
                }
            });
        }

        $request = request();
        $orderIndex = $request->input('order.0.column');
        $columns = $request->input('columns');

        if (isset($columns[$orderIndex]['data'])) {
            $columnKey = $columns[$orderIndex]['data'];

            if (isset($listColumn[$columnKey])) {
                $sortColumn = $listColumn[$columnKey][0] ?? null;
                if ($sortColumn) {
                    $datatables->orderColumn($columnKey, function ($query, $direction) use ($sortColumn) {
                        $query->orderBy($sortColumn, $direction);
                    });
                }
            } else {
                // fallback jika tidak ada di mapping
                $datatables->orderColumn($columnKey, function ($query, $direction) use ($columnKey) {
                    $query->orderBy($columnKey, $direction);
                });
            }
        }

        return $datatables;
    }
}
