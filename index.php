<?php
session_start();
if(!isset($_SESSION['username'])) {
    header('Location: /faculty_allocation/log_in.php');
}
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faculty-Allocation</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        /* 
            This function in called when go button is clicked after selecting date and session
            It hides that date selecting block and calls the get_faculty_details method and displays alloting block 
        */
        function session_selected(flag) {
            document.getElementById("selected_faculty_count").innerHTML = 0;
            document.getElementById("available_faculty_count").innerHTML = 0;
            document.getElementsByClassName("faculty_in_allot")[0].innerHTML = "";
            document.getElementsByClassName("faculty_in_allot")[1].innerHTML = "";
            var date_selected = document.getElementById("selected_date").value;
            var session = '';
            if (document.getElementById("after_noon").checked)
                session = 'AN';
            if (document.getElementById("fore_noon").checked)
                session = 'FN';
            if ((date_selected != null && date_selected.length != 0) && (session == 'AN' || session == 'FN')) { 
                get_faculty_details(flag);
                var day = new Date(date_selected);
                sat = "";
                if(day.getDay() == 6) {
									var sat = "(Sat)"; 
								}
                document.getElementById("date_selected_input").value = date_selected;
                document.getElementById("session_selected_input").value = session;
                document.getElementById("faculties_selected_input").value = "";

                document.getElementById("selected_session_value").innerHTML = date_selected + sat + ' | ' + session;
                document.getElementById("before_select_session").style.display = 'none';
                document.getElementById("after_select_session").style.display = 'block';
            } else {
                document.getElementById("select_session_error_msg").innerHTML = "Invalid Session";
            }
        }

        /* 
            This function is called by session_selected() method
            It makes a ajax call and gets the faculty details in JSON format and calls write_faculty_details() by passing response text
         */
        function get_faculty_details(flag) {
            var date_selected = document.getElementById("selected_date").value;
            var session = '';
            if (document.getElementById("after_noon").checked)
                session = 'AN';
            if (document.getElementById("fore_noon").checked)
                session = 'FN';
            
            var res_text = '';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    res_text = this.responseText;
                    write_faculty_details(res_text);
                }
            };
            if(flag==1) {
                var temp = document.getElementById("auto_allot_faculty_count").value;
                if(temp.length==0 || isNaN(temp)) {
                    temp = 0;
                }
                var temp1 = document.getElementById("auto_allot_roomboy_count").value;
                if(temp1.length==0 || isNaN(temp1)) {
                    temp1 = 0;
                }
                var duties = temp + '|' + temp1;
                xhttp.open("GET", "get_faculties_to_allot.php?date="+date_selected+"&session="+session+"&auto_allot="+duties, true);
            }
            else {
                xhttp.open("GET", "get_faculties_to_allot.php?date="+date_selected+"&session="+session, true);
            }
            xhttp.send();
        }

        /* 
            It is called by get_faculty_details() and takes json response of faculty details as parameter
            It writes the faculties data to respective tables
         */
        function write_faculty_details(res) {
            //var res = '{"available": [{"id": "1", "name": "sharath", "department": "ISE", "duties": "3"}, {"id": "2", "name": "punith", "department": "ISE", "duties": "4"}, {"id": "3", "name": "ramya", "department": "ISE", "duties": "2"}, {"id": "4", "name": "krishna prasad", "department": "MEC", "duties": "1"}], "selected": [ {"id": "5", "name": "saraswathi", "department": "ISE", "duties": "3"}, {"id": "6", "name": "ajay", "department": "ISE", "duties": "4"}, {"id": "7", "name": "victor", "department": "CSE", "duties": "2"}, {"id": "8", "name": "natraj", "department": "basic science", "duties": "1"}]}';
            var myObj = JSON.parse(res);
            var available = myObj.available;
            l = available.length-1;
            count = 0;
            for (i = 0; i < l; i++) {
                if(available[i]['id']) {
                    row = '<tr id="a_' + available[i]['id'] + '"><td>' + available[i]['id'] + '</td><td onmouseover="get_faculty_duties_to_title(this, '+ available[i]['id'] +')">' + available[i]['name'] + '</td><td>' + available[i]['department'] + '</td><td>' + available[i]['duties'] + '</td><td>' + available[i]['designation'] + '</td><td class="text-center"><button class="py-1 btn btn-outline-success" onclick="select(\'a_' + available[i]['id'] + '\',\'' + available[i]['id'] + '\',\'' + available[i]['name'] + '\',\'' + available[i]['department'] + '\',\'' + available[i]['duties'] + '\',\'' + available[i]['designation'] + '\')">-></button></td></tr>';
                    $('#available_table').append(row);
                    count++;
                }
            }
            document.getElementById("available_faculty_count").innerHTML = count;

            var selected = myObj['selected'];
            l = selected.length-1;
            count = 0;
            for (i = 0; i < l; i++) {
                if(selected[i]['id']) {
                    row = '<tr id="a_' + selected[i]['id'] + '"><td class="text-center"><button class="py-1 btn btn-outline-danger" onclick="remove(\'a_' + selected[i]['id'] + '\',\'' + selected[i]['id'] + '\',\'' + selected[i]['name'] + '\',\'' + selected[i]['department'] + '\',\'' + selected[i]['duties'] + '\',\'' + selected[i]['designation'] + '\')"><-</button></td><td>' + selected[i]['id'] + '</td><td onmouseover="get_faculty_duties_to_title(this, '+ selected[i]['id'] +')">' + selected[i]['name'] + '</td><td>' + selected[i]['department'] + '</td><td>' + selected[i]['duties'] + '</td><td>' + selected[i]['designation'] + '</td></tr>';
                    $('#selected_table').append(row);
                    add_faculty_to_input(selected[i]['id']);
                    count++;
                }
            }
            document.getElementById("selected_faculty_count").innerHTML = count;

        }

        /* 
            It is called when right arrow button is clicked of any faculty in available faculty table
            It removes that row from available faculty table and adds it to selected faculties table
         */
        function select(id, fid, name, dept, duties, designation) {
            document.getElementById(id).remove();
            id = id.slice(2);
            //onclick = 'onclick="remove(\'s_'+id+'\',\''+fid+'\',\''+name+'\',\''+dept+'\',\''+duties+'\')"';
            //alert(onclick);
            row = '<tr id="s_' + id + '"><td class="text-center"><button class="btn btn-outline-danger" onclick="remove(\'s_' + id + '\',\'' + fid + '\',\'' + name + '\',\'' + dept + '\',\'' + duties + '\',\'' + designation + '\')"><-</button></td><td onmouseover="get_faculty_duties_to_title(this, '+ fid +')">' + fid + '</td><td>' + name + '</td><td>' + dept + '</td><td>' + duties + '</td><td>' + designation + '</td></tr>';
            $('#selected_table').append(row);
            selected = document.getElementById("selected_faculty_count");
            available = document.getElementById("available_faculty_count");
            selected.innerHTML = parseInt(selected.innerHTML) + 1;
            available.innerHTML = parseInt(available.innerHTML) - 1;
            add_faculty_to_input(fid);
        }

        /* 
            It is called when left arrow button is clicked of any faculty in selected faculty table
            It removes that row from selected faculty table and adds it to available faculties table
         */
        function remove(id, fid, name, dept, duties, designation) {
            document.getElementById(id).remove();
            id = id.slice(2);
            row = '<tr id="a_' + id + '"><td>' + fid + '</td><td onmouseover="get_faculty_duties_to_title(this, '+ fid +')">' + name + '</td><td>' + dept + '</td><td>' + duties + '</td><td>' + designation + '</td><td class="text-center"><button class="btn btn-outline-success" onclick="select(\'a_' + id + '\',\'' + fid + '\',\'' + name + '\',\'' + dept + '\',\'' + duties + '\',\''+ designation +'\')">-></button></td></tr>';
            $('#available_table').append(row);
            selected = document.getElementById("selected_faculty_count");
            available = document.getElementById("available_faculty_count");
            selected.innerHTML = parseInt(selected.innerHTML) - 1;
            available.innerHTML = parseInt(available.innerHTML) + 1;
            remove_faculty_from_input(fid);
        }

        /* 
            It is called when Close button is clicked which is displayed after selecting data and session to allot
            it closes the allot block and reopens date selecting block
         */
        function close_allotment() {
            document.getElementById("selected_faculty_count").innerHTML = 0;
            document.getElementById("available_faculty_count").innerHTML = 0;
            document.getElementsByClassName("faculty_in_allot")[0].innerHTML = "";
            document.getElementsByClassName("faculty_in_allot")[1].innerHTML = "";
            document.getElementById("before_select_session").style.display = 'block';
            document.getElementById("after_select_session").style.display = 'none';
        }

        function add_faculty_to_input(fid) {
            input_ele = document.getElementById("faculties_selected_input");
            input_ele.value = input_ele.value+'|'+fid;
        }

        function remove_faculty_from_input(fid) {
            input_ele = document.getElementById("faculties_selected_input");
            temp = input_ele.value;
            temp = temp.replace('|'+fid, '');
            input_ele.value = temp;
        }

        function check_allot_before_submit() {
            input_ele = document.getElementById("faculties_selected_input");
            temp = input_ele.value;
            if(temp[0] == '|') {
                input_ele.value = temp.substring(1);
            }
        }

        function get_faculty_duties_to_title(ele, fid) {
            var res_text = '';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    res_text = this.responseText;
                    var myObj = JSON.parse(res_text);
                    var duties = myObj.duties;
                    var l = duties.length-1;
                    var temp = '';
                    for(i=0;i<l;i++) {
		                    var day = new Date(duties[i]['date']);
		                    sat = "";
		                    if(day.getDay() == 6) {
													var sat = "(Sat)"; 
												}
                        temp = temp + duties[i]['date'] + ' | ' + duties[i]['session'] + " " + sat +'\n';
                    }
                    ele.title = temp;
                }
            };
            xhttp.open("GET", "get_faculty_duties.php?fid="+fid, true);
            xhttp.send();
        }

        function get_a_faculty_details(fid) {
            //event.preventDefault();
            var res_text = '';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    res_text = this.responseText;
                    var myObj = JSON.parse(res_text);
                    var duties = myObj.duties;
                    var l = duties.length-1;
                    var temp = '';
                    for(i=0;i<l;i++) {
                        temp = temp + '<tr><td>' + duties[i]['date'] + '</td><td>' + duties[i]['session'] + '</td><td>' + duties[i]['reliever'] + '</td></tr>';
                    }
                    document.getElementById("faculty_duty_details_table").innerHTML = temp;
                    document.getElementById("faculty_name").innerHTML = myObj.name;
                    document.getElementById("faculty_branch").innerHTML = myObj.department;
                    document.getElementById("faculty_phone").innerHTML = myObj.phone;
                    document.getElementById("faculty_appointment_form").href = "appointment_form.php?fid="+fid;
                    
                }
            };
            xhttp.open("GET", "get_faculty_duties.php?fid="+fid, true);
            xhttp.send();

            document.getElementById("all_faculty_table").style.display = 'none';
            document.getElementById("single_faculty_details").style.display = 'block';
        }

        function show_all_faculties_table() {
            document.getElementById("all_faculty_table").style.display = 'block';
            document.getElementById("single_faculty_details").style.display = 'none';
        }

        function reliever(ele, fid, date) {
            reliever_value = 'False';
            if(ele.checked) {
                reliever_value = 'True';
            }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    res = this.responseText;
                    if(res=='True') {
                        ele.checked = true;
                    }
                    else {
                        ele.checked = false;
                    }
                    
                }
            };
            xhttp.open("GET", "reliever.php?fid="+fid+"&date="+date+"&reliever="+reliever_value, true);
            xhttp.send();
        }
        function delete_faculty(faculty) {
            choice = confirm('Want to delete ' + faculty + ' ?')
            if (choice == false) {
                event.preventDefault()
            }
        }
    </script>
