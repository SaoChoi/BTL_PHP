<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style\WebTemplate.css">
    <link rel="stylesheet" type="text/css" href="FontAwesome/css/all.css">
    <link rel="icon" href="Images\Logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="./Style/style.css">
    <link rel="stylesheet" href="./Style/page/index.css">
    <link rel="stylesheet" href="./Style/page/hanghoa.css">
    <link rel="stylesheet" href="./bootstrap/css//bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Products</title>
    <?php
    session_start();
    require __DIR__ . '.\common\configdb.php';
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    mysqli_set_charset($conn, "utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit();
    }

    $textSearch = $_GET['textSearch'] ?? '';
    $subpierID = $_GET['SubpierID'] ?? -1;
    $stockId = $_GET['StockId'] ?? -1;

    if ($_SESSION['isLogin']) {
        $sql = "SELECT   p.*, u.UnitName, s.StockName, s1.SuplierName FROM product p INNER JOIN unit u ON p.UnitId = u.Id  INNER JOIN stock s ON p.StockId = s.Id INNER JOIN suplier s1 ON p.SuplierId = s1.Id WHERE p.ProductName LIKE '%" . $textSearch . "%' " . ($subpierID == -1 ? '' : 'AND p.SuplierId = ' . $subpierID . '') . " " . ($stockId == -1 ? '' : 'AND p.StockId = ' . $stockId . '') . ";";
        echo $sql;
        $result = mysqli_query($conn, $sql);
    } else {
        echo '<script language ="javascript">alert("Phiên đăng nhập hết hạn");</script>';
        header("Location: index.php");
    }
    
    $sqlTotal = "SELECT COUNT(*) AS Total FROM product p INNER JOIN unit u ON p.UnitId = u.Id  INNER JOIN stock s ON p.StockId = s.Id INNER JOIN suplier s1 ON p.SuplierId = s1.Id WHERE p.ProductName LIKE '%" . $textSearch . "%' " . ($subpierID == -1 ? '' : 'AND p.SuplierId = ' . $subpierID . '') . " " . ($stockId == -1 ? '' : 'AND p.StockId = ' . $stockId . '') . " ;";

    $total = mysqli_fetch_array(mysqli_query($conn, $sqlTotal))[0];

    // Lấy danh sách kho
    $sqlGetStock = "SELECT * FROM stock";
    $listStock = mysqli_query($conn, $sqlGetStock);
    $listStockForm = mysqli_query($conn, $sqlGetStock);

    // Lấy danh sách nhà cung cấp

    $sqlGetSuplier = "SELECT * FROM suplier";
    $listSuplier = mysqli_query($conn, $sqlGetSuplier);
    $listSuplierForm = mysqli_query($conn, $sqlGetSuplier);
    // Lấy danh sách đơn vị

    $sqlGetUnit = "SELECT * FROM unit";
    $listUnit = mysqli_query($conn, $sqlGetUnit);

    if (count($_GET) > 0) {
        $a = $_GET["StockId"] ?? null ;
        $b =  $_GET["Id"] ?? null ;
        if ($a && $b) {

            $sqlDelete = "DELETE FROM product p WHERE p.Id = " . $_GET["Id"] . " AND p.StockId = " . $_GET["StockId"] . "";
            echo $sqlDelete;
            mysqli_query($conn, $sqlDelete);

            $total = mysqli_fetch_array(mysqli_query($conn, $sqlTotal))[0];

            $result = mysqli_query($conn, $sql);
        }
    }



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $typeForm =  $_COOKIE['typeForm'];
        $result;
        if ($typeForm == 'update') {
            $sqlUpdate = "UPDATE product p SET  StockId = " . $_POST['StockIdFrom'] . ", SuplierId = " . $_POST['subpierIDForm'] . ", UnitId = " . $_POST['unit'] . ", Description = '" . $_POST['description'] . "', ProductName = '" . $_POST['productName'] . "', Quantity = " . $_POST['quantity'] . ",  UnitDisplay = '', UnitPrice = " . $_POST['price'] . "  WHERE Id = " . $_POST['productId'] . " AND StockId = " . $_POST['StockIdFrom'] . ";";
            mysqli_query($conn, $sqlUpdate);
        } else if ($typeForm == 'insert') {
            $newId = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(Id) + 1 FROM product"))[0];

            $sqlInsert = "INSERT INTO product (Id, StockId, SuplierId, UnitId, Description, ProductName, Quantity, UnitDisplay, UnitPrice) VALUES (" . $newId . ", " . $_POST['StockIdFrom'] . ", " . $_POST['subpierIDForm'] . ", " . $_POST['unit'] . ", '" . $_POST['description'] . "', '" . $_POST['productName'] . "', " . $_POST['quantity'] . ", '', " . $_POST['price'] . ");";
            echo $sqlInsert;
            mysqli_query($conn, $sqlInsert);
        }

        $total = mysqli_fetch_array(mysqli_query($conn, $sqlTotal))[0];

        $result = mysqli_query($conn, $sql);
    }

    mysqli_close($conn);


    ?>
