/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

            var request;
            var dest;
            var timeInterval = [];
            

            function processStateChange(){
                if (request.readyState == 4){
                    contentDiv = document.getElementById(dest);
                    if (request.status == 200){
                        response = request.responseText;
                        contentDiv.innerHTML = response;
                    } else {
                        contentDiv.innerHTML = "Error: Status "+request.status;
                    }
                }
            }
            function browserChoice(){
                var bC;
                if (window.XMLHttpRequest){
                    bC = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                    bC = new ActiveXObject("Microsoft.XMLHTTP");
                }
                return bC;
            }

            function loadHTML(URL, destination, method){
                dest = destination;
                request = browserChoice();
                request.onreadystatechange = processStateChange;
                request.open(method, URL, true);
                request.send(null);
            }

            /*function dosubmit(destination, URL, form) {
                new Ajax.Updater( destination, URL, { method: 'post',
                    parameters: $(form).serialize() } );
                $(form).reset();
                
            }*/
            function dosubmit(id_ ,URL , type_) {
                var options = {
                target: '#'+id_,
                url: URL,
                type: type_
                }
            $('#'+id_).ajaxSubmit(options);
            }
            function dosubmitblank(id_, URL) {
                var options = {
                target: '_blank',
                url: URL,
                type: POST
                }
            $('#'+id_).submit(options);
            }

          
            function showLayerJ(id) {
                $('#'+id).css('visibility', 'visible');

            }
            
            function testJava(){
                alert('тест пройден');
            }
            
            function showSNMPObj(id, mode)  
            {  
                $.ajax({  
                    url: "get_status.php?id_obj="+id+"&mode="+mode,  
                    cache: false,  
                    success: function(html){  
                        $('#'+id).html(html);  
                    }  
                });  
            }  
            function reloadObj(id, mode){  
                showSNMPObj(id, mode);  
                timeInterval[id] = setInterval(function(){showSNMPObj(id, mode)},3000);  
            };
            function loadAndKill(URL, destination, method){
                for (var key in timeInterval){
                    clearInterval(timeInterval[key]);
                }
                loadHTML(URL, destination, method);
            }
            function checkall(id){
                $('form#'+id+' input[type=checkbox]').attr('checked','checked');
                return false;
            }
            function uncheckall(id)
            {
                $('form#'+id+' input[type=checkbox]').removeAttr('checked');
                return false;
            } 
            function ajaxLoad(file, id){
                $.ajax({
                    url: file,
                    cache: false,
                    success: function(html){  
                        $('#'+id).html(html);  
                    }
                })
            }
            function ajaxKill(){
                for (var key in timeInterval){
                    clearInterval(timeInterval[key]);
                }
            }
            function ajaxChoiseSpan(id){
                $('#'+id).css('background', 'white');
                $('#'+id).css('border-bottom', '1px solid white');
            }
            function ajaxNoChoise(){
                $('div#topmenu span').css('background', 'lightgrey');
                $('div#topmenu span').css('border-bottom', '1px solid grey');
            }
            function ajaxLoadAndKill(file, id, buttonid){
                ajaxKill();
                ajaxNoChoise();
                ajaxLoad(file,id);
                ajaxChoiseSpan(buttonid);
            }

      
          