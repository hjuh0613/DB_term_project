<html>
<head>
<style>
* {
  text-decoration: none;
  font-family:sans-serif;
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
a:hover {
        color: #F14249;
        text-decoration: underline;
    }
.full-screen {
    height: 60%;
    width: 100%;
    display: flex;
    gap: 20px;
    margin-top: 20px;
    justify-content: center;
}
.left-section {
    width: 25%;
    border: 2px solid black;
    border-radius: 10px;
    text-align:center;
}
.right-section {
    width: 40%;
    border: 2px solid black;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.joinForm {
  position:absolute;
  width:400px;
  height:350px;
  padding: 30px, 20px;
  margin-top: 50px;
  background-color:#FFFFFF; /*EEEEEE */
  text-align:center;
  border-radius: 15px;
}
.joinForm h2 {
  text-align: center;
  margin: 30px;
}
.textForm {
  border-bottom: 2px solid #adadad;
  margin: 30px;
  padding: 10px 10px;
}
.id {
  width: 100%;
  border:none;
  outline:none;
  color: #636e72;
  font-size:16px;
  height:25px;
  background: none;
}
.pw {
  width: 100%;
  border:none;
  outline:none;
  color: #636e72;
  font-size:16px;
  height:25px;
  background: none;
}
.btn {
  position:relative;
  left:40%;
  transform: translateX(-50%);
  margin-bottom: 40px;
  width:80%;
  height:40px;
  background: linear-gradient(125deg,#81ecec,#6c5ce7,#81ecec);
  background-position: left;
  background-size: 200%;
  color:white;
  font-weight: bold;
  border:none;
  cursor:pointer;
  transition: 0.4s;
  display:inline;
}
.btn:hover {
  background-position: right;
}

</style>
</head>
<body>
  <?php
// 데이터베이스 연결
$dbConn = mysqli_connect("localhost", "root", "gudwns13", "ev_charging_recommendation");

// 로그인 폼에서 전송된 데이터가 있는지 확인
if ( isset($_POST['loginId']) && isset($_POST['loginPw']) ) {
    $loginId = $_POST['loginId'];
    $loginPw = $_POST['loginPw'];

    // 아이디와 비밀번호가 일치하는 회원 데이터 조회
    $sql = "
    SELECT *
    FROM `member`
    WHERE `loginId` = '{$loginId}'
    AND `loginPw` = '{$loginPw}'
    ";
    $rs = mysqli_query($dbConn, $sql);
    $member = mysqli_fetch_assoc($rs);

    if ( $member ) {
        // 로그인 성공
        header('Location: test2.php');
        exit;
    }
    else {
        // 로그인 실패
        echo "<script>
        alert('아이디 또는 비밀번호가 일치하지 않습니다.');
        location.replace('main.php');
        </script>";
        exit;
    }
}
?>
<header  style="margin-left: 50px; margin-right:50px">
<div id="header"  style="margin-left: 20px; margin-right">
    <h1 style="color: #FFFFFF;">
      <a href="main.php" style="color: inherit; text-decoration: none;">
        Nearby Charger Finder
      </a>
    </h1>
</div>
</header>
<div class="full-screen">
    <div class="left-section">
      <h3>About</h3>
      <h3 style="color: #E46065;">"Nearby Charger Finder"</h3>
          로그인 후 이용하실 수 있습니다<br><br>
          조회 가능 목록은 아래와 같습니다<br>
          <ul>
            <li>가까운 전기차 충전소 목록
            <li>실시간 충전기 상태
          </ul>
    </div>
    <div class="right-section">
        <form action="main.php" method="POST" class="joinForm" onsubmit="DoJoinForm__submit(this); return false;">
          <h2>login</h2>
          <div class="textForm">
            <input name="loginId" type="text" class="id" placeholder="아이디">
            </input>
          </div>
          <div class="textForm">
            <input name="loginPw" type="password" class="pw" placeholder="비밀번호">
          </div>
          <input type="submit" class="btn" value="J O I N">
          <br>
          회원이 아니신가요? <a href="createId.php" style="color: #2780DB;">회원가입</a>
        </form>

    </div>
</div>

</body>
</html>