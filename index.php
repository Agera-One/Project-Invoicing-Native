<?php
require_once 'connection.php';

$sql = 'SELECT * FROM item';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body>
    <div class="container-fluid">
        <a href="index-add.php" class="btn btn-primary">Add New Item</a>
        <div class="card">
            <div class="card-body">
                <div id="users-table" class="tabulator" role="grid" aria-owns="tabulator-table-body"
                    tabulator-layout="fitColumns">
                    <div class="tabulator-header" role="rowgroup">
                        <div class="tabulator-header-contents">
                            <div class="tabulator-headers" role="row" style="height: 84px;">
                                <div class="tabulator-col tabulator-sortable tabulator-col-sorter-element"
                                    role="columnheader" aria-sort="none" tabulator-field="id"
                                    style="min-width: 40px; width: 60px; height: 84px;">
                                    <div class="tabulator-col-content">
                                        <div class="tabulator-col-title-holder">
                                            <div class="tabulator-col-title">#</div>
                                            <div class="tabulator-col-sorter">
                                                <div class="tabulator-arrow"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div><span class="tabulator-col-resize-handle" style="height: 84px;"></span>
                                <div class="tabulator-col tabulator-sortable tabulator-col-sorter-element"
                                role="columnheader" aria-sort="none" tabulator-field="reference_number"
                                style="min-width: 40px; width: 245px; height: 84px;">
                                <div class="tabulator-col-content">
                                    <div class="tabulator-col-title-holder">
                                        <div class="tabulator-col-title">Reference Number</div>
                                        <div class="tabulator-col-sorter">
                                            <div class="tabulator-arrow"></div>
                                        </div>
                                    </div>
                                    <div class="tabulator-header-filter"><input type="search"
                                    style="padding: 4px; width: 100%; box-sizing: border-box;"
                                    placeholder=""></div>
                                </div>
                            </div><span class="tabulator-col-resize-handle" style="height: 84px;"></span>
                            <div class="tabulator-col tabulator-sortable tabulator-col-sorter-element"
                                role="columnheader" aria-sort="none" tabulator-field="name"
                                style="min-width: 40px; width: 244px; height: 84px;">
                                <div class="tabulator-col-content">
                                    <div class="tabulator-col-title-holder">
                                        <div class="tabulator-col-title">Name</div>
                                        <div class="tabulator-col-sorter">
                                            <div class="tabulator-arrow"></div>
                                        </div>
                                    </div>
                                    <div class="tabulator-header-filter"><input type="search"
                                            style="padding: 4px; width: 100%; box-sizing: border-box;"
                                            placeholder=""></div>
                                </div>
                            </div><span class="tabulator-col-resize-handle" style="height: 84px;"></span>
                            <div class="tabulator-col tabulator-sortable tabulator-col-sorter-element"
                                    role="columnheader" aria-sort="none" tabulator-field="Price"
                                    style="min-width: 40px; width: 120px; height: 84px;">
                                    <div class="tabulator-col-content">
                                        <div class="tabulator-col-title-holder">
                                            <div class="tabulator-col-title">Price</div>
                                            <div class="tabulator-col-sorter">
                                                <div class="tabulator-arrow"></div>
                                            </div>
                                        </div>
                                        <div class="tabulator-header-filter"><input type="search"
                                                style="padding: 4px; width: 100%; box-sizing: border-box; cursor: default; caret-color: transparent;"
                                                placeholder=""></div>
                                    </div>
                                </div><span class="tabulator-col-resize-handle" style="height: 84px;"></span>
                                <div class="tabulator-col tabulator-sortable tabulator-col-sorter-element"
                                    role="columnheader" aria-sort="none" tabulator-field="action"
                                    style="min-width: 40px; width: 130px; height: 84px;">
                                    <div class="tabulator-col-content">
                                        <div class="tabulator-col-title-holder">
                                            <div class="tabulator-col-title">Action</div>
                                            <div class="tabulator-col-sorter">
                                                <div class="tabulator-arrow"></div>
                                            </div>
                                        </div>
                                        <div class="tabulator-header-filter"><input type="search"
                                                style="padding: 4px; width: 100%; box-sizing: border-box; cursor: default; caret-color: transparent;"
                                                placeholder=""></div>
                                    </div>
                                </div><span class="tabulator-col-resize-handle" style="height: 84px;"></span>
                            </div>
                            <div class="tabulator-frozen-rows-holder" style="min-width: 0px;"></div>
                        </div>
                    </div>
                    <div class="tabulator-tableholder" tabindex="0" style="height: 490px;">
                        <div class="tabulator-table" role="rowgroup" id="tabulator-table-body"
                            style="padding-top: 0px; padding-bottom: 0px;">
                            <?php while($item = mysqli_fetch_assoc($result)): ?>
                                <div class="tabulator-row tabulator-selectable tabulator-row-odd" role="row">
                                    <div class="tabulator-cell" role="gridcell" style="width: 60px; height: 48px;"
                                        tabulator-field="id"><?= $item['id'] ?></div>
                                    <div class="tabulator-cell" role="gridcell" style="width: 244px; height: 48px;"
                                        tabulator-field="name"><?= $item['ref_no'] ?></div>
                                    <div class="tabulator-cell" role="gridcell" style="width: 245px; height: 48px;"
                                        tabulator-field="email"><?= $item['name'] ?></div>
                                    <div class="tabulator-cell" role="gridcell" style="width: 120px; height: 48px;"
                                        tabulator-field="role"><?= $item['price'] ?></div>
                                    <div class="tabulator-cell" role="gridcell" style="width: 100px; height: 48px;"
                                        tabulator-field="role">
                                        <a class="btn btn-sm btn-success" href="index-edit.php?id=<?= $item['id'] ?>">Edit</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>
