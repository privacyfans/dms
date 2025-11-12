<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .section-title {
            margin-top: 40px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .no-data {
            color: red;
            font-style: italic;
        }
        .error-message {
            color: red;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .download-zip-btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .download-zip-btn:hover {
            background-color: #218838;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Pencarian Data</h1>
    
    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display success/error messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('loan.search-history') }}">
        @csrf
        <label for="loan_app_no">Loan App No:</label>
        <input type="text" 
               name="loan_app_no" 
               id="loan_app_no" 
               placeholder="Masukkan Loan App No" 
               value="{{ old('loan_app_no', $loan_app_no ?? '') }}">
        <input type="submit" value="Cari">
    </form>

    @if(isset($loan_data) && $loan_data->count() > 0)
        <div class="section-title">Data Loan</div>
        <table>
            <thead>
                <tr>
                    <th>Modul</th>
                    <th>Kode Cabang</th>
                    <th>Loan App No</th>
                    <th>No CIF</th>
                    <th>No KTP</th>
                    <th>Nama Debitur</th>
                    <th>TTL</th>
                    <th>Alamat Rumah</th>
                    <th>No Telepon Rumah</th>
                    <th>Instansi</th>
                    <th>Alamat Kantor</th>
                    <th>No Telepon Kantor</th>
                    <th>Plafond</th>
                    <th>Jangka Waktu</th>
                    <th>Rate</th>
                    <th>Angsuran</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Produk</th>
                    <th>User Input</th>
                    <th>Branch Input</th>
                    <th>Date Input</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loan_data as $row)
                <tr>
                    <td>{{ $row->modul }}</td>
                    <td>{{ $row->kode_cabang }}</td>
                    <td>{{ $row->loan_app_no }}</td>
                    <td>{{ $row->no_cif }}</td>
                    <td>{{ $row->no_ktp }}</td>
                    <td>{{ $row->nama_debitur }}</td>
                    <td>{{ $row->ttl }}</td>
                    <td>{{ $row->alamat_rumah }}</td>
                    <td>{{ $row->no_tlp_rumah }}</td>
                    <td>{{ $row->instansi }}</td>
                    <td>{{ $row->alamat_kantor }}</td>
                    <td>{{ $row->no_tlp_kantor }}</td>
                    <td>{{ $row->plafond }}</td>
                    <td>{{ $row->jangka_waktu }}</td>
                    <td>{{ $row->rate }}</td>
                    <td>{{ $row->angsuran }}</td>
                    <td>{{ $row->tanggal_jatuh_tempo }}</td>
                    <td>{{ $row->produk }}</td>
                    <td>{{ $row->user_input }}</td>
                    <td>{{ $row->branch_input }}</td>
                    <td>{{ $row->date_input }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($loan_app_no))
        <p class="no-data">Tidak ada data loan ditemukan untuk Loan App No: {{ $loan_app_no }}</p>
    @endif

    @if(isset($detail_data) && $detail_data->count() > 0)
        <div class="section-title">Data Detail</div>
        
        {{-- Download All Files Button --}}
        @if(isset($has_files) && $has_files)
            <form method="POST" action="{{ route('loan.download-all-history') }}" style="margin-bottom: 15px;">
                @csrf
                <input type="hidden" name="loan_app_no" value="{{ $loan_app_no }}">
                <button type="submit" class="download-zip-btn">
                    📦 Download Semua File (ZIP)
                </button>
            </form>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Loan App No</th>
                    <th>File</th>
                    <th>Branch Dir</th>
                    <th>Alias</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail_data as $row)
                <tr>
                    <td>{{ $row->loan_app_no }}</td>
                    <td>
                        @if($row->file_url)
                            <a href="{{ $row->file_url }}" target="_blank">{{ $row->file }}</a>
                        @else
                            <span class="error-message">Gagal mengunduh file</span>
                        @endif
                    </td>
                    <td>{{ $row->branch_dir }}</td>
                    <td>{{ $row->alias }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($loan_app_no))
        <p class="no-data">Tidak ada data detail ditemukan untuk Loan App No: {{ $loan_app_no }}</p>
    @endif
</body>
</html>