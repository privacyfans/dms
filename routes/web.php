<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Index;
use App\Http\Livewire\Accordion;
use App\Http\Livewire\Alerts;
use App\Http\Livewire\Avatar;
use App\Http\Livewire\Background;
use App\Http\Livewire\Badge;
use App\Http\Livewire\Blog;
use App\Http\Livewire\Bloglist01;
use App\Http\Livewire\Bloglist02;
use App\Http\Livewire\Bloglist03;
use App\Http\Livewire\Bloglist04;
use App\Http\Livewire\Bloglist05;
use App\Http\Livewire\Border;
use App\Http\Livewire\Breadcrumbs;
use App\Http\Livewire\Buttons;
use App\Http\Livewire\Calendar;
use App\Http\Livewire\Cards;
use App\Http\Livewire\Carousel;
use App\Http\Livewire\ChartChartjs;
use App\Http\Livewire\ChartEchart;
use App\Http\Livewire\ChartFlot;
use App\Http\Livewire\ChartMorris;
use App\Http\Livewire\ChartPeity;
use App\Http\Livewire\ChartSparkline;
use App\Http\Livewire\Chat;
use App\Http\Livewire\Collapse;
use App\Http\Livewire\Contacts;
use App\Http\Livewire\Counters;
use App\Http\Livewire\Darggablecards;
use App\Http\Livewire\Display;
use App\Http\Livewire\Dropdown;
use App\Http\Livewire\Editprofile;
use App\Http\Livewire\Emptypage;
use App\Http\Livewire\Error404;
use App\Http\Livewire\Error500;
use App\Http\Livewire\Extras;
//use App\Http\Livewire\Faq;
use App\Http\Livewire\Flex;
use App\Http\Livewire\Forgot;
use App\Http\Livewire\FormAdvanced;
use App\Http\Livewire\FormEditor;
use App\Http\Livewire\FormElements;
use App\Http\Livewire\FormElementsSizes;
use App\Http\Livewire\FormInputmask;
use App\Http\Livewire\FormLayouts;
use App\Http\Livewire\FormUpload;
use App\Http\Livewire\FormValidation;
use App\Http\Livewire\FormWizards;
use App\Http\Livewire\Gallery;
use App\Http\Livewire\Height;
use App\Http\Livewire\Horizontal;
use App\Http\Livewire\Icons;
use App\Http\Livewire\Icons2;
use App\Http\Livewire\Icons3;
use App\Http\Livewire\Icons4;
use App\Http\Livewire\Icons5;
use App\Http\Livewire\Icons6;
use App\Http\Livewire\ImageCompare;
use App\Http\Livewire\Images;
use App\Http\Livewire\Invoice;
use App\Http\Livewire\ListGroup;
use App\Http\Livewire\Lockscreen;
use App\Http\Livewire\MailCompose;
use App\Http\Livewire\MailInbox;
use App\Http\Livewire\MailRead;
use App\Http\Livewire\MailSettings;
use App\Http\Livewire\MapLeaflet;
use App\Http\Livewire\MapVector;
use App\Http\Livewire\Margin;
use App\Http\Livewire\MediaObject;
use App\Http\Livewire\Modals;
use App\Http\Livewire\Navigation;
use App\Http\Livewire\Notification;
use App\Http\Livewire\Padding;
use App\Http\Livewire\Pagination;
use App\Http\Livewire\Popover;
use App\Http\Livewire\Position;
use App\Http\Livewire\Pricing;
use App\Http\Livewire\ProductCart;
use App\Http\Livewire\ProductCheckout;
use App\Http\Livewire\ProductDetails;
use App\Http\Livewire\Products;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Progress;
use App\Http\Livewire\Rangeslider;
use App\Http\Livewire\Rating;
use App\Http\Livewire\Reset;
use App\Http\Livewire\Search;
use App\Http\Livewire\Signin;
use App\Http\Livewire\Signup;
use App\Http\Livewire\Spinners;
use App\Http\Livewire\SweetAlert;
use App\Http\Livewire\TableBasic;
use App\Http\Livewire\TableData;
use App\Http\Livewire\Tabs;
use App\Http\Livewire\Tags;
use App\Http\Livewire\Thumbnails;
use App\Http\Livewire\Timeline;
use App\Http\Livewire\Toast;
use App\Http\Livewire\Todotask;
use App\Http\Livewire\Tooltip;
use App\Http\Livewire\Treeview;
use App\Http\Livewire\Typography;
use App\Http\Livewire\Underconstruction;
use App\Http\Livewire\Userlist;
use App\Http\Livewire\Vertical;
use App\Http\Livewire\WidgetNotification;
use App\Http\Livewire\Widgets;
use App\Http\Livewire\Width;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgressBarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchlistController;
use App\Http\Controllers\DataFilesController;
use App\Http\Controllers\CutOffController;
use App\Http\Controllers\BranchTboController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\LoginehrController;
use App\Http\Controllers\PerpanjanganJamController;
use App\Http\Controllers\PerpanjanganTboController;
use App\Http\Controllers\CutOffHqController;
use App\Http\Controllers\SettingTimeController;
use App\Http\Controllers\SettingAiController;
use App\Http\Controllers\LoanSearchController;
use App\Http\Controllers\MasterInstansiController;
use App\Http\Controllers\MasterKlasifikasiDebiturController;
use App\Http\Controllers\KebijakanSopController;
use App\Http\Controllers\InternalMemoController;
use App\Http\Controllers\PeraturanKebijakanController;

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
Auth::routes();
Route::post('loginehr', [LoginehrController::class,'login'])->name('loginehr');
//Route::post('logout', [LoginehrController::class,'logout'])->name('logout');
//Route::get('itemPdfView',array('as'=>'itemPdfView','uses'=>'ItemController@itemPdfView')); 
// Route::get('/', function () {
//     return view('livewire.index');
// });

