<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use App\Order;
use Illuminate\Http\Request;
use Session;
use Auth;
use Stripe\Stripe;
use Stripe\Charge;


class ProductController extends Controller
{
    public function getIndex(){

        $products = Product::all();
        return view('shop.index', ['products' => $products]);

    }

    public function getAddToCart(Request $request, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->id);

        $request->session()->put('cart', $cart);
        //dd($request->session()->get('cart'));

        return redirect()->route('product.index');
    }

    public function getCart(){
        if (!Session::has('cart')){
            return view('shop.shopping-cart', ['products' => null]);
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('shop.shopping-cart', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }

    public function getCheckout(){
        //dd(session()->get('error'));
        if (!Session::has('cart')){
            return view('shop.shopping-cart', ['products' => null]);
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total =$cart->totalPrice;
        return view('shop.checkout', ['total' => $total]);
       // dd($e->getMessage());
    }

    public function postCheckout(Request $request){

        if (!Session::has('cart')){
            return redirect()->route('shop.shoppingCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        //
        Stripe::setApiKey('sk_test_0HK2Ch11Qt6wmMxq0GjVzB0D00WapQ3EPU');
//        dd(session()->get('error'));
        try{
            $charge = Charge::create(array([
                "amount" => $cart->totalPrice * 100,
                "currency" => "usd",
                "source" => $request->input('stripeToken'), // obtained with Stripe.js
                "description" => "Charge Test"
            ]));
            $order = new Order();
            $order->cart = serialize($cart);
            $order->address = $request->input('address');
            $order->name = $request->input('name');
            $order->paymend_id =$charge->id;

            Auth::user()->orders()->save($order);

        }catch (\Exception $e){
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }

        Session::forget('cart');
            return redirect()->route('product.index')->with('success', 'Successfully' );
    }
}
