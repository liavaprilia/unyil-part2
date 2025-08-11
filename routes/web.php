<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('welcome')->middleware('guest')->name('welcome');
Route::get('/home', [UserController::class, 'index'])->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(UserController::class)->group(function () {

        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/{id}', [UserController::class, 'orderDetail'])->name('order-detail');
        Route::put('/orders/{id}/confirm', [UserController::class, 'confirmOrder'])->name('order.confirm');

        Route::get('/products', function () {
            return view('products');
        })->name('products');

        Route::get('/product-detail/{id}', function ($id) {
            $data['product'] = \App\Models\Product::find($id);
            return view('product-detail', $data);
        })->name('product-detail');
    });

    // route cart
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        // update cart
        Route::post('/update-item', 'updateCart')->name('update');
        // delete cart
        Route::post('/delete-item', 'deleteCart')->name('destroy');
    });

    // route checkout
    Route::prefix('checkout')->name('checkout.')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        // Route::post('/process', 'processCheckout')->name('process');
    });

    Route::get('/api-provinces', function () {
        $response = Http::get('https://wilayah.id/api/provinces.json');
        return response()->json($response->json()['data']);
    });

    Route::post('/api-cities', function (Request $request) {
        $city = $request->city;
        if (str_contains($city, 'Kabupaten') || str_contains($city, 'Kota')) {
            $city = str_replace(['Kabupaten ', 'Kota '], '', $city);
        }
        $response = Http::asForm()->post('https://ongkoskirim.id/', [
            'submit' => $request->submit ?? 'from_city',
            'city' => $city,
        ]);

        $response = $response->json();
        if($response && isset($response[0])) {
            $response = response()->json([
                'code' => explode(';', $response[0])[1],
            ]);
        } else {
            $response = [];
        }
        // Kembalikan body response langsung ke frontend
        return $response;
    })->name('api.cities');

    // cities from wilayah.id
    Route::post('/api-cities-wilayah', function (Request $request) {
        $response = Http::asForm()->get('https://wilayah.id/api/regencies/'. $request->province_id .'.json', []);

        return response()->json($response->json()['data']);
    })->name('api.cities.wilayah');

    // api districts
    Route::post('/api-districts', function (Request $request) {
        $response = Http::asForm()->get('https://wilayah.id/api/districts/'. $request->city_id .'.json', []);

        return response()->json($response->json()['data']);
    })->name('api.districts.wilayah');

    // api sub-districts
    Route::post('/api-sub-districts', function (Request $request) {
        $response = Http::asForm()->get('https://wilayah.id/api/villages/'. $request->district_id .'.json', []);

        return response()->json($response->json()['data']);
    })->name('api.sub.districts.wilayah');

    Route::post('/api-check-ongkir', function (Request $request) {
        $response = Http::asForm()->post('https://ongkoskirim.id/', [
            'from_city_id' => $request->from_city_id,
            'to_city_id' => $request->to_city_id,
            'submit' => 'cekongkir',
            'weight' => $request->weight ?? 0, // Tambahkan berat jika ada
        ]);
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    })->name('api.checkongkir');

    // Route admin
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        // Route::get('/', function () {
        //     return view('admin.dashboard');
        // })->name('admin.dashboard');
        Route::resource('products',  ProductController::class);
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');


        Route::resource(('transactions'), TransactionController::class);
    });



    // });
    // Route::get('/admin/orders', function () {
    //     return view('admin.orders');
    // })->name('admin.orders');
    // Route::get('/admin/users', function () {
    //     return view('admin.users');
    // })->name('admin.users');
    // Route::get('/admin/settings', function () {
    //     return view('admin.settings');
    // })->name('admin.settings');
    // Route::get('/admin/analytics', function () {
    //     return view('admin.analytics');
    // })->name('admin.analytics');
    // Route::get('/admin/reports', function () {
    //     return view('admin.reports');
    // })->name('admin.reports');
    // Route::get('/admin/notifications', function () {
    //     return view('admin.notifications');
    // })->name('admin.notifications');
    // Route::get('/admin/support', function () {
    //     return view('admin.support');
    // })->name('admin.support');
    // Route::get('/admin/feedback', function () {
    //     return view('admin.feedback');
    // })->name('admin.feedback');
    // Route::get('/admin/roles', function () {
    //     return view('admin.roles');
    // })->name('admin.roles');
    // Route::get('/admin/permissions', function () {
    //     return view('admin.permissions');
    // })->name('admin.permissions');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
