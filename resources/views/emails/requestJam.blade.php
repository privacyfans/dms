@if($details['role'] == 'pc')
<div>Ibu/Bapak Pemimpin Cabang Yth.</div>
@elseif($details['role'] == 'spv5')
<div>Kepala Divisi Network & Operation Yth.</div>
@endif
<div>Terdapat Permohonan Perpanjangan Jam Layanan DCRM yang diajukan oleh Cabang {{ $details['cabang'] }} menjadi Pukul {{ $details['jam'] }}.</div>
<div>Mohon approval dari Ibu/Bapak agar dapat diproses.&nbsp;</div>
<br />
<div>Informasi detail permohonan ini, dapat dilihat dan diekseskusi pada link berikut {{ $details['link'] }}</div>
<br />
<div>Demikian kami sampaikan, terimakasih.</div>
<br />
