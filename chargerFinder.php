<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title>Nearby Charger Finder</title>
     <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=b1897c08b02c2e104cefdda47fbae513"></script>
     <style>
       #container {
         display: flex;
       }
       #left {
         width: 50%;
       }
       #right {
         width: 50%;
       }
       header {
          background-color: #4AA3F8;
           padding: 10px;
           display: flex;
           justify-content: space-between;
           border-radius: 25px;
           border: 2px solid #1E90FF;
           padding: 10px;
       }
   table {
    font-size: 14px;
    border-collapse: collapse;
    text-align: center;
    }
    table, th, td {
    border: 1px solid black;
    }
   .right-section {
       display: flex;
         align-items: center;
    }
    .login-box {
         margin-left: 10px;
         padding: 5px 10px;
         background-color: lightblue;
    }
    </style>
</head>
<body>
     <header>
    <div id="header">
      <h1 style="color: #FFFFFF;">
        <a href="test2.php" style="color: inherit; text-decoration: none;">
          Nearby Charger Finder
        </a>
      </h1>
    </div>
     </header>
     <div id="container">
       <div id="left">
         <h2>가까운 전기차 충전소를 찾아드립니다.</h2>
         <h3>본인의 지역과 차종을 입력해 주세요.</h3>
         <select id="region1">
           <option value="서울특별시">서울특별시</option>
           <option value="경기도">경기도</option>
           <option value="인천광역시">인천광역시</option>
           <option value="부산광역시">부산광역시</option>
           <option value="대전광역시">대전광역시</option>
           <option value="대구광역시">대구광역시</option>
           <option value="울산광역시">울산광역시</option>
           <option value="세종특별자치시">세종특별자치시</option>
           <option value="광주광역시">광주광역시</option>
           <option value="강원도">강원도</option>
           <option value="충청북도">충청북도</option>
           <option value="충청남도">충청남도</option>
           <option value="경상북도">경상북도</option>
           <option value="경상남도">경상남도</option>
           <option value="전라북도">전라북도</option>
           <option value="전라남도">전라남도</option>
           <option value="제주특별자치도">제주특별자치도</option>
         </select>

         <select id="region2"></select>

         <select id="charger_type">
           <option value="B타입 (5핀)">B타입 (5핀)</option>
           <option value="C타입 (5핀)">C타입 (5핀)</option>
           <option value="BC타입 (5핀)">BC타입 (5핀)</option>
           <option value="BC타입 (7핀)">BC타입 (7핀)</option>
           <option value="DC차데모">DC차데모</option>
           <option value="AC3상">AC3상</option>
           <option value="DC콤보">DC콤보</option>
           <option value="DC차데모+DC콤보">DC차데모+DC콤보</option>
           <option value="DC차데모+AC3상">DC차데모+AC3상</option>
           <option value="DC차데모+DC콤보,AC3상">DC차데모+DC콤보,AC3상</option>
         </select>
         <button type="button" onclick="submitSelection()">조회하기</button>
         <div id="result"></div>
       </div>
         <div id="map" style="width:50%;height:450px;
         margin-top:150px; margin-right:20px; margin-bottom:30px">
         </div>
     </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
   // 지도 생성할 div 표시 후 지도의 중심좌표 설정하고 지도 생성
   var mapContainer = document.getElementById('map');
   var mapOptions = {
      center: new kakao.maps.LatLng(36.94914, 127.90305),
       level: 5
   };
  var map = new kakao.maps.Map(mapContainer, mapOptions);

   function submitSelection(){
     var region1 = document.getElementById('region1').value;
     var region2 = document.getElementById('region2').value;
     var region= region1+" "+region2;
     //marker.setMap(null);
     $.ajax({
       type: "GET",
       url: "http://api.odcloud.kr/api/EvInfoServiceV2/v1/getEvSearchList",
       data: {
         page: 1,
         perPage: 20,
         returnType: "JSON",
         "cond[addr::LIKE]": region,
         serviceKey: "O4SeBFtaO/s7pMc6m5hm9BbQ+b6OH8r6B8A983NU1RZm9FAIidUS5xGKy3hGn2OQTyXB6w4WMn86aCYZEa2ZkQ=="
       },
       success: function(response) {
         // 요청받은 데이터들 콘솔창 출력
         console.log(response);
         // 받은 JSON 데이터들 출력
         //$("#result").text(JSON.stringify(response));
         var resultElement= document.getElementById('result');
         // resultElement 비우기
         resultElement.innerHTML='';
         var latSum= 0;
         var longiSum= 0;
         var tableElement = document.createElement('table');
         resultElement.appendChild(tableElement);

         var headerTrElement = document.createElement('tr');
         tableElement.appendChild(headerTrElement);

         var csNmThElement = document.createElement('th');
         csNmThElement.textContent = '충전소명';
         headerTrElement.appendChild(csNmThElement);

         var addrThElement = document.createElement('th');
         addrThElement.textContent = '주소';
         headerTrElement.appendChild(addrThElement);

         for(var i=0; i<response.data.length; i++){
           var trElement = document.createElement('tr');
           tableElement.appendChild(trElement);

           var csNmTdElement = document.createElement('td');
           csNmTdElement.textContent = response.data[i].csNm;
           trElement.appendChild(csNmTdElement);

           var addrTdElement = document.createElement('td');
           addrTdElement.textContent = response.data[i].addr;
           trElement.appendChild(addrTdElement);
           // 마커 표시할 위치를 저장
           var markerPosition= new kakao.maps.LatLng(response.data[i].lat, response.data[i].longi);
          // 마커생성
          var marker= new kakao.maps.Marker({
             position: markerPosition
           });
           //마커 지도위에 표시하기
           marker.setMap(map);

           var infowindow = new kakao.maps.InfoWindow({
             content: '<div style="padding:5px;font-size:12px;text-align:center;"><b>' + response.data[i].csNm + '</b></div>'
           });
           kakao.maps.event.addListener(marker, 'mouseover', makeOverListener(map, marker, infowindow));
           kakao.maps.event.addListener(marker, 'mouseout', makeOutListener(infowindow));

           kakao.maps.event.addListener(marker, 'click', function() {
          window.open('popup.php', 'popupWindow', 'width=1000,height=200, top=250');
          });

           latSum+=Number(response.data[i].lat);
           longiSum+=Number(response.data[i].longi);
         }
         function makeOverListener(map, marker, infowindow) {
           return function() {
             infowindow.open(map, marker);
           };
         }
         function makeOutListener(infowindow) {
           return function() {
             infowindow.close();
           };
         }
         var latAvg= latSum / response.data.length;
        var longiAvg = longiSum / response.data.length;

        map.setCenter(new kakao.maps.LatLng(latAvg, longiAvg))
       },
       error: function(xhr, status, error) {
         //에러나면 콘솔창에 에러 출력
         console.error(error);
       }
     });
   }

     var regions = {
       서울특별시: {
      강남구: [], 강동구: [], 강북구: [], 강서구: [], 관악구: [], 광진구: [], 구로구: [], 금천구: [], 노원구: [],
      도봉구: [], 동대문구: [], 동작구: [], 마포구: [], 서대문구: [], 서초구: [], 성동구: [], 성북구: [], 송파구: [],
      양천구: [], 영등포구: [], 용산구: [], 은평구: [], 종로구: [], 중구: [], 중랑구: []
       },
    경기도: {
      수원시: [], 성남시: [], 부천시: [],
   안양시: [], 안산시: [], 용인시: [],
   광명시: [], 평택시: [], 과천시: [], 시흥시: [], 군포시: [], 의왕시: [], 오산시: [], 하남시: [], 이천시: [],
   김포시: [], 안성시: [], 화성시: [], 여주시: [], 양평군: [], 의정부시: [], 고양시: [],
   동두천시: [], 구리시: [], 남양주시: [], 파주시: [], 양주시: [], 포천시: [], 가평군: [], 연천군: []
    },
    인천광역시: {
      서구: [], 남동구: [], 부평구: [], 미추홀구: [], 연수구: [], 계양구: [], 중구: [], 강화군: [], 동구: [],
   옹진군: []
    },
    부산광역시: {
      중구: [], 서구: [], 동구: [], 영도구: [], 부산진구: [], 동래구: [], 남구: [], 북구: [], 해운대구: [],
   사하구: [], 금정구: [], 강서구: [], 연제구: [], 수영구: [], 사상구: []
    },
    대전광역시: {
      유성구: [], 대덕구: [], 서구: [], 중구: [], 동구: []
    },
    대구광역시: {
      북구: [], 동구: [], 서구: [], 중구: [], 달서구: [], 수성구: [], 남구: [], 달성군: []
    },
    울산광역시: {
      울주군: [], 중구: [], 북구: [], 남구: [], 동구: []
    },
    세종특별자치시: {
      세종시: []
    },
    광주광역시: {
      광산구: [], 북구: [], 서구: [], 동구: [], 남구: []
    },
    강원도: {
   춘천시: [], 원주시: [], 강릉시: [], 동해시: [], 태백시: [], 속초시: [], 삼척시: [], 홍천군: [], 횡성군: [],
   영월군: [], 평창군: [], 정선군: [], 철원군: [], 화천군: [], 양구군: [], 인제군: [], 고성군: [], 양양군: []
    },
    충청북도: {
   단양군: [], 제천시: [], 충주시: [], 음성군: [], 진천군: [], 증평군: [], 괴산군: [], 청주시: [], 보은군: [],
   옥천군: [], 영동군: []
    },
    충청남도: {
      당진시: [], 서산시: [], 태안군: [], 홍성군: [], 청양군: [], 보령시: [], 서천군: [], 아산시: [], 천안시: [],
   예산군: [], 공주시: [], 계룡시: [], 금산군: [], 논산시: [], 부여군: []
    },
    경상북도: {
      포항시: [], 경주시: [], 김천시: [], 안동시: [], 구미시: [], 영주시: [], 영천시: [], 상주시: [], 문경시: [],
   경산시: [], 군위군: [], 의성군: [], 청송군: [], 영양군: [], 영덕군: [], 청도군: [], 고령군: [], 성주군: [],
   칠곡군: [], 예천군: [], 봉화군: [], 울진군: [], 울릉군: []
    },
    경상남도: {
      창원시: [], 김해시: [], 양산시: [], 진주시: [], 거제시: [], 통영시: [], 사천시: [], 밀양시: [], 함안군: [],
   거창군: [], 창녕군: [], 고성군: [], 합천군: [], 하동군: [], 남해군: [], 함양군: [], 산청군: [], 의령군: []
    },
    전라북도: {
      전주시: [], 군산시: [], 익산시: [], 정읍시: [], 남원시: [], 김제시: [], 완주군: [], 진안군: [], 무주군: [],
   장수군: [], 임실군: [], 순창군: [], 고창군: [], 부안군: []
    },
    전라남도: {
   목포시: [], 여수시: [], 순천시: [], 나주시: [], 광양시: [], 담양군: [], 곡성군: [], 구례군: [], 고흥군: [],
   보성군: [], 화순군: [], 장흥군: [], 강진군: [], 해남군: [], 영암군: [], 무안군: [], 함평군: [], 영광군: [],
   장성군: [], 완도군: [], 진도군: [], 신안군: []
    },
    제주특별자치도: {
      제주시: [], 서귀포시: []
    }
     };

     function updateOptions(select, options) {
       select.innerHTML = '';

       for (var i = 0; i < options.length; i++) {
            var option = document.createElement('option');
            option.value = options[i];
            option.text = options[i];
            select.appendChild(option);
       }
     }

     region1.addEventListener('change', function() {
       var selectedRegion1 = region1.value;
       var region2Options = Object.keys(regions[selectedRegion1]);

       updateOptions(region2, region2Options);

       var selectedRegion2 = region2.value;

     });

     region2.addEventListener('change', function() {
       var selectedRegion1 = region1.value;
       var selectedRegion2 = region2.value;
     });

     region1.dispatchEvent(new Event('change'));
   </script>
</html>