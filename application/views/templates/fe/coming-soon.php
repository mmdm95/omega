<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>در دست تعمیر</title>

    <style>
        @font-face {
            font-family: "IRANSansWeb";
            font-style: normal;
            font-weight: 400;
            src: local("IRANSansWeb"), local("../fonts/IRANSansWeb"),
            url("<?= base_url('framework'); ?>/fonts/IRANSansWeb.woff") format("woff"),
            url("<?= base_url('framework'); ?>/fonts/IRANSansWeb.ttf") format("truetype")
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: IRANSansWeb, sans-serif;
            padding: 1%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            background: linear-gradient(to left, #f5548e, #fa8b0c);
            text-align: center;
            display: -ms-flexbox;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        h1 {
            font-size: 3rem;
        }

        a {
            text-decoration: none;
            font-size: 1.4rem;
            margin-top: 25px;
            display: inline-block;
            color: #ddd;
            padding: 10px 24px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>
        <span style="display: block;">سایت در دست تعمیر می‌باشد</span>
        <a href="<?= base_url('index'); ?>">
            بازگشت
        </a>
    </h1>
</body>
</html>