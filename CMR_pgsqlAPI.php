<?php
if (isset($_POST['functionname'])) {
    $paPDO = initDB();
    $paSRID = '4326';
    $paPoint = $_POST['paPoint'];
    $functionname = $_POST['functionname'];

    //$aResult = "null";
    if ($functionname == 'getGeoCMRToAjax')
        getGeoCMRToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getInfoCMRToAjax')
        echo getInfoCMRToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'RedZone')
        RedZone($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'OrangeZone')
        OrangeZone($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'GreenZone')
        GreenZone($paPDO, $paSRID, $paPoint);
    else if ($functionname == "GetAllCity")
        GetAllCity($paPDO, $paSRID, $paPoint);

    //echo $aResult;

    closeDB($paPDO);
}

function initDB()
{
    // Kết nối CSDL
    $paPDO = new PDO('pgsql:host=localhost;dbname=BTL;port=5432', 'postgres', 'quang');
    return $paPDO;
}
function query($paPDO, $paSQLStr)
{
    try {
        // Khai báo exception
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sử đụng Prepare 
        $stmt = $paPDO->prepare($paSQLStr);
        // Thực thi câu truy vấn
        $stmt->execute();

        // Khai báo fetch kiểu mảng kết hợp
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Lấy danh sách kết quả
        $paResult = $stmt->fetchAll();
        return $paResult;
    } catch (PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
}

function query2($paPDO, $paSQLStr)
{
    try {
        // Khai báo exception
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sử đụng Prepare 
        $stmt = $paPDO->prepare($paSQLStr);

        // Khai báo fetch kiểu mảng kết hợp
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Thực thi câu truy vấn
        $stmt->execute();

        // Lấy danh sách kết quả
        $paResult = $stmt->fetchAll();
        return $paResult;
    } catch (PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
}

function closeDB($paPDO)
{
    // Ngắt kết nối
    $paPDO = null;
}
/*
function example1($paPDO)
{
    $mySQLStr = "SELECT * FROM \"gadm36_vnm_1\"";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            echo $item['name_0'] . ' - ' . $item['name_1'];
            echo "<br>";
        }
    } else {
        echo "example1 - null";
        echo "<br>";
    }
}
function example2($paPDO)
{
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\"";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            echo $item['geo'];
            echo "<br><br>";
        }
    } else {
        echo "example2 - null";
        echo "<br>";
    }
}
function example3($paPDO, $paSRID, $paPoint)
{
    echo $paPoint;
    echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    echo $paPoint;
    echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=" . $paSRID . ";" . $paPoint . "'::geometry,geom)";
    echo $mySQLStr;
    echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            echo $item['geo'];
            echo "<br><br>";
        }
    } else {
        echo "example2 - null";
        echo "<br>";
    }
}
*/
function getResult($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=" . $paSRID . ";" . $paPoint . "'::geometry,geom)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
}
function getGeoCMRToAjax($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=" . $paSRID . ";" . $paPoint . "'::geometry,geom)";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from gadm36_vnm_1 where varname_1 in (select name from covid where infected >=50 and infected <100)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            echo $item['geo'];
        }
    }
}

function RedZone($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from gadm36_vnm_1 where varname_1 in (select name from covid where infected >=100)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $arr = [];
        for ($i = 0; $i < sizeof($result); $i++) {
            array_push($arr, $result[$i]['geo']);
        }
        // // Lặp kết quả
        // foreach ($result as $item){
        //     echo $item['geo'];
        // }
        echo str_replace('}"', '}', str_replace('"{', '{', str_replace('\\', '', json_encode($arr))));
    }
}

function OrangeZone($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from gadm36_vnm_1 where varname_1 in (select name from covid where infected >=50 and infected <100)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $arr = [];
        for ($i = 0; $i < sizeof($result); $i++) {
            array_push($arr, $result[$i]['geo']);
        }
        // // Lặp kết quả
        // foreach ($result as $item){
        //     echo $item['geo'];
        // }
        echo str_replace('}"', '}', str_replace('"{', '{', str_replace('\\', '', json_encode($arr))));
    }
}

function GreenZone($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from gadm36_vnm_1 where varname_1 in (select name from covid where infected >=1 and infected <50)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $arr = [];
        for ($i = 0; $i < sizeof($result); $i++) {
            array_push($arr, $result[$i]['geo']);
        }
        // // Lặp kết quả
        // foreach ($result as $item){
        //     echo $item['geo'];
        // }
        echo str_replace('}"', '}', str_replace('"{', '{', str_replace('\\', '', json_encode($arr))));
    }
}


function getInfoCMRToAjax($paPDO, $paSRID, $paPoint)
{
    //echo $paPoint;
    //echo "<br>";
    $paPoint = str_replace(',', ' ', $paPoint);
    //echo $paPoint;
    //echo "<br>";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=4326;POINT(12 5)'::geometry,geom)";
    //$mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_vnm_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
    $mySQLStr = "SELECT covid.name_vn, covid.infected, covid.death, covid.recovered, covid.active FROM covid, gadm36_vnm_1 WHERE covid.name = gadm36_vnm_1.varname_1 AND ST_Within('SRID=" . $paSRID . ";" . $paPoint . "'::geometry,geom)";
    //echo $mySQLStr;
    //echo "<br><br>";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // Lặp kết quả
        foreach ($result as $item) {
            $resFin = $resFin . '<tr><td>Tỉnh/Thành phố: ' . $item['name_vn'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Tổng ca nhiễm: ' . $item['infected'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Đang bị nhiễm: ' . $item['active'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Tử vong: ' . $item['death'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Phục hồi: ' . $item['recovered'] . '</td></tr>';
            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "null";
}

function GetAllCity($paPDO, $paSRID, $paPoint){
    $mySQLStr = "SELECT varname_1, name_1 FROM gadm36_vnm_1";

    $result = query($paPDO, $mySQLStr);

    if($result != null){
        $resFin = "";
        foreach($result as $item){
            $resFin = $resFin . '<option value="'. $item['varname_1'] .'">'. $item['name_1']. '</option>';
        }
        echo $resFin;
    }
}