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
    <link rel="stylesheet" href="./Style/page/nhaphanphoi.css">
    <link rel="stylesheet" href="./bootstrap/css//bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>WareHouse</title>
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
    $address = $_GET['Address']??-1;
    $stockName = $_GET['StockName']??-1;

    if ($_SESSION['isLogin']) {
        $sql = "SELECT s.StockName, s.Address, p.ProductName, p.Quantity, p.UnitDisplay, sp.SuplierName, s.Id FROM stock s LEFT JOIN product p ON s.Id = p.StockId left JOIN suplier sp ON p.SuplierId = sp.Id WHERE s.Id LIKE '%".$textSearch."%' ".($address==-1?'':'AND s.Address LIKE "%'.($address).'%"')." ".($stockName==-1?'':'AND s.StockName LIKE "%'.($stockName).'%"').";";
        echo $sql;
        $result = mysqli_query($conn, $sql);
    } else {
        echo '<script language ="javascript">alert("Phiên đăng nhập hết hạn");</script>';
        header("Location: index.php");
    }

    

    $sqlTotal = "SELECT COUNT(*) AS Total FROM stock";

    $total = mysqli_fetch_array(mysqli_query($conn, $sqlTotal))[0];
    $listAddress = mysqli_query($conn,"SELECT DISTINCT Address FROM stock;");
    $listName = mysqli_query($conn, "SELECT StockName FROM stock;");


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $typeForm =  $_COOKIE['typeForm'];
        $result;

        if ($typeForm == 'update') {
            $sqlUpdate = " UPDATE stock SET StockName = '".$_POST['StockName']."', Address = '".$_POST['Address']."' WHERE Id = ".$_POST['StockId']."; ";
            mysqli_query($conn, $sqlUpdate);

        } else if ($typeForm == 'insert') {
            //$newId = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(Id) + 1 FROM product"))[0];

            $sqlInsert = "INSERT INTO stock (StockName, Address)
            VALUES ('".$_POST['StockName']."', '".$_POST['Address']."');";
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
                <li><a href="HangHoa.php"><i class="fa fa-clipboard-list"></i> Products</a></li>
                <li><a href="Kho.php" class="active"><i class="fa fa-arrow-alt-circle-down"></i> Warehouse</a></li>
                <li><a href="Nhap.php"><i class="fa fa-arrow-alt-circle-down"></i> Import/ Export</a></li>
                <li><a href="Logout.php"><i class="fa fa-sign-out-alt"></i> Sign out</a></li>
            </ul>
        </div>
        <!-- content -->
        <div class="container-product-page">
            <form action="" method="GET" class="toolbar" style="width: 50%; margin-left: 25%">
                <div class="group-control">
                    <div class="search mr-3">
                        <input class="c" type="text" name="textSearch" id="" placeholder="Mã kho" value="<?php echo $textSearch ?>">
                        <img src="./Images/icons/magnifying-glass-solid.svg" class="icon">
                    </div>
                    <div class="combobox-control" style="margin-right: 8px;">
                        <div class="combobox-lable">
                            Địa chỉ kho
                        </div>
                        <select name="Address" id="">
                            <option value='-1'>Tất cả</option>
                            <?php while ($stock = mysqli_fetch_assoc($listAddress)){?>
                                <option><?php echo $stock['Address'];  ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="combobox-control" style="margin-right: 8px;">
                        <div class="combobox-lable">
                            Tên kho
                        </div>
                        <select name="StockName" id="">
                            <option value='-1'>Tất cả</option>
                            <?php
                            while ($stock = mysqli_fetch_assoc($listName)) {
                            ?>
                                <option><?php echo $stock['StockName'];  ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <input id="search" type="submit" value="Tìm kiếm" name="search">
                </div>
                <div class="btn btn-primar btn-add">Thêm</div>
            </form>

            <!-- table -->

            <div style="height: 100% ; width: 70%; margin-left: 15%">
                <table cellpadding="5">
                    <!-- <table> -->
                        <tr style="background-color: #bcbcbc; color:black;">
                            <td class="col-stt">STT</td>
                            <td class="col-supplier-name">Tên kho</td>
                            <td class="col-address">Địa chỉ</td>
                            <td class="col-phonenumber">Tên sản phẩm</td>
                            <td class="col-email">Số lượng</td>
                            <td class="col-email">Đơn vị</td>
                            <td class="col-email">Nhà phân phối</td>
                            <td class="col-action" style="border:none;"></td>
                        </tr>
                            <?php
                            $stt = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $stt++
                            ?>
                                <tr>
                                    <td class="col-stt"><?php echo $stt; ?></td>
                                    <td class="col-supplier-name">
                                        <?php echo $row['StockName']; ?>
                                </td>
                            </div>
                            <td class="col-address"><?php echo $row['Address']; ?></td>
                            
                            <td class="col-phonenumber">
                                <?php echo $row['ProductName']; ?>
                            </td>

                            <td class="col-email"><?php echo $row['Quantity']; ?></td>
                            <td class="col-email"><?php echo $row['UnitDisplay']; ?></td>
                            <td class="col-email"><?php echo $row['SuplierName']; ?></td>
                                <script>
                                    var <?php echo 'data_' . $row['Id'] ?> = <?php echo (json_encode($row)); ?>;
                                </script>
                        <td class="col-action" style="border:none;">
                            <a class="btn icon-edit" onclick="<?php echo 'openForm(data_' . $row['Id'] . ')' ?>">
                            <i class="fa-solid fa-pen-to-square icon-edit"></i>
                            </a>
                        </td>
                        </tr>
                <?php } ?>
                <!-- </table> -->
            </table>
        </div>
                            </div>

    <div class="paging">

        <div class="total-record">
            <?php
            echo "Kho quản lý: <b>", $total;
            ?>
        </div>

    </div>

    <div class="mark-form">
        <div class="form" style="min-height: auto; top: 30%">
            <!-- Title form -->
            <div class="form-header">
                <div class="form-title" style="font-size: 20px; font-weight: bold;">
                    Sửa thông tin kho
                </div>
            </div>
            <!-- Content form -->
            <div class="form-content">
                <form action="Kho.php" method="post" id="form">
                    <input type="hidden" name="StockId" id="StockId">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="input">
                                <div class="input-label require">Tên kho</div>
                                <div class="cover-input">
                                    <input type="text" name="StockName" id="StockName">
                                </div>
                                <span class="error-mess"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input">
                                <div class="input-label require">Địa chỉ</div>
                                <div class="cover-input">
                                    <input type="text" name="Address" id="Address">
                                </div>
                        <span class="error-mess"></span>
                    </div>
                </div>
            </div>
                    
                    <div class="row mt-4">
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
<script src="./js/kho.js"></script>
</html>
