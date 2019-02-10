/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
if(document.getElementById("comments")) {
    let comments = document.getElementById("comments");
    comments.setAttribute("class","hidden");
    let button_comments = document.getElementById("create_comment");
    button_comments.addEventListener("click", function () {
        comments.removeAttribute("class");
        button_comments.setAttribute("class","hidden");
    });

}