Route::get('/loan-search-history', [LoanSearchController::class, 'index'])->name('loan.indexhistory');
Route::get('/loan-search-history/search', [LoanSearchController::class, 'search'])->name('loan.search-history');
Route::get('/loan-search-history/download/{filename}', [LoanSearchController::class, 'downloadFile'])->name('loan.download-history');
Route::post('/loan-search-history/download-all-history', [LoanSearchController::class, 'downloadAllFiles'])->name('loan.download-all-history');

Route::get('/', [HomeController::class,'index'])->name('index'); 
Route::get('/index', [HomeController::class,'index']); 
Route::get('/chart', [HomeController::class,'chart'])->middleware('checkRole')->name('chart'); 
Route::get('/test', [HomeController::class,'test']); 
Route::get('/unlock_loan', [HomeController::class,'unlock_loan']); 
Route::post('/unlock_loan', [HomeController::class,'unlock_loan'])->name('unlock_loan'); 
Route::get('/test-email', [HomeController::class,'testEmail'])->name('testEmail');
Route::get('/get-server-time', [HomeController::class,'getServerTime'])->name('getServerTime');
Route::get('/upload', [ProgressBarController::class, 'index']);

Route::post('/upload-doc-file', [ProgressBarController::class, 'uploadToServer']);

Route::get('data-file', function() {
    return view('data-file.list');
});

Route::get('detail-file', function() {
    return view('detail-file.list');
});

Route::get('upload-file', function() {
    return view('detail-file.upload');
});

Route::post('upload-file', function() {
    return view('detail-file.submit');
});

Route::resource('users', UserController::class);
Route::get('branch-search', [UserController::class,'branchSearch']);
Route::get('role-search', [UserController::class,'roleSearch']);
Route::resource('branch', BranchlistController::class);

Route::resource('master-instansi', MasterInstansiController::class);
Route::get('klasifikasi-search', [MasterInstansiController::class,'klasifikasiSearch']);
Route::resource('master-klasifikasi-debitur', MasterKlasifikasiDebiturController::class);

