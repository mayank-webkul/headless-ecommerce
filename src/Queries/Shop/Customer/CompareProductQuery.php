<?php

namespace Webkul\GraphQLAPI\Queries\Shop\Customer;

use Illuminate\Database\Eloquent\Builder;
use Webkul\GraphQLAPI\Queries\BaseFilter;

class CompareProductQuery extends BaseFilter
{
    /**
     * Filter query for compare products.
     */
    public function __invoke(Builder $query, array $input): Builder
    {
        $customer = bagisto_graphql()->authorize();

        $query->leftJoin('product_flat', 'compare_items.product_id', '=', 'product_flat.product_id')
            ->where('compare_items.customer_id', $customer->id);

        $filters = [
            'compare_items.id'         => $input['id'] ?? null,
            'compare_items.product_id' => $input['product_id'] ?? null,
            'product_flat.price'       => $input['price'] ?? null,
        ];

        $query = $this->applyFilter($query, $filters);

        if (! empty($input['product_name'])) {
            $query->where('product_flat.name', 'like', '%'.$input['product_name'].'%');
        }

        return $query;
    }

    /**
     * Get the specified compare product.
     */
    public function getItem(Builder $query): Builder
    {
        $customer = bagisto_graphql()->authorize();

        return $query->where('customer_id', $customer->id);
    }
}
