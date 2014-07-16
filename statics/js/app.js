// Submit Handler
$(function() { 
    $('#form').submit(function(e) { 
        e.preventDefault();
        if($(this).parsley('validate')) {
            $('table#images_table tbody').find('tr').remove();
            $('#download_images').hide();
            $('button#process_url').attr('disabled',true);

            var url = $('#url').val();
            $.ajax({
                type:'POST',
                data:{url:url},
                url: '/index.php/url',
                beforeSend:function(){
                    $('button#process_url').text('Fetching Image Urls.........');
                },
                success:function(data)
                {
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        for(i=0;i<data.content.length;i++)
                        {
                            var nodes = '';
                            nodes += '<tr id="image'+i+'">';
                                nodes += '<td><span class="url">'+data.content[i]+'</span></td>';
                                nodes += '<td><span class="status" id="status'+(i + 1)+'" style="color:red;">Pending</span></td>';
                                nodes += '<td class="img" id="img'+(i + 1)+'">None</td>';
                            nodes += '</tr>';

                            $('table#images_table tbody').append(nodes);
                        }
                        alert("Found "+data.content.length+" images");
                        $('button#process_url').text('Run Application');
                        $('button#process_url').removeAttr('disabled');
                        $('#download_images').show();
                        console.log(data.content);
                    }else{
                        console.log(data);
                        $('button#process_url').text('Run Application');
                        $('#download_images').hide();
                        alert(data.content);
                    }
                }
            });
        }
    });
}); 


$('#download_images').click(function(){

    $('button#download_images').attr('disabled',true);
    $('button#process_url').attr('disabled',true);

    urls = $('span.url');
    count = 0;
    ajaxRequest();
    function ajaxRequest()
    {
        if(count < urls.length)
        {
            $.ajax({
                type: "POST",
                url: "/index.php/download",
                data: "url=" + urls[count].innerText,
                success: function(data){
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        console.log(data);
                        node = '<a href="'+data.content+'" target="_blank">'+data.content+'</a>';
                        $('#status'+count).text('Success');
                        $('#status'+count).css('color','green');
                        $('#img'+count).text('');
                        $('#img'+count).append(node);
                    }else{
                        $('#status'+count).text('Failed');
                    }
                    setTimeout(ajaxRequest(),7000);
                }
            });

            count++;
        }else{
            alert('All images has been sucessfully downloaded');
            $('button#download_images').removeAttr('disabled');
            $('button#download_images').hide();
            $('button#process_url').removeAttr('disabled');
        }
    }
});
