<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKPI</title>
    <link rel="icon" type="image/png" href="{{ url('templates/img/logo.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> --}}
</head>
<style>
    @page {
        margin: 220px 3em 3em 3em;
    }

    header {
        position: fixed;
        top: -200px;
        left: 0;
        right: 0;
        height: 200px;
        padding: 0px;
        margin: 0px;
        z-index: 900;
        vertical-align: bottom;
    }

    .table {
        position: relative;
        width: 90%;
        max: width 100%;
        margin: auto;
    }

    .table1 {
        position: relative;
        width: 100%;
        max: width 100%;
        margin: auto;
        border: 1px solid black;
        border-collapse: collapse;
    }


    .isi {
        margin-left: 2em;
        margin-right: 2em;
        margin-bottom: 1em;
        margin-top: -6em;
    }

    .isi2 {
        margin-left: 2em;
        margin-right: 2em;
        margin-bottom: 1em;
        margin-top: -3em;
    }

    .container {
        display: flex;
        align-items: flex-start;
    }

    .p-al {
        font-size: 11px;
        line-height: 0.2;
    }

    .p-itenas {
        font-size: 25px;
        font-family: Georgia, 'Times New Roman', Times, serif;
        line-height: 0.2;
    }

    .isisurat {
        text-align: justify;
    }

    footer {
        position: fixed;
        top: 21cm;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
    }

    .tembusan {
        bottom: 110;
        margin-left: 3em;
        /* position:fixed; */
    }

    .over {
        text-decoration: overline;
    }

    .ttd {
        bottom: 250;
        margin-left: 19em;
        margin-right: 0;
        /* position:fixed; */
    }

    .ttd2 {

        bottom: 250;
        margin-left: 0.1em;
        margin-right: 5em;

        /* position:fixed; */
    }

    .ttd3 {
        position: absolute;
        left: 12%;
        margin-left: -120px;
        /* (300/2) */
    }

    .paddingttd {

        padding-bottom: 10px;
    }

    .page:after {
        content: counter(page);
    }
</style>

