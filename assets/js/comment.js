if(document.getElementById("comments")) {
    let comments = document.getElementById("comments");
    comments.classList.add("hidden");
    let button_comments = document.getElementById("create_comment");
    button_comments.addEventListener("click", function () {
        comments.classList.remove("hidden");
        button_comments.classList.add("hidden");
    });
    let annuler = document.getElementById("annuler");
    annuler.addEventListener("click", function () {
        comments.classList.add("hidden");
        button_comments.classList.remove("hidden");
    });

}

if(document.getElementById("showmore")) {
    let showmore = document.getElementById("showmore");
    showmore.classList.add("hidden");
    let showless = document.getElementById("showless");
    showless.classList.remove("hidden");

    let voir_plus = document.getElementById("voir_plus")
    let voir_moins = document.getElementById("voir_moins");

    voir_moins.classList.add("hidden");
    voir_plus.addEventListener("click", function () {
        showmore.classList.remove("hidden");
        showless.classList.add("hidden");
        voir_plus.classList.add("hidden");
        voir_moins.classList.remove("hidden");
    });
    voir_moins.addEventListener("click", function () {
        showless.classList.remove("hidden");
        showmore.classList.add("hidden");
        voir_moins.classList.add("hidden");
        voir_plus.classList.remove("hidden");
    });

}