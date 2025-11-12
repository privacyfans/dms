<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Mail\MySendEmail;
use Mail;
use DB;
use App\Models\SlaBpr;
use Carbon\Carbon;
class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function getServerTime()
  {
    return response()->json(['server_time' => now()]);
  }

  public function test(Request $request)
  {
    var_dump(checkTboOverCount());
  }

  public function index(Request $request)
  {

    $news = DB::table('news')
      ->where('popup', '=', 1)
      ->first();
    $branch = Session('branch_code');
    $branch_parent = Session('branch_parent');

    $totaldaily = DB::table('data_file')
    ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
    ->where('final_status_spv1', '!=', 0)
    ->count();

    $waitingdailyspv = DB::table('data_file')
      ->where(function ($query) {
        $query->where('data_file.final_status_spv1', '=', 0)
          ->orWhere('data_file.final_status_spv1', '=', 2);
      })

      ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
      ->count();

    $waitingdailybpr1 = DB::table('data_file')
      ->where('data_file.final_status_spv1', '<>', 0)
      ->where('data_file.final_status_spv2', '=', 0)
      ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
      ->count();

    // $waitingdailybpr2 = DB::table('data_file')
    //   ->where(function ($query) {
    //     $query->where('data_file.final_status_spv1', '=', 1)
    //       ->orWhere('data_file.final_status_spv1', '=', 3);
    //   })
    //   ->where('data_file.final_status_spv3', '=', 0)
    //   ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
    //   ->count();

    $waitingdailybpr2 = DB::table('data_file as df')
        ->join('branchlist as br', 'df.kode_cabang', '=', 'br.branch_code')
        ->where(function ($query) {
            $query->where('df.final_status_spv1', '=', 1)
                  ->orWhere('df.final_status_spv1', '=', 3);
        })
        ->where('df.final_status_spv2', '=', 0)
        ->whereNotIn('df.loan_app_no', function($query) {
            $query->select('loan_app_no')
                  ->from('list_pickup')
                  ->where('status', '=', 0);
        })
        ->where(function($query) {
            $query->where(function($q) {
                // Data hari ini dan kemarin
                $q->whereIn(DB::raw('DATE(df.date_input)'), [
                    DB::raw('CURDATE()'),
                    DB::raw('CURDATE() - INTERVAL 1 DAY')
                ]);
            })
            ->orWhere(function($q) {
                // Data Jumat jika hari ini Senin
                $q->where(DB::raw('DAYOFWEEK(NOW())'), '=', 2)
                  ->where(DB::raw('DATE(df.date_input)'), '=', DB::raw('DATE(SUBDATE(NOW(), 3))'))
                  ->where(DB::raw('DATE(df.date_flag_spv1)'), '=', DB::raw('CURDATE()'));
            });
        })
        ->where('df.final_status', '!=', 4);

    // Add AI condition if enabled
    $ai_enabled = DB::table('setting_ai')->value('enable');
    if ($ai_enabled == 1) {
        $waitingdailybpr2->whereExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('detail_file as dtf')
                  ->whereColumn('dtf.loan_app_no', 'df.loan_app_no')
                  ->where(function($q) {
                      $q->whereNotNull('dtf.score')
                        ->orWhere(DB::raw('TIMESTAMPDIFF(MINUTE, df.date_flag_spv1, NOW())'), '>', 5);
                  });
        });
    }

    $waitingdailybpr2 = $waitingdailybpr2->distinct()
        ->count(DB::raw('df.loan_app_no'));


    $totaldailyprocessed = DB::table('data_file')
      ->where('data_file.final_status_spv1', '<>', 0)
      ->where('data_file.final_status_spv2', '<>', 0)
      ->where(function ($query) {
        $query->where('data_file.final_status_spv3', '=', 1)
          ->orWhere('data_file.final_status_spv3', '=', 3);
      })
      ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
      ->count();

    if (session("role") == "staff" || session("role") == "spv1" || session("role") == "pc" || session("role") == "pcp") {

      $limit_tbo_overdue = getLimitTBOOverdue(Session('branch_code'));

      $total = DB::table('data_file')
        ->where('data_file.kode_cabang', '=', Session('branch_code'))
        ->count();

      $total_spv1 = DB::table('data_file')
        ->where(function ($query) {
          $query->where('data_file.final_status_spv1', '=', 0)
            ->orWhere('data_file.final_status_spv1', '=', 2);
        })
        ->count();

      $total_spv2 = DB::table('data_file')
        //->where('data_file.kode_cabang','=',Session('branch_code'))
        ->where('data_file.final_status_spv1', '=', 1)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      $total_spv2_tbo = DB::table('data_file')
        //->where('data_file.kode_cabang','=',Session('branch_code'))
        ->where('data_file.final_status_spv1', '=', 3)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

        $total_spv3 = DB::table('data_file')
        ->whereDate('date_input', '!=', now()->toDateString())
        ->where('data_file.final_status_spv1', '=', 1)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      $total_spv3_tbo = DB::table('data_file')
        //->where('data_file.kode_cabang','=',Session('branch_code'))
        ->where('data_file.final_status_spv1', '=', 3)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      // $total_tbo = DB::table('data_file')
      // ->where('data_file.kode_cabang','=',Session('branch_code'))
      // ->where('data_file.final_status','=',3)
      // ->count();

      if ($branch == $branch_parent) {
        $sql_total_tbo = 'select count(1) as jml from
            (
            SELECT
              df.*,
              c.comment,
              c.user_name,
              c.level_spv,
              c.comment_date,
              c.flag_spv,
              c.reason,
              MAX(c.tbo_date) AS tbo_date 
          FROM
              data_file df
              INNER JOIN comment c ON c.loan_app_no = df.loan_app_no
              INNER JOIN branchlist bl ON bl.branch_code = df.kode_cabang 
          WHERE
              df.final_status = 3 
              AND df.kode_cabang IN (
                  SELECT branch_code 
                  FROM branchlist 
                  WHERE parent_branch = (
                      SELECT parent_branch 
                      FROM branchlist 
                      WHERE branch_code = "' . $branch . '"
                  )
              )
          GROUP BY
              df.loan_app_no
          ORDER BY
              MAX(c.tbo_date) DESC
              ) x';
      } else {
        $sql_total_tbo = 'select count(1) as jml from
            (SELECT
                      count(1)
                FROM
                    `data_file`
                    INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
                WHERE
                    -- date(`comment`.`tbo_date`) < date(now()) AND
                    `data_file`.`kode_cabang` = "' . $branch . '" and
                    `data_file`.`final_status` = 3
                    GROUP BY`data_file`.`loan_app_no` ) x';
      }


      $count_total_tbo = DB::select($sql_total_tbo);
      $total_tbo = $count_total_tbo;
      foreach ($count_total_tbo as $c_total_tbo) {
        $total_tbo = $c_total_tbo->jml;
      }

      if ($branch == $branch_parent) {
        $sql = "select count(1) as jml from(
            SELECT
    df.*,
    c.comment,
    c.user_name,
    c.level_spv,
    c.comment_date,
    c.flag_spv,
    c.reason,
    MAX(c.tbo_date) AS tbo_date 
FROM
    data_file df
    INNER JOIN comment c ON c.loan_app_no = df.loan_app_no
    INNER JOIN branchlist bl ON bl.branch_code = df.kode_cabang 
WHERE
    df.final_status = 3 
    AND df.kode_cabang IN (
        SELECT branch_code 
        FROM branchlist 
        WHERE parent_branch = (
            SELECT parent_branch 
            FROM branchlist 
            WHERE branch_code = '" . $branch . "'
        )
    )
GROUP BY
    df.loan_app_no
HAVING
    DATE(MAX(c.tbo_date)) < CURDATE() 
ORDER BY
    MAX(c.tbo_date) DESC
              ) x
          ";
      } else {
        $sql = "select count(1) as jml from(
            SELECT
              loan_app_no,
              max( tbo_date ) AS tbo_date 
            FROM
            COMMENT 
            WHERE
              loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang = '" . $branch . "') 
            GROUP BY
              loan_app_no 
            HAVING
              date(
              max( tbo_date )) < date(
              now())
              ) x
          ";
      }

      $count_tbo = DB::select($sql);
      foreach ($count_tbo as $c_total_tbo) {
        $total_tbo_overdue = $c_total_tbo->jml;
      }

      if ($branch == $branch_parent) {
        $sql_tbo_pending = "select count(1) as jml from(
           SELECT
    df.*,
    c.comment,
    c.user_name,
    c.level_spv,
    c.comment_date,
    c.flag_spv,
    c.reason,
    MAX(c.tbo_date) AS tbo_date 
FROM
    data_file df
    INNER JOIN comment c ON c.loan_app_no = df.loan_app_no
    INNER JOIN branchlist bl ON bl.branch_code = df.kode_cabang 
WHERE
    df.final_status = 3 
    AND df.kode_cabang IN (
        SELECT branch_code 
        FROM branchlist 
        WHERE parent_branch = (
            SELECT parent_branch 
            FROM branchlist 
            WHERE branch_code = '" . $branch . "'
        )
    )
GROUP BY
    df.loan_app_no
HAVING
    DATE(MAX(c.tbo_date)) >= CURDATE() 
ORDER BY
    MAX(c.tbo_date) DESC
            ) x
         ";
      }else{
        $sql_tbo_pending = "select count(1) as jml from(
          SELECT
            loan_app_no,
            max( tbo_date ) AS tbo_date 
          FROM
          COMMENT 
          WHERE
            loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang = '" . $branch . "') 
          GROUP BY
            loan_app_no 
          HAVING
            date(
            max( tbo_date )) >= date(
            now())
            ) x
         ";
      }
      
      $count_tbo_pending = DB::select($sql_tbo_pending);

      foreach ($count_tbo_pending as $c_total_tbo) {
        $total_tbo_pending = $c_total_tbo->jml;
      }
    } else {

      $limit_tbo_overdue = "-";
      $total = DB::table('data_file')->count();

      $total_spv1 = DB::table('data_file')
        ->where(function ($query) {
          $query->where('data_file.final_status_spv1', '=', 0)
            ->orWhere('data_file.final_status_spv1', '=', 2);
        })
        ->count();

      $total_spv2 = DB::table('data_file')
        ->where('data_file.final_status_spv1', '=', 1)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      $total_spv2_tbo = DB::table('data_file')
        ->where('data_file.final_status_spv1', '=', 3)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

        $total_spv3 = DB::table('data_file')
        ->whereDate('date_input', '!=', now()->toDateString())
        ->where('data_file.final_status_spv1', '=', 1)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      $total_spv3_tbo = DB::table('data_file')
        ->where('data_file.final_status_spv1', '=', 3)
        ->where('data_file.final_status_spv2', '=', 0)
        ->count();

      // $total_tbo = DB::table('data_file')
      // ->where('data_file.final_status','=',3)
      // ->count();
      $sql_total_tbo = 'select count(1) as jml from
          (SELECT
                    count(1)
              FROM
                  `data_file`
                  INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
              WHERE
                  `data_file`.`final_status` = 3
                  GROUP BY`data_file`.`loan_app_no` ) x';
      $count_total_tbo = DB::select($sql_total_tbo);

      foreach ($count_total_tbo as $c_total_tbo) {
        $total_tbo = $c_total_tbo->jml;
      }

      $sql = "select count(1) as jml from(
            SELECT
              loan_app_no,
              max( tbo_date ) AS tbo_date 
            FROM
            COMMENT 
            WHERE
              loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 ) 
            GROUP BY
              loan_app_no 
            HAVING
              date(
              max( tbo_date )) < date(
              now())
              ) x
          ";
      $count_tbo = DB::select($sql);

      foreach ($count_tbo as $c_total_tbo) {
        $total_tbo_overdue = $c_total_tbo->jml;
      }

      $sql_tbo_pending = "select count(1) as jml from(
            SELECT
              loan_app_no,
              max( tbo_date ) AS tbo_date 
            FROM
            COMMENT 
            WHERE
              loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 ) 
            GROUP BY
              loan_app_no 
            HAVING
              date(
              max( tbo_date )) >= date(
              now())
              ) x
          ";
      $count_tbo_pending = DB::select($sql_tbo_pending);

      foreach ($count_tbo_pending as $c_total_tbo) {
        $total_tbo_pending = $c_total_tbo->jml;
      }

    }


    if (checkTboCount()) {
      return view('home.index')
        ->with('total', $total)
        ->with('total_spv1', $total_spv1)
        ->with('total_spv2', $total_spv2)
        ->with('total_spv3', $total_spv3)
        ->with('total_tbo', $total_tbo)
        ->with('total_tbo_overdue', $total_tbo_overdue)
        ->with('total_spv2_tbo', $total_spv2_tbo)
        ->with('total_spv3_tbo', $total_spv3_tbo)
        ->with('news', $news)
        ->with('totaldaily', $totaldaily)
        ->with('waitingdailyspv', $waitingdailyspv)
        ->with('waitingdailybpr1', $waitingdailybpr1)
        ->with('waitingdailybpr2', $waitingdailybpr2)
        ->with('totaldailyprocessed', $totaldailyprocessed)
        ->with('total_tbo_pending', $total_tbo_pending)
        ->with('limit_tbo_overdue', $limit_tbo_overdue)
        ->with('error', 'TBO Quota Exceeded - Pastikan hanya dokumen lengkap (Verify) yang dapat di input.')
      ;

    }

    if (checkCutOff(1)) {
      return view('home.index')
        ->with('total', $total)
        ->with('total_spv1', $total_spv1)
        ->with('total_spv2', $total_spv2)
        ->with('total_spv3', $total_spv3)
        ->with('total_tbo', $total_tbo)
        ->with('total_tbo_overdue', $total_tbo_overdue)
        ->with('total_spv2_tbo', $total_spv2_tbo)
        ->with('total_spv3_tbo', $total_spv3_tbo)
        ->with('news', $news)
        ->with('totaldaily', $totaldaily)
        ->with('waitingdailyspv', $waitingdailyspv)
        ->with('waitingdailybpr1', $waitingdailybpr1)
        ->with('waitingdailybpr2', $waitingdailybpr2)
        ->with('totaldailyprocessed', $totaldailyprocessed)
        ->with('total_tbo_pending', $total_tbo_pending)
        ->with('limit_tbo_overdue', $limit_tbo_overdue)
        ->with('error', 'Proses upload dokumen dapat dilakukan pada Jam Layanan yang telah ditentukan')
      ;
    }

    return view('home.index')
      ->with('total', $total)
      ->with('total_spv1', $total_spv1)
      ->with('total_spv2', $total_spv2)
      ->with('total_spv3', $total_spv3)
      ->with('total_tbo', $total_tbo)
      ->with('total_tbo_overdue', $total_tbo_overdue)
      ->with('total_spv2_tbo', $total_spv2_tbo)
      ->with('total_spv3_tbo', $total_spv3_tbo)
      ->with('news', $news)
      ->with('totaldaily', $totaldaily)
      ->with('waitingdailyspv', $waitingdailyspv)
      ->with('waitingdailybpr1', $waitingdailybpr1)
      ->with('waitingdailybpr2', $waitingdailybpr2)
      ->with('totaldailyprocessed', $totaldailyprocessed)
      ->with('total_tbo_pending', $total_tbo_pending)
      ->with('limit_tbo_overdue', $limit_tbo_overdue)
      ->with('error', '')
    ;
  }

  public function chart(Request $request)
  {

    if ($request->from_date == "" || $request->end_date == "") {
      $month_ini = new \DateTime("first day of last month");
      $month_end = new \DateTime("last day of last month");

      $dari = $month_ini->format('Y-m-d');
      $sampai = $month_end->format('Y-m-d');
      $dari = date('Y-m-d');
      $sampai = date('Y-m-d');
    } else {

      $dari = Carbon::parse($request->from_date);
      $sampai = Carbon::parse($request->end_date);

      // Membandingkan tanggal
      if ($dari->gt($sampai)) { // gt = greater than
        // Menyimpan pesan peringatan ke session flash
        session()->flash('warning', 'Tanggal End Date harus lebih besar dari Tanggal From Date.');

        // Redirect ke halaman sebelumnya dengan input yang sudah dimasukkan
        return back()->withInput();
      } else {
        $dari = $request->from_date;
        $sampai = $request->end_date;
      }




    }


    $dailybpr1 = DB::select("call daily_bpr1(?,?)", [$dari, $sampai]);
    $datadailybpr1['label'] = ['08:00 - 10:00', '10:00 - 12:00', '12:00 - 13:00', '13:00 - 15:00', '15:00 - 17:00', '17:00 - 19:00', '> 19:00'];
    $datadailybpr1['data'] = [(int) $dailybpr1[0]->a, (int) $dailybpr1[0]->b, (int) $dailybpr1[0]->c, (int) $dailybpr1[0]->d, (int) $dailybpr1[0]->e, (int) $dailybpr1[0]->f, (int) $dailybpr1[0]->g];

    $dailybpr2 = DB::select("call daily_bpr2(?,?)", [$dari, $sampai]);
    $datadailybpr2['label'] = ['08:00 - 10:00', '10:00 - 12:00', '12:00 - 13:00', '13:00 - 15:00', '15:00 - 17:00', '17:00 - 19:00', '> 19:00'];
    $datadailybpr2['data'] = [(int) $dailybpr2[0]->a, (int) $dailybpr2[0]->b, (int) $dailybpr2[0]->c, (int) $dailybpr2[0]->d, (int) $dailybpr2[0]->e, (int) $dailybpr2[0]->f, (int) $dailybpr2[0]->g];




    $record = DB::select("call stacked_bpr1(?,?)", [$dari, $sampai]);
    $recordtotal = DB::select("call total_reviewer_bpr1(?,?)", [$dari, $sampai]);


    $record_bpr2 = DB::select("call stacked_bpr2(?,?)", [$dari, $sampai]);
    $recordtotal_bpr2 = DB::select("call total_reviewer_bpr2(?,?)", [$dari, $sampai]);

    $record_history = DB::select("call stacked_bpr1_history(?,?)", [$dari, $sampai]);
    $recordtotal_history = DB::select("call total_reviewer_bpr1_history(?,?)", [$dari, $sampai]);


    $record_bpr2_history = DB::select("call stacked_bpr2_history(?,?)", [$dari, $sampai]);
    $recordtotal_bpr2_history = DB::select("call total_reviewer_bpr2_history(?,?)", [$dari, $sampai]);


    $recordtotal_produk = DB::select("call total_produk(?,?)", [$dari, $sampai]);
    $recordtotal_perpanjangan = DB::select("call total_perpanjangan(?,?)", [$dari, $sampai]);
    $recordtotal_reject = DB::select("call total_reject(?,?)", [$dari, $sampai]);

    $execute_sla_agregate = DB::select("call staging_resume_dcrm(?,?)", [$dari, $sampai]);
    //$execute_sla_agregate=[];
    $sla_agregate = DB::select("select DATE_FORMAT(tanggal,'%d %b')as tanggal, round(TIME_TO_SEC(rata_waktu_perhari)/60) as rata_waktu_perhari,round(TIME_TO_SEC(rata_waktu_perhari_ideal)/60) as rata_waktu_perhari_ideal from temp_sla_agregate where tanggal >= '" . $dari . "' and tanggal <= '" . $sampai . "'");
    //$sla_agregate=[];
    $slabpr1 = DB::select("call sla_dcrm(?,?)", [$dari, $sampai]);
    $slabpr2 = DB::select("call sla_bpr2(?,?)", [$dari, $sampai]);
    $slapcikup = DB::select("call sla_pickup(?,?)", [$dari, $sampai]);
    //dd($slabpr2);

    $data = [];
    $datatotal = [];
    $databpr2 = [];
    $datatotalbpr2 = [];
    $data_history = [];
    $datatotal_history = [];
    $databpr2_history = [];
    $datatotalbpr2_history = [];
    $datatotalproduk = [];
    $dataslaagregate = [];
    $dataperpanjangan = [];
    $datareject = [];
    $datapickup = [];




    foreach ($record_history as $row) {
      $data_history['label'][] = $row->name;
      $data_history['verify'][] = (int) $row->verify;
      $data_history['not_verify'][] = (int) $row->not_verify;
      $data_history['tbo'][] = (int) $row->tbo;
      $data_history['reject'][] = (int) $row->reject;
      $data_history['total'][] = (int) $row->total;
    }


    foreach ($recordtotal_history as $row) {
      $datatotal_history['label'][] = $row->status;
      $datatotal_history['data'][] = (int) $row->jml;

    }

    foreach ($record as $row) {
      $data['label'][] = $row->name;
      $data['verify'][] = (int) $row->verify;
      $data['not_verify'][] = (int) $row->not_verify;
      $data['tbo'][] = (int) $row->tbo;
      $data['reject'][] = (int) $row->reject;
      $data['total'][] = (int) $row->total;
    }


    foreach ($recordtotal as $row) {
      $datatotal['label'][] = $row->status;
      $datatotal['data'][] = (int) $row->jml;

    }

    foreach ($record_bpr2_history as $row) {
      $databpr2_history['label'][] = $row->name;
      $databpr2_history['verify'][] = (int) $row->verify;
      $databpr2_history['not_verify'][] = (int) $row->not_verify;
      $databpr2_history['tbo'][] = (int) $row->tbo;
      $databpr2_history['reject'][] = (int) $row->reject;
      $databpr2_history['total'][] = (int) $row->total;
    }

    foreach ($recordtotal_bpr2_history as $row) {
      $datatotalbpr2_history['label'][] = $row->status;
      $datatotalbpr2_history['data'][] = (int) $row->jml;

    }


    foreach ($record_bpr2 as $row) {
      $databpr2['label'][] = $row->name;
      $databpr2['verify'][] = (int) $row->verify;
      $databpr2['not_verify'][] = (int) $row->not_verify;
      $databpr2['tbo'][] = (int) $row->tbo;
      $databpr2['reject'][] = (int) $row->reject;
      $databpr2['total'][] = (int) $row->total;
    }

    foreach ($recordtotal_bpr2 as $row) {
      $datatotalbpr2['label'][] = $row->status;
      $datatotalbpr2['data'][] = (int) $row->jml;

    }

    foreach ($sla_agregate as $row) {
      $dataslaagregate['label'][] = $row->tanggal;
      //$dataslaagregate['rata'][] = $row->rata_waktu_perhari;
      $dataslaagregate['rata_ideal'][] = $row->rata_waktu_perhari_ideal;
    }

    foreach ($recordtotal_produk as $row) {
      $datatotalproduk['label'][] = $row->modul;
      $datatotalproduk['data'][] = (int) $row->jml;

    }

    foreach ($recordtotal_perpanjangan as $row) {
      $dataperpanjangan['label'][] = $row->branch_name;
      $dataperpanjangan['data'][] = (int) $row->jml;

    }

    if ($slapcikup !== null && !empty($slapcikup)) {
      foreach ($slapcikup as $row) {
          $datapickup['label'][] = $row->name;
          $datapickup['data'][] = (int) $row->total_loan_applications;
          $datapickup['data_average'][] = $row->average_processing_time;
      }
  } else {
      // Initialize with empty arrays or default values
      $datapickup['label'] = [];
      $datapickup['data'] = [];
      $datapickup['data_average'] = [];
  }

    // foreach($recordtotal_reject as $row) {
    //   $datareject['label'][] = $row->branch_name;
    //   $datareject['total_data'][] = (int) $row->total_jumlah_data;
    //   $datareject['reject'][] = (int) $row->reject;
    //   $datareject['persentase_reject'][] = (int) $row->persentase_reject;
    //   $datareject['not_verify'][] = (int) $row->not_verify;
    //   $datareject['persentase_not_verify'][] = (int) $row->persentase_not_verify;
    // }

    $datareject = DB::select("call total_reject(?,?)", [$dari, $sampai]);

    $data['chart_data'] = json_encode($data);
    $datatotal['chart_data_total'] = json_encode($datatotal);
    $databpr2['chart_data_bpr2'] = json_encode($databpr2);
    $datatotalbpr2['chart_data_total_bpr2'] = json_encode($datatotalbpr2);

    $data_history['chart_data_history'] = json_encode($data_history);
    $datatotal_history['chart_data_total_history'] = json_encode($datatotal_history);
    $databpr2_history['chart_data_bpr2_history'] = json_encode($databpr2_history);
    $datatotalbpr2_history['chart_data_total_bpr2_history'] = json_encode($datatotalbpr2_history);

    $datatotalproduk['chart_data_total_produk'] = json_encode($datatotalproduk);
    $datadailybpr1['chart_data_daily_bpr1'] = json_encode($datadailybpr1);
    $datadailybpr2['chart_data_daily_bpr2'] = json_encode($datadailybpr2);
    $dataslaagregate['chat_sla_agregate'] = json_encode($dataslaagregate);
    $dataperpanjangan['chart_data_perpanjangan'] = json_encode($dataperpanjangan);

    $datapickup['chart_data_pickup'] = json_encode([
      'label' => $datapickup['label'],
      'data' => $datapickup['data'],
      'data_average' => $datapickup['data_average']
    ]);

    // $datareject['chart_data_reject'] = json_encode($datareject);
    // dd($datadailybpr1);
    //return view('home.chart', $data);


    return view('home.chart')
      ->with('dari', $dari)
      ->with('sampai', $sampai)
      ->with('dailybpr1', $dailybpr1)
      ->with('datadailybpr1', $datadailybpr1)
      ->with('dailybpr2', $dailybpr2)
      ->with('datadailybpr2', $datadailybpr2)
      ->with('dataslaagregate', $dataslaagregate)
      ->with('slabpr1', $slabpr1)
      ->with('slabpr2', $slabpr2)
      ->with('slapcikup', $slapcikup)
      ->with('data', $data)
      ->with('datatotal', $datatotal)
      ->with('databpr2', $databpr2)
      ->with('datatotalbpr2', $datatotalbpr2)

      ->with('data_history', $data_history)
      ->with('datatotal_history', $datatotal_history)
      ->with('databpr2_history', $databpr2_history)
      ->with('datatotalbpr2_history', $datatotalbpr2_history)

      ->with('datatotalproduk', $datatotalproduk)
      ->with('dataperpanjangan', $dataperpanjangan)
      ->with('datareject', $datareject)
      ->with('datapickup', $datapickup);

  }

  public function testEmail()
  {
    $details = [
      'title' => 'Test Email DMS',
      'body' => 'This is for testing email using smtp'
    ];

    Mail::to('dcrmdepartment@bankwoorisaudara.com')->send(new MySendEmail($details));

    dd("Email sudah terkirim.");
  }


  public function unlock_loan(Request $request)
  {

    if (!empty($request->loan_app_no)) {
      // Check di list_pickup table (sistem baru)
      $lockInfo = isLoanLocked($request->loan_app_no);

      if ($lockInfo !== null) {
        // Unlock menggunakan helper function
        $success = unlockLoan($request->loan_app_no);

        if ($success) {
          return redirect()->route('unlock_loan')
            ->with('success', "Loan App No {$request->loan_app_no} Unlocked Successfully (was locked by {$lockInfo['name']})");
        } else {
          return redirect()->route('unlock_loan')
            ->with('error', 'Failed to unlock loan. Please try again.');
        }
      } else {
        // Jika tidak ditemukan di list_pickup, cek di SlaBpr (sistem lama - backward compatibility)
        $log_sla = SlaBpr::where('loan_app_no', '=', $request->loan_app_no)->where('isLocked', '=', 1)->first();
        if ($log_sla <> null) {
          $log_sla->isLocked = 0;
          $log_sla->save();
          return redirect()->route('unlock_loan')
            ->with('success', 'Loan App No Unlocked Successfully (legacy system)');
        } else {
          return redirect()->route('unlock_loan')
            ->with('error', 'Loan App No Not Found or Not Locked');
        }
      }


    } else {
      //dd($request->loan_app_no);
      return view('home.unlock');
    }

  }

  // public function root()
  // {
  //     if (Session::get('lang')) {
  //         App::setLocale(Session::get('lang'));
  //     }

  //    $totaltrx = DB::table('transaction')->count();
  //    $total_ltktk = DB::table('transaction')
  // 				 ->where('transaction.report_code','=','LTKTK')
  // 				 ->count();

  //    $total_ltktm = DB::table('transaction')
  // 				 ->where('transaction.report_code','=','LTKTM')
  // 				 ->count();

  // 	 $total_tklib = DB::table('transaction')
  // 				 ->where('transaction.report_code','=','TKLIB')
  // 				 ->count();
  // 	$total_tklob = DB::table('transaction')
  // 				 ->where('transaction.report_code','=','TKLOB')
  // 				 ->count();
  // 	$total_ltkm = DB::table('transaction')
  // 				 ->where('transaction.report_code','=','LTKM')
  // 				 ->count();

  //    //dd($total_ltktk);
  //     $transactions= DB::table('transaction')
  //     ->paginate(10);

  //     return view('dashboard-all')
  // 	->with('totaltrx',$totaltrx)
  // 	->with('total_ltktk',$total_ltktk)
  // 	->with('total_ltktm',$total_ltktm)
  // 	->with('total_tklib',$total_tklib)
  // 	->with('total_tklob',$total_tklob)
  // 	->with('total_ltkm',$total_ltkm)
  // 	->with('transactions',$transactions);     
  // }
}
