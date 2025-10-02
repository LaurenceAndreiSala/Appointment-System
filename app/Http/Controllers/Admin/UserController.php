<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { return view('admin.users.index'); }
    public function create() { return view('admin.users.create'); }
    public function store(Request $request) { /* save user */ }
    public function edit($id) { return view('admin.users.edit'); }
    public function update(Request $request, $id) { /* update user */ }
    public function destroy($id) { /* delete user */ }
}

class NotificationController extends Controller
{
    public function index() { return view('admin.notifications.index'); }
    public function create() { return view('admin.notifications.create'); }
    public function store(Request $request) { /* send notif */ }
}

class ReportController extends Controller
{
    public function index() { return view('admin.reports.index'); }
}

class SystemLogController extends Controller
{
    public function index() { return view('admin.logs.index'); }
}
