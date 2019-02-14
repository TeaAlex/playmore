if(document.getElementById("comments")) {
    let comments = document.getElementById("comments");
    comments.setAttribute("class","hidden");
    let button_comments = document.getElementById("create_comment");
    button_comments.addEventListener("click", function () {
        comments.removeAttribute("class");
        button_comments.setAttribute("class","hidden");
    });

}