<?php
    session_start();

    include_once("connect.php");

    /*
    =====================================================
                        USERS
    =====================================================
    */

    if(isset($_POST['u'])){
        $con = connect();
        $query = "SELECT *
                    FROM USERS
                    WHERE email=? AND password=?";
        $sentence = $con->prepare($query);
        $sentence->execute(array($_POST['u'], $_POST['p']));
        $result = $sentence->fetchAll();
        $coun = 0;
        $arrData;

        foreach($result as $r){
            $coun += 1;
            $arrData = $r;
        }
        if($coun == 1){
            $_SESSION['user'] = $arrData[4].' '.$arrData[5].' '.$arrData[6].' '.$arrData[7];
            $_SESSION['type'] = $arrData[3];
            $_SESSION['id'] = $arrData[0];
            $_SESSION['pic'] = $arrData[9];
            $_SESSION['address'] = $arrData[8];
            $_SESSION['email'] = $arrData[1];
            $_SESSION['password'] = $arrData[2];
        }

        echo $coun;
    }

    if(isset($_POST['g'])){
        $_SESSION['user'] = $_POST['g'];
        $_SESSION['type'] = 'G';
        $_SESSION['id'] = 'noid';
        $_SESSION['pic'] = 'user.png';
    }


    /*
    =====================================================
                        DASHBOARD
    =====================================================
    */

    function coursesGraph(){
        if($_SESSION['type'] == 'S')
            echo graph(queryStudentCourses());
        else if($_SESSION['type'] == 'T')
            echo graph(queryTeacherCourses());
        else
            echo graph(queryAllCourses());
    }

    function graph($result){
        $output = "";
        foreach($result as $r){
            $output .= '<div class="col-xl-3 col-md-6">'.
                            '<div class="card mb-5 courseClass" style="width: 18rem;">'.
                                '<a href="#" class="btn" onclick="fun('.$r[0].')">'.
                                    '<img src="./img/coursesImages/'.$r[0].'.png" class="card-img-top" alt="course image">'.
                                    '<div class="card-body">'.
                                        '<h5 class="card-title mb-0  fw-bold">'.$r[1].'</h5>'.
                                    '</a>'.
                                        '<hr>'.
                                        '<p class="card-text">'.$r[2].'</p>'.
                                    '</div>'.
                            '</div>'.
                        '</div>';                        
        }
        return $output;
    }

    function graphCourseImage(){
        return '<img src="./img/coursesImages/'.$_SESSION['page'].'.png"
            alt="course image" class="img-responsive" width="450" height="250">';
    }

    function graphUserImage(){
        return '<img src="./img/usersImages/'.$_SESSION['pic'].'"
        alt="user image" class="img-responsive" width="40" height="40" style="border-radius: 50%">';
    }

    function coursesGraphNavbar(){
        if($_SESSION['type'] == 'S')
            echo graph2(queryStudentCourses());
        else if($_SESSION['type'] == 'T')
            echo graph2(queryTeacherCourses());
        else
            echo graph2(queryAllCourses());
    }

    function graph2($result){
        $output = "";
        foreach($result as $r){
            $output .= '<a class="nav-link" href="#" onclick = fun('.$r[0].')>'.$r[1].'</a>';
        }
        return $output;
    }

    function graphAllCoursesStudents(){
        if($_SESSION['type'] == 'S')
            return '<ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">All Courses</li>
                    </ol>
                    <div class="row" id="courses">'.
                    graph(queryAllCourses()).
                    '</div>';
    }


    function queryAllCourses(){
        $con = connect();
        $sentence = $con->prepare("SELECT C.id_co, S.name_su, S.des_su
                                    FROM COURSES C, SUBJECTS S
                                    WHERE C.subject_per = S.id_su");
        $sentence->execute();
        return $sentence->fetchAll();
    }

    function queryStudentCourses(){
        $con = connect();
        $sentence = $con->prepare("SELECT C.id_co, S.name_su, S.des_su
                                FROM COURSES C, SUBJECTS S, STUD_LIST L
                                WHERE C.subject_per = S.id_su
                                AND L.course_per = C.id_co
                                AND L.student_per = ?");
        $sentence->execute(array($_SESSION['id']));
        return $sentence->fetchAll();
    }

    function queryTeacherCourses(){
        $con = connect();
        $sentence = $con->prepare("SELECT C.id_co, S.name_su, S.des_su
                                FROM COURSES C, SUBJECTS S
                                WHERE C.subject_per = S.id_su
                                AND C.teacher_per = ?");
        $sentence->execute(array($_SESSION['id']));
        return $sentence->fetchAll();
    }

    function hasMyCourses(){
        $con = connect();
        $sentence = $con->prepare("SELECT COUNT(1)
                                FROM COURSES C, SUBJECTS S, STUD_LIST L
                                WHERE C.subject_per = S.id_su
                                AND L.course_per = C.id_co
                                AND L.student_per = ?");
        $sentence->execute(array($_SESSION['id']));
        foreach($sentence->fetchAll() as $r)
            $counting = $r[0];
        
        if($_SESSION['type'] == 'S'){
            if($counting != 0)
                return $out = '<ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item active">My Courses</li>
                                </ol>';
        }
        else
            return $out = '<ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">All Courses</li>
                    </ol>';
    }



    /*
    =====================================================
                        COURSES
    =====================================================
    */


    if(isset($_POST['course_id'])){
        $_SESSION['page'] = $_POST['course_id'];
        foreach(dataCourse() as $d){
            $_SESSION['subName'] = $d[0];
            $_SESSION['subDes'] = $d[1];
            $_SESSION['teacher'] = $d[2].' '.$d[3].' '.$d[4].' '.$d[5];
        }
        isEnrolledFuction();
    }

    function dataCourse(){
        $con = connect();
        $sentence = $con->prepare("SELECT S.name_su, S.des_su, T.name1, T.name2, T.lastName1, T.lastName2
                                FROM COURSES C, SUBJECTS S, USERS T
                                WHERE C.subject_per = S.id_su
                                AND C.teacher_per = T.id_us
                                AND C.id_co = ?");
        $sentence->execute(array($_SESSION['page']));
        return $sentence->fetchAll();
    }

    function graphAddSubTeacher(){
        if($_SESSION['type'] == "T")
            return '<button class="btn btn-warning" onclick="funAddAssign()">Add Assignment</button>';
    }

    function isEnrolledFuction(){
        $con = connect();
        $sentence = $con->prepare("SELECT COUNT(1)
                                FROM COURSES C, SUBJECTS S, STUD_LIST L
                                WHERE C.subject_per = S.id_su
                                AND L.course_per = C.id_co
                                AND L.student_per = ?
                                AND L.course_per = ?");
        $sentence->execute(array($_SESSION['id'], $_SESSION['page']));
        foreach($sentence->fetchAll() as $r)
            $_SESSION['isEnrolled'] = $r[0];
    }




    /*
    =====================================================
                        ASSIGNMENTS
    =====================================================
    */
    

    function graphAssignments($result){
        $out = "";
        foreach($result as $r){
            $out .= '<div class="container pb-4">
                        <div class="row align-items-start">
                            <div class="col-4 text-end">
                                <img src="./img/fileAssig.png" 
                                alt="file" class="img-responsive" width="40" height="40">
                            </div>
                            <div class="col-8">
                                <a class="btn" href="#" onclick = funAssign('.$r[0].')>
                                    '.$r[1].'
                                </a>
                            </div>
                        </div>
                    </div>';
        }
        echo $out;
    }

    function dataAssignment(){
        $con = connect();
        $sentence = $con->prepare("SELECT A.id_as, A.name_as
                                FROM ASSIGNMENTS A, ASSIG_LIST L
                                WHERE A.id_as = L.id_as_li
                                AND course_per = ?");
        $sentence->execute(array($_SESSION['page']));
        return $sentence->fetchAll();
    }

    function graphAssignments2($result){
        $out = "";
        $tmp;
        foreach($result as $r){
            foreach(dataAssignment2($r[0]) as $gr){
                if($gr[0] == "")
                    $tmp = "-";
                else
                    $tmp = $gr[0];
                $out .= '<div class="container pb-4">
                            <div class="row align-items-start">
                                <div class="col-4 text-end">
                                    <img src="./img/fileAssig.png" 
                                    alt="file" class="img-responsive" width="40" height="40">
                                </div>
                                <div class="col-4">
                                    <a class="btn" href="#" onclick = funAssign('.$r[0].')>
                                        '.$r[1].'
                                    </a>
                                </div>
                                <div class="col-2">
                                    <h5 class="fw-bold">'.$tmp.'</h5>
                                </div>
                            </div>
                        </div>';
            }
        }
        echo $out;
    }

    function dataAssignment2($id_assign){
        $con = connect();
        $sentence = $con->prepare("SELECT G.grade
                                FROM ASSIGNMENTS A, ASSIG_LIST L, GRADES G
                                WHERE A.id_as = L.id_as_li
                                AND A.id_as = G.assign_gr_per
                                AND course_per = ?
                                AND A.id_as = ?                                
                                AND G.student_gr_per = ?");
        $sentence->execute(array($_SESSION['page'], $id_assign, $_SESSION['id']));
        return $sentence->fetchAll();
    }

    if(isset($_POST['assign_id'])){
        $_SESSION['assign'] = $_POST['assign_id'];
        if($_SESSION['type'] == 'S')
            $results = dataAssignmentSpecific();
        else
            $results = dataAssignmentSpecificTeacher();
        foreach($results as $d){
            $_SESSION['id_assign'] = $d[0];
            $_SESSION['name_assign'] = $d[1];
            $_SESSION['descri_assign'] = $d[2];
            $_SESSION['file_assign'] = $d[3];
            $_SESSION['date_assign'] = $d[4];
            if($_SESSION['type'] == 'S'){
                $_SESSION['grade_assign'] = $d[5];
                $_SESSION['submi_assign'] = $d[6];
            }
        }
    }

    function dataAssignmentSpecific(){
        $con = connect();
        $sentence = $con->prepare("SELECT A.id_as, A.name_as, A.des_as, A.file_as, A.date_as, G.grade, G.file_gr
                                FROM ASSIGNMENTS A, ASSIG_LIST L, GRADES G
                                WHERE A.id_as = L.id_as_li
                                AND A.id_as = G.assign_gr_per
                                AND course_per = ?
                                AND A.id_as = ?                                
                                AND G.student_gr_per = ?");
        $sentence->execute(array($_SESSION['page'], $_SESSION['assign'], $_SESSION['id']));
        return $sentence->fetchAll();
    }

    function dataAssignmentSpecificTeacher(){
        $con = connect();
        $sentence = $con->prepare("SELECT A.id_as, A.name_as, A.des_as, A.file_as, A.date_as
                                FROM ASSIGNMENTS A, ASSIG_LIST L, COURSES C
                                WHERE A.id_as = L.id_as_li
                                AND L.course_per = C.id_co
                                AND course_per = ?
                                AND A.id_as = ?
                                AND C.teacher_per = ?");
        $sentence->execute(array($_SESSION['page'], $_SESSION['assign'], $_SESSION['id']));
        return $sentence->fetchAll();
    }

    function gradeAverage($id_student){
        $con = connect();
        $sentence = $con->prepare("SELECT ROUND(AVG(G.grade), 2)
                                    FROM ASSIGNMENTS A, ASSIG_LIST L, GRADES G
                                    WHERE A.id_as = L.id_as_li
                                    AND course_per = ?
                                    AND A.id_as = G.assign_gr_per
                                    AND G.student_gr_per = ?
                                    GROUP BY G.student_gr_per");
        $sentence->execute(array($_SESSION['page'], $id_student));
        foreach($sentence->fetchAll() as $r){
            if($r[0] == "")
                return "-";
            else
                return $r[0];
        }
    }

    function fileAttached($file, $bool){
        $out = "";
        if($bool)
            $out = 'Attached file: ';
        if($file != "")
            return $out.'<a href="validate.php?fileDownload='.$file.'" style="text-decoration: none">
            <img src="./img/pdf_icon.png" class="pb-1"
            alt="file" class="img-responsive" width="20" height="24"> '.
            $file.'</a>';
        else
            return "-";
    }

    function fileAttached2(){
        if($_SESSION['file_assign'] != "")
            return 'Attached file: <a href="validate.php?fileDownload='.$_SESSION['file_assign'].'" style="text-decoration: none">
            <img src="./img/pdf_icon.png" class="pb-1"
            alt="file" class="img-responsive" width="20" height="24"> '.
            $_SESSION['file_assign'].'</a>';
    }

    
    function submiStatusAddAssign(){
        if($_SESSION['type'] == 'S')
            return '<div class="row">
                        <div class="col px-lg-5">
                            <h3 class="mt-4 text-justify fw-bold">Submission status</h3>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <table class="table table-bordered table-responsive table-striped">
                                <tbody>
                                    <tr>
                                        <th class="col-3">Grade</th>
                                        <td>
                                            '.gradedStatus().
                                        '</td>
                                    </tr>
                                    <tr>
                                        <th>Time remaining</th> 
                                        <td>
                                            '.remainingTime().
                                        '</td>
                                    <tr>
                                        <th>File submissions</th>
                                        <td>                
                                            <p class="fw-bold">
                                                '.submiFile().
                                            '</p>                                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>';
    }

    
    /*
    =====================================================
                        GRADES
    =====================================================
    */

    function remainingTime(){
        $now = new DateTime();
        $future_date = new DateTime($_SESSION['date_assign']);
        $interval = $future_date->diff($now);
        $out = '';
        if($interval->format("%R") == "+"){
            $_SESSION['addSub'] = false;
            if($_SESSION['submi_assign'] == ""){
                $out .= 'Assignment is overdue by: ';
            }else{
                if($_SESSION['grade_assign'] == "")
                    return "Waiting for grade";
                else
                    return "Graded";
            }
        }else
            $_SESSION['addSub'] = true;
        return $out.= $interval->format("%a days - %h hours - %i minutes - %s seconds");
    }

    function gradedStatus(){
        if($_SESSION['grade_assign'] == "")
            return "Not graded";
        else
            return $_SESSION['grade_assign'];
    }

    function submiFile(){
        if($_SESSION['submi_assign'] == "")
            return "";
        else
            return '<a href="validate.php?fileDownload='.$_SESSION['submi_assign'].'" style="text-decoration: none">
            <img src="./img/pdf_icon.png" class="pb-1"
            alt="file" class="img-responsive" width="20" height="24"> '.
            $_SESSION['submi_assign'].'</a>';
    }

    function addSubmissionAssign(){
        if(($_SESSION['addSub'] && $_SESSION['grade_assign'] == "") && $_SESSION['submi_assign'] == "")
            return '<button class="btn btn-dark" onclick="uploadAssign('.$_SESSION['id_assign'].')">Add submission</button>';
    }

    if(isset($_POST['course_id_2'])){
        $_SESSION['page'] = $_POST['course_id_2'];
        foreach(dataCourse() as $d){
            $_SESSION['subName'] = $d[0];
            $_SESSION['subDes'] = $d[1];
            $_SESSION['teacher'] = $d[2].' '.$d[3].' '.$d[4].' '.$d[5];
        }
    }



    /*
    =====================================================
                        UPLOADING
    =====================================================
    */

    if(isset($_POST['idAssign'])){
        $_SESSION['idAssign'] = $_POST['idAssign'];
    }

    if(isset($_POST['submit'])){
        $file = $_FILES['file'];

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        if($fileError === 0){
            //$fileNameNew = uniqid('', true).".".$fileActualExt; //UNIQ
            $fileNameNew = $_SESSION['id']."_".$fileName;
            if($_SESSION['type'] == "S")
                uploadFileStudent($fileNameNew);
            else
                $_SESSION['tmpFileTeacher'] = $fileNameNew;
            $fileDestination = 'uploads/'.$fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
        }
        if($_SESSION['type'] == "S")
            header("location:assignment.php");
        else
            header("location:addAssign.php");
    }

    if(isset($_POST['image'])){
        $folderPath = 'img/usersImages/';
        $image_parts = explode(";base64,", $_POST['image']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = $_SESSION['id'].uniqid().'.png';
        $file = $folderPath . $imageName;        
        file_put_contents($file, $image_base64);

        updateUserPicture($imageName);
        $_SESSION['pic'] = $imageName;

        echo json_encode(["Image uploaded successfully."]);
    }

    function updateUserPicture($imageName){
        $con = connect();
        $sentence = $con->prepare("UPDATE USERS
                                    SET picture = ?
                                    WHERE id_us = ?");
        $sentence->execute(array($imageName, $_SESSION['id']));
    }

    
    if(isset($_POST['date'])){
        uploadAssignTeacher($_POST['title'],
                            $_POST['descrip'],
                            date("Y-m-d H:i:s",strtotime($_POST['date'])));
        $_SESSION['last_id_inserted'] = selectLastId();
        setAssignToCourse($_SESSION['last_id_inserted']);
        foreach(studentsCourse() as $r)
            setAssignGrade($_SESSION['last_id_inserted'], $r[0]);
        $_SESSION['fileFlagTeacher'] = true;
    }
        

    function uploadFileStudent($fileNameUpload){
        $con = connect();
        $sentence = $con->prepare("UPDATE GRADES
                                    SET file_gr = ?
                                    WHERE assign_gr_per = ?
                                    AND student_gr_per = ?");
        $sentence->execute(array($fileNameUpload, $_SESSION['id_assign'], $_SESSION['id']));
        $_SESSION['submi_assign'] = $fileNameUpload;
    }

    function uploadAssignTeacher($title, $descrip, $date){
        $con = connect();
        $sentence = $con->prepare("INSERT INTO ASSIGNMENTS
                                    SET name_as = ?,
                                    des_as = ?,
                                    date_as = ?");
        $sentence->execute(array($title, $descrip, $date));
    }

    function selectLastId(){
        $con = connect();
        $sentence = $con->prepare("SELECT MAX(id_as) from ASSIGNMENTS");
        $sentence->execute();
        foreach($sentence->fetchAll() as $r)
            return $r[0];
    }

    function setAssignToCourse($lastId){
        $con = connect();
        $sentence = $con->prepare("INSERT INTO ASSIG_LIST
                                SET assign_per = ?,
                                course_per = ?");
        $sentence->execute(array($lastId, $_SESSION['page']));
    }

    function setAssignGrade($lastId, $id_std){
        $con = connect();
        $sentence = $con->prepare("INSERT INTO GRADES
                                    SET assign_gr_per = ?,
                                    student_gr_per = ?");
        $sentence->execute(array($lastId, $id_std));
    }

    function setFileAssignTeacher(){
        $con = connect();
        $sentence = $con->prepare("UPDATE ASSIGNMENTS
                                    SET file_as = ? 
                                    WHERE id_as = ?");
        $sentence->execute(array($_SESSION['tmpFileTeacher'], $_SESSION['last_id_inserted']));
    }


    
    
    /*
    =====================================================
                        DOWNLOADING
    =====================================================
    */

    if(isset($_GET['fileDownload'])){
        $fileNameDown = basename($_GET['fileDownload']);
        $filePath = 'uploads/'.$fileNameDown;

        if(!empty($fileNameDown) && file_exists($filePath)){
            header("Cache-Control: public");
            header("Content-Description: FIle Transfer");
            header("Content-Disposition: attachment; filename=$fileNameDown");
            header("Content-Type: application/zip");
            header("Content-Transfer-Emcoding: binary");
    
            readfile($filePath);
            exit;
        }
        else{
            echo json_encode(["This File Does not exist."]);
            sleep(3);
            header("location:assignment.php");
        }
    }




    /*
    =====================================================
                        ENROLLING
    =====================================================
    */
    
    if(isset($_POST['enroll'])){
        $sql = 'INSERT INTO STUD_LIST VALUES(null, "'.$_SESSION['id'].'", '.$_SESSION['page'].');';
        foreach(dataAssignment() as $d)
            $sql .= 'INSERT INTO GRADES VALUES(null, null, '.$d[0].', "'.$_SESSION['id'].'" , null);';
        //$_SESSION['variable'] = "hola";
        $con = connect();
        $consulta = $con->prepare($sql);
        $consulta->execute();
        echo 1;
    }



    /*
    =====================================================
            TEACHER: STUDENTS LIST
    =====================================================
    */
    function graphStudentsList(){
        $i = 0;
        $tmp;
        $out1 = '<thead>
                    <tr>
                        <th>Student</th>';
        $out2 = '<tbody>';
        foreach(studentsCourse() as $r){
            $out2 .= '<tr>
                        <td>'.$r[1].' '.$r[2].' '.$r[3].' '.$r[4].'</td>';
            foreach(studentsGrades($r[0]) as $g){
                if($i == 0)
                    $out1 .= '<th>'.$g[0].'</th>';
                if($g[1] == "")
                    $tmp = "-";
                else
                    $tmp = $g[1];
                $out2 .= '<td>'.$tmp.'</td>';
            }
            $out2 .= '<td>'.gradeAverage($r[0]).'</td></tr>';
            $i++;
        }
        $out1 .= '<th>Average</th>
                </tr>
            </thead>';
        $out2 .= '</tbody>';
        
        echo '<center>
        <div class="card mb-4" style="display: block;overflow-x: auto;white-space: nowrap; width: 1300px;">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Students List
            </div>
            <div class="card-body">
                <table id="datatablesSimple">'.$out1.$out2.'</table>
                </div>
            </div>
        </center>';
    }

    function graphStudentsListForGrade(){
        $i = 0;
        $tmp;
        $out1 = '<thead>
                    <tr>
                        <th>Student</th>';
        $out2 = '<tbody>';
        foreach(studentsCourse() as $r){
            $out2 .= '<tr>
                        <td>'.$r[1].' '.$r[2].' '.$r[3].' '.$r[4].'</td>';
            foreach(studentsGradesForEach($r[0]) as $g){
                if($i == 0){
                    $out1 .= '<th>'.$_SESSION['name_assign'].'</th>';
                    $out1 .= '<th>Grade</th>';    

                }
                
                $out2 .= '<td>'.fileAttached($g[0], false).'</td>';
                if($g[1] == "")
                    $tmp = "-";
                else
                    $tmp = $g[1];
                $out2 .= '<td><input type="number" min="0" max="10" value="'.$tmp.'" disabled></td>'; //nota
                $i++;
            }
            $out2 .= '<td><button class="btn btn-secondary edit" id="'.$r[0].'">Edit</button></td>
                    <td><button class="btn btn-success save" id="'.$r[0].'">Save</button></td></tr>';
        }
        $out1 .= '<th>Edit</th>
                <th>Save</th>
                </tr>
            </thead>';
        $out2 .= '</tbody>';
        
        echo '<center>
        <div class="card mb-4" style="display: block;overflow-x: auto;white-space: nowrap; width: 1300px;">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Students List
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="tableList">'.$out1.$out2.'</table>
                </div>
            </div>
        </center>';
    }


    function studentsCourse(){
        $con = connect();
        $sentence = $con->prepare("SELECT S.id_us, S.name1, S.name2, S.lastName1, S.lastName2
                                    FROM USERS S, STUD_LIST L, COURSES C
                                    WHERE L.student_per = S.id_us
                                    AND L.course_per = C.id_co
                                    AND C.id_co = ?");
        $sentence->execute(array($_SESSION['page']));
        return $sentence->fetchAll();
    }

    function studentsGrades($id_student){
        $con = connect();
        $sentence = $con->prepare("SELECT A.name_as, G.grade
                                FROM ASSIGNMENTS A, ASSIG_LIST L, GRADES G
                                WHERE A.id_as = L.id_as_li
                                AND A.id_as = G.assign_gr_per
                                AND course_per = ?
                                AND G.student_gr_per = ?");
        $sentence->execute(array($_SESSION['page'], $id_student));
        return $sentence->fetchAll();
    }

    function studentsGradesForEach($id_student){
        $con = connect();
        $sentence = $con->prepare("SELECT G.file_gr, G.grade
                                FROM ASSIGNMENTS A, ASSIG_LIST L, GRADES G
                                WHERE A.id_as = L.id_as_li
                                AND A.id_as = G.assign_gr_per
                                AND course_per = ?
                                AND G.student_gr_per = ?
                                AND A.id_as = ?");
        $sentence->execute(array($_SESSION['page'], $id_student, $_SESSION['id_assign']));
        return $sentence->fetchAll();
    }

    if(isset($_POST['grade_std'])){
        $con = connect();
        $sentence = $con->prepare("UPDATE GRADES
                                    SET grade = ?
                                    WHERE assign_gr_per = ?
                                    AND student_gr_per = ?");
        $sentence->execute(array($_POST['grade_std'], $_SESSION['id_assign'], $_POST['id_std_gr']));
        echo "Grade saved successfully!";
    }



    /*
    =====================================================
            TEACHER: STUDENTS LIST
    =====================================================
    */

    if(isset($_POST['home'])){
        $aux = verifyUniqEmail(trim($_POST['email']));
        if($aux == "" || $aux == $_SESSION['id']){
            $con = connect();
            $sentence = $con->prepare("UPDATE USERS
                                        SET address = ?,
                                        email = ?,
                                        password = ?
                                        WHERE id_us = ?");
            $sentence->execute(array($_POST['home'], trim($_POST['email']), $_POST['pass'], $_SESSION['id']));
            $_SESSION['address'] = $_POST['home'];
            $_SESSION['email'] = trim($_POST['email']);
            $_SESSION['password'] = $_POST['pass'];
            echo 1;
        }else
            echo 0;
    }

    if(isset($_POST['tmp_id_user'])){
        $_SESSION['tmp_id_user_mod'] = $_POST['tmp_id_user'];
        $con = connect();
        $query = "SELECT *
                    FROM USERS
                    WHERE id_us = ?";
        $sentence = $con->prepare($query);
        $sentence->execute(array($_POST['tmp_id_user']));
        foreach($sentence->fetchAll() as $r){
            $_SESSION['cedulaMod'] = $r[0];
            $_SESSION['email2Mod'] = $r[1];
            $_SESSION['pass2Mod'] = $r[2];
            $_SESSION['nam1Mod'] = $r[4];
            $_SESSION['nam2Mod'] = $r[5];
            $_SESSION['lasnam1Mod'] = $r[6];
            $_SESSION['lasnam2Mod'] = $r[7];
            $_SESSION['home2Mod'] = $r[8];
        }

    }

    if(isset($_POST['typeAdd'])){
        if($_POST['typeAdd'] == "Student")
            $typeForAdd = "S";
        else
            $typeForAdd = "T";
        //$auxCedula = verifyUniqCedula(trim($_POST['cedula']));
        //$auxEmail = verifyUniqEmail(trim($_POST['email']));

        $con = connect();
        if($_SESSION['cedulaMod'] == ""){
            $sentence = $con->prepare("INSERT INTO USERS
                                    SET id_us = ?,
                                    email = ?,
                                    password = ?,
                                    type = ?,
                                    name1 = ?,
                                    name2 = ?,
                                    lastName1 = ?,
                                    lastName2 = ?,
                                    address = ?,
                                    picture = 'user.png'");
            $sentence->execute(array($_POST['cedula'],
                                    trim($_POST['email2']),
                                    $_POST['pass2'],
                                    $typeForAdd,
                                    $_POST['nam1'],
                                    $_POST['nam2'],
                                    $_POST['lasnam1'],
                                    $_POST['lasnam2'],
                                    $_POST['home2']));
        }else{
            $sentence = $con->prepare("UPDATE USERS
                                    SET email = ?,
                                    password = ?,
                                    name1 = ?,
                                    name2 = ?,
                                    lastName1 = ?,
                                    lastName2 = ?,
                                    address = ?
                                    WHERE id_us = ?");
            $sentence->execute(array(trim($_POST['email2']),
                                    $_POST['pass2'],
                                    $_POST['nam1'],
                                    $_POST['nam2'],
                                    $_POST['lasnam1'],
                                    $_POST['lasnam2'],
                                    $_POST['home2'],
                                    $_SESSION['tmp_id_user_mod']));
                                    
        }
        echo 1;
        // if($auxCedula == "" && $auxEmail == ""){
            
        // }else{
        //     echo 0;

        // }
    }

    /*
    =====================================================
                    VALIDATE
    =====================================================
    */

    function verifyUniqEmail($email){        
        $con = connect();
        $sentence = $con->prepare("SELECT id_us
                                FROM USERS
                                WHERE email = ?
                                GROUP BY id_us");
        $sentence->execute(array($email));
        foreach($sentence->fetchAll() as $r)
            return $r[0];
    }

    function verifyUniqCedula($cedula){        
        $con = connect();
        $sentence = $con->prepare("SELECT id_us
                                FROM USERS
                                WHERE id_us = ?
                                GROUP BY id_us");
        $sentence->execute(array($cedula));
        foreach($sentence->fetchAll() as $r)
            return $r[0];
    }

    /*
    =====================================================
                    ADMIN
    =====================================================
    */

    if(isset($_POST['id_delete'])){
        $con = connect();
        if($_POST['type_delete'] == "Student"){
            $query = "DELETE FROM USERS
                        WHERE id_us = ?;
                        DELETE FROM STUD_LIST
                        WHERE student_per = ?;
                        DELETE FROM GRADES
                        WHERE student_gr_per = ?";
            $sentence = $con->prepare($query);
            $sentence->execute(array($_POST['id_delete'], $_POST['id_delete'], $_POST['id_delete']));
            echo "Student deleted successfully!";
        }else{
            $query = "UPDATE COURSES
                        SET teacher_per = 'auxiliar'
                        WHERE teacher_per = ?;
                        DELETE FROM USERS
                        WHERE id_us = ?;";
            $sentence = $con->prepare($query);
            $sentence->execute(array($_POST['id_delete'], $_POST['id_delete']));    
            echo "Teacher deleted successfully!";
        }
    }

    function showAllUsers(){
        $con = connect();
        $query = "SELECT *
                    FROM USERS
                    WHERE type != 'A'";
        $sentence = $con->prepare($query);
        $sentence->execute();
        return $sentence->fetchAll();
    }

    function graphUsersList(){
        $i = 0;
        $tmp;
        $out1 = '<thead>
                    <tr>
                        <th>ID</th>';
        $out2 = '<tbody>';
        foreach(showAllUsers() as $r){
            $out2 .= '<tr>
                        <td>'.$r[0].'</td>
                        <td>'.$r[4].' '.$r[5].' '.$r[6].' '.$r[7].'</td>';
            if($r[3] == "S")
                $tmp = "Student";
            else
                $tmp = "Teacher";
            
            $out2 .= '<td>'.$tmp.'</td>
                    <td>'.$r[8].'</td>
                    <td><button class="btn btn-secondary edit" id="'.$r[0].'">Edit</button></td>
                    <td><button class="btn btn-success delete" id="'.$r[0].'">Delete</button></td></tr>';
        }
        $out1 .= '<th>User</th>
                <th>Type</th>
                <th>Address</th>
                <th>Edit</th>
                <th>Delete</th>
                </tr>
            </thead>';
        $out2 .= '</tbody>';
        
        echo '<br><center>
        <div class="card mb-4" style="display: block;overflow-x: auto;white-space: nowrap; width: 1300px;">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Users List
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="tableList">'.$out1.$out2.'</table>
                </div>
            </div>
        </center>';
    }


    function graphSubjectsList(){
        $out1 = '<thead>
                    <tr>
                        <th>ID</th>';
        $out2 = '<tbody>';
        foreach(showAllSubjects() as $r){
            $out2 .= '<tr>
                        <td>'.$r[0].'</td>
                        <td>'.$r[1].'</td>';
            
            $out2 .= '<td>'.$r[2].'</td>
                    <td><button class="btn btn-secondary edit" id="'.$r[0].'">Edit</button></td>
                    <td><button class="btn btn-success delete" id="'.$r[0].'">Delete</button></td></tr>';
        }
        $out1 .= '<th>Subject</th>
                <th>Description</th>
                <th>Edit</th>
                <th>Delete</th>
                </tr>
            </thead>';
        $out2 .= '</tbody>';
        
        echo '<br><center>
        <div class="card mb-4" style="display: block;overflow-x: auto;white-space: nowrap; width: 1300px;">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Users List
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="tableList">'.$out1.$out2.'</table>
                </div>
            </div>
        </center>';
    }


    function showAllSubjects(){
        $con = connect();
        $query = "SELECT *
                    FROM SUBJECTS";
        $sentence = $con->prepare($query);
        $sentence->execute();
        return $sentence->fetchAll();
    }

    if(isset($_POST['tmp_id_sub'])){
        $_SESSION['tmp_id_sub_up'] = $_POST['tmp_id_sub'];
        $con = connect();
        $query = "SELECT *
                    FROM SUBJECTS
                    WHERE id_su = ?";
        $sentence = $con->prepare($query);
        $sentence->execute(array($_POST['tmp_id_sub']));
        foreach($sentence->fetchAll() as $r){
            $_SESSION['subMod'] = $r[1];
            $_SESSION['desMod'] = $r[2];
        }

    }

    if(isset($_POST['id_delete_sub'])){
        $con = connect();
        $query = "DELETE FROM SUBJECTS
                    WHERE id_su = ?;
                    DELETE FROM COURSES
                    WHERE subject_per = ?";
        $sentence = $con->prepare($query);
        $sentence->execute(array($_POST['id_delete_sub'], $_POST['id_delete_sub']));
        echo "Subject deleted successfully!";
    }

    if(isset($_POST['subjectAdd'])){
        $con = connect();
        if($_SESSION['subMod'] == ""){
            $sentence = $con->prepare("INSERT INTO SUBJECTS
                                    SET name_su = ?,
                                    des_su = ?");
            $sentence->execute(array($_POST['subjectAdd'],
                                    $_POST['descriptionAdd']));
        }else{
            $sentence = $con->prepare("UPDATE SUBJECTS
                                    SET name_su = ?,
                                    des_su = ?
                                    WHERE id_su = ?");
            $sentence->execute(array($_POST['subjectAdd'],
                                    $_POST['descriptionAdd'],
                                    $_SESSION['tmp_id_sub_up']));                                    
        }
        echo 1;
    }


    function graphCourses(){
        return showSubjects().showFreeTeachers();
    }

    function graphCoursesFree(){
        return showFreeCourses().showFreeTeachers();
    }

    function showSubjects(){
        $con = connect();
        $query = "SELECT *
                    FROM SUBJECTS
                    WHERE id_su NOT IN (SELECT S.id_su
                            FROM SUBJECTS S, COURSES C
                            WHERE S.id_su = C.subject_per)";
        $sentence = $con->prepare($query);
        $sentence->execute();
        $out = '<select class="form-select" aria-label="Default select example" id="sub">';
        foreach($sentence->fetchAll() as $r)
            $out .= '<option value="'.$r[0].'">'.$r[1].'</option>';
        return $out.'</select><br>';
    }

    function showFreeTeachers(){
        $con = connect();
        $query = "SELECT *
                    FROM USERS
                    WHERE type = 'T'
                    AND id_us NOT IN (SELECT T.id_us
                            FROM USERS T, COURSES C
                            WHERE T.id_us = C.teacher_per)";
        $sentence = $con->prepare($query);
        $sentence->execute();
        $out = '<select class="form-select" aria-label="Default select example" id="tea">';
        foreach($sentence->fetchAll() as $r)
            $out .= '<option value="'.$r[0].'">'.$r[4].$r[5].$r[6].$r[7].'</option>';
        return $out.'</select><br>';
    }

    function showFreeCourses(){
        $con = connect();
        $query = "SELECT * 
                FROM SUBJECTS S, COURSES C
                WHERE S.id_su = C.subject_per
                AND C.teacher_per = 'auxiliar'";
        $sentence = $con->prepare($query);
        $sentence->execute();
        $out = '<select class="form-select" aria-label="Default select example" id="cour">';
        foreach($sentence->fetchAll() as $r)
            $out .= '<option value="'.$r[3].'">'.$r[1].'</option>';
        return $out.'</select><br>';
    }

    if(isset($_POST['id_sub_course'])){
        $con = connect();
        $sentence = $con->prepare("INSERT INTO COURSES
                                SET subject_per = ?,
                                teacher_per = ?");
        $sentence->execute(array($_POST['id_sub_course'],
                                $_POST['id_tea_course']));
        echo "Course added succesfully!";
    }

    if(isset($_POST['id_cour_update'])){
        $con = connect();
        $sentence = $con->prepare("UPDATE COURSES
                                SET teacher_per = ?
                                WHERE id_co = ?");
        $sentence->execute(array($_POST['id_tea_update'],
                                $_POST['id_cour_update']));
        echo "Course updated succesfully!";
    }

    function showAdminOptions(){
        if($_SESSION['type'] == "A")
        return '<div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/usersAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <p class="card-text">Add, modify, and delete users.</p>
                                <a href="usersShow.php" class="btn btn-primary">Users Mananger</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/subjectAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Subjects</h5>
                                <p class="card-text">Add, modify, and delete subjects.</p>
                                <a href="subjectsShow.php" class="btn btn-primary">Subjects Mananger</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/courseAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Courses</h5>
                                <p class="card-text">Create a course, setting a teacher.</p>
                                <a href="coursesShow.php" class="btn btn-primary">Courses Mananger</a>
                            </div>
                        </div>
                    </div>
                </div>';
    }



    //Inserting TEST
    // function insertTest(){
    //     $con = connect();
    //     $sql = "INSERT INTO test
    //         set id = 4;";
    //     $consulta = $con->prepare($sql);
    //     $consulta->execute();
    //     //echo "OK";
    // }


    // function graphCourseClicked($result){        
    //     $out;
    //     foreach($result as $r)
    //         $out = $r[0].'...'.$r[1];
    //     echo $out;
    // }

    // function listUsers(){
    //     $con = connect2();
    //     $query = "SELECT * FROM USUARIOS";
    //     $sentence = $con->prepare($query);
    //     $sentence->execute();
    //     $result = $sentence->fetchAll();

    //     $row = "";
    //     foreach($result as $r){
    //         $row .= '<tr>
    //                     <td>'.$r['id'].'</td>
    //                     <td>'.$r['user'].'</td>
    //                     <td>'.$r['password'].'</td>
    //                     <td>
    //                         <button class="edit">Edit</button>
    //                         <button class="delete">Delete</button>
    //                     </td>
    //                 </tr>';
    //     }

    //     return $row;
    // }

    // //Inserting USERS
    // if(isset($_POST['u'])){
    //     $con = connect2();
    //     $sql = "INSERT INTO USUARIOS
    //         set user=?, password=?";
    //     $consulta = $con->prepare($sql);
    //     $consulta->execute(array($_POST['u'], $_POST['p']));
    //     echo 1;
    // }else
    //     echo 0;
?>