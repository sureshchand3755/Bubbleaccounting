<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once(__DIR__ . '/user/userAuth.php');
require_once(__DIR__ . '/user/admin.php');
require_once(__DIR__ . '/user/facility.php');
require_once(__DIR__ . '/user/user.php');
require_once(__DIR__ . '/user/active_client.php');
require_once(__DIR__ . '/user/aml.php');
require_once(__DIR__ . '/user/bubblemail.php');
require_once(__DIR__ . '/user/build_invoice.php');
require_once(__DIR__ . '/user/client_review.php');
require_once(__DIR__ . '/user/cm.php');
require_once(__DIR__ . '/user/crm.php');
require_once(__DIR__ . '/user/croard.php');
require_once(__DIR__ . '/user/financial.php');
require_once(__DIR__ . '/user/infile.php');
require_once(__DIR__ . '/user/invoice.php');
require_once(__DIR__ . '/user/keydocs.php');
require_once(__DIR__ . '/user/messageus.php');
require_once(__DIR__ . '/user/opening_balance.php');
require_once(__DIR__ . '/user/payep30.php');
require_once(__DIR__ . '/user/payment.php');
require_once(__DIR__ . '/user/payroll.php');
require_once(__DIR__ . '/user/project_tracking.php');
require_once(__DIR__ . '/user/qba.php');
require_once(__DIR__ . '/user/rct.php');
require_once(__DIR__ . '/user/receipt.php');
require_once(__DIR__ . '/user/request.php');
require_once(__DIR__ . '/user/statement.php');
require_once(__DIR__ . '/user/supplementary.php');
require_once(__DIR__ . '/user/supplier.php');
require_once(__DIR__ . '/user/supplier_invoice.php');
require_once(__DIR__ . '/user/ta.php');
require_once(__DIR__ . '/user/taskmanager.php');
require_once(__DIR__ . '/user/taskyear.php');
require_once(__DIR__ . '/user/timejob.php');
require_once(__DIR__ . '/user/timeme.php');
require_once(__DIR__ . '/user/twobill.php');
require_once(__DIR__ . '/user/yearend.php');
require_once(__DIR__ . '/user/calendar.php');
require_once(__DIR__ . '/user/allocations.php');