<?php
namespace App\Http\Controllers\Web;

use App\Models\Purchase;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{

	use ValidatesRequests;

	public function __construct()
	{
		$this->middleware('auth:web')->except('list');
	}

	public function list(Request $request)
	{

		$query = Product::select("products.*");

		$query->when(
			$request->keywords,
			fn($q) => $q->where("name", "like", "%$request->keywords%")
		);

		$query->when(
			$request->min_price,
			fn($q) => $q->where("price", ">=", $request->min_price)
		);

		$query->when($request->max_price, fn($q) =>
			$q->where("price", "<=", $request->max_price));

		$query->when(
			$request->order_by,
			fn($q) => $q->orderBy($request->order_by, $request->order_direction ?? "ASC")
		);

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null)
	{
		if (!auth()->user()->hasRole('Employee')) {
			abort(403, 'Only employees can manage products.');
		}

		$product = $product ?? new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null)
	{
		if (!auth()->user()->hasRole('Employee')) {
			abort(403, 'Only employees can manage products.');
		}

		$this->validate($request, [
			'code' => ['required', 'string', 'max:32'],
			'name' => ['required', 'string', 'max:128'],
			'model' => ['required', 'string', 'max:256'],
			'description' => ['required', 'string', 'max:1024'],
			'price' => ['required', 'numeric'],
			'photo' => ['required', 'string', 'max:255'],
			'stock' => ['required', 'integer', 'min:0'],
		]);

		$product = $product ?? new Product();
		$product->fill($request->all());
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product)
	{
		if (!auth()->user()->hasRole('Employee')) {
			abort(403, 'Only employees can manage products.');
		}

		if (!auth()->user()->hasPermissionTo('delete_products')) {
			abort(401);
		}

		$product->delete();

		return redirect()->route('products_list');
	}
	public function purchase(Request $request, Product $product)
	{
		$user = auth()->user();

		// Check if the user is a customer
		if (!$user || !$user->hasRole('Customer')) {
			abort(403, 'Only customers can purchase products.');
		}

		// Check if the product is in stock
		if ($product->stock <= 0) {
			return redirect()->back()->withErrors('Product is out of stock.');
		}

		// Check if the user has enough credit
		if ($user->credit < $product->price) {
			return redirect()->back()->withErrors('Insufficient credit to purchase this product.');
		}

		// Deduct the price from the user's credit
		$user->credit -= $product->price;
		$user->save();

		// Reduce the product's stock
		$product->stock -= 1;
		$product->save();

		// Record the purchase
		$purchase = new Purchase();
		$purchase->user_id = $user->id;
		$purchase->product_id = $product->id;
		$purchase->price = $product->price;
		$purchase->save();

		return redirect()->route('products_list')->with('success', 'Product purchased successfully!');
	}
}