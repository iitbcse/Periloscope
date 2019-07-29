let hoster = "http://localhost";
const default_message = "You have already made a Submission for this round!!";

let heading = $("#rounds");

// shift this function in a seperate file and run this and the end of each file (as of now)
async function update_ranking() {
    let xmll = new XMLHttpRequest();
    xmll.open('POST',hoster + '/peril/getter.php');
    xmll.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            $('#update-ranking li').remove();
            // console.log(response + 'something');
            for (let i = 0; i < response.length; i++) {
                $('#update-ranking').append('<li><span style="display: inline-block; margin-top: 5px;">'+response[i][0]+' with worth of $'+ moneyFormatIndia(response[i][1])+'</span></li>');
            }
        }
    };
    xmll.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmll.send('update=some');
}

//update ranking when the round expires do it manually

var data = JSON.parse("[]");
// to execute on submission of the policies.
async function submit_policies(some_val) {
    var sender = new XMLHttpRequest();
    var toSend = "";

    sender.onreadystatechange = function() {
        if (this.readyState == 1) {
            var all = $('input[name=yes]');
            for (var i = 0; i < all.length; i++) {
                if (all[i].checked) {
                    var poli = JSON.parse(all[i].value)['peril_id'];
                    data.push(poli);
                }
            }
            toSend += "{\"data\":" + JSON.stringify(data) + ",\"team_id\":\"" + the_user + "\",\"update\":\"" + some_val + "\"}";
            var temp = "all_checked=" + toSend;
            sender.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            sender.send(temp);

        }
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            try {
                if (JSON.parse(this.responseText)  == "true") {
                    var req = new XMLHttpRequest();
                    req.open('POST',hoster + '/peril/member.php');
                    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                    req.onreadystatechange = function() {
                        if (req.readyState == 4 && req.status == 200) {
                            window.location = "submission.php";
                        }
                    };
                    req.send('ok=true');
                }
                else {
                    
                    $('#lets_update').text(moneyFormatIndia(this.responseText));
                    data = JSON.parse("[]");
                }
            }
            catch(e) {
                alert(this.responseText);
            }
        }
    };
    sender.open('POST', hoster + '/peril/getter.php');
}


//when user click on the SUBMIT button. 
$("#submit-all").click(function() {
    submit_policies("no");
    // console.log("POLICIES submitted");
});

//update the premium amount when the check box is clicked.

$("input[name=yes]").click(function() {
    submit_policies("yes");        
    // console.log("total premium updated...");
});