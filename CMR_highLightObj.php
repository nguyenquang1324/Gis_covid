<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <link rel="stylesheet" href="http://localhost:8080/libs/openlayers/css/ol.css" type="text/css" />
    <script src="http://localhost:8080/libs/openlayers/build/ol.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

    <script src="http://localhost:8080/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script>

    <style>
        /* css pop up */
        .ol-popup {
            position: absolute;
            background-color: white;
            -webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #cccccc;
            bottom: 12px;
            left: -50px;
            min-width: 180px;
        }

        .ol-popup:after,
        .ol-popup:before {
            top: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .ol-popup:after {
            border-top-color: white;
            border-width: 10px;
            left: 48px;
            margin-left: -10px;
        }

        .ol-popup:before {
            border-top-color: #cccccc;
            border-width: 11px;
            left: 48px;
            margin-left: -11px;
        }

        .ol-popup-closer {
            text-decoration: none;
            position: absolute;
            top: 2px;
            right: 8px;
        }

        .ol-popup-closer:after {
            content: "✖";
        }
    </style>

</head>

<body onload="initialize_map();">
    <?php include 'header.php' ?>
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content">
        </div>
    </div>
    
    <table>
        <tr>
            <td>
                <div id="map" style="width: 80vw; height: 100vh;"></div>
            </td>
            <td>
                <img src="soluongcovid.png" alt="">
                <div id="info"></div>
                <a href="updateCovid.php" class="btn btn-info" role="button">Cập nhật covid</a>
            </td>
        </tr>
    </table>
    <?php include 'CMR_pgsqlAPI.php' ?>
    <?php
    // $myPDO = initDB();
    // $mySRID = '4326';
    // $pointFormat = 'POINT(12,5)';

    // example1($myPDO);
    // example2($myPDO);
    // example3($myPDO,'4326','POINT(12,5)');
    // $result = getResult($myPDO,$mySRID,$pointFormat);

    // closeDB($myPDO);
    ?>
    <script>
        var format = 'image/png';
        var map;
        var minX = 102.144584655762;
        var minY = 8.38135528564453;
        var maxX = 109.469177246094;
        var maxY = 23.3926944732666;
        var cenX = (minX + maxX) / 2;
        var cenY = (minY + maxY) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var mapDefaultZoom = 6;
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');
        
        /**
        * Create an overlay to anchor the popup to the map.Khởi tạo pop up
        */
        var overlay = new ol.Overlay(/** @type {olx.OverlayOptions} */({
            element: container,
            autoPan: true,
            autoPanAnimation: {
            duration: 250
        }
        }));
        /**
        * Add a click handler to hide the popup.
        * @return {boolean} Don't follow the href.
        */
        closer.onclick = function () {
            overlay.setPosition(undefined);
            closer.blur();
            return false;
        };

        function initialize_map() {
            //*
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            //*/
            var layerCMR_adm1 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/btl/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        STYLES: '',
                        LAYERS: 'gadm36_vnm_1',
                    }
                })
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
                //projection: projection
            });
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerCMR_adm1],
                //layers: [layerCMR_adm1],
                //Khai báo pop up để sử dụng trên map //
                overlays: [overlay],
                view: viewMap
            });
            //map.getView().fit(bounds, map.getSize());

            var redzone = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'red'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    })
                })
            };
            var styleFunction = function(feature) {
                return redzone[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction
            });
            map.addLayer(vectorLayer);

            var orangezone = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'orange'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    })
                })
            };
            var styleFunction2 = function(feature) {
                return orangezone[feature.getGeometry().getType()];
            };
            var vectorLayer2 = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction2
            });
            map.addLayer(vectorLayer2);

            var greenzone = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'green'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    })
                })
            };
            var styleFunction3 = function(feature) {
                return greenzone[feature.getGeometry().getType()];
            };
            var vectorLayer3 = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction3
            });
            map.addLayer(vectorLayer3);

