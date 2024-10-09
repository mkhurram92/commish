<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Reports\FmDirect\PipelinereportController;
use App\Http\Controllers\Admin\Reports\FmDirect\FmdirectController;
use App\Http\Controllers\Admin\Reports\Broker\BrokerReportsController;
use App\Http\Controllers\Admin\Reports\Referror\ReferrorReportsController;
use App\Http\Middleware\DatabaseSwitcher;

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

Route::group(['middleware' => ['admin', DatabaseSwitcher::class]], function () {
    // select2 routes
    Route::post('/deals/clients', 'App\Http\Controllers\Admin\DealsController@getClients')->name('deals.getClients');
    Route::prefix('admin')->group(function () {
        Route::get('dashboard-data', 'App\Http\Controllers\Admin\SettingController@getCounts')->name('admin.dashboard.counts');
        Route::get('dashboard', 'App\Http\Controllers\Admin\SettingController@dashboard')->name('admin.dashboard');
        Route::get('setting/profile', 'App\Http\Controllers\Admin\SettingController@profileSetting')->name('admin.setting.profile');
        Route::post('setting/profile/edit', 'App\Http\Controllers\Admin\SettingController@editProfileSetting')->name('admin.setting.profile.edit');
        //Contact Search
        Route::get('contact/list', 'App\Http\Controllers\Admin\ContactSearchController@contactList')->name('admin.contact.list');
        Route::post('contact/get-records', 'App\Http\Controllers\Admin\ContactSearchController@getRecords')->name('admin.contact.getrecords');
        Route::get('contact/add', 'App\Http\Controllers\Admin\ContactSearchController@contactAdd')->name('admin.contact.add');
        Route::get('contact/referrer_add', 'App\Http\Controllers\Admin\ContactSearchController@ReferrerAdd')->name('referrer_add');
        Route::post('contact/post', 'App\Http\Controllers\Admin\ContactSearchController@contactPost')->name('admin.contact.post');
        Route::get('contact/edit/{id}', 'App\Http\Controllers\Admin\ContactSearchController@contactEdit')->name('admin.contact.edit');
        Route::get('contact/view/{id}', 'App\Http\Controllers\Admin\ContactSearchController@contactView')->name('admin.contact.view');
        Route::get('referrer/view/{id}', 'App\Http\Controllers\Admin\ContactSearchController@contactViewReferrer')->name('admin.referrer.view');
        Route::post('contact/update/{id}', 'App\Http\Controllers\Admin\ContactSearchController@contactUpdate')->name('admin.contact.update');
        Route::get('contact/delete/{id}', 'App\Http\Controllers\Admin\ContactSearchController@contactDelete')->name('admin.contact.delete');
        Route::get('contact/get-single-deal-commissions', 'App\Http\Controllers\Admin\ContactSearchController@getSgDCommissions')->name('admin.contact.getcomdata');
        Route::post('get-commission', 'App\Http\Controllers\Admin\ContactSearchController@getCommission')->name('admin.contact.getcommission');

        //Contact Referred To
        Route::match(["get", "post"], "contact/referred-to/{contact_id}", 'App\Http\Controllers\Admin\ContactSearchController@addReferredTo')->name("admin.contact.addreferredto");
        Route::match(["get", "post"], "contact/referred-to/crud/{referred_to_id}", 'App\Http\Controllers\Admin\ContactSearchController@viewEditReferredTo')->name("admin.contact.viewEditReferredTo");
        //Contact relationships
        Route::match(["get", "post"], "contact/relationship/{contact_id}", 'App\Http\Controllers\Admin\ContactSearchController@addRelationship')->name("admin.contact.addrelationship");
        Route::match(["get", "post"], "contact/relationship/crud/{relationship_id}", 'App\Http\Controllers\Admin\ContactSearchController@viewEditRelationship')->name("admin.contact.viewEditRelationship");
        //Contact source of client
        Route::post("contact/source-of-client/{contact_id}", 'App\Http\Controllers\Admin\ContactSearchController@editSourceOfClient')->name("admin.contact.editSourceOfClient");
        //Contact Tasks
        Route::prefix('contact-tasks')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\Admin\ContactTasksController@brokerExpList')->name('admin.contacttsk.list');
            Route::post('get-records/{id}', 'App\Http\Controllers\Admin\ContactTasksController@getRecords')->name('admin.contacttsk.getrecords');
            Route::get('add/{id}', 'App\Http\Controllers\Admin\ContactTasksController@brokerAdd')->name('admin.contacttsk.add');
            Route::post('post/{id}', 'App\Http\Controllers\Admin\ContactTasksController@brokerPost')->name('admin.contacttsk.post');

            Route::post('delete', 'App\Http\Controllers\Admin\ContactTasksController@deleteTaskRecord')->name('admin.contacttsk.deleteTaskRecord');

            Route::get('edit/{id}/{bid}', 'App\Http\Controllers\Admin\ContactTasksController@brokerEdit')->name('admin.contacttsk.edit');
            Route::POST('get-record/{id}/{bid}', 'App\Http\Controllers\Admin\ContactTasksController@brokerGetRecord')
                ->name('admin.contacttsk.record');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\ContactTasksController@brokerView')->name('admin.contacttsk.view');
            Route::post('update/{id}/{bid}', 'App\Http\Controllers\Admin\ContactTasksController@brokertskUpdate')->name('admin.contacttsk.update');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\ContactTasksController@brokerDelete')->name('admin.contacttsk.delete');
        });
        // contact addresses
        Route::match(["get", "post"], "contact/address/crud/{contact_id}", 'App\Http\Controllers\Admin\ContactSearchController@viewEditAddress')->name("admin.contact.viewEditAddress");
        // Users
        Route::prefix('user')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\UserController@index')->name('admin.user');
            Route::post('/post', 'App\Http\Controllers\Admin\UserController@saveData')->name('admin.user.post');
            Route::get('/get-records', 'App\Http\Controllers\Admin\UserController@getRecords')->name('admin.user.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\UserController@getRecord')->name('admin.user.getrecord');
            Route::post('get-modules', 'App\Http\Controllers\Admin\UserController@getModules')->name('admin.user.getmodules');
            Route::post('save-modules', 'App\Http\Controllers\Admin\UserController@saveModules')->name('admin.user.savemodules');
            Route::post('update', 'App\Http\Controllers\Admin\UserController@updateData')->name('admin.user.update');
            Route::post('delete', 'App\Http\Controllers\Admin\UserController@deleteData')->name('admin.user.delete');
            Route::get('/permissions/{user_id}', 'App\Http\Controllers\Admin\PermissionController@permissionsData')->name('admin.user.permissions');

        });

        // Route for storing the permissions submitted via the form
        Route::post('/permissions/store', 'App\Http\Controllers\Admin\PermissionController@store')->name('permissions.store');


        //Entity
        Route::prefix('entity')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\EntityController@entityEdit')->name('admin.entity.edit');
            Route::post('contact/update', 'App\Http\Controllers\Admin\EntityController@entityUpdate')->name('admin.entity.update');
        });

        //Broker
        Route::prefix('brokers')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\BrokersController@brokerList')->name('admin.brokers.list');
            Route::post('get-records', 'App\Http\Controllers\Admin\BrokersController@getRecords')->name('admin.brokers.getrecords');
            Route::get('add', 'App\Http\Controllers\Admin\BrokersController@brokerAdd')->name('admin.brokers.add');
            Route::post('post', 'App\Http\Controllers\Admin\BrokersController@brokerPost')->name('admin.brokers.post');
            Route::get('edit/{id}', 'App\Http\Controllers\Admin\BrokersController@brokerEdit')->name('admin.brokers.edit');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\BrokersController@brokerView')->name('admin.brokers.view');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\BrokersController@brokerUpdate')->name('admin.brokers.update');
            Route::post('get-commission', 'App\Http\Controllers\Admin\BrokersController@getCommission')->name('admin.brokers.getcommission');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\BrokersController@brokerDelete')->name('admin.brokers.delete');
            Route::post('broker-staff/{broker_id}', 'App\Http\Controllers\Admin\BrokersController@addBrokerStaff')->name('admin.brokers.addBrokerStaff');
            Route::match(["GET", "POST"], 'broker-staff/edit/{broker_staff_id}', 'App\Http\Controllers\Admin\BrokersController@editBrokerStaff')->name('admin.brokers.editBrokerStaff');
            Route::post('broker-staff/delete/{broker_staff_id}', 'App\Http\Controllers\Admin\BrokersController@deleteBrokerStaff')->name('admin.brokers.deleteBrokerStaff');
        });

        //Broker Commissions
        Route::prefix('broker-commissions')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerExpList')->name('admin.brokercom.list');
            Route::post('get-records/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@getRecords')->name('admin.brokercom.getrecords');
            Route::get('add/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerAdd')->name('admin.brokercom.add');
            Route::post('post/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerPost')->name('admin.brokercom.post');
            Route::post('get-cm-ml/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@getCommissionModel')->name('admin.brokercom.getcmml');
            Route::get('edit/{id}/{bid}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerEdit')->name('admin.brokercom.edit');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerView')->name('admin.brokercom.view');
            Route::post('update/{id}/{bid}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokertskUpdate')->name('admin.brokercom.update');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@brokerDelete')->name('admin.brokercom.delete');
        });

        //Referrer Commissions
        Route::prefix('referrer-commissions')->group(function () {
            Route::post('get-cm-ml/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@getReferrerCommissionModel')->name('admin.referrercom.getcmml');
            Route::post('post/{id}', 'App\Http\Controllers\Admin\BrokerCommissionsController@referrerPost')->name('admin.referrercom.post');
        });

        //Broker Fees
        Route::prefix('broker-fees')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerExpList')->name('admin.brokerfee.list');
            Route::post('get-records/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@getRecords')->name('admin.brokerfee.getrecords');
            Route::get('add/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerAdd')->name('admin.brokerfee.add');
            Route::post('post/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerPost')->name('admin.brokerfee.post');
            Route::get('edit/{id}/{bid}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerEdit')->name('admin.brokerfee.edit');
            Route::post('edit_get/{id}/{bid}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerEditGet')->name('admin.brokerfee.edit_get');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerView')->name('admin.brokerfee.view');
            Route::post('update/{id}/{bid}', 'App\Http\Controllers\Admin\BrokerFeesController@brokertskUpdate')->name('admin.brokerfee.update');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\BrokerFeesController@brokerDelete')->name('admin.brokerfee.delete');
        });

        //Deals
        Route::prefix('deals')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\DealsController@dealList')->name('admin.deals.list');
            Route::get('get-records', 'App\Http\Controllers\Admin\DealsController@getRecords')->name('admin.deals.getrecords');
            Route::get('get-deal-commission', 'App\Http\Controllers\Admin\DealsController@getDealCommission')->name('getDealCommission');
            Route::post('update-deal-commission', 'App\Http\Controllers\Admin\DealsController@updateDealCommission')->name('updateDealCommission');
            Route::get('add', 'App\Http\Controllers\Admin\DealsController@dealAdd')->name('admin.deals.add');
            Route::post('post', 'App\Http\Controllers\Admin\DealsController@dealPost')->name('admin.deals.post');

            Route::get('edit/{id}', 'App\Http\Controllers\Admin\DealsController@dealEdit')->name('admin.deals.edit');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\DealsController@dealView')->name('admin.deals.view');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\DealsController@dealUpdate')->name('admin.deals.update');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\DealsController@dealDelete')->name('admin.deals.delete');
            Route::get('commissions', 'App\Http\Controllers\Admin\DealsController@commissions')->name('admin.deals.commissions');
            Route::post('importcommission', 'App\Http\Controllers\Admin\DealsController@commissionPost')->name('admin.deals.importcommission');
            Route::post('addactual/{id}', 'App\Http\Controllers\Admin\DealsController@addActual')->name('admin.deals.addactual');
            Route::get('getdealdata/{id}', 'App\Http\Controllers\Admin\DealsController@getDealData')->name('admin.deals.getdealdata');
            Route::get('get-commissions', 'App\Http\Controllers\Admin\DealsController@getCommissions')->name('admin.deals.getcommissions');
            Route::get('get-single-deal-commissions', 'App\Http\Controllers\Admin\DealsController@getSgDCommissions')->name('admin.deals.getcomdata');
            Route::get('get-single-deal-commissions/edit/{id}', 'App\Http\Controllers\Admin\DealsController@getSgDCommissionsEdit')->name('admin.deals.getcomdataedit');
            Route::post('get-single-deal-commissions/edit', 'App\Http\Controllers\Admin\DealsController@updateSgDCommissionsEdit')->name('admin.deals.updatecomdata');
            Route::get('get-brokers-record', 'App\Http\Controllers\Admin\DealsController@getBrokerComRecords')
                ->name('admin.deal.broker_record');

            Route::get('deal-missing', 'App\Http\Controllers\Admin\DealsController@dealMissingList')->name('admin.deals.dealMissingList');
            Route::get('deal-missing-progress/{id}', 'App\Http\Controllers\Admin\DealsController@updateProgress')->name('admin.deals.dealMissingList.progress');

            Route::post('get-deal-missing-records', 'App\Http\Controllers\Admin\DealsController@getdealMissingRecords')->name('admin.deals.getdealMissingRecords');
            Route::match(["GET", "POST"], 'edit-note/{note_id}', 'App\Http\Controllers\Admin\DealsController@editNote')->name('admin.deals.editNote');
            Route::match(["POST"], 'delete-note/{note_id}', 'App\Http\Controllers\Admin\DealsController@deleteNote')->name('admin.deals.deleteNote');
            Route::post('add-note/{deal_id}', 'App\Http\Controllers\Admin\DealsController@addNote')->name('admin.deals.addNote');
            Route::match(["GET", "POST"], 'add-relation/{deal_id}', 'App\Http\Controllers\Admin\DealsController@addRelation')->name('admin.deals.addRelation');
            Route::match(["GET", "POST"], 'edit-relation/{relationship}', 'App\Http\Controllers\Admin\DealsController@editRelation')->name('admin.deals.editRelation');
        });

        //Deals Tasks
        Route::prefix('deals-tasks')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\Admin\DealTasksController@brokerExpList')->name('admin.dealtsk.list');
            Route::post('get-records/{id}', 'App\Http\Controllers\Admin\DealTasksController@getRecords')->name('admin.dealtsk.getrecords');
            Route::get('add/{id}', 'App\Http\Controllers\Admin\DealTasksController@brokerAdd')->name('admin.dealtsk.add');
            Route::post('post/{id}', 'App\Http\Controllers\Admin\DealTasksController@brokerPost')->name('admin.dealtsk.post');
            Route::get('edit/{id}/{bid}', 'App\Http\Controllers\Admin\DealTasksController@brokerEdit')->name('admin.dealtsk.edit');

            Route::post('get-record/{id}/{bid}', 'App\Http\Controllers\Admin\DealTasksController@brokergetRecord')->name('admin.dealtsk.getRecord');
            Route::get('view/{id}', 'App\Http\Controllers\Admin\DealTasksController@brokerView')->name('admin.dealtsk.view');
            Route::post('update/{id}/{bid}', 'App\Http\Controllers\Admin\DealTasksController@brokertskUpdate')->name('admin.dealtsk.update');
            Route::get('delete/{id}', 'App\Http\Controllers\Admin\DealTasksController@brokerDelete')->name('admin.dealtsk.delete');
        });
        //ABP Search
        Route::prefix('abp')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ABPController@index')->name('admin.abp.index');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ABPController@getRecords')->name('admin.abp.getrecords');
            // Route::get('add', 'App\Http\Controllers\Admin\ABPController@insertData')->name('admin.abp.add');
            Route::post('post', 'App\Http\Controllers\Admin\ABPController@saveData')->name('admin.abp.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\ABPController@getRecord')->name('admin.abp.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ABPController@updateData')->name('admin.abp.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ABPController@deleteData')->name('admin.abp.delete');
        });

        //Commission
        Route::prefix('commission')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\CommissionController@index')->name('admin.commission');
            Route::post('post', 'App\Http\Controllers\Admin\CommissionController@saveData')->name('admin.commission.post');
        });
        Route::prefix('relationship')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\RelationshipController@index')->name('admin.relationship');
            Route::post('/get-records', 'App\Http\Controllers\Admin\RelationshipController@getRecords')->name('admin.relationship.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\RelationshipController@saveData')->name('admin.relationship.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\RelationshipController@getRecord')->name('admin.relationship.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\RelationshipController@updateData')->name('admin.relationship.update');
            Route::post('delete', 'App\Http\Controllers\Admin\RelationshipController@deleteData')->name('admin.relationship.delete');
        });
        //Industry
        Route::prefix('industry')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\IndustryController@index')->name('admin.industry');
            Route::post('post', 'App\Http\Controllers\Admin\IndustryController@saveData')->name('admin.industry.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\IndustryController@getRecords')->name('admin.industry.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\IndustryController@getRecord')->name('admin.industry.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\IndustryController@updateData')->name('admin.industry.update');
            Route::post('delete', 'App\Http\Controllers\Admin\IndustryController@deleteData')->name('admin.industry.delete');
        });

        //Lenders
        Route::prefix('lenders')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\LendersController@index')->name('admin.lenders');
            Route::post('post', 'App\Http\Controllers\Admin\LendersController@saveData')->name('admin.lenders.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\LendersController@getRecords')->name('admin.lenders.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\LendersController@getRecord')->name('admin.lenders.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\LendersController@updateData')->name('admin.lenders.update');
            Route::post('delete', 'App\Http\Controllers\Admin\LendersController@deleteData')->name('admin.lenders.delete');
        });

        //Lenders Commission Schedule
        Route::prefix('lenders-commission-schedules')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@index')->name('admin.lendercommissionschedule');
            Route::post('post', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@saveData')->name('admin.lendercommissionschedule.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@getRecords')->name('admin.lendercommissionschedule.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@getRecord')->name('admin.lendercommissionschedule.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@updateData')->name('admin.lendercommissionschedule.update');
            Route::post('delete', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@deleteData')->name('admin.lendercommissionschedule.delete');
            Route::post('get-single-commission', 'App\Http\Controllers\Admin\LenderCommissionSchedulesController@getSgCommission')->name('admin.lendercommissionschedule.getsingle');
        });

        //Refferor Commission Schedule
        Route::prefix('refferor-commission-schedules')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@index')->name('admin.refferorcommissionschedule');
            Route::post('post', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@saveData')->name('admin.refferorcommissionschedule.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@getRecords')->name('admin.refferorcommissionschedule.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@getRecord')->name('admin.refferorcommissionschedule.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@updateData')->name('admin.refferorcommissionschedule.update');
            Route::post('delete', 'App\Http\Controllers\Admin\RefferorCommissionSchedulesController@deleteData')->name('admin.refferorcommissionschedule.delete');
        });

        //Products
        Route::prefix('products')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ProductController@index')->name('admin.products');
            Route::post('post', 'App\Http\Controllers\Admin\ProductController@saveData')->name('admin.products.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ProductController@getRecords')->name('admin.products.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\ProductController@getRecord')->name('admin.products.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ProductController@updateData')->name('admin.products.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ProductController@deleteData')->name('admin.products.delete');
        });


        //Commission Type
        Route::prefix('commission-types')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\CommissionTypesController@index')->name('admin.commissiontypes');
            Route::post('post', 'App\Http\Controllers\Admin\CommissionTypesController@saveData')->name('admin.commissiontypes.post');
            Route::post('/get-records', 'App\Http\Controllers\Admin\CommissionTypesController@getRecords')->name('admin.commissiontypes.getrecords');
            Route::post('get-record', 'App\Http\Controllers\Admin\CommissionTypesController@getRecord')->name('admin.commissiontypes.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\CommissionTypesController@updateData')->name('admin.commissiontypes.update');
            Route::post('delete', 'App\Http\Controllers\Admin\CommissionTypesController@deleteData')->name('admin.commissiontypes.delete');
        });

        //ExpenseType
        Route::prefix('expensetype')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ExpenseTypeController@index')->name('admin.expensetype');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ExpenseTypeController@getRecords')->name('admin.expensetype.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\ExpenseTypeController@saveData')->name('admin.expensetype.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\ExpenseTypeController@getRecord')->name('admin.expensetype.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ExpenseTypeController@updateData')->name('admin.expensetype.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ExpenseTypeController@deleteData')->name('admin.expensetype.delete');
        });

        //ExpenseType
        Route::prefix('service')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ServiceController@index')->name('admin.service');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ServiceController@getRecords')->name('admin.service.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\ServiceController@saveData')->name('admin.service.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\ServiceController@getRecord')->name('admin.service.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ServiceController@updateData')->name('admin.service.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ServiceController@deleteData')->name('admin.service.delete');
        });

        //ReferralSource
        Route::prefix('referral')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ReferralSourceController@index')->name('admin.referral');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ReferralSourceController@getRecords')->name('admin.referral.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\ReferralSourceController@saveData')->name('admin.referral.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\ReferralSourceController@getRecord')->name('admin.referral.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ReferralSourceController@updateData')->name('admin.referral.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ReferralSourceController@deleteData')->name('admin.referral.delete');
        });

        //LoanTypes
        Route::prefix('loantype')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\LoanTypesController@index')->name('admin.loantype');
            Route::post('/get-records', 'App\Http\Controllers\Admin\LoanTypesController@getRecords')->name('admin.loantype.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\LoanTypesController@saveData')->name('admin.loantype.savedata');
            Route::post('get-record', 'App\Http\Controllers\Admin\LoanTypesController@getRecord')->name('admin.loantype.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\LoanTypesController@updateData')->name('admin.loantype.update');
        });

        //Processor
        Route::prefix('processor')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\ProcessorsController@index')->name('admin.processor');
            Route::post('/get-records', 'App\Http\Controllers\Admin\ProcessorsController@getRecords')->name('admin.processor.getrecords');
            Route::post('post', 'App\Http\Controllers\Admin\ProcessorsController@saveData')->name('admin.processor.post');
            Route::post('get-record', 'App\Http\Controllers\Admin\ProcessorsController@getRecord')->name('admin.processor.getrecord');
            Route::post('update', 'App\Http\Controllers\Admin\ProcessorsController@updateData')->name('admin.processor.update');
            Route::post('delete', 'App\Http\Controllers\Admin\ProcessorsController@deleteData')->name('admin.processor.delete');
        });

        Route::prefix('reports')->group(function () {
            Route::prefix('fm-direct')->group(function () {
                Route::get('/', [FmdirectController::class, 'fmDirect'])
                    ->name('admin.fm_direct.index');
                //////////////////////FM Direct Pipeline///////////////////////////
                Route::get('/pipeline', [PipelinereportController::class, 'pipeline'])
                    ->name('admin.pipeline');
                Route::post(
                    '/preview-pipeline',
                    [PipelinereportController::class, 'getPreviewPipeline']
                )
                    ->name('admin.fm_direct.preview_pipeline');
                Route::post(
                    '/fm-direct/pipeline-records',
                    [PipelinereportController::class, 'exportPipelineRecords']
                )
                    ->name('admin.fm_direct.export_pipeline_records');
                /////////////FM Direct Pipeline///////////////////////////
                Route::get('/monthly-pipeline', [PipelinereportController::class, 'monthlyPipeline'])
                    ->name('admin.pipeline');
                Route::post(
                    '/preview-monthly-pipeline',
                    [PipelinereportController::class, 'getPreviewMonthlyPipeline']
                )
                    ->name('admin.fm_direct.get_preview_monthly_pipeline_records');
                Route::post(
                    '/fm-direct/export-monthly-pipeline-records',
                    [PipelinereportController::class, 'exportMonthlyPipelineRecords']
                )
                    ->name('admin.fm_direct.export_monthly_pipeline_records');
                ////////////////Clients//////////////////////////////////
                Route::get('/clients', [FmdirectController::class, 'clients'])
                    ->name('admin.fm_direct.clients');
                Route::post(
                    '/get-client-records',
                    [FmdirectController::class, 'getClientRecords']
                )
                    ->name('admin.fm_direct.get_client_records');
                Route::post(
                    '/export-client-records',
                    [FmdirectController::class, 'exportClientRecords']
                )
                    ->name('admin.fm_direct.export_client_records');
                Route::get('/deal-tasks', [PipelinereportController::class, 'dealTasks'])
                    ->name('admin.fm_direct.deal_tasks');
                Route::post(
                    '/get-deal-tasks-records',
                    [PipelinereportController::class, 'getDealTasks']
                )
                    ->name('admin.fm_direct.get_deal_tasks_records');
                Route::post(
                    '/export-deal-tasks-records',
                    [PipelinereportController::class, 'exportDealTasks']
                )
                    ->name('admin.fm_direct.export_deal_tasks_records');
                Route::get('/settled-deals', [FmdirectController::class, 'dealsSettled'])
                    ->name('admin.deals_settled');
                Route::post(
                    '/get-settled-deals-records',
                    [FmdirectController::class, 'getDealsSettledRecords']
                )
                    ->name('admin.fm_direct.get_deal_settled_records');
                Route::post(
                    '/export-pipeline-records',
                    [FmdirectController::class, 'exportDealsSettled']
                )
                    ->name('admin.fm_direct.export_deal_settled_records');
                //////////////////////////////////////////AIP Reports//////////////
                Route::get('/approved-in-principle', [FmdirectController::class, 'dealsAIP'])
                    ->name('admin.deals_aip');
                Route::post(
                    '/preview-approved-in-principle-records',
                    [FmdirectController::class, 'getDealsAIPRecords']
                )
                    ->name('admin.fm_direct.get_approved_in_principle_records');
                Route::post(
                    '/export-approved-in-principle-records',
                    [FmdirectController::class, 'exportAIPRecords']
                )
                    ->name('admin.fm_direct.export_approved_in_principle_records');
                //////////////////////////////////////Birthday//////////////////////
                Route::get('/birthdays', [FmdirectController::class, 'birthday'])
                    ->name('admin.fm_direct.birthdays');
                Route::post(
                    '/get-birthday-records',
                    [FmdirectController::class, 'getBirthdayRecords']
                )
                    ->name('admin.fm_direct.get_birthday_records');
                Route::post(
                    '/export-birthday-records',
                    [FmdirectController::class, 'exportBirthdayRecords']
                )
                    ->name('admin.fm_direct.export_birthday_record');
                ////////////////////////////////////Referrer Commission/////////////////
                Route::post(
                    '/referrer-commission-rating-preview-records',
                    [FmdirectController::class, 'getReferrerCommissionRatingPreviewRecords']
                )
                    ->name('admin.fm_direct.referrer_commission_rating_preview_records');
                Route::post(
                    '/referrer-commission-rating-preview',
                    [FmdirectController::class, 'getReferrerCommissionRatingPreview']
                )
                    ->name('admin.fm_direct.referrer_commission_rating_preview');
                Route::post(
                    '/export-referrer-commission-rating',
                    [FmdirectController::class, 'exportReferrerCommissionRating']
                )
                    ->name('admin.fm_direct.referrer_commission_rating_export');
                ////////////////////////////////////Upfront Commission/////////////////
                Route::post(
                    '/preview-upfront-outstanding-commission',
                    [FmdirectController::class, 'getUpfrontOutstandingPreview']
                )
                    ->name('admin.fm_direct.preview_upfront_outstanding');
                Route::post(
                    '/export-upfront-outstanding-records',
                    [FmdirectController::class, 'exportUpfrontOutstanding']
                )
                    ->name('admin.fm_direct.export_upfront_outstanding_records');
                ////////////////////////////////////Trail Commission/////////////////
                Route::post(
                    '/preview-trail-outstanding-commission',
                    [FmdirectController::class, 'getTrailOutstandingPreview']
                )
                    ->name('admin.fm_direct.preview_trail_outstanding');
                Route::post(
                    '/export-trail-outstanding-records',
                    [FmdirectController::class, 'exportTrailOutstanding']
                )
                    ->name('admin.fm_direct.export_trail_outstanding_records');
                ////////////////////////////////////Outstanding Commission/////////////////
                Route::post(
                    '/preview-outstanding-commission',
                    [FmdirectController::class, 'getOutstandingCommissionPreview']
                )
                    ->name('admin.fm_direct.preview_outstanding_commission');
                Route::post(
                    '/export-outstanding-commission-records',
                    [FmdirectController::class, 'exportOutstandingCommission']
                )
                    ->name('admin.fm_direct.export_outstanding_commission_records');
                ////////////////////////////////////LEADS DNP/////////////////
                Route::post(
                    '/deals_dnp_report',
                    [FmdirectController::class, 'getLeadsDNP']
                )
                    ->name('admin.fm_direct.get_deals_dnp_report');
                Route::post(
                    '/export_deals_dnp_report',
                    [FmdirectController::class, 'exportLeadsDNP']
                )
                    ->name('admin.fm_direct.export_deals_dnp_report');
                ////////////////////////////////////Track Deals/////////////////
                Route::post(
                    '/deals_to_track',
                    [FmdirectController::class, 'getDealTrack']
                )
                    ->name('admin.fm_direct.get_deals_to_track');
                Route::post(
                    '/export_deals_to_track',
                    [FmdirectController::class, 'exportDealTrack']
                )
                    ->name('admin.fm_direct.export_deals_to_track');
                ////////////////////////////////////Sales/////////////////
                Route::post(
                    '/sales',
                    [FmdirectController::class, 'getSales']
                )
                    ->name('admin.fm_direct.get_sales');
                Route::post(
                    '/export_sales',
                    [FmdirectController::class, 'exportSales']
                )
                    ->name('admin.fm_direct.export_sales');
                ////////////////////////////////////Referrer Commission Upfront/////////////////
                Route::post(
                    '/referrer_commission_upfront',
                    [FmdirectController::class, 'getReferrerCommissionUpfront']
                )
                    ->name('admin.fm_direct.get_referrer_commission_upfront');
                Route::post(
                    '/export_referrer_commission_upfront',
                    [FmdirectController::class, 'exportReferrerCommissionUpfront']
                )
                    ->name('admin.fm_direct.export_referrer_commission_upfront');
                ////////////////////////////////////Deal History/////////////////
                Route::post(
                    '/deals_history',
                    [FmdirectController::class, 'getDealsHistory']
                )
                    ->name('admin.fm_direct.get_deals_history');
                Route::post(
                    '/export_deals_history',
                    [FmdirectController::class, 'exportDealsHistory']
                )
                    ->name('admin.fm_direct.export_deals_history');
                ////////////////////////////////////Snapshot of Deals/////////////////
                Route::post(
                    '/get_snapshot_of_all_deals',
                    [FmdirectController::class, 'getSnapshotOfDeals']
                )
                    ->name('admin.fm_direct.get_snapshot_of_all_deals');
                Route::post(
                    '/export_snapshot_of_all_deals',
                    [FmdirectController::class, 'exportSnapshotOfDeals']
                )
                    ->name('admin.fm_direct.export_snapshot_of_all_deals');
                ////////////////////////////////////Commission Ranking/////////////////
                Route::post(
                    '/get_commission_ranking',
                    [FmdirectController::class, 'getCommissionRanking']
                )
                    ->name('admin.fm_direct.get_commission_ranking');
                Route::post(
                    '/export_commission_ranking',
                    [FmdirectController::class, 'exportCommissionRanking']
                )
                    ->name('admin.fm_direct.export_commission_ranking');
                ////////////////////////////////////Trail Discrepancies/////////////////
                Route::post(
                    '/get_trail_discrepancies',
                    [FmdirectController::class, 'getTrailDiscrepancies']
                )
                    ->name('admin.fm_direct.get_trail_discrepancies');
                Route::post(
                    '/export_trail_discrepancies',
                    [FmdirectController::class, 'exportTrailDiscrepancies']
                )
                    ->name('admin.fm_direct.export_trail_discrepancies');
                ////////////////////////////////////Upfront Discrepancies/////////////////
                Route::post(
                    '/get_upfront_discrepancies',
                    [FmdirectController::class, 'getUpfrontDiscrepancies']
                )
                    ->name('admin.fm_direct.get_upfront_discrepancies');
                Route::post(
                    '/export_upfront_discrepancies',
                    [FmdirectController::class, 'exportUpfrontDiscrepancies']
                )
                    ->name('admin.fm_direct.export_upfront_discrepancies');
            });
            Route::prefix('broker')->group(function () {
                Route::get('/', [BrokerReportsController::class, 'index'])
                    ->name('admin.broker.index');
                //////////////////////FM Direct Pipeline///////////////////////////
                Route::get('/referrer-commission-summary', [BrokerReportsController::class, 'referrerCommissionSummary'])
                    ->name('admin.broker.referrer_commission_summary');
                Route::post(
                    '/preview-referrer-commission-summary',
                    [BrokerReportsController::class, 'getReferrerCommissionSummary']
                )
                    ->name('admin.broker.preview_referrer_commission_summary');
                Route::post(
                    '/export-referrer-commission-summary',
                    [BrokerReportsController::class, 'exportReferrerCommissionSummary']
                )
                    ->name('admin.broker.export_referrer_commission_summary');
                /////////////FM Direct Pipeline///////////////////////////
                Route::post(
                    '/preview-broker-invoice',
                    [BrokerReportsController::class, 'getBrokerInvoice']
                )
                    ->name('admin.broker.preview_broker_invoice');
                Route::post(
                    '/export-broker-invoice',
                    [BrokerReportsController::class, 'exportBrokerInvoice']
                )
                    ->name('admin.broker.export_broker_invoice');
                /////////////FM Direct Pipeline///////////////////////////
                Route::post(
                    '/preview-performance-report',
                    [BrokerReportsController::class, 'getPerformanceRecords']
                )
                    ->name('admin.broker.get_performance_records');
                Route::post(
                    '/export-performance-report',
                    [BrokerReportsController::class, 'exportPerformanceRecords']
                )
                    ->name('admin.broker.export_performance_records');
                ///////////////////////////Commision Monitoring////////////////
                Route::post(
                    '/preview-commission-monitoring',
                    [BrokerReportsController::class, 'getCommissionMonitoringReport']
                )
                    ->name('admin.broker.preview_commission_monitoring_report');
                Route::post(
                    '/export-commission-monitoring',
                    [BrokerReportsController::class, 'exportCommissionMonitoringReport']
                )
                    ->name('admin.broker.export_commission_monitoring_report');
                //////////////////////////////////////Brokers List///////////////////////
                Route::post(
                    '/get-broker-records',
                    [BrokerReportsController::class, 'getBrokerRecords']
                )
                    ->name('admin.broker.get_broker_records');
                Route::post(
                    '/export-broker-records',
                    [BrokerReportsController::class, 'exportBrokerRecords']
                )
                    ->name('admin.broker.export_broker_records');
            });
            Route::prefix('bdm')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Reports\BDMReportsController::class, 'index'])
                    ->name('admin.bdm.index');
            });
            Route::prefix('lender')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'index'])
                    ->name('admin.lender.index');
                Route::post(
                    '/get-lender-records',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getLenderRecords']
                )
                    ->name('admin.lender.get_lender_records');
                Route::post(
                    '/export-lender-records',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'exportLenderRecords']
                )
                    ->name('admin.lender.export_lender_records');
                Route::post(
                    '/get-lender-reconciliation-records',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getLenderReconciliationRecords']
                )
                    ->name('admin.lender.get_lender_records');
                Route::post(
                    '/export-lender-reconciliation-records',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'exportLenderReconciliationRecords']
                )
                    ->name('admin.lender.export_lender_reconciliation_records');
                //////////////////////////////////////////////////////////////////
                Route::post(
                    '/get-trail-commission-not-received',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getTrailCommissionNotReceivedRecords']
                )
                    ->name('admin.lender.get_trail_commission_not_received');
                Route::post(
                    '/export-trail-commission-not-received',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'exportTrailCommissionNotReceivedRecords']
                )
                    ->name('admin.lender.export_trail_commission_not_received');
                //////////////////////////////////////////////////////////////////
                Route::post(
                    '/get-upfront-commission-not-received',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getUpfrontCommissionNotReceivedRecords']
                )
                    ->name('admin.lender.get_upfront_commission_not_received');
                Route::post(
                    '/export-upfront-commission-not-received',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'exportUpfrontCommissionNotReceivedRecords']
                )
                    ->name('admin.lender.export_upfront_commission_not_received');
                Route::post(
                    '/lender_commission_statement',
                    [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getLenderCommissionStatement']
                )
                    ->name('admin.lender.lender_commission_statement');
            });
            Route::prefix('outstanding')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Reports\LenderReportsController::class, 'getCommissionOtstanding'])
                    ->name('admin.outstanding.index');
            });
            Route::prefix('reconciliation')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Reports\ReconciliationReportsController::class, 'index'])
                    ->name('admin.reconciliation.index');
            });
            Route::prefix('referrors')->group(function () {
                Route::get('/', [ReferrorReportsController::class, 'index'])
                    ->name('admin.referrors.index');

                Route::get('/referror_invoice', [ReferrorReportsController::class, 'getReferrerInvoice'])
                    ->name('admin.referrors.referror_invoice');

                //Route::prefix('broker')->group(function () {
                //    Route::get('/', [BrokerReportsController::class, 'index'])
                //        ->name('admin.broker.index');

                //    Route::post('/preview-broker-invoice',
                //        [BrokerReportsController::class, 'getBrokerInvoice'])
                //        ->name('admin.broker.preview_broker_invoice');
            });
        });
    });
});
