<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Signal Lost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/error.css' ?>">
</head>

<body>

    <div class="stage">
        <div class="ring ring--1"></div>
        <div class="ring ring--2"></div>
        <div class="ring ring--3"></div>
        <div class="radar"></div>

        <div class="content">
            <div class="eyebrow"><span class="dot"></span> Signal disconnected</div>

            <h1 class="code display">4<span>0</span>4</h1>
            <h2 class="headline display">The page you're looking for can't be found</h2>

            <div class="actions">
                <a href="<?= BASEURL . 'dashboard' ?>" class="btn-signal">Back to Dashboard</a>
                <a href="javascript:history.back()" class="btn-ghost">Previous Page</a>
            </div>

            <div class="coords display">ERR_CODE&nbsp;404 &nbsp;·&nbsp; STATUS&nbsp;NOT_FOUND &nbsp;·&nbsp; LAT&nbsp;0.000&nbsp;LON&nbsp;0.000</div>
        </div>
    </div>

</body>

</html>