<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./bootstrap/css//bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Style\WebTemplate.css">
    <link rel="stylesheet" type="text/css" href="FontAwesome\css\all.css">
    <link rel="icon" href="Images\Logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="./Style/page/import.css">
    <link rel="stylesheet" href="./Style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="./js/jquery.js"></script>
    <title>Import/ Export</title>
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

    if ($_SESSION['isLogin']) {
        $sql = "SELECT r.Title, r.Id, r.Content, r.IsAppect, r.CreateDate, r.ReceiptType, s.StockName, e.FullName FROM receipt r INNER JOIN stock s ON r.StockId = s.Id INNER JOIN employee e ON r.EmployeeId = e.Id;";
        echo $sql;
        $result = mysqli_query($conn, $sql);
    } else {
        echo '<script language ="javascript">alert("Phiên đăng nhập hết hạn");</script>';
        header("Location: index.php");
    }

    // Lấy danh sách kho
    $sqlGetStock = "SELECT * FROM stock";
    $listStockForm = mysqli_query($conn, $sqlGetStock);

        // Lấy danh sách kho
        $sqlGetProductForm = "SELECT * FROM product";
        $listProductForm = mysqli_query($conn, $sqlGetProductForm);

    $receiptId =  $_COOKIE['receiptId'] ?? null;

    $listProduct;
    if ($receiptId) {
        $sqlGetProducts = "SELECT pr.ProductId, pr.ReceiptId, p.UnitPrice, p.ProductName, u.UnitName, pr.ActualNumber  FROM product_receipt pr INNER JOIN product p ON pr.ProductId = p.Id INNER JOIN unit u ON p.UnitId = u.Id WHERE pr.ReceiptId = " . $receiptId . ";";
        $listProduct = mysqli_query($conn, $sqlGetProducts);
    }

    mysqli_close($conn);
    ?>
</head>