// T&C / Peraturan dan Kebijakan Routes
Route::resource('kebijakan-sop', KebijakanSopController::class);
Route::resource('internal-memo', InternalMemoController::class);
Route::delete('internal-memo-file/{fileId}', [InternalMemoController::class, 'deleteFile'])->name('internal-memo.delete-file');
Route::get('tc-peraturan-kebijakan', [PeraturanKebijakanController::class, 'index'])->name('peraturan-kebijakan.index');

Route::resource('loans', DataFilesController::class);
Route::get('show-new/{id}', [DataFilesController::class,'show_new'])->name('datafile.show_new');
Route::post('datafile/dokumen-store', [DataFilesController::class, 'dokumen_store'])->name('datafile.dokumen_store');
Route::get('/send_documents_batch', [DataFilesController::class,'send_documents_batch'])->name('datafile.send_documents_batch');
Route::get('/send-document/{id}', [DataFilesController::class,'send_document'])->name('datafile.send_document');
Route::get('/send-document-test/{id}', [DataFilesController::class,'send_document_test'])->name('datafile.send_document_test');
Route::get('show-pickup/{id}', [DataFilesController::class,'show_pickup'])->name('datafile.show_pickup');
Route::get('show-pickup-new/{id}', [DataFilesController::class,'show_pickup_new'])->name('datafile.show_pickup_new');
Route::get('pickup-count', [DataFilesController::class, 'pickup_count'])->name('pickup_count');
Route::get('list-pickup', [DataFilesController::class,'list_pickup'])->name('datafile.list_pickup');
Route::get('queue-reviewer', [DataFilesController::class,'queue_reviewer'])->name('datafile.queue_reviewer');
Route::get('queue-team-leader', [DataFilesController::class,'queue_team_leader'])->name('datafile.queue_team_leader');

// Admin Loan Lock Management Routes
Route::get('admin/locked-loans', [DataFilesController::class,'admin_locked_loans'])->name('admin.locked_loans');
Route::post('admin/force-unlock/{loan_app_no}', [DataFilesController::class,'admin_force_unlock'])->name('admin.force_unlock');
Route::post('admin/cleanup-legacy-locks', [DataFilesController::class,'admin_cleanup_legacy_locks'])->name('admin.cleanup_legacy_locks');
Route::get('pickup', [DataFilesController::class,'pickup'])->name('datafile.pickup.form');
Route::post('pickup', [DataFilesController::class,'pickup'])->name('datafile.pickup.submit');
Route::get('download-zip/{id}', [DataFilesController::class,'downloadZip'])->name('datafile.downloadZip');
Route::get('daily-transaction', [DataFilesController::class,'daily_transaction']);
Route::get('daily-spv1', [DataFilesController::class,'daily_spv1']);
Route::get('daily-bpr1', [DataFilesController::class,'daily_bpr1']);
Route::get('daily-bpr2', [DataFilesController::class,'daily_bpr2']);
Route::get('loanappno-search', [DataFilesController::class,'loanappnoSearch']);
Route::get('loanappno-search-all', [DataFilesController::class,'loanappnoSearchAll']);