//tạo layer vùng highlight khi click chọn// 
            var highlight = {
                'MultiPolygon': new ol.style.Style({
                    // fill: new ol.style.Fill({
                    //     color: 'green'
                    // }),
                    stroke: new ol.style.Stroke({
                        color: 'white',
                        width: 3
                    })
                })
            };
            var styleFunction4 = function(feature) {
                return highlight[feature.getGeometry().getType()];
            };
            var vectorLayer4 = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction4
            });
            map.addLayer(vectorLayer4);





            function displayObjInfo(result, coordinate) {
                //alert("result: " + result);
                //alert("coordinate des: " + coordinate);
                $("#info").html(result);
            }

            function createJsonObj(result) {
                var geojsonObject = '{' +
                    '"type": "FeatureCollection",' +
                    '"crs": {' +
                    '"type": "name",' +
                    '"properties": {' +
                    '"name": "EPSG:4326"' +
                    '}' +
                    '},' +
                    '"features": [';
                for (let i = 0; i < result.length; i++) {
                    geojsonObject += '{' +
                        '"type": "Feature",' +
                        '"geometry": ' + JSON.stringify(result[i]) +
                        '},'
                };
                geojsonObject = geojsonObject.slice(0, -1)
                geojsonObject += ']' +
                    '}';
                //console.log(geojsonObject);
                return geojsonObject;
            }

            function createJsonObj2(result) {
                var geojsonObject = '{' +
                    '"type": "FeatureCollection",' +
                    '"crs": {' +
                    '"type": "name",' +
                    '"properties": {' +
                    '"name": "EPSG:4326"' +
                    '}' +
                    '},' +
                    '"features": [{' +
                    '"type": "Feature",' +
                    '"geometry": ' + result +
                    '}]' +
                    '}';
                return geojsonObject;
            }


            function drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
            }

            function highLightGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer.setSource(vectorSource);
                /*
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
                */
            }

            function highLightGeoJsonObj2(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer2.setSource(vectorSource);
            }

            function highLightGeoJsonObj3(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer3.setSource(vectorSource);
            }

            function highLightGeoJsonObj4(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer4.setSource(vectorSource);
                /*
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
                */
            }

            function RedZoneColor(result) { // redzone
                var objJson = JSON.parse(result);
                // const myJSON = JSON.stringify(objJson[0]);
                var strObjJson = createJsonObj(objJson);
                // console.log(myJSON)
                //alert(JSON.stringify(objJson));
                //drawGeoJsonObj(objJson);
                // console.log(strObjJson);
                highLightGeoJsonObj(strObjJson);
            }

            function OrangeZoneColor(result) { // orange zone
                var objJson = JSON.parse(result);
                // const myJSON = JSON.stringify(objJson[0]);
                var strObjJson = createJsonObj(objJson);
                // console.log(myJSON)
                //alert(JSON.stringify(objJson));
                //drawGeoJsonObj(objJson);
                // console.log(strObjJson);
                highLightGeoJsonObj2(strObjJson);
            }

            function GreenZoneColor(result) { // green zone
                var objJson = JSON.parse(result);
                // const myJSON = JSON.stringify(objJson[0]);
                var strObjJson = createJsonObj(objJson);
                // console.log(myJSON)
                //alert(JSON.stringify(objJson));
                //drawGeoJsonObj(objJson);
                // console.log(strObjJson);
                highLightGeoJsonObj3(strObjJson);
            }

            function highLightObj(result) {
                //alert("result: " + result);
                var strObjJson = createJsonObj2(result);
                //alert(strObjJson);
                var objJson = JSON.parse(strObjJson);
                //alert(JSON.stringify(objJson));
                //drawGeoJsonObj(objJson);
                highLightGeoJsonObj4(objJson);
            }


            map.on('singleclick', function(evt) {
                //alert("coordinate: " + evt.coordinate);
                //var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                //alert("myPoint: " + myPoint);
                //*
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionname: 'RedZone',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //console.log(result);
                        RedZoneColor(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionname: 'OrangeZone',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //console.log(result);
                        OrangeZoneColor(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionname: 'GreenZone',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //console.log(result);
                        GreenZoneColor(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                // lấy ra vùng được chọn để highlight//
                //trả về json//
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionname: 'getGeoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //console.log(result);
                        highLightObj(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionname: 'GetAllCity',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        console.log(result);

                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                //*/
            });


            map.on('singleclick', function(evt){
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    //data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                    data: {
                        functionname: 'getInfoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        $("#popup-content").html(result);
                        overlay.setPosition(evt.coordinate);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
            });

            // map.on('singleclick', function (evt) {
            //     //alert("coordinate: " + evt.coordinate);
            //     //var myPoint = 'POINT(12,5)';
            //     var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
            //     var lon = lonlat[0];
            //     var lat = lonlat[1];
            //     var myPoint = 'POINT(' + lon + ' ' + lat + ')';
            //     //alert("myPoint: " + myPoint);
            //     //*
            //     $.ajax({
            //         type: "POST",
            //         url: "CMR_pgsqlAPI.php",
            //         //dataType: 'json',
            //         data: {functionname: 'getGeoCMRToAjax', paPoint: myPoint},
            //         success : function (result, status, erro) {
            //             //console.log(result);
            //             highLightObj2(result);
            //         },
            //         error: function (req, status, error) {
            //             alert(req + " " + status + " " + error);
            //         }
            //     });
            //     //*/
            // });
//bắt sự kiện click vào vùng chọn để highlight//
            map.on('singleclick', function(evt) {
                //alert("coordinate org: " + evt.coordinate);
                //var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                //alert("myPoint: " + myPoint);
                //*
                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    //data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                    data: {
                        functionname: 'getInfoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        displayObjInfo(result, evt.coordinate);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                //*/
            });
        };
    </script>
</body>

</html>