</head>

<body>
    <div class="mx-5 mt-3">
        <!--<div class="jumbotron">
            <h1>Faculty Allocation</h1>
        </div>-->
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#faculty">Faculty</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#allot">Allot</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#view_alloted">View Alloted</a>
            </li>
            <li class="nav-item ml-auto">
                <a class="nav-link" href="log_out.php">LOG-OUT</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content border border-top-0 pb-2 mb-2">
            <!-- Faculty List -->
            <div id="faculty" class="tab-pane active pt-2 mx-3">
                <div id="all_faculty_table">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <h3 class="mb-2">Faculty List</h3>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control" id="search_faculty" placeholder="Search by faculty details">
                        </div>
                        <div class="col-auto">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#faculty_adding_file_modal" type="button">Add Faculties file</button>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#faculty_adding_form_modal">Add a Faculty</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row border border-left-0 border-right-0">
                        <div class="col px-0">
                            <div class="table-responsive">
                                <div style="overflow:auto; height:445px;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>FID</th>
                                                <th>NAME</th>
                                                <th>Department</th>
                                                <th>Phone Number</th>
                                                <th>Designation</th>
                                                <th>Number Of duties</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="faculty_details_table">
                                            <?php
                                            $sql = "SELECT * FROM faculty;";
                                            $res = mysqli_query($conn, $sql);
                                            while ($row = mysqli_fetch_array($res)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ('<a onclick="get_a_faculty_details(\'' . $row[0] . '\')">' . $row[0] . '</a>'); ?></td>
                                                    <td><?php echo ('<a class="btn btn-link" onclick="get_a_faculty_details(\'' . $row[0] . '\')">' . $row[1] . '</a>'); ?></td>
                                                    <td><?php echo ($row[2]); ?></td>
                                                    <td><?php echo ($row[3]); ?></td>
                                                    <td><?php echo ($row[4]); ?></td>
                                                    <td><?php echo ($row[5]); ?></td>
                                                    <td><a href="delete_faculty.php/?fid=<?php echo($row[0]); ?>" onclick="delete_faculty('<?php echo($row[1]); ?>')"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto ml-auto mt-2">
                            <a href="appointment_form.php?fid=All" class="btn btn-outline-primary">Print</a>
                        </div>
                    </div>
                </div>
                <div id="single_faculty_details" style="display: none;">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <h3 class="mb-2">
                                <span id="faculty_name">Sharath HOD</span> |
                                <span id="faculty_branch">ISE</span> |
                                <span id="faculty_phone">9087654321</span>
                            </h3>
                        </div>
                        <div class="col-auto">
                            <button onclick="show_all_faculties_table()" class="btn btn-danger btn-block px-3"> <b>&times;</b> </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Session</th>
                                            <th>Reliever</th>
                                        </tr>
                                    </thead>
                                    <tbody id="faculty_duty_details_table">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-auto ml-auto">
                            
                        <div class="col-auto">
                            <a href="" id="faculty_appointment_form" class="btn btn-outline-primary px-5">Print</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Allot -->
            <div id="allot" class="mx-3 tab-pane pt-1 px-0">
                <div id="before_select_session">
                    <div class="row justify-content-center mt-1">
                        <div class="col-auto">
                            <form action="#" method="GET">
                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <input type="date" class="form-control" name="date" id="selected_date" value="1998-09-19">
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="fore_noon" name="session">
                                    <label class="custom-control-label" for="fore_noon">Fore Noon</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="after_noon" name="session">
                                    <label class="custom-control-label" for="after_noon">After Noon</label>
                                </div>
                                <div class="form-group text-center mt-1">
                                    <span id="select_session_error_msg" class="text-danger"></span>
                                    <input type="button" value="Go" class="mt-1 btn btn-block btn-outline-primary" onclick="session_selected(0)">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="after_select_session" style="display:none;">
                    <div class="row justify-content-between mb-2 border border-top-0 border-right-0 border-left-0">
                        <div class="col-4">
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="Faculties" id="auto_allot_faculty_count">
                                <input type="number" class="form-control" placeholder="Room boys" id="auto_allot_roomboy_count">
                                <div class="input-group-append">
                                    <button onclick="session_selected(1)" class="btn btn-primary" type="submit">Auto allot</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 pl-1 pt-2 align-self-center">
                            <h4 id="selected_session_value"></h4>
                        </div>
                        <div class="col-auto">
                            <button onclick="close_allotment()" class="btn btn-danger btn-block px-3"> <b>&times;</b> </button>
                        </div>
                    </div>
                    <!--
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Number Of Duties">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Auto allot</button>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="row justify-content-between mt-2">
                        <div class="col-auto align-self-center">
                            <h5 class="text-left">Available : <span id="available_faculty_count">5</span></h5>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control" id="search_faculty_in_allot" placeholder="Search by faculty details">
                        </div>
                        <div class="col-auto align-self-center">
                            <h5 class="text-left">Selected : <span id="selected_faculty_count">5</span></h5>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-2 border border-left-0 border-right-0 border-bottom-0">
                        <div class="col px-0">
                            <div class="table-responsive">
                                <div style="overflow:auto; height:380px;">
                                    <table class="table table-bordered" id="available_table">
                                        <thead>
                                            <tr>
                                                <th>FID</th>
                                                <th>NAME</th>
                                                <th>Department</th>
                                                <th>Duties</th>
                                                <th>Designation</th>
                                                <th>Select</th>
                                            </tr>
                                        </thead>
                                        <tbody class="faculty_in_allot">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col px-0 border border-right-0 border-bottom-0 border-top-0">
                            <div class="table-responsive">
                                <div style="overflow:auto; height:380px;">
                                    <table class="table table-bordered" id="selected_table">
                                        <thead>
                                            <tr>
                                                <th>Remove</th>
                                                <th>FID</th>
                                                <th>NAME</th>
                                                <th>Department</th>
                                                <th>Duties</th>
                                                <th>Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody class="faculty_in_allot">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 border"></div>
                        <div class="col-2 mt-2">
                            <form action="allot_faculties.php" method="POST">
                                <input type="hidden" name="date" id="date_selected_input" value="">
                                <input type="hidden" name="session" id="session_selected_input" value="">
                                <input type="hidden" name="faculties_selected" id="faculties_selected_input" value="">
                                <button class="btn btn-outline-primary btn-block" name="allot">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Alloted -->
            <div id="view_alloted" class="container tab-pane fade"><br>
                <div id="cards">
                    <?php
                        $checkbox_count=1;
                        $sql = "SELECT day_of_exam, session FROM faculty_duty GROUP BY day_of_exam, session;";
                        if($res = mysqli_query($conn, $sql)) {
                            $count = 1;
                            while($row = mysqli_fetch_array($res)) {
                                $sql1 = "SELECT f.fid, f.name, f.department, f.duties, fd.reliever FROM faculty f, faculty_duty fd WHERE f.fid=fd.fid AND fd.day_of_exam='$row[0]' AND fd.session='$row[1]';";
                                $res1 = mysqli_query($conn, $sql1);
                                $num = mysqli_num_rows($res1);
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <a class="card-link" data-toggle="collapse" href="#<?php echo('session_'.$count); ?>">
                                        <div class="" style="font-size:1.25rem;"><?php echo($row[0]); ?> | <?php echo($row[1]); ?></div>
                                    </a>
                                </div>
                                <div class="col-auto ml-auto align-self-center">
                                    Count : <?php echo($num); ?>
                                </div>
                                <div class="col-auto">
                                    <a href='<?php echo("duties_of_the_day.php?date=$row[0]&session=$row[1]"); ?>' class="btn btn-outline-primary">Print</a>
                                    <a href='<?php echo("delete_session.php?date=$row[0]&session=$row[1]"); ?>' class="btn btn-outline-danger ml-2">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div id="<?php echo('session_'.$count++); ?>" class="collapse" data-parent="#cards">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>FID</th>
                                                        <th>NAME</th>
                                                        <th>Department</th>
                                                        <th>Number Of duties</th>
                                                        <?php
                                                            if(date("Y-m-d")==$row[0]) {
                                                        ?>
                                                            <th>Reliever</th>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    while($row1 = mysqli_fetch_array($res1)) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo($row1[0]) ?></td>
                                                        <td onmouseover="get_faculty_duties_to_title(this, '<?php echo($row1[0]); ?>')"><?php echo($row1[1]) ?></td>
                                                        <td><?php echo($row1[2]) ?></td>
                                                        <td><?php echo($row1[3]) ?></td>
                                                        <?php
                                                            $todays_date = date("Y-m-d");
                                                            if($todays_date==$row[0]) {
                                                                $sql3 = "SELECT COUNT(*), day_of_exam FROM faculty_duty WHERE fid='$row1[0]' AND day_of_exam!='$todays_date' AND reliever='True';";
                                                                $res3 = mysqli_query($conn, $sql3);
                                                                $res3 = mysqli_fetch_array($res3);
                                                                $count_res3 = $res3[0];
                                                        ?>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" onclick="reliever(this, '<?php echo($row1[0]) ?>', '<?php echo($row[0]) ?>')" class="custom-control-input" id="<?php echo($checkbox_count); ?>" name="example1" <?php if($row1[4]=='True')echo('checked'); ?> >
                                                                <label class="custom-control-label" for="<?php echo($checkbox_count++); ?>">
                                                                    <?php
                                                                    if($count_res3!=0) {
                                                                        echo("Relieved on $res3[1]");
                                                                    }?>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tr>
                                                <?php
                                                    }
                                                ?>  
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- The Faculty Adding Form in Modal -->
    <form action="add_faculty.php" method="POST">
        <div class="modal" id="faculty_adding_form_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add Faculty</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fid">FID:</label>
                            <input type="text" name="fid" class="form-control" id="fid">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <select class="form-control" id="department" name="department">
                                <option value="IS">IS</option>
                                <option value="CS">CS</option>
                                <option value="EC">EC</option>
                                <option value="CI">CI</option>
                                <option value="ME">ME</option>
                                <option value="MBA">MBA</option>
                                <option value="MCA">MCA</option>
                                <option value="MATH">MATH</option>
                                <option value="PHY">PHY</option>
                                <option value="CHE">CHE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <input type="text" name="phone" class="form-control" id="phone">
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation:</label>
                            <select class="form-control" id="designation" name="designation">
                                <option value="Teaching">Teaching</option>
                                <option value="Non-Teaching">Non-Teaching</option>
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="submit" name="faculty_form_submit" class="btn btn-outline-primary" value="ADD">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </form>


    <!-- The Faculty Adding File in Modal -->
    <form action="add_faculty.php" method="POST" enctype="multipart/form-data">
        <div class="modal" id="faculty_adding_file_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add Faculty File</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="custom-file">
                            <input type="file" name="faculty_file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <div class="text-center mt-2">
                            Format of Excel file
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mt-1">
                                <tr>
                                    <td></td>
                                    <th>A</th>
                                    <th>B</th>
                                    <th>C</th>
                                    <th>D</th>
                                    <th>E</th>
                                </tr>
                                <tr>
                                    <th>1</th>
                                    <td>FID</td>
                                    <td>Name</td>
                                    <td>Department</td>
                                    <td>Phone Number</td>
                                    <td>Designation</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>1</td>
                                    <td>XYZ</td>
                                    <td>ISE</td>
                                    <td>1234567890</td>
                                    <td>Teaching</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="submit" name="faculty_file_submit" class="btn btn-outline-primary" value="ADD">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </form>

</body>
<script>
    $(document).ready(function() {
        $("#search_faculty").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#faculty_details_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    $(document).ready(function() {
        $("#search_faculty_in_allot").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".faculty_in_allot tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>

</html>
