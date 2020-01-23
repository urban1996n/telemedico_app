var fetch_url;
var edit_url;
var delete_url;
var add_url;

    function fetch()
{
    $.ajax({
    url: fetch_url,
    }).fail(function() {
        showMessage("Wystąpił błąd podczas pobierania informacji o urządzeniach","warning");
    }).done(function(data) {
        $('#content-box').html('');
        data = JSON.parse(data);
        for(var i = 0 ; i < data.length; i++)
        {   
            if(data[i]['ping_test']){ping_test = "checked" ;}else{ping_test = ""};
            if(data[i]['http_test']){http_test = "checked" ;}else{http_test = ""};
            if(data[i]['other_test']){other_test = "checked";}else{other_test = ""};
            
            row = ` 
                <div class='border col-2'><input class='form-control' type="text" id='client_name`+data[i]['id']+`' value='`+data[i]['client_name']+`' name='client_name'></div>
                <div class='border col-2'><input class='form-control' type="text" id='machine_name`+data[i]['id']+`'  value='`+data[i]['machine_name']+`' name='machine_name'></div>
                <div class='border col-2'><input class='form-control'  type='text' id='ip_address`+data[i]['id']+`'   value='`+data[i]['ip_address']+`' style='width:100%' name='start_point'></div>
                <div class='border col-1'><input class='form-control'  type='checkbox' id='ping_test`+data[i]['id']+`'  `+ping_test+` style='width:100%' name='move_point'></div>
                <div class='border col-1'><input class='form-control'  type='checkbox' id='http_test`+data[i]['id']+`'  `+http_test+` style='width:100%' name='overload_power'></div>
                <div class='border col-1'><input class='form-control'  type='checkbox' id='other_test`+data[i]['id']+`' `+other_test+` style='width:100%' name='Id'></div>
                <div class='border col-3'>
                    <button onClick="edit(`+data[i]['id']+`,'`+data[i]['username']+`')" class='btn btn-success btn-edit' data-id='`+data[i]['id']+`' type='button' style='width:100%; margin-bottom:8px;'>Zmień</button><br/>
                    <button onClick="deleteOne(`+data[i]['id']+`)" class='btn btn-danger btn-edit' data-id='`+data[i]['id']+`' type='button' style='width:100%;'>Usuń</button>
                </div>
            `;
            $('#content-box').append(row);

            row = ` 
            <div onclick='currents(`+data[i]['id']+`,`+data[i]['move_point']+`,`+data[i]['start_point']+`,`+data[i]['overload_power']+`)'  class='col border machine' >`+data[i]['machine_name']+`</div>
            `;
            $('.machines').append(row);
        }
    });
}

$('#btn-add').click(function(){     
	client_name = $('#client_name').val(); 
	machine_name = $('#machine_name').val(); 
	ip_address = $('#ip_address').val();
    machine_id = $('#machine_id').val();
     
    if($('#ping_test').is(":checked")){ping_test = 1 ;}else{ping_test = 0};
    if($('#http_test').is(":checked")){http_test = 1 ;}else{http_test = 0};
    if($('#other_test').is(":checked")){other_test = 1;}else{other_test = 0};
    
	$.ajax({
	async: true,
	url: add_url,
	data: {client_name:client_name, machine_name:machine_name, ip_address:ip_address, ping_test:ping_test, http_test:http_test ,other_test:other_test, machine_id:machine_id},
	}).fail(function() {
		showMessage("Wystąpił błąd podczas dodawania maszyny","warning");
	}).done(function(data) {
		showMessage("Maszyna została dodana!","success");
		fetch();
	});
});

function edit(id){   
    client_name = $('#client_name'+id).val(); 
	machine_name = $('#machine_name'+id).val(); 
	ip_address = $('#ip_address'+id).val();
    machine_id = $('#machine_id'+id).val();
    
    if($('#ping_test'+id).is(":checked")){ping_test = 1 ;}else{ping_test = 0};
    if($('#http_test'+id).is(":checked")){http_test = 1 ;}else{http_test = 0};
    if($('#other_test'+id).is(":checked")){other_test = 1;}else{other_test = 0};
    
    $.ajax({
    async: true,
    type:'POST',
    url: edit_url,
    data: {client_name:client_name, machine_name:machine_name, ip_address:ip_address, ping_test:ping_test, http_test:http_test ,other_test:other_test, machine_id:machine_id, old_id:id},
    }).fail(function() {
        showMessage("Wystąpił błąd podczas edycji danych maszyny","warning");
    }).done(function(data) {
        showMessage("Dane maszyny zmienione!","success");
        fetch();
    });
}

function deleteOne(id){
    var choice = confirm("Czy na pewno chcesz usunąć ten wpis?");
    if(choice == true)
    {
        $.ajax({
        async: true,
        url: delete_url,
        data: {id:id},
        }).fail(function() {
            showMessage("Wystąpił błąd podczas usuwania maszyny","warning");
        }).done(function(data) {
            showMessage("Usunięto maszynę!","success");
            fetch();
        });
    }       
}