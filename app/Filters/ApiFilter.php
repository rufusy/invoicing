<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/10/2024
 * @time: 10:42 PM
 */

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    /**
     * Make sure what is in the filter and the operators are what we support.
     */
    protected array $safeParams = [];

    protected  array $columnMap = [];

    protected  array $operatorMap = [];

    public function transform(Request $request): array
    {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if(!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if(isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
