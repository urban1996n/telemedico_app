
function fetch(url)
{
var client_names = [];

    $.ajax({
    url: url ,
    }).fail(function() {
        showMessage("Nie udało się pobrać informacji o maszynach","warning");
    }).done(function(data) {
        $('h3').remove();
        $('#content-table').html('');
        data = JSON.parse(data);

        var rows = "<tr class='row0'>";
        $('#content-table').append(rows);
        var y = 0;
        var client_name = data[0]["client_name"];
        client_names.push(client_name);
        var rowsCount = 0 ;
        var headerOffset = parseFloat($(".row0").offset().top);
        headerOffset -= (1/10*(headerOffset));
        for(var i = 0 ; i < data.length; i++)
        {

            if(y == 10 )
            {
                rows +=`</tr><tr>
                    </tr><tr class='' style='border-top:1.5px black solid !important; padding-top:5px !important;'>
                `;
                
                y = 0;
            }
            
            if(client_name != data[i]["client_name"])
            {
                rowsCount++;
                client_name = data[i]["client_name"];
                client_names.push(client_name);
                
                rows +=`</tr><tr>
                </tr><tr class='row`+rowsCount+`' style='border-top:1.5px black solid !important; padding-top:5px !important;'>
                `;
            
                y = 0;

            }

            if(data[i]['overall_result'] == false)
            {
                var color = "rgba(150,0,0,0.3)";
            }else{
                var color = "rgba(0,255,0,0.3)";
            }
            if(data[i]['date_time'] == undefined)
            {
                data[i]['date_time'] = "Brak pomiarów";
            }
            rows += ` 
                <td class='border' style='background-color:`+color+`;max-width:10% !important; width:10% !important; height:10% !important;'><b>`+data[i]['machine_name']+`</b><br/> `+new Date(data[i]['date_time']).toLocaleString()+`<br/>`+data[i]['ip_address']+`</td>
            `;          
            y++;
        }
        $('#content-table').append(rows);

        for (i = 0; i <= client_names.length; i++)
        {
           var offsetTop = parseFloat($(".row"+i).offset().top);
           $('body').append("<h3 class='header"+i+"' style='position:absolute; top:"+offsetTop+"px'>" +client_names[i]+ "</h3>");
        }
        rows +='</tr>';
    });
}