<body>
    <header class="header">
        <table style="width: 100%">
            <tr>
                <td style="width:10%;">
                    <img src="{{ url('img/logo.png') }}" width="120" height="110">
                </td>
                <td class="header">
                    <p align="center"><b>YAYASAN PENDIDIKAN DAYANG SUMBI</b></p>
                    <p align="center" class="p-itenas"><b>INSTITUT TEKNOLOGI NASIONAL</b>
                    <p align="center" class="p-al">Jl. PKH. Hasan Mustafa No. 23 Bandung 40124
                        Indonesia,Telepon:+62-22-7272215 ext 181; Fax: +62-22-7202892</p>
                    <p align="center" class="p-al">Website: <font color="blue">http://www.itenas.ac.id</font>;
                        Email : <font color="blue">baa@itenas.ac.id</font>
                    </p>
                </td>
            </tr>
        </table>
        <hr style="border-top: 1px solid black;margin-top: -1px">
    </header>

    <div class="isi">
        <center>
            <br>
            <p style="font-size: 20px;"> <b><u> SURAT KETERANGAN PENDAMPING IJAZAH</u></b></p>
        </center>

        <p align="justify" style="font-size: 16px">
            Surat Keterangan Pendamping Ijazah (SKPI) ini mengacu pada Kerangka Kualifikasi Nasional Indonesia (KKNI)
            dan Standar Nasional Pendidikan Tinggi Indonesia
            tentang pengakuan studi, ijazah dan gelar pendidikan tinggi. Tujuan dari SKPI ini adalah menjadi dokumen
            yang menyatakan capaian pembelajaran lulusan
            yang terdiri atas unsur sika, keterampilan umum, keterampilan Khusus, dan pengetahuan pemegangnya.</p>

        <p align="justify" style="font-size: 14px">
            <i> This Diploma Supplement refers to the Indonesian National Qualification Framework and Indonesian
                National Standard for Higher Education on the Recognition of Studies,
                Diplomas and Degrees in Higher Education. The purpose of SKPI is to be a formal document that states
                learning outcomes consist of attitude,
                general skills, specific skills, and knowledge of the holder.</i>
        </p>
        <table class="table">
            <tr>
                <td style="vertical-align:middle;width:270px;font-size: 16px;">Nama Lulusan/<i>Name</i></td>
                <td>:</td>
                <td style="font-size: 16px;"> {{ $data->nama_mhs }}</td>
            </tr>
            <tr>
                <td style="vertical-align:middle;width:150px;font-size: 16px;">Nomor Induk Mahasiswa/<i>Regisration
                        Number</i></td>
                <td>:</td>
                <td style="font-size: 16px;"> {{ $data->nim }}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;width:150px;font-size: 16px;">Fakultas-Program Studi/<i>Faculty - Study
                        Program</i></td>
                <td style="vertical-align:top;">:</td>
                <td align="justify" style="font-size: 16px;vertical-align:top;"> {{ $data->nama_fakultas }} -
                    {{ $data->nama_prodi }} <i
                        style="font-size: 12px;vertical-align:top;">({{ $data->nama_fakultas_en }} -
                        {{ $data->nama_prodi_eng }})</i></td>
            </tr>
        </table>
    </div>
    <div class="isi2">

    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="isi">
        <center>
            <br>
            <p style="font-size: 16px;"> <b><u>CAPAIAN PEMBELAJARAN LULUSAN</u></b></p>
            <p style="font-size: 16px;margin-top:-15px"> <i>Student Outcomes</i> </p>
        </center>

        @foreach ($cpl as $key => $c)
            @if ($key > 8)
                <p align="justify" style="font-size: 16px;page-break-after: avoid;"> {{ $c->kode_cpl }}.
                    {{ $c->nama_cpl }} <i> ({{ $c->nama_cpleng }})</i></p>
            @else
                <p align="justify" style="font-size: 16px;"> {{ $c->kode_cpl }}. {{ $c->nama_cpl }} <i>
                        ({{ $c->nama_cpleng }})
                    </i></p>
            @endif
        @endforeach
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="isi">
        <center>
            <br>
            <p style="font-size: 18px;"> <b>Keterangan Capaian</b></p>
            <p style="font-size: 16px;margin-top:-15px"> <i>Explanation of Achievement</i></p>
        </center>

        <table class="table1" border="1">
            <tr>
                <td style="vertical-align:top;width:150px;font-size: 16px;padding: 5px;">Rentang Capaian Pembelajaran
                    <i>(Student
                        Achievement Intervals)</i>
                </td>
                <td style="font-size: 16px;text-align: center; ">3.01 - 4.00</td>
                <td style="font-size: 16px;text-align: center; ">2.01 - 3.00</td>
                <td style="font-size: 16px;text-align: center; ">1.01 - 2.00</td>
                <td style="font-size: 16px;text-align: center; ">0.00 - 1.00</td>
            </tr>
            <tr>
                <td style="vertical-align:middle;width:150px;font-size: 16px;padding: 5px;">Makna <i>(Meaning)</i></td>
                <td style="font-size: 16px;text-align: center; ">Lebih dari cukup <i>(Exemplary)</i></td>
                <td style="font-size: 16px;text-align: center; ">Mahir <i>(Proficient)</i></td>
                <td style="font-size: 16px;text-align: center; ">Berkembang <i>(Apprentince)</i></td>
                <td style="font-size: 16px;text-align: center; ">Belum Berkembang <i>(Novice)</i></td>
            </tr>
        </table>

        <center>
            <br>
            <p style="font-size: 18px;padding: 5px;"> <b>Capaian Lulusan</b></p>
            <p style="font-size: 16px;margin-top:-15px;padding: 5px;"> <i>Achievment of Graduate</i></p>
        </center>

        <table class="table1" border="1">
            <thead>
                <td style="font-size: 16px;text-align: center;">Capaian Pembelajaran Lulusan <i>(Student Achiebement
                        Intervals)</i></td>
                <td style="font-size: 16px;text-align: center;">Level Capaian <i>(Achievement Level)</i></td>
            </thead>
            <tbody>
                @foreach ($datacpl['cpl'] as $c)
                    <tr>
                        <td style="font-size: 16px; padding: 5px;">
                            <p align="justify"><b>{{ $c->kode_cpl }}</b> -
                                {{ $c->nama_cpl }}</p>
                        </td>
                        {{-- <td style="font-size: 16px;">{{ $c->nama_cpl }}</td> --}}
                        <td style="font-size: 16px;text-align: center;">{{ getNilaiCPL($c->id, $datacpl['mhs']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div id="ChartCPL">
            <p align="center">
                <img src="{{ $chartUrl }}" alt="" id="ImgChart">
            </p>
        </div>
    </div>
    </footer>
</body>

</html>
