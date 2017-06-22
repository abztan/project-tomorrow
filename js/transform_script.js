function communities_container(xhr) {
    if(xhr.status == 200){
      document.getElementById('set_community').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

  function display_communities() {
    var year_value = document.getElementById("set_year").value;
    var batch_value = document.getElementById("set_batch").value;
    var base_value = document.getElementById("set_base").value;

    if(year_value > 0 && batch_value > 0 && base_value > 0) {
      if((year_value > 2009 && year_value < 2101) && (batch_value > 0 && batch_value < 4) && base_value > 0) {
        var xmlhttp = null;
        if(typeof XMLHttpRequest != 'udefined'){
            xmlhttp = new XMLHttpRequest();
        }else if(typeof ActiveXObject != 'undefined'){
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }else
            throw new Error('You browser doesn\'t support ajax');

        xmlhttp.open('GET', '../transform/_controller-transform.php?command=display_drop_down_communities&year='+year_value+'&batch='+batch_value+'&base='+base_value, true);
        xmlhttp.onreadystatechange = function (){
          if(xmlhttp.readyState == 4 && xmlhttp.status==200)
            window.communities_container(xmlhttp);
        };
        xmlhttp.send(null);
      }
      else
        alert("Something is not right with your numbers... ಥ_ಥ");
    }
  }

function community_information_container(xhr) {
    if(xhr.status == 200){
      document.getElementById('inf_contents').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

  function display_community_main() {
    var application_pk = document.getElementById("set_community").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    //window.location.href = '../transform/_controller-transform.php?command=display_community_main&application_pk='+application_pk;
    xmlhttp.open('GET', '../transform/information_main.php?application_pk='+application_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.community_information_container(xmlhttp);
    };
    xmlhttp.send(null);

  }

function people_container(xhr) {
    if(xhr.status == 200){
      document.getElementById('people_default').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function people_table(xhr) {
    if(xhr.status == 200){
      document.getElementById('peo_contents').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

  function display_people_main(sort) {
    var application_pk = document.getElementById("set_community").value;
    document.getElementById("people_table").style.display = "";
    document.getElementById("people_default").style.display = "none";

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    //window.location.href = '../transform/_controller-transform.php?command=display_community_main&application_pk='+application_pk;
    xmlhttp.open('GET', '../transform/people_main.php?sort='+sort+'&application_pk='+application_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.people_table(xmlhttp);
    };
    xmlhttp.send(null);

  }

  function display_people_view(participant_pk) {
    document.getElementById("people_table").style.display = "none";
    document.getElementById("people_default").style.display = "";
    var application_pk = document.getElementById("set_community").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    //window.location.href ='../transform/people_view.php?application_pk='+application_pk+'&participant_pk='+participant_pk;
    xmlhttp.open('GET', '../transform/people_view.php?application_pk='+application_pk+'&participant_pk='+participant_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.people_container(xmlhttp);
    };
    xmlhttp.send(null);
  }

  function display_people_add() {
    document.getElementById("people_table").style.display = "none";
    document.getElementById("people_default").style.display = "";
    var application_pk = document.getElementById("set_community").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '../transform/people_add.php?application_pk='+application_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.people_container(xmlhttp);
    };
    xmlhttp.send(null);
  }

  function display_people_edit(participant_pk) {
    var application_pk = document.getElementById("set_community").value;
    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '../transform/people_edit.php?application_pk='+application_pk+'&participant_pk='+participant_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.people_container(xmlhttp);
    };
    xmlhttp.send(null);
  }

  function people_notify(xhr) {
      if(xhr.status == 200){
        document.getElementById('notify').innerHTML = xhr.responseText;
      }else
          throw new Error('Server has encountered an error\n'+
              'Error code = '+xhr.status);
  }

  function add_participant(username) {
    var application_pk = document.getElementById("set_community").value;
    var last_name = document.getElementById("add_last_name").value;
    var first_name = document.getElementById("add_first_name").value;

    if(last_name == "" || first_name == "") {
      alert("You missed a required field. (^～^;)ゞ");
    }
    else {
      var really = confirm("Are you sure you want to add this participant? ＼(ﾟｰﾟ＼)");
      if (really == true) {
        var middle_name = document.getElementById("add_middle_name").value;
        var gender = document.getElementById("add_gender").value;
        var birthday = document.getElementById("add_birthday").value;
        var contact_number = document.getElementById("add_contact_number").value;
        var people_class = document.getElementById("add_class").value;
        var status = document.getElementById("add_status").value;
        var notes = document.getElementById("add_notes").value;
        var username = username;
        var xmlhttp = null;
        if(typeof XMLHttpRequest != 'udefined'){
            xmlhttp = new XMLHttpRequest();
        }else if(typeof ActiveXObject != 'undefined'){
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }else
            throw new Error('You browser doesn\'t support ajax');

        xmlhttp.open('GET','../transform/_controller-transform.php?command=add_participant&application_pk='+application_pk+
        '&last_name='+last_name+
        '&first_name='+first_name+
        '&middle_name='+middle_name+
        '&gender='+gender+
        '&birthday='+birthday+
        '&contact_number='+contact_number+
        '&people_class='+people_class+
        '&status='+status+
        '&notes='+notes+
        '&username='+username, true);
        xmlhttp.onreadystatechange = function (){
            if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
              window.people_notify(xmlhttp);
          }
        };
        xmlhttp.send(null);
      }
    }
  }

  function update_participant(application_pk,participant_pk,username) {
    var r = confirm("Are you sure you want to save changes? (☞ﾟヮﾟ)☞");
    if (r == true) {
      var last_name = document.getElementById("edit_last_name").value;
      var first_name = document.getElementById("edit_first_name").value;
      var middle_name = document.getElementById("edit_middle_name").value;
      var gender = document.getElementById("edit_gender").value;
      var birthday = document.getElementById("edit_birthday").value;
      var contact_number = document.getElementById("edit_contact_number").value;
      var people_class = document.getElementById("edit_class").value;
      var status = document.getElementById("edit_status").value;
      var notes = document.getElementById("edit_notes").value;
      var username = username;

      var xmlhttp = null;
      if(typeof XMLHttpRequest != 'udefined'){
          xmlhttp = new XMLHttpRequest();
      }else if(typeof ActiveXObject != 'undefined'){
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
      }else
          throw new Error('You browser doesn\'t support ajax');

      //window.location.href = ;
      xmlhttp.open('GET','../transform/_controller-transform.php?command=update_participant&participant_pk='+participant_pk+
      '&last_name='+last_name+
      '&first_name='+first_name+
      '&middle_name='+middle_name+
      '&gender='+gender+
      '&birthday='+birthday+
      '&contact_number='+contact_number+
      '&people_class='+people_class+
      '&status='+status+
      '&notes='+notes+
      '&username='+username, true);
      xmlhttp.onreadystatechange = function (){
          if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
            display_people_view(participant_pk);
          xmlhttp.responseText;
        }
      };
      xmlhttp.send(null);
    }
  }

function attendance_container(xhr) {
    if(xhr.status == 200){
      document.getElementById('att_contents').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

  function display_attendance_main() {
    var application_pk = document.getElementById("set_community").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '../transform/attendance_main.php?application_pk='+application_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.attendance_container(xmlhttp);
    };
    xmlhttp.send(null);
  }

function update_people_attendance(command,participant_pk,identity,trigger,week_number,default_value,username) {
  var x = "att_" + identity;
  var y = "div_att" + identity;
  var value = document.getElementById(x).value;
  var column = "week_" + week_number;
  document.getElementById(y).style.display = "";

  if(1 == trigger) {
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '../transform/_controller-transform.php?command='+command+'&participant_pk='+participant_pk+'&column='+column+'&value='+value+'&username='+username, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        xmlhttp.responseText;
    };
    xmlhttp.send(null);
  }
  else {
    document.getElementById(x).value = default_value;
  }
}

function update_h2h_attendance(participant_pk,identity,trigger,week_letter,username) {
  var x = "h2h_" + identity;
  var y = "div_h2h_" + identity;

  var result = document.getElementById(x);
  var value = result.checked;

  document.getElementById(y).style.display = "";

  if(1 == trigger) {
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    //window.location.href = '../transform/_controller-transform.php?command=update_h2h_instance&participant_pk='+participant_pk+'&week_letter='+week_letter+'&value='+value+'&username='+username;

    xmlhttp.open('GET', '../transform/_controller-transform.php?command=update_h2h_instance&participant_pk='+participant_pk+'&week_letter='+week_letter+'&value='+value+'&username='+username, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        xmlhttp.responseText;
    };
    xmlhttp.send(null);
  }
}

function weekly_container(xhr) {
    if(xhr.status == 200){
      document.getElementById('wee_contents').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

  function display_weekly_main() {
    var application_pk = document.getElementById("set_community").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '../transform/weekly_main.php?application_pk='+application_pk, true);
    xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        window.weekly_container(xmlhttp);
    };
    xmlhttp.send(null);
  }

  function update_weekly_data(application_pk,column,identity,trigger,week,username) {
    var x = "input_" + identity;
    var y = "div_wk" + identity;
    var value = document.getElementById(x).value;

    //catches double lesson checkbox entry
    var z = identity.substr(0,1);
    if(z == 4 || z == 5) {
      var result = document.getElementById(x);
      var value = result.checked;
    }

    document.getElementById(y).style.display = "";

    if(1 == trigger) {

      if(typeof XMLHttpRequest != 'udefined'){
          xmlhttp = new XMLHttpRequest();
      }else if(typeof ActiveXObject != 'undefined'){
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
      }else
          throw new Error('You browser doesn\'t support ajax');

      xmlhttp.open('GET', '../transform/_controller-transform.php?command=update_weekly_instance&application_pk='+application_pk+'&week='+week+'&value='+value+'&column='+column+'&username='+username, true);
      xmlhttp.onreadystatechange = function (){
          if(xmlhttp.readyState == 4 && xmlhttp.status==200)
          xmlhttp.responseText;
      };
      xmlhttp.send(null);
    }
  }

//NON AJAX
function display_attendance_update(identity) {
  var y = "div_att" + identity;
  document.getElementById(y).style.display = "block";
}

function display_h2h_update(identity) {
  var y = "div_h2h_" + identity;
  document.getElementById(y).style.display = "block";
}

function display_weekly_update(identity) {
  var y = "div_wk" + identity;
  document.getElementById(y).style.display = "block";
}
