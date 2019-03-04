<?php
namespace App\Http\Controllers\Auth;

use App\Menu;
use App\Domain;

class AuthorizationChecker extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('authorize')->only('domainAuthorizationChecker');
        $this->middleware('authorizeMenu')->only('menuAuthorizationChecker');
    }

    public function domainAuthorizationChecker($domain) {
        return response()->json(false);
    }

    public function menuAuthorizationChecker($menu) {
        return response()->json(true);
    }
}