Route::get('dataloan-search/{id}', [DataFilesController::class,'dataloanSearch']);
Route::get('dataloan-search-all/{id}', [DataFilesController::class,'dataloanSearchAll']);
Route::get('showpdf{id}', [DataFilesController::class,'showpdf'])->name('datafile.showpdf')->where('id', '(.*)');
Route::get('tbo', [DataFilesController::class,'tbo'])->name('datafile.tbo');
Route::get('download_tbo', [DataFilesController::class,'download_tbo'])->name('datafile.download_tbo');
Route::get('tbo_overdue', [DataFilesController::class,'tbo_overdue'])->name('datafile.tbo_overdue');
Route::get('tbo_overdue_pending', [DataFilesController::class,'tbo_overdue_pending'])->name('datafile.tbo_overdue_pending');
Route::get('download_tbo_overdue', [DataFilesController::class,'download_tbo_overdue'])->name('datafile.download_tbo_overdue');
Route::get('download_tbo_pending', [DataFilesController::class,'download_tbo_pending'])->name('datafile.download_tbo_pending');
Route::get('pending_tbo', [DataFilesController::class,'pending_tbo'])->name('datafile.pending_tbo');
Route::get('pending_tbo_overdue', [DataFilesController::class,'pending_tbo_overdue'])->name('datafile.pending_tbo_overdue');
Route::get('waiting_spv1', [DataFilesController::class,'waiting_spv1'])->name('datafile.waiting_spv1');
Route::get('waiting_spv2', [DataFilesController::class,'waiting_spv2'])->name('datafile.waiting_spv2');
Route::get('waiting_spv3', [DataFilesController::class,'waiting_spv3'])->name('datafile.waiting_spv3');
Route::get('waiting_tbo_spv2', [DataFilesController::class,'waiting_tbo_spv2'])->name('datafile.waiting_tbo_spv2');
Route::get('waiting_tbo_spv3', [DataFilesController::class,'waiting_tbo_spv3'])->name('datafile.waiting_tbo_spv3');

// Team Verifikator Routes
Route::get('waiting_verifikator_lvl1_recommendation', [DataFilesController::class,'waiting_verifikator_lvl1_recommendation'])->name('datafile.waiting_verifikator_lvl1_recommendation');
Route::get('waiting_verifikator_lvl1_upload', [DataFilesController::class,'waiting_verifikator_lvl1_upload'])->name('datafile.waiting_verifikator_lvl1_upload');
Route::get('waiting_verifikator_lvl2', [DataFilesController::class,'waiting_verifikator_lvl2'])->name('datafile.waiting_verifikator_lvl2');
Route::post('upload-bukti-verifikator', [DataFilesController::class,'uploadBuktiVerifikator'])->name('datafile.uploadBuktiVerifikator');

// Pending Disbursement Routes (SPV2, SPV3, SPV4)
Route::get('pending-disbursement', [DataFilesController::class,'pendingDisbursement'])->name('datafile.pendingDisbursement');
Route::post('flag-ready-to-disburs/{loan_app_no}', [DataFilesController::class,'flagReadyToDisburs'])->name('datafile.flagReadyToDisburs');

Route::get('kupen', [DataFilesController::class,'kupen'])->name('datafile.kupen');
Route::get('kupen-janda-duda', [DataFilesController::class,'kupenJandaDuda'])->name('datafile.kupenJandaDuda');
Route::get('kupen-hybrid', [DataFilesController::class,'kupenHybrid'])->name('datafile.kupenHybrid');
Route::get('kupen-hybrid-go', [DataFilesController::class,'kupengo'])->name('datafile.kupengo');
Route::get('kupeg', [DataFilesController::class,'kupeg'])->name('datafile.kupeg');
Route::get('kph', [DataFilesController::class,'kph'])->name('datafile.kph');
Route::get('tht', [DataFilesController::class,'tht'])->name('datafile.tht');
Route::get('tawon', [DataFilesController::class,'tawon'])->name('datafile.tawon');
Route::get('kpkb-wni', [DataFilesController::class,'kpkb_wni'])->name('datafile.kpkb_wni');
Route::get('kpkb-wna', [DataFilesController::class,'kpkb_wna'])->name('datafile.kpkb_wna');
Route::get('verify', [DataFilesController::class,'verify'])->name('datafile.verify');
Route::get('processverify/{id}', [DataFilesController::class,'processverify'])->name('datafile.processverify');
Route::get('loans/listdocs/{id}', [DataFilesController::class,'listdoc'])->name('datafile.listDoc');
Route::get('report', [DataFilesController::class,'report'])->name('datafile.report');
Route::post('loans/report-submit', [DataFilesController::class,'reportSubmit'])->name('datafile.reportSubmit');
Route::get('report-detail', [DataFilesController::class,'report_detail'])->name('datafile.report-detail');
Route::post('loans/report-detail-submit', [DataFilesController::class,'reportDetailSubmit'])->name('datafile.reportDetailSubmit');
Route::get('report-sla', [DataFilesController::class,'sla'])->middleware('checkRole')->name('datafile.sla');
Route::post('loans/sla-submit', [DataFilesController::class,'slaSubmit'])->name('datafile.slaSubmit');

