<style>
    .table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    }

    .border {
        border: 1px solid #edeef7 !important;
    }
</style>

@if($details['role'] == 'pc')
<div>Ibu/Bapak Pemimpin Cabang Yth.</div>
@elseif($details['role'] == 'spv5')
<div>Kepala Divisi Network & Operation Yth.</div>
@endif
<br />
<div>Mohon dapat diketahui, terdapat permohonan perpanjangan tanggal pemenuhan dokumen TBO yang diajukan oleh Cabang {{ $details['cabang'] }} dengan data sebagai berikut:</div>
<br />
                        <div class="table-responsive">
                            <table class="border">
                                <thead>
                                <tr>
                                    <th style="text-align:center;border: 1px solid #edeef7;">No</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Loan App No</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Nama Debitur</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Dokumen TBO</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Tgl Sebelum Perubahan</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Tgl Sesudah Perubahan</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Perubahan Ke</th>
                                    <th style="text-align:center;border: 1px solid #edeef7;">Alasan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $x=1;
                                ?>
                                
                                 @foreach($details['detailloan'] as $dt)                                                               
                                <tr>
                                    <td style="text-align:center;border: 1px solid #edeef7;">{{ $x }}</td>
                                    <td style="border: 1px solid #edeef7;">{{$dt->loan_app_no}}</td>
                                    <td style="border: 1px solid #edeef7;">{{$dt->nama_debitur}}</td>
                                    <td style="border: 1px solid #edeef7;">{{$dt->dokumen_tbo}}</td>
                                    <td style="text-align:center;border: 1px solid #edeef7;">{{$dt->tgl_sebelum_perubahan}}</td>
                                    <td style="text-align:center;border: 1px solid #edeef7;">{{$dt->tgl_sesudah_perubahan}}</td>
                                    <td style="text-align:center;border: 1px solid #edeef7;">{{$dt->perubahan_ke}}</td>
                                    <td style="text-align:center;border: 1px solid #edeef7;">{{$dt->note}}</td>
                                    
                                </tr>
                                <?php
                                $x++;
                                ?>
                                @endforeach
                                                       
                                </tbody>
                                </table>
                        </div>
                        <br />
<div>Mohon persetujuan dari Ibu/Bapak, agar tidak menjadi masalah  dan menjadi concern atas kualitas dokumen kredit bagi Bank Woori Saudara.</div>
<br />
<div>Informasi detail permohonan ini, dapat dilihat dan diekseskusi pada link berikut: {{ $details['link'] }}</div>
<br />
<div>Demikian kami sampaikan, atas persetujuan yang diberikan kami ucapkan terimakasih.</div>