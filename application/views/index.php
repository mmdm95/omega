<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form test</title>
</head>
<body>
<form action="index.php" method="post">
    <?= $data['form_token']; ?>

    <label for="">نام:</label>
    <input type="text" value="" name="name">

    <button type="submit">
        تست
    </button>
</form>
</body>
</html>