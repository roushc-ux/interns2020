$(document).ready(function(){
    $("button").click(function(){
        $.get("/sql/data.sql", function(data, status){
            alert("Data: " + data + "\nStatus: " + status);
        });
    })

    $(document).ready(function(){
        $('#addDeck').click(function(){
            var clickBtnValue = $(this).val();
            var ajaxurl = 'game1page.php',
            data =  {'action': clickBtnValue};
            $.post(ajaxurl, data, function (response) {
                // Response div goes here.
                alert("action performed successfully");
            });
        });
    });
});