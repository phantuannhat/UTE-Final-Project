<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="https://vietnamese.cdc.gov/TemplatePackage/contrib/widgets/COVIDCountyCheck/images/icon.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <meta name="description" content="Hệ thống quản lý rác thải Covid" />
    <meta name="keywords" content="spkt, covid19, racthai, f0, hethongquanlyrac, benhnhancovid, sinhvienspkt, khkt, sv2022" />

    <!-- các công cụ tìm kiếm fb, zalo -->
    <meta property="og:title" content="Hệ thống quản lý rác thải Covid">
    <meta property="og:description" content="Hệ thống quản lý rác thải bệnh nhân Covid19">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://<?= $_SERVER['SERVER_NAME']; ?>/" />
    <meta property="og:image" content="https://syt.thuathienhue.gov.vn/uploadfiles/TinTuc/2022/1/3/f0cachlytainha03jpeg162705591519154291627055927.jpg" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="315" />
    <!-- Link -->
    <link href="../assets/css/bootstrap3/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/datatables.min.css" rel="stylesheet">
    <style>
        #map {
            position: relative;
            height: 600px;
            width: 100%;
        }

        .load {
            width: 100%;
            height: 100%;
            /* background: #fff; */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100000000000;
            display: none;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        .load img {
            width: 150px;
            height: 150px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -75px;
            margin-left: -75px;
        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }
    </style>
</head>