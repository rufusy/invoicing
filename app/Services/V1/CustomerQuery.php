<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/8/2024
 * @time: 12:52 PM
 */

namespace App\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery
{
    /**
     * Make sure what is in the filter and the operators are what we support.
     */
    protected array $safeParams = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt']
    ];

    protected  array $columnMap = [
        'postalCode' => 'postal_code'
    ];

    protected  array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];

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