Route::get('loans/deletedetailfile/{loan}/{id}', [DataFilesController::class,'deletedetailfile'])->name('datafile.deletedetailfile');
Route::get('show-new/deletedetailfile/{loan}/{id}', [DataFilesController::class,'deletedetailfilenew'])->name('datafile.deletedetailfilenew');
Route::get('search-loan', [DataFilesController::class,'searchLoan'])->name('datafile.searchLoan');
Route::post('loans/submit-search-loan', [DataFilesController::class,'submitsearchLoan'])->name('datafile.submitsearchLoan');
Route::post('loans/save-search-loan', [DataFilesController::class,'savesearchLoan'])->name('datafile.savesearchLoan');
Route::post('upload-file', [DataFilesController::class, 'uploadToServer'])->name('datafile.uploadfile');
Route::post('copy-file', [DataFilesController::class, 'copyToServer'])->name('datafile.copyfile');
Route::post('updateflag', [DataFilesController::class, 'updateFlag'])->name('datafile.updateflag');
Route::post('emptylabel', [DataFilesController::class, 'emptyLabel'])->name('datafile.emptyLabel');

Route::get('updatebasedata/{id}', [DataFilesController::class,'updateBaseData'])->name('datafile.updatebasedata');
Route::get('test-loan-api/{loanAppNo?}', [DataFilesController::class,'testLoanApi'])->name('datafile.testLoanApi');
Route::get('clear-api-cache', [DataFilesController::class,'clearApiCache'])->name('datafile.clearApiCache');
Route::get('test-debtor-fields/{loanAppNo?}', [DataFilesController::class,'testDebtorFields'])->name('datafile.testDebtorFields');
Route::get('getFlag', function () {
    $reason = App\Models\FlagSpv::All();
    return response()->json($reason);
});
Route::get('getReason/{id}', function ($id) {
    $reason = App\Models\Reason::where('id_flag',$id)->get();
    return response()->json($reason);
});
Route::get('getReview/{id}', function ($id) {
    $reason = App\Models\Comment::select('*')
                ->leftJoin("users", "comment.user_name", "=", "users.nik")
                ->where('comment.loan_app_no',$id)->get();
    return response()->json($reason);
});

//Route::resource('setting_time', SettingTimeController::class);
Route::get('setting-time/{id}', [SettingTimeController::class,'edit'])->name('setting_time.edit');
Route::put('setting-time/{id}', [SettingTimeController::class, 'update'])->name('setting_time.update');

Route::get('setting-ai/{id}', [SettingAiController::class,'edit'])->name('setting_ai.edit');
Route::put('setting-ai/{id}', [SettingAiController::class, 'update'])->name('setting_ai.update');

Route::resource('cut_off', CutOffController::class);
Route::get('cut_off/{id}/approve', [CutOffController::class,'approve'])->name('cut_off.approve');
Route::post('cut_off/approve-submit', [CutOffController::class,'approveSubmit'])->name('cut_off.approveSubmit');
Route::post('cut_off/approve-submit-list', [CutOffController::class,'approveSubmitList'])->name('cut_off.approveSubmitList');

