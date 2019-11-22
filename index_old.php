<?php
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

    <script>
        function session_selected() {
            var date_selected = document.getElementById("selected_date").value;
            var session = '';
            if (document.getElementById("after_noon").checked)
                session = 'AN';
            if (document.getElementById("fore_noon").checked)
                session = 'FN';
            if ((date_selected != null || date_selected.lenght != 0) && (session == 'AN' || session == 'FN')) {
                document.getElementById("selected_session_value").innerHTML = date_selected + ' | ' + session;
                document.getElementById("before_select_session").style.display = 'none';
                document.getElementById("after_select_session").style.display = 'block';
                get_faculty_details();
            } else {
                document.getElementById("select_session_error_msg").innerHTML = "Invalid Session";
            }
        }
        function get_faculty_details() {
            var res = '{"available": [{"id": "1", "name": "sharath", "department": "ISE", "duties": "3"}, {"id": "2", "name": "punith", "department": "ISE", "duties": "4"}, {"id": "3", "name": "ramya", "department": "ISE", "duties": "2"}, {"id": "4", "name": "krishna prasad", "department": "MEC", "duties": "1"}], "selected": [ {"id": "5", "name": "saraswathi", "department": "ISE", "duties": "3"}, {"id": "6", "name": "ajay", "department": "ISE", "duties": "4"}, {"id": "7", "name": "victor", "department": "CSE", "duties": "2"}, {"id": "8", "name": "natraj", "department": "basic science", "duties": "1"}]}';
            var myObj = JSON.parse(res);
            var available = myObj.available;
            l = available.length;
            document.getElementById("available_faculty_count").innerHTML = l;
            for(i=0;i<l;i++) {
                row = '<tr id="a_' + available[i]['id'] + '"><td>' + available[i]['id'] + '</td><td>' + available[i]['name'] + '</td><td>' + available[i]['department'] + '</td><td>' + available[i]['duties'] + '</td><td><button class="btn btn-success btn-block" onclick="select(\'a_' + available[i]['id'] + '\',\'' + available[i]['id'] + '\',\'' + available[i]['name'] + '\',\'' + available[i]['department'] + '\',\'' + available[i]['duties'] + '\')">-></button></td></tr>';
                $('#available_table').append(row);
            }
            var selected = myObj['selected'];
            l = selected.length;
            document.getElementById("selected_faculty_count").innerHTML = l;
            for(i=0;i<l;i++) {
                row = '<tr id="a_' + selected[i]['id'] + '"><td><button class="btn btn-danger btn-block" onclick="remove(\'a_' + selected[i]['id'] + '\',\'' + selected[i]['id'] + '\',\'' + selected[i]['name'] + '\',\'' + selected[i]['department'] + '\',\'' + selected[i]['duties'] + '\')"><-</button></td><td>' + selected[i]['id'] + '</td><td>' + selected[i]['name'] + '</td><td>' + selected[i]['department'] + '</td><td>' + selected[i]['duties'] + '</td></tr>';
                $('#selected_table').append(row);
            }
            
        }
        function select(id, fid, name, dept, duties) {
            document.getElementById(id).remove();
            id = id.slice(2);
            //onclick = 'onclick="remove(\'s_'+id+'\',\''+fid+'\',\''+name+'\',\''+dept+'\',\''+duties+'\')"';
            //alert(onclick);
            row = '<tr id="s_' + id + '"><td><button class="btn btn-danger btn-block" onclick="remove(\'s_' + id + '\',\'' + fid + '\',\'' + name + '\',\'' + dept + '\',\'' + duties + '\')"><-</button></td><td>' + fid + '</td><td>' + name + '</td><td>' + dept + '</td><td>' + duties + '</td></tr>';
            $('#selected_table').append(row);
            selected = document.getElementById("selected_faculty_count");
            available = document.getElementById("available_faculty_count");
            selected.innerHTML = parseInt(selected.innerHTML)+1;
            available.innerHTML = parseInt(available.innerHTML)-1;
        }

        function remove(id, fid, name, dept, duties) {
            document.getElementById(id).remove();
            id = id.slice(2);
            row = '<tr id="a_' + id + '"><td>' + fid + '</td><td>' + name + '</td><td>' + dept + '</td><td>' + duties + '</td><td><button class="btn btn-success btn-block" onclick="select(\'a_' + id + '\',\'' + fid + '\',\'' + name + '\',\'' + dept + '\',\'' + duties + '\')">-></button></td></tr>';
            $('#available_table').append(row);
            selected = document.getElementById("selected_faculty_count");
            available = document.getElementById("available_faculty_count");
            selected.innerHTML = parseInt(selected.innerHTML)-1;
            available.innerHTML = parseInt(available.innerHTML)+1;
        }
    </script>
</head>

