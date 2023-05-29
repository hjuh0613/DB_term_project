<!DOCTYPE html>
<html>
  <head>
    <style>
      * {
  text-decoration: none;
  font-family:sans-serif;

}
body {
  background-image:#34495e;
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
.joinForm {
  position:absolute;
  width:400px;
  height:400px;
  padding: 30px, 20px;
  background-color:#FFFFFF;
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
  position: relative;
  top: 23px;
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

.name {
  width: 100%;
  border:none;
  outline:none;
  color: #636e72;
  font-size:16px;
  height:25px;
  background: none;
}

.email {
  width: 100%;
  border:none;
  outline:none;
  color: #636e72;
  font-size:16px;
  height:25px;
  background: none;
}

.nickname {
  width: 100%;
  border:none;
  outline:none;
  color: #636e72;
  font-size:16px;
  height:25px;
  background: none;
}

.cellphoneNo {
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

.id_overlap_button {
  transform: translateX(450%);
  height:25px;
  background: gray;
  background-size: 100%;
  color:white;
  font-weight: bold;
  border:none;
  cursor:pointer;
  display: inline-block;
}
</style>
</head>
<script>
function id_overlap_check() {
  //id라는 클래스를 가진 값 가져와서 loginId에 저장
  var loginId = document.querySelector('.id').value;
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.responseText === 'true') {
              alert('이미 사용중인 아이디 입니다');
          } else {
              alert('사용 가능한 아이디 입니다');
          }
      }
  }
  xhr.open('POST', 'id_overlap_check.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('loginId=' + loginId);
}
</script>
<?php
$host = 'localhost';
$user = 'root';
$password = 'gudwns13';
$dbname = 'practice';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

if (isset($_POST['loginId']) && isset($_POST['loginPw']) && isset($_POST['name']) && isset($_POST['nickname'])) {
    $loginId = $_POST['loginId'];
    $loginPw = $_POST['loginPw'];
    $name = $_POST['name'];
    $nickname = $_POST['nickname'];

    $sql = "SELECT * FROM member WHERE loginId='$loginId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // 중복된 loginId가 있음
        // ...
    } else {
        // 중복된 loginId가 없음
        $sql = "INSERT INTO member (loginId, loginPw, name, nickname) VALUES ('$loginId', '$loginPw', '$name', '$nickname')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header('Location: create_complete.php');
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
} else {
    // $_POST 배열의 키가 정의되지 않음
}

mysqli_close($conn);
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
<body>
<div style="display: flex; justify-content: center;">
<form action="createId.php" method="POST" class="joinForm">
      <h2>회원가입</h2>
      <div class="textForm">
        <input name="loginId" type="text" class="id" placeholder="사용할 아이디 입력">
          <button type="button" class="id_overlap_button" onclick="id_overlap_check()">중복검사</button>
      </div>
      <div class="textForm">
        <input name="loginPw" type="password" class="pw" placeholder="사용할 비밀번호 입력">
      </div>
       <div class="textForm">
        <input name="loginPwConfirm" type="password" class="pw" placeholder="비밀번호 확인">
      </div>
      <div class="textForm">
        <input name="name" type="text" class="name" placeholder="사용할 이름 입력">
      </div>
      <div class="textForm">
        <input name="nickname" type="text" class="nickname" placeholder="사용할 닉네임 입력">
      </div>
      <input type="submit" class="btn" value="J O I N">
    </form>
</div>
</body>
  </html>