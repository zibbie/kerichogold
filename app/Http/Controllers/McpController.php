<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Response;

class McpController extends Controller
{
    /**
     * Web Model Context Protocol (WebMCP) Proxy
     * Handles JSON-RPC requests from AI Agents.
     */
    public function handle(Request $request)
    {
        $json = $request->json()->all();

        if (!isset($json['method'])) {
            return response()->json(['error' => 'Invalid WebMCP request'], 400);
        }

        $method = $json['method'];
        $params = $json['params'] ?? [];

        try {
            $result = match ($method) {
                'list_tools' => $this->listTools(),
                'search_products' => $this->searchProducts($params),
                'get_product' => $this->getProduct($params),
                'get_categories' => $this->getCategories(),
                default => throw new \Exception("Method {$method} not supported"),
            };

            return response()->json([
                'jsonrpc' => '2.0',
                'result' => $result,
                'id' => $json['id'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'jsonrpc' => '2.0',
                'error' => ['code' => -32601, 'message' => $e->getMessage()],
                'id' => $json['id'] ?? null
            ], 400);
        }
    }

    protected function listTools()
    {
        return [
            [
                'name' => 'search_products',
                'description' => 'Search for products by name or category',
                'parameters' => ['query' => 'string']
            ],
            [
                'name' => 'get_product',
                'description' => 'Get detailed information and current price for a specific product ID',
                'parameters' => ['id' => 'integer']
            ],
            [
                'name' => 'get_categories',
                'description' => 'List all available shop categories',
            ]
        ];
    }

    protected function searchProducts($params)
    {
        $query = $params['query'] ?? '';
        
        return Product::search($query)
            ->where('status', true)
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price . ' PLN',
                'url' => route('product.details', $p->slug)
            ]);
    }

    protected function getProduct($params)
    {
        $id = $params['id'] ?? null;
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            throw new \Exception("Product not found");
        }
        
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => strip_tags($product->description),
            'price' => $product->price . ' PLN',
            'stock_status' => $product->quantity > 0 ? 'In Stock' : 'Out of Stock',
            'category' => $product->category?->name,
            'brand' => 'Nevro',
            'url' => route('product.details', $product->slug)
        ];
    }

    protected function getCategories()
    {
        return Category::where('status', true)
            ->orderBy('position', 'asc')
            ->get(['id', 'name', 'slug'])
            ->toArray();
    }
}