<body>

    <div id="container">
        <div id="menu">
            <ul>
                <li><a href="TrangChu.php"><img src="Images\Logo.PNG" alt="Company's Logo"></a></li>
                <li><a href="TrangChu.php"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="NhaPhanPhoi.php"><i class="fa fa-industry"></i> Supplier</a></li>
                <li><a href="HangHoa.php"><i class="fa fa-clipboard-list"></i> Products</a></li>
                <li><a href="Kho.php"><i class="fa fa-arrow-alt-circle-down"></i> Warehouse</a></li>
                <li><a href="Nhap.php" class="active"><i class="fa fa-arrow-alt-circle-down"></i> Import/ Export</a></li>  
                <li><a href="Logout.php"><i class="fa fa-sign-out-alt"></i> Sign out</a></li>
            </ul>
        </div>
        <div id="content">
            <div>
                <table cellpadding="5">
                    <tr style="background-color: #bcbcbc; color:black;">
                        <td>STT</td>
                        <td>Tiêu đề</td>
                        <td>Nội dung</td>
                        <td>Loại phiếu</td>
                        <td>Kho</td>
                        <td>Người lập</td>
                        <td>Ngày lập</td>
                        <td></td>
                    </tr>
                    <?php
                    $stt = 0;
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $stt++;
                    ?>
                        <tr>
                            <td class="col-stt"><?php echo $stt; ?></td>
                            <td><?php echo $row['Title']; ?></td>
                            <td class="col-content" style="border:none"><?php echo $row['Content']; ?></td>
                            <td><?php if ($row['ReceiptType'] == 0) {
                                    echo "Phiếu nhập";
                                } else {
                                    echo "Phiếu xuất";
                                }; ?></td>
                            <td><?php echo $row['StockName']; ?></td>
                            <td><?php echo $row['FullName']; ?></td>
                            <td><?php echo  $row['CreateDate']; ?></td>
                            <td class="col-action">
                                <i class="fa-solid fa-pen-to-square icon-edit">
                                    <div class="mark-form">
                                        <div class="form">
                                            <div class="header">
                                                <div class="title">
                                                    Chỉnh sửa phiếu nhập
                                                </div>
                                            </div>
                                            <div class="content">
                                                <div style="display:none;">
                                                    <input type="text" id="receiptId" value="<?php echo $row['Id'] ?>">
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col">

                                                        <div class="input" style="width: 100%;">
                                                            <div class="input-label require">Tiêu đề</div>
                                                            <div class="cover-input">
                                                                <input type="text" name="title" id="title" value="<?php echo $row['Title']  ?>">
                                                            </div>
                                                            <span class="error-mess"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col">

                                                        <div class="control-text-area">
                                                            <div class="textarea-lable require">Nội dung</div>
                                                            <div class="container-controll">
                                                                <textarea name="description" id="description" cols="30" rows="3"><?php echo $row['Content'] ?></textarea>
                                                            </div>
                                                            <span class="error-mess"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col">
                                                        <div class="input" style="width: 100%;">
                                                            <div class="input-label require">Người lập</div>
                                                            <div class="cover-input ">
                                                                <input disabled type="text" name="title" id="title" value="<?php echo $row['FullName']  ?>">
                                                            </div>
                                                            <span class="error-mess"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input" style="width: 100%;">
                                                            <div class="input-label require">Ngày lập</div>
                                                            <div class="cover-input ">
                                                                <input type="date" name="title" id="title" value="<?php echo $row['CreateDate']  ?>">
                                                            </div>
                                                            <span class="error-mess"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col">
                                                        <div class="combobox-control" style="margin-right: 8px;">
                                                            <div class="combobox-lable require">
                                                                Kho hàng
                                                            </div>
                                                            <div class="container-controll">
                                                                <select style="width: 100%;" name="StockIdFrom" id="stock">
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
                                                    <div class="col"></div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col">
                                                        <div class="combobox-control" style="margin-right: 8px;">
                                                            <div class="combobox-lable require">
                                                            Sản phẩm
                                                            </div>
                                                            <div class="container-controll">
                                                                <select style="width: 100%;" name="ProductFrom" id="product">
                                                                    <?php
                                                                    while ($product = mysqli_fetch_assoc($listProductForm)) {
                                                                    ?>
                                                                        <option class='product-item-<?php echo $product['Id']; ?>' value='<?php echo $product['Id'];  ?>'><?php echo $product['ProductName'];  ?></option>
                                                                        <script>
                                                                            $('.product-item-<?php echo $product['Id']; ?>').data(<?php $product['ProductName'];?>)
                                                                        </script>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <span class="error-mess"></span>

                                                        </div>
                                                        <div class="list-product">
                                                            <table class="table-form">
                                                                <tr style="background-color: #bcbcbc; color:black;">
                                                                    <td class="col-stt-form">STT</td>
                                                                    <td>Tên sản phẩm</td>
                                                                    <td class="col-unit-form">Đơn vị tính</td>
                                                                    <td class="col-price-form">Đơn giá</td>
                                                                    <td class="col-quantity-form">Số lượng</td>
                                                                    <td class="col-action-form">
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $stt = 0;

                                                                while ($row = mysqli_fetch_assoc($listProduct)) {
                                                                    $stt++
                                                                ?>
                                                                    <tr class="product-<?php echo $stt ?>">
                                                                        <td class="col-stt-form"><?php echo $stt ?></td>
                                                                        <td class="col-product-name-form"><?php echo $row['ProductName'];  ?></td>
                                                                        <td class=""><?php echo $row['UnitName'];  ?></td>
                                                                        <td class="col-supplier-form"><?php echo $row['UnitPrice'];  ?></td>
                                                                        <td class="col-quantity-form"><?php echo $row['ActualNumber'];  ?></td>
                                                                        <td class="col-action-form">
                                                                            <i class="fa-solid fa-trash icon-delete-product"></i>

                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="footer">
                                                <div class="row">
                                                    <div class="col">
                                                     <div class="btn btn-close" style="background: #ccc;border-radius: 6px;text-align: center; width: 100%;">
                                                     Đóng
                                                    </div>
                                                    </div>
                                                    <div class="col">
                                                        <input style="background: green;color: white;" class="btn btn-primar" type="submit" value="Lưu">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </i>
                                <i class="fa-solid fa-trash icon-delete"></i>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

    </div>
</body>
<script src="./bootstrap/js//bootstrap.min.js"></script>
<script src="./js/jquery.js"></script>
<script src="./js/import.js"></script>

</html>