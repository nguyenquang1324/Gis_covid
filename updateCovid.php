<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <center>
            <table>
                <tr>    
                    <td>Ca nhiễm mới: </td>
                    <td><input type="text" name="newcase" value="0"></td>
                </tr>
                <tr>    
                    <td>Ca phục hồi: </td>
                    <td><input type="text" name="newrecover" value="0"></td>
                </tr>
                <tr>    
                    <td>Ca tử vong: </td>
                    <td><input type="text" name="newdeath" value="0"></td>
                </tr>
            </table>
            <select name="type" id="">
                <option value="">CHỌN THÀNH PHỐ</option>
                <option value="An Giang">An Giang</option><option value="Bac Lieu">Bạc Liêu</option><option value="Bac Giang">Bắc Giang</option><option value="Bac Kan">Bắc Kạn</option><option value="Bac Ninh">Bắc Ninh</option><option value="Ben Tre">Bến Tre</option><option value="Ba Ria - Vung Tau">Bà Rịa - Vũng Tàu</option><option value="Binh Dinh">Bình Định</option><option value="Binh Duong">Bình Dương</option><option value="Binh Phuoc">Bình Phước</option><option value="Binh Thuan">Bình Thuận</option><option value="Can Tho">Cần Thơ</option><option value="Ca Mau">Cà Mau</option><option value="Cao Bang">Cao Bằng</option><option value="Dak Lak">Đắk Lắk</option><option value="Dak Nong">Đắk Nông</option><option value="Dong Nai">Đồng Nai</option><option value="Dong Thap">Đồng Tháp</option><option value="Da Nang">Đà Nẵng</option><option value="Dien Bien">Điện Biên</option><option value="Gia Lai">Gia Lai</option><option value="Hai Duong">Hải Dương</option><option value="Hai Phong">Hải Phòng</option><option value="Hau Giang">Hậu Giang</option><option value="Ho Chi Minh">Hồ Chí Minh</option><option value="Ha Giang">Hà Giang</option><option value="Ha Noi">Hà Nội</option><option value="Ha Nam">Hà Nam</option><option value="Ha Tinh">Hà Tĩnh</option><option value="Hoa Binh">Hoà Bình</option><option value="Hung Yen">Hưng Yên</option><option value="Khanh Hoa">Khánh Hòa</option><option value="Kien Giang">Kiên Giang</option><option value="Kon Tum">Kon Tum</option><option value="Lang Son">Lạng Sơn</option><option value="Lai Chau">Lai Châu</option><option value="Lam Dong">Lâm Đồng</option><option value="Lao Cai">Lào Cai</option><option value="Long An">Long An</option><option value="Nam Dinh">Nam Định</option><option value="Nghe An">Nghệ An</option><option value="Ninh Binh">Ninh Bình</option><option value="Ninh Thuan">Ninh Thuận</option><option value="Phu Tho">Phú Thọ</option><option value="Phu Yen">Phú Yên</option><option value="Quang Binh">Quảng Bình</option><option value="Quang Nam">Quảng Nam</option><option value="Quang Ngai">Quảng Ngãi</option><option value="Quang Ninh">Quảng Ninh</option><option value="Quang Tri">Quảng Trị</option><option value="Soc Trang">Sóc Trăng</option><option value="Son La">Sơn La</option><option value="Tay Ninh">Tây Ninh</option><option value="Thua Thien Hue">Thừa Thiên Huế</option><option value="Thai Binh">Thái Bình</option><option value="Thai Nguyen">Thái Nguyên</option><option value="Thanh Hoa">Thanh Hóa</option><option value="Tien Giang">Tiền Giang</option><option value="Tra Vinh">Trà Vinh</option><option value="Tuyen Quang">Tuyên Quang</option><option value="Vinh Long">Vĩnh Long</option><option value="Vinh Phuc">Vĩnh Phúc</option><option value="Yen Bai">Yên Bái</option>
            </select>
            <button name="btnSave" type="submit" id="" class="btn btn-primary" href="#" role="button" style="width:100px">UPDATE</button>
        </center>
        
    </form>
    <?php include 'CMR_pgsqlAPI.php' ?>
    <?php
        if(isset($_POST['btnSave'])){
            $newCase = $_POST['newcase'];
            $newRecover = $_POST['newrecover'];
            $newDeath = $_POST['newdeath'];
            $city = $_REQUEST['type'];
            if($city!= ""){
                $mySQLStr = "UPDATE covid set death = death +".$newDeath.",infected = infected + ".$newCase.", recovered = recovered + ".$newRecover.", active = infected - recovered - death where name like '".$city."'";
                query(initDB(),$mySQLStr);
                $mySQLStr = "UPDATE covid set active = infected - recovered - death where name like '".$city."'";
                query(initDB(),$mySQLStr);
                $city = "null";
                header("Location:CMR_highLightObj.php");
            }else{
                echo "Ban chua chon thanh pho";
            }
        }
    ?>
</body>
</html>