</head>

<body>
    <div id="container">
        <!-- header  -->
        <div id="menu">
            <ul>
                <li><a href="TrangChu.php"><img src="Images\Logo.PNG" alt="Company's Logo"></a></li>
                <li><a href="TrangChu.php"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="NhaPhanPhoi.php"><i class="fa fa-industry"></i> Supplier</a></li>
                <li><a href="HangHoa.php" class="active"><i class="fa fa-clipboard-list"></i> Products</a></li>
                <li><a href="Kho.php"><i class="fa fa-arrow-alt-circle-down"></i> Warehouse</a></li>
                <li><a href="Nhap.php"><i class="fa fa-arrow-alt-circle-down"></i> Import/ Export</a></li>
                <li><a href="Logout.php"><i class="fa fa-sign-out-alt"></i> Sign out</a></li>
            </ul>
        </div>
        <!-- content -->
        <div class="container-product-page">
            <form action="" method="GET" class="toolbar">


                <div class="group-control">
                    <div class="search mr-3">
                        <input class="c" type="text" name="textSearch" id="" placeholder="Nhập tên sản phẩm" value="<?php echo $textSearch ?>">
                        <img src="./Images/icons/magnifying-glass-solid.svg" class="icon">
                    </div>
                    <div class="combobox-control" style="margin-right: 8px;">
                        <div class="combobox-lable">
                            Kho hàng
                        </div>
                        <select name="StockId" id="">
                            <option value='-1'>Tất cả</option>
                            <?php
                            while ($stock = mysqli_fetch_assoc($listStock)) {
                            ?>
                                <option value='<?php echo $stock['Id'];  ?>' <?php $selectedStock = $stockId == $stock['Id'] ? 'selected' : '';
                                                                                echo $selectedStock; ?>><?php echo $stock['StockName'];  ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="combobox-control" style="margin-right: 8px;">
                        <div class="combobox-lable">
                            Nhà cung cấp
                        </div>
                        <select name="SubpierID" id="">
                            <option value='-1'>Tất cả</option>

                            <?php
                            while ($suplier = mysqli_fetch_assoc($listSuplier)) {
                            ?>
                                <option value='<?php echo $suplier['Id'];  ?>' <?php $selected = $subpierID == $suplier['Id'] ? 'selected' : '';
                                    echo $selected; ?>><?php echo $suplier['SuplierName'];  ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <input id="search" type="submit" value="Tìm kiếm" name="search">
                </div>
                <div class="btn btn-primar btn-add">Thêm sản phẩm</div>

            </form>
            <!-- table -->

            <div style="height: 100% ;">
                <table cellpadding="5">
                    <table>
                        <tr style="background-color: #bcbcbc; color:black;">
                            <td class="col-stt">STT</td>
                            <td class="col-product-name">Tên sản phẩm</td>
                            <td class="col-unit">Đơn vị tính</td>
                            <td>
                                <div class="col-description">

                                    Mô tả
                                </div>
                            </td>
                            <td class="col-stock">Kho</td>
                            <td class="col-suplier">Nhà cung cấp</td>
                            <td class="col-suplier">Số lượng</td>
                            <td class="col-price">Giá bán</td>
                            <td class="col-action-header"></td>
                        </tr>
                    </table>
                    <div class="container-data-table">
                        <table>
                            <?php
                            $stt = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $stt++;
                            ?>
                                <tr>
                                    <td class="col-stt"><?php echo $stt; ?></td>
                                    <td class="col-product-name">
                                        <?php echo $row['ProductName']; ?>
                                    </td>
                    </div>
                    <td class="col-unit"><?php echo $row['UnitName']; ?></td>

                    <td>
                        <?php echo '<div class="col-description" title="' . $row['Description'] . '" .> '
                                    . $row['Description'] .
                                    '</div> ';
                        ?>
                    </td>
                    <td class="col-stock"><?php echo $row['StockName']; ?></td>
                    <td class="col-suplier"><?php echo $row['SuplierName']; ?></td>
                    <td class="col-suplier"><?php echo $row['Quantity']; ?></td>

                    <td class="col-price"><?php echo number_format($row['UnitPrice'], 0, '', ',') . ' VND'; ?></td>
                    <script>
                        var <?php echo 'data_' . $row['Id'] ?> = <?php echo (json_encode($row)); ?>;
                    </script>
                    <td class="col-action-header">
                        <a class="btn icon-edit" onclick="<?php echo 'openForm(data_' . $row['Id'] . ')' ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a class="btn icon-delete" href="HangHoa.php?Id=<?php echo $row["Id"] . "&StockId=" . $row["StockId"]; ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>

                    </tr>
                <?php } ?>
                </table>
            </div>

            </table>
        </div>
    </div>

    <div class="paging">

        <div class="total-record">
            <?php
            echo "Tổng cộng: <b>" . $total . "</b> sản phẩm";
            ?>
        </div>

    </div>

    <div class="mark-form">
        <div class="form">
            <!-- Title form -->
            <div class="form-header">
                <div class="form-title" style="font-size: 20px; font-weight: bold;">
                    Sửa thông tin sản phẩm
                </div>
            </div>
            <!-- Content form -->
            <div class="form-content">
                <form action="HangHoa.php" method="post" id="form">
                    <input type="hidden" name="productId" id="productId">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="input">
                                <div class="input-label require">Tên sản phẩm</div>
                                <div class="cover-input">
                                    <input type="text" name="productName" id="productName">
                                </div>
                                <span class="error-mess"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input">
                                <div class="input-label require">Số lượng</div>
                                <div class="cover-input">
                                    <input type="number" name="quantity" id="quantity">
                                </div>
                                <span class="error-mess"></span>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="combobox-control" style="margin-right: 8px;">
                                <div class="combobox-lable require">
                                    Đơn vị tính
                                </div>
                                <div class="container-controll">
                                    <select name="unit" id="unit">
                                        <?php
                                        while ($unit = mysqli_fetch_assoc($listUnit)) {
                                        ?>
                                            <option value='<?php echo $unit['Id'];  ?>'><?php echo $unit['UnitName'];  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="error-mess"></span>

                            </div>
                        </div>
                        <div class="col">
                            <div class="input">
                                <div class="input-label require">Đơn giá</div>
                                <div class="cover-input" style="display: flex;align-items: center;padding-right: 8px;">
                                    <input type="number" name="price" id="price">
                                    VNĐ
                                </div>
                                <span class="error-mess"></span>

                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col">
                            <div class="combobox-control" style="margin-right: 8px;">
                                <div class="combobox-lable require">
                                    Kho hàng
                                </div>
                                <div class="container-controll">
                                    <select name="StockIdFrom" id="stock">
                                        <?php
                                        while ($stockForm = mysqli_fetch_assoc($listStockForm)) {
                                        ?>
                                            <option value='<?php echo $stockForm['Id'];  ?>'><?php echo $stockForm['StockName'];  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="error-mess"></span>

                            </div>
                        </div>

                        <div class="col">
                            <div class="combobox-control" style="margin-right: 8px;">
                                <div class="combobox-lable require">
                                    Nhà cung cấp
                                </div>
                                <div class="container-controll">

                                    <select name="subpierIDForm" id="subpier">

                                        <?php
                                        while ($suplierForm = mysqli_fetch_assoc($listSuplierForm)) {
                                        ?>
                                            <option value='<?php echo $suplierForm['Id'];  ?>'><?php echo $suplierForm['SuplierName'];  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="error-mess"></span>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="control-text-area">
                                <div class="textarea-lable require">Mô tả</div>
                                <div class="container-controll">
                                    <textarea name="description" id="description" cols="30" rows="5"></textarea>
                                </div>
                                <span class="error-mess"></span>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- footer form -->
            <div class="form-footer">
                <div class="btn2 mr-1 close-form">
                    Đóng
                </div>
                <input type="submit" class="btn2 btn-primar btn-save" value="Lưu" onclick="validationController" style="width: 120px;">

            </div>
        </div>

    </div>
</body>
<script src="./bootstrap/js//bootstrap.min.js"></script>
<script src="./js/jquery.js"></script>
<script src="./js/hanghoa.js"></script>

</html>