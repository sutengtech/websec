<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;

class PurchasesController extends Controller {

    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Display a listing of the customer's purchases
     */
    public function list(Request $request)
    {
        // Retrieve user's purchases with product details
        $purchases = Auth::user()->purchases()->with('product')->get();
        
        return view('purchases.list', compact('purchases'));
    }
    
    /**
     * Purchase a product
     */
    public function purchase(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $user = Auth::user();
        
        // Begin transaction to ensure all operations succeed or fail together
        DB::beginTransaction();
        
        try {
            // Check if user has sufficient credit
            $totalCost = $product->price * $quantity;
            
            if ($user->credit < $totalCost) {
                return redirect()->back()->with('error', 'Insufficient credit balance. Please add more credit to your account.');
            }
            
            // Check if product has sufficient stock
            if ($product->stock < $quantity) {
                return redirect()->back()->with('error', 'Not enough items in stock. Only ' . $product->stock . ' available.');
            }
            
            // Create purchase record
            $purchase = new Purchase();
            $purchase->user_id = $user->id;
            $purchase->product_id = $product->id;
            $purchase->quantity = $quantity;
            $purchase->total_price = $totalCost;
            $purchase->save();
            
            // Update product stock
            $product->stock -= $quantity;
            $product->save();
            
            // Deduct credit from user's balance
            $user->credit -= $totalCost;
            $user->save();
            
            DB::commit();
            
            return redirect()->route('profile')->with('success', 'Product purchased successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing your purchase. Please try again.');
        }
    }
    
    /**
     * Display the purchase page for a product
     */
    public function showPurchaseForm(Request $request, Product $product)
    {
        return view('purchases.form', compact('product'));
    }
    
    /**
     * Add credit to customer account (for Employees)
     */
    public function addCredit(Request $request, User $user)
    {
        // Check for required permission
        if (!Auth::user()->hasPermissionTo('manage_customer_credit')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Ensure we're only adding credit to Customer accounts
        if (!$user->hasRole('Customer')) {
            return redirect()->back()->with('error', 'Credit can only be added to customer accounts.');
        }
        
        // Validate to ensure a positive amount (any positive value)
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'min:0.01']
        ]);
        
        // Add the credit to the user's account
        $user->credit += $request->amount;
        $user->save();
        
        // Redirect back to the users list (customers list for employees)
        return redirect()->route('users')
            ->with('success', "Added $" . number_format($request->amount, 2) . " credit to {$user->name}'s account.");
    }
} 