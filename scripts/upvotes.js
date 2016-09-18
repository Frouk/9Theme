// Way too complicated, should find cleaner solution
function vote(var1, var2) {
    var uped = (document.getElementById("upvoteicon " + var1).getAttribute("class") == "voteup") ? 0 : -1;
    var downed = (document.getElementById("downvoteicon " + var1).getAttribute("class") == "votedown") ? 0 : 1;

    if (var2 == 1) {
       document.getElementById("upvoteicon " + var1).setAttribute("class", "voteupactive");
       document.getElementById("downvoteicon " + var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",0);");
       document.getElementById("downvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",2);");
       document.getElementById("score " + var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) + 1 + downed;
    }else if (var2 == 2){
       document.getElementById("upvoteicon " + var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon " + var1).setAttribute("class", "votedownactive");
       document.getElementById("upvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",1);");
       document.getElementById("downvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",0);");
       document.getElementById("score " + var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) - 1 + uped;
    }else{
       document.getElementById("score " + var1).innerHTML=parseInt(document.getElementById("score "+ var1).innerHTML) + uped + downed;
       document.getElementById("upvoteicon " + var1).setAttribute("class", "voteup");
       document.getElementById("downvoteicon " + var1).setAttribute("class", "votedown");
       document.getElementById("upvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",1);");
       document.getElementById("downvoteicon " + var1).setAttribute("onclick", "vote(" + var1 + ",2);");
    }

    jQuery.ajax({
    url: my_ajax_script.ajaxurl,
    data: ({action : 'vote',para1:var1,para2:var2}),
    success: function() {
     //jQuery change color
    }
    });
}