<body>
    <div class="container mt-3">
        <!--<div class="jumbotron">
            <h1>Faculty Allocation</h1>
        </div>-->
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#faculty">Faculty</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#allot">Allot</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#view_alloted">View Alloted</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content border border-top-0 pb-3 mb-3">
            <!-- Faculty List -->
            <div id="faculty" class="container tab-pane"><br>
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <h3 class="mb-2">Faculty List</h3>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="search_faculty" placeholder="Search by faculty details">
                    </div>
                    <div class="col-auto">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-primary" type="button">Add Faculties file</button>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#faculty_adding_form_modal">Add a Faculty</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>FID</th>
                                        <th>NAME</th>
                                        <th>Department</th>
                                        <th>Phone Number</th>
                                        <th>Number Of duties</th>
                                    </tr>
                                </thead>
                                <tbody id="faculty_details_table">
                                    <?php
                                    $sql = "SELECT * FROM faculty";
                                    $res = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_array($res)) {
                                        ?>
                                        <tr>
                                            <td><?php echo ($row[0]); ?></td>
                                            <td><?php echo ($row[1]); ?></td>
                                            <td><?php echo ($row[2]); ?></td>
                                            <td><?php echo ($row[3]); ?></td>
                                            <td><?php echo ($row[4]); ?></td>
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

            <!-- Allot -->
            <div id="allot" class="container tab-pane active pt-3">
                <div class="row justify-content-center" id="before_select_session">
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
                                <input type="button" value="Go" class="mt-1 btn btn-block btn-outline-primary" onclick="session_selected()">
                            </div>
                        </form>
                    </div>
                </div>
                <div id="after_select_session" style="display:none;">
                    <div class="row justify-content-center mb-3 border border-top-0 border-right-0 border-left-0">
                        <div class="col-auto">
                            <h4 id="selected_session_value">12/12/2019 | AN</h4>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Number Of Duties">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Auto allot</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between mt-3">
                        <div class="col-auto">
                            <h5 class="text-left" >Available : <span id="available_faculty_count">5</span></h5>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control" id="search_faculty_in_allot" placeholder="Search by faculty details">
                        </div>
                        <div class="col-auto">
                            <h5 class="text-left">Selected : <span id="selected_faculty_count">5</span></h5>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-1">
                        <div class="col">
                            <div class="table-responsive">
                                <div style="overflow:auto; height:320px;">
                                    <table class="table table-bordered mt-3" id="available_table">
                                        <thead>
                                            <tr>
                                                <th>FID</th>
                                                <th>NAME</th>
                                                <th>Department</th>
                                                <th>Duties</th>
                                                <th>Select</th>
                                            </tr>
                                        </thead>
                                        <tbody class="faculty_in_allot">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="table-responsive">
                                <div style="overflow:auto; height:320px;">
                                    <table class="table table-bordered mt-3" id="selected_table">
                                        <thead>
                                            <tr>
                                                <th>Remove</th>
                                                <th>FID</th>
                                                <th>NAME</th>
                                                <th>Department</th>
                                                <th>Duties</th>
                                            </tr>
                                        </thead>
                                        <tbody class="faculty_in_allot">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-2 mt-2">
                            <button class="btn btn-outline-primary btn-block">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Alloted -->
            <div id="view_alloted" class="container tab-pane fade"><br>
                <div id="cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <a class="card-link" data-toggle="collapse" href="#session1">
                                        <div class="" style="font-size:1.25rem;">12/12/2019 | AN</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="session1" class="collapse" data-parent="#cards">
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
                                                        <th>Designation</th>
                                                        <th>Number Of duties</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <a class="card-link" data-toggle="collapse" href="#session2">
                                        <div class="" style="font-size:1.25rem;">13/12/2019 | AN</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="session2" class="collapse" data-parent="#cards">
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
                                                        <th>Designation</th>
                                                        <th>Number Of duties</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <a class="card-link" data-toggle="collapse" href="#session3">
                                        <div class="" style="font-size:1.25rem;">14/12/2019 | AN</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="session3" class="collapse" data-parent="#cards">
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
                                                        <th>Designation</th>
                                                        <th>Number Of duties</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIS001</td>
                                                        <td>Saharath</td>
                                                        <td>ISE</td>
                                                        <td>Teaching</td>
                                                        <td>3</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Faculty Adding Form in Modal -->
    <form action="/add_faculty.php">
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
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <select class="form-control" id="department" name="department">
                                <option value="ISE">ISE</option>
                                <option value="CSE">CSE</option>
                                <option value="ECE">ECE</option>
                                <option value="CIV">CIV</option>
                                <option value="MEC">MEC</option>
                                <option value="MBA">MBA</option>
                                <option value="MCA">MCA</option>
                                <option value="Basic Science">Basic Science</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <input type="text" name="name" class="form-control" id="phone">
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-outline-primary" value="ADD">
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
</script>

</html>