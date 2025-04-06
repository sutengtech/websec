<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {
		// Check for permission to edit products
		if(!auth()->user()->hasPermissionTo('edit_products')) {
			abort(403, 'You do not have permission to edit products.');
		}

		$product = $product ?? new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {
		// Check for permission to edit products
		if(!auth()->user()->hasPermissionTo('edit_products')) {
			abort(403, 'You do not have permission to edit products.');
		}

		// Validation rules for the form
		$rules = [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric', 'min:0'],
	        'stock' => ['required', 'integer', 'min:0'],
	    ];
	    
	    // Add photo validation rule if there's a new photo
	    if ($request->hasFile('photo_file')) {
		        $rules['photo_file'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'];
	    } else {
	        // If updating existing product without new photo
	        if (!$product || !$product->exists) {
	            $rules['photo_file'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'];
	        }
	    }
	    
	    $this->validate($request, $rules);

		$product = $product ?? new Product();
		$oldStock = $product->exists ? $product->stock : 0;
		
		// Handle photo upload
		if ($request->hasFile('photo_file')) {
		    $file = $request->file('photo_file');
		    $fileName = time() . '_' . $file->getClientOriginalName();
		    
		    // Store in public/images directory
		    $file->move(public_path('images'), $fileName);
		    
		    // Update photo field with the file name
		    $request->merge(['photo' => $fileName]);
		}
		
		// Special handling for stock updates
		if ($product->exists && $oldStock != $request->stock) {
		    // Verify permission to manage stock
		    if (!auth()->user()->hasPermissionTo('manage_stock')) {
		        abort(403, 'You do not have permission to update product stock.');
		    }
		    
		    // Log stock change if needed
		    // For example: $this->logStockChange($product, $oldStock, $request->stock);
		}
		
		$product->fill($request->except('photo_file'));
		$product->save();

		return redirect()->route('products_list')->with('success', 'Product saved successfully.');
	}

	public function delete(Request $request, Product $product) {
		if(!auth()->user()->hasPermissionTo('delete_products')) {
			abort(403, 'You do not have permission to delete products.');
		}

		// Remove the product image if it exists
		if ($product->photo && file_exists(public_path('images/' . $product->photo))) {
		    unlink(public_path('images/' . $product->photo));
		}

		$product->delete();

		return redirect()->route('products_list')->with('success', 'Product deleted successfully.');
	}
} 