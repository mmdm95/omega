<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>wait OK!</title>

    <style>
        @font-face {
            font-family: "IRANSansWeb";
            font-style: normal;
            font-weight: 400;
            src: local("IRANSansWeb"), local("../fonts/IRANSansWeb"),
            url("<?= base_url('framework'); ?>/fonts/IRANSansWeb.woff") format("woff"),
            url("<?= base_url('framework'); ?>/fonts/IRANSansWeb.ttf") format("truetype")
        }

        body {
            font-family: IRANSansWeb, sans-serif;
            padding: 1%;
        }

        .message {
            height: auto;
            padding: 20px;
            border-radius: 7px;
            font-size: 18px;
            -webkit-box-shadow: 0 7px 18px 0rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0 7px 18px 0 #rgba(0, 0, 0, 0.12);
            box-shadow: 0 7px 18px 0 rgba(0, 0, 0, 0.12);
            direction: rtl;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="message">
    <?= $message; ?>
</div>
</body>
</html>