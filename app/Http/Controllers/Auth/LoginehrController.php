<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use DB;

class LoginehrController extends Controller
{
	public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->email;
        $password = $request->password;

                // $getuser = $this->getUserapp($username);

	        	// if(isset($getuser[0]->nik) == $username){
	        	// 	$role = $getuser[0]->role;

                //     $getbranch = $dtl['branch'];
                //     $branch = get_object_vars($getbranch);
                    
                //     //$branch_code='110';
                //     //$branch_name='KC DIPONEGORO';
                //      $getWil = $branch['wilayah'];
                //      $wil = get_object_vars($getWil);

                //      $getlevel = $dtl['level'];
                //      $level = get_object_vars($getlevel);

                //     Session::put('nik', $getuser[0]->nik);
                //     Session::put('name', $getuser[0]->name);
                //     Session::put('email', $getuser[0]->email);
                //     Session::put('unit_id', $dtl['unit_id']);
                //     Session::put('unit_name', $dtl['unit_name']);
                //     Session::put('branch_name', $branch_name);
                //     Session::put('branch_code', $branch_code);
                //     Session::put('role', $role);
                    
                //     log_aktifitas('login');
                //     //print_r($request->session()->get('name'));

                //     return redirect('/');
                // } else {
            	// 	return redirect('login')->with('error', 'Hak akses tidak diperbolehkan.');
               	// }
        $resLogin = $this->toapi($username, $password);
       
        $dcd = json_decode($resLogin);
        $dtl = get_object_vars($dcd);
     //dd($dtl);
		if($dcd == NULL){
			return redirect('login')->with('error', 'Opps! You have entered invalid credentials');
		} else {
           
			if(isset($dtl['error_description']) == "Bad credentials"){
	        	echo "<script type='text/javascript'>alert('Username atau password salah.');</script>";
	        	return redirect('login')->with('error', 'Username atau password salah.');
	        } else {
	        	$getuser = $this->getUserapp($username);
              // dd($getuser);
	        	if(isset($getuser[0]->nik) == $username){
	        		$role = $getuser[0]->role;
                    
                   
                   
                    $getbranch = $dtl['branch'];
                    $branch = get_object_vars($getbranch);
                    //$branch_code=$branch['id'];
                    $branch_code=$getuser[0]->cabang;
                    if($branch_code<110){
                        $branch_code='000';
                    }
                    $branch_type = getBranchtype($branch_code);
                    $branch_parent = getBranchParent($branch_code);
                    //$branch_name=$branch['name'];
                    $branch_name=getBranchname($branch_code);
                    $getWil = $branch['wilayah'];
                    $wil = get_object_vars($getWil);

                    $getlevel = $dtl['level'];
                    $level = get_object_vars($getlevel);

                    Session::put('nik', $dtl['nik']);
                    Session::put('name', $dtl['nama']);
                    Session::put('email', $dtl['email']);
                    Session::put('unit_id', $dtl['unit_id']);
                    Session::put('unit_name', $dtl['unit_name']);
                    Session::put('branch_name', $branch_name);
                    Session::put('branch_code', $branch_code);
                    Session::put('branch_type', $branch_type);
                    Session::put('branch_parent', $branch_parent);
                    Session::put('role', $role);
                    
                    log_aktifitas('login');
                   // dd($role);
                    return redirect('/index');
                    
	        	} else {
	        		
	        		return redirect('login')->with('error', 'Hak akses tidak diperbolehkan.');
	        	}

	        	
	        }
		}
    }

    private function getUserapp($nik)
    {
    	$reports= DB::table('users')
                //->join('branchlist', 'users.cabang', '=', 'branchlist.branch_code')
                ->where('nik',$nik)
                ->whereNull('blocked_at')
                ->get();

        return $reports;
    }

    public function logout(Request $request)
    {
        log_aktifitas('logout','');
        $this->guard()->logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        $request->session()->flush();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        //
       
    }

    private function toapi($username,$pass)
	{
		$password = urlencode($pass);
        $reqmsg = "username=$username&password=$password&grant_type=password";
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_PORT => "8181",
  		  //CURLOPT_URL => "http://172.28.97.10:8181/beegate/oauth/token",
		  CURLOPT_URL => "http://172.28.97.135:8181/beegate/oauth/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $reqmsg,
          CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
		    "Authorization: Basic YndzY2xpZW50OnBhc3N3b3Jk",
		    "Content-Type: application/x-www-form-urlencoded",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //dd($response);
        if($err){
            $hasil = array("rc" => "98", "msg" => str_replace("'", " ", $err));
            return json_encode($hasil);
        } else {
            return $response;
        }
    }
}
