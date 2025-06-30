(function($) {
    "use strict";

    // Hàm callback cho Google Maps API
    window.initMap = function() {
        console.log('Google Maps API đã load, đang khởi tạo map...');
        initializeMap();
    };

    // Đợi document ready
    $(document).ready(function() {
        // Nếu Google Maps API đã load sẵn
        if (typeof google !== 'undefined' && google.maps) {
            initializeMap();
        }
    });

    function initializeMap() {
        console.log('Đang khởi tạo Google Maps...');
        
        // Địa điểm: 13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội
        var mapOptions = {
            zoom: 15,
            scrollwheel: false,
            center: new google.maps.LatLng(21.0285, 105.7542), // Tọa độ Hà Nội
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var mapElement = document.getElementById('googleMap');
        if (!mapElement) {
            console.error('Không tìm thấy element #googleMap');
            return;
        }

        var map = new google.maps.Map(mapElement, mapOptions);

        // Tạo marker cho địa điểm cụ thể
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(21.0285, 105.7542),
            animation: google.maps.Animation.BOUNCE,
            title: '13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
            map: map
        });

        // Thêm InfoWindow cho marker
        var infowindow = new google.maps.InfoWindow({
            content: '<div style="padding: 10px;"><strong>Địa chỉ:</strong><br>13 Trịnh Văn Bô<br>Nam Từ Liêm, Hà Nội</div>'
        });

        // Hiển thị InfoWindow khi click vào marker
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        // Style cho map (màu sắc đẹp hơn)
        var styles = [
            {
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#8d8d8d"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 45
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#dbdbdb"
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }
        ];
        
        map.setOptions({styles: styles});
        console.log('Google Maps đã được khởi tạo thành công!');
    }

})(jQuery); 