Route::resource('perpanjangan_jam', PerpanjanganJamController::class);
Route::get('perpanjangan_jam/create', [PerpanjanganJamController::class,'create'])->name('perpanjangan_jam.create');
Route::post('perpanjangan_jam/store', [PerpanjanganJamController::class,'store']);
Route::get('perpanjangan_jam/{id}', [PerpanjanganJamController::class,'show']);
Route::post('perpanjangan_jam/approve-submit', [PerpanjanganJamController::class,'approveSubmit'])->name('perpanjangan_jam.approveSubmit');
Route::get('report-waktu-layanan', [PerpanjanganJamController::class,'waktu_layanan'])->middleware('checkRole')->name('perpanjangan_jam.waktu_layanan');
Route::post('perpanjangan_jam/waktu-layanan-submit', [PerpanjanganJamController::class,'waktu_layananSubmit'])->name('perpanjangan_jam.waktu_layananSubmit');

Route::resource('perpanjangan_tbo', PerpanjanganTboController::class);
Route::get('perpanjangan_tbo/create', [PerpanjanganTboController::class,'create'])->name('perpanjangan_tbo.create');
Route::post('perpanjangan_tbo/store', [PerpanjanganTboController::class,'store']);
Route::get('perpanjangan_tbo/{id}', [PerpanjanganTboController::class,'show']);
Route::post('perpanjangan_tbo/approve-submit', [PerpanjanganTboController::class,'approveSubmit'])->name('perpanjangan_tbo.approveSubmit');
Route::get('report-tgl-tbo', [PerpanjanganTboController::class,'tanggal_tbo'])->name('perpanjangan_tbo.tanggal_tbo');
Route::post('perpanjangan_jam/tgl-tbo-submit', [PerpanjanganTboController::class,'tanggal_tboSubmit'])->name('perpanjangan_tbo.tanggal_tboSubmit');


Route::resource('branch_tbo', BranchTboController::class);
Route::get('branch_tbo/{id}/approve', [BranchTboController::class,'approve'])->name('branch_tbo.approve');
Route::post('branch_tbo/approve-submit', [BranchTboController::class,'approveSubmit'])->name('branch_tbo.approveSubmit');
// Route::any('faq', [FaqController::class,'index'])->name('faq.index');
// Route::get('faq/create', [FaqController::class,'create'])->name('faq.create');
// Route::get('faq/{id}', [FaqController::class,'show'])->name('faq.show');
// Route::get('faq/edit', [FaqController::class,'edit'])->name('faq.edit');
// Route::post('faq/destroy', [FaqController::class,'destroy'])->name('faq.destroy');
Route::resource('faq', FaqController::class);
Route::post('faq-search', [FaqController::class, 'faq_search'])->name('faq.search');

Route::get('faq-list', [FaqController::class,'list'])->name('faq.list');
Route::resource('news', NewsController::class);
Route::get('news-list', [NewsController::class,'list'])->name('news.list');

Route::resource('cut_off_hq', CutOffHqController::class);
Route::get('cut_off_hq/{id}/approve', [CutOffHqController::class,'approve'])->name('cut_off_hq.approve');
Route::post('cut_off_hq/approve-submit', [CutOffHqController::class,'approveSubmit'])->name('cut_off_hq.approveSubmit');


