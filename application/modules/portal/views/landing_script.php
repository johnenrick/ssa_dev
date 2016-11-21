<script>
    $(document).ready(function(){
        $("#logInAccountForm").ajaxForm({
            beforeSubmit : function(){
                $("#logInButton").button("loading");
            },
            success : function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    window.location = response["data"];
                }
                $("#logInButton").button("reset");
            }
        });
        
    });
</script>