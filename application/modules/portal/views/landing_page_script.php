<script>
    /*global systemApplication*/
    $(document).ready(function(){
        if(systemApplication.userInformation.userID){
            $("#logInAccountForm").parent().hide();
            $("#userLoggedInInformation span").text(systemApplication.userInformation.lastName);
        }
        $("#logInAccountForm").ajaxForm({
            beforeSubmit : function(){
                $("#logInButton").button("loading");
            },
            success : function(data){
                console.log(data);
                var response = JSON.parse(data);
                if(!response["error"].length){
                    window.location = response["data"];
                }
                $("#logInButton").button("reset");
            }
        });
        
    });
</script>