//Route::get('index', Index::class);
Route::get('accordion', Accordion::class);
Route::get('alerts', Alerts::class);
Route::get('avatar', Avatar::class);
Route::get('background', Background::class);
Route::get('badge', Badge::class);
Route::get('blog', Blog::class);
Route::get('bloglist01', Bloglist01::class);
Route::get('bloglist02', Bloglist02::class);
Route::get('bloglist03', Bloglist03::class);
Route::get('bloglist04', Bloglist04::class);
Route::get('bloglist05', Bloglist05::class);
Route::get('border', Border::class);
Route::get('breadcrumbs', Breadcrumbs::class);
Route::get('buttons', Buttons::class);
Route::get('calendar', Calendar::class);
Route::get('cards', Cards::class);
Route::get('carousel', Carousel::class);
Route::get('chart-chartjs', ChartChartjs::class);
Route::get('chart-echart', ChartEchart::class);
Route::get('chart-flot', ChartFlot::class);
Route::get('chart-morris', ChartMorris::class);
Route::get('chart-peity', ChartPeity::class);
Route::get('chart-sparkline', ChartSparkline::class);
Route::get('chat', Chat::class);
Route::get('collapse', Collapse::class);
Route::get('contacts', Contacts::class);
Route::get('counters', Counters::class);
Route::get('darggablecards', Darggablecards::class);
Route::get('display', Display::class);
Route::get('dropdown', Dropdown::class);
Route::get('editprofile', Editprofile::class);
Route::get('emptypage', Emptypage::class);
Route::get('error404', Error404::class);
Route::get('error500', Error500::class);
Route::get('extras', Extras::class);
//Route::get('faq', Faq::class);
Route::get('flex', Flex::class);
Route::get('forgot', Forgot::class);
Route::get('form-advanced', FormAdvanced::class);
Route::get('form-editor', FormEditor::class);
Route::get('form-elements', FormElements::class);
Route::get('form-elements-sizes', FormElementsSizes::class);
Route::get('form-inputmask', FormInputmask::class);
Route::get('form-layouts', FormLayouts::class);
Route::get('form-upload', FormUpload::class);
Route::get('form-validation', FormValidation::class);
Route::get('form-wizards', FormWizards::class);
Route::get('gallery', Gallery::class);
Route::get('height', Height::class);
Route::get('horizontal', Horizontal::class);
Route::get('icons', Icons::class);
Route::get('icons2', Icons2::class);
Route::get('icons3', Icons3::class);
Route::get('icons4', Icons4::class);
Route::get('icons5', Icons5::class);
Route::get('icons6', Icons6::class);
Route::get('image-compare', ImageCompare::class);
Route::get('images', Images::class);
Route::get('invoice', Invoice::class);
Route::get('list-group', ListGroup::class);
Route::get('lockscreen', Lockscreen::class);
Route::get('mail-compose', MailCompose::class);
Route::get('mail-inbox', MailInbox::class);
Route::get('mail-read', MailRead::class);
Route::get('mail-settings', MailSettings::class);
Route::get('map-leaflet', MapLeaflet::class);
Route::get('map-vector', MapVector::class);
Route::get('margin', Margin::class);
Route::get('media-object', MediaObject::class);
Route::get('modals', Modals::class);
Route::get('navigation', Navigation::class);
Route::get('notification', Notification::class);
Route::get('padding', Padding::class);
Route::get('pagination', Pagination::class);
Route::get('popover', Popover::class);
Route::get('position', Position::class);
Route::get('pricing', Pricing::class);
Route::get('product-cart', ProductCart::class);
Route::get('product-checkout', ProductCheckout::class);
Route::get('product-details', ProductDetails::class);
Route::get('products', Products::class);
Route::get('profile', Profile::class);
Route::get('progress', Progress::class);
Route::get('rangeslider', Rangeslider::class);
Route::get('rating', Rating::class);
Route::get('reset', Reset::class);
Route::get('search', Search::class);
Route::get('signin', Signin::class);
Route::get('signup', Signup::class);
Route::get('spinners', Spinners::class);
Route::get('sweet-alert', SweetAlert::class);
Route::get('table-basic', TableBasic::class);
Route::get('table-data', TableData::class);
Route::get('tabs', Tabs::class);
Route::get('tags', Tags::class);
Route::get('thumbnails', Thumbnails::class);
Route::get('timeline', Timeline::class);
Route::get('toast', Toast::class);
Route::get('todotask', Todotask::class);
Route::get('tooltip', Tooltip::class);
Route::get('treeview', Treeview::class);
Route::get('typography', Typography::class);
Route::get('underconstruction', Underconstruction::class);
Route::get('userlist', Userlist::class);
Route::get('vertical', Vertical::class);
Route::get('widget-notification', WidgetNotification::class);
Route::get('widgets', Widgets::class);
Route::get('width